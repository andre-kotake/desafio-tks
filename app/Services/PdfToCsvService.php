<?php

namespace App\Services;

use Smalot\PdfParser\Parser;
use Spatie\PdfToText\Pdf;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use PhpParser\Node\Stmt\Break_;
use SebastianBergmann\CodeCoverage\Report\PHP;

class PdfToCsvService
{
    function convert()
    {
        $pdf = (new Pdf())->setPdf(storage_path('app/files') . '/LeituraPDF.pdf');

        $pages = explode('', $pdf->text());

        $this->generateCsv($pages);

        return Storage::disk('files')->exists('pdf_to_csv.csv') ? 'Arquivo CSV criado com sucesso.' : 'Erro ao criar CSV.';
    }

    private function getDadosPrestador($page)
    {
        $text = array_values(
            array_filter(
                preg_split('/\r\n|\r|\n/', $page),
                fn ($item) => $item != ''
            )
        );

        $numeroGuiaPrestador = $this->formatDados('13 - Número da Guia no Prestador', $text, '15 - Senha');
        $numeroGuiaAtribuidoOperadora = $this->formatDados('14 - Número da Guia Atribuido pela Operadora', $text, '16 - Nome do Beneficiário');
        $senha = $this->formatDados('15 - Senha', $text, '14 - Número da Guia Atribuido pela Operadora');
        $nomeBeneficiario = $this->formatDados('16 - Nome do Beneficiário', $text, '17 - Número da Carteira');
        $numeroCarteira = $this->formatDados('17 - Número da Carteira', $text, '18 - Data Início do Faturamento');

        $indexDataInicial = array_search('21 - Hora Fim do Faturamento', $text);
        $dataInicioFaturamento = $text[$indexDataInicial + 1];
        if ($dataInicioFaturamento == '22 - Código da Glosa da Guia') {
            $indexDataInicial++;
        }

        $dataInicioFaturamento = $text[$indexDataInicial + 1];
        $horaInicioFaturamento = $text[$indexDataInicial + 2];
        $dataFimFaturamento = $text[$indexDataInicial + 3];

        $codigoGlossaGuia = $text[$indexDataInicial + 5];

        if ($codigoGlossaGuia == '23 - Data de') {
            $codigoGlossaGuia = '';
        }

        $values = array(
            $numeroGuiaPrestador,
            $numeroGuiaAtribuidoOperadora,
            $senha,
            $nomeBeneficiario,
            $numeroCarteira,
            $dataInicioFaturamento,
            $horaInicioFaturamento,
            $dataFimFaturamento,
            $codigoGlossaGuia,
        );

        $returnValues = array();

        $dadosTabela = $this->getDadosTabela($text);

        foreach ($dadosTabela as $value) {
            array_push($returnValues, array_merge($values, $value));
        }

        return $returnValues;
    }


    private function generateCsv($pages)
    {
        $columns = array(
            'Registro ANS',
            'Nome da Operadora',
            'Código na Operadora',
            'Nome do Contratado',
            'Número do Lote',
            'Número do Protocolo',
            'Data do Protocolo',
            'Código da Glosa do Protocolo',
            'Número da Guia no Prestador',
            'Número da Guia Atribuído pela Operadora',
            'Senha',
            'Nome do Beneficiário',
            'Número da Carteira',
            'Data Inicio do faturamento',
            'Hora Inicio do Faturamento',
            'Data Fim do Faturamento',
            'Código da Glosa da Guia',
            'Data de realização',
            'Tabela',
            'Código do Procedimento',
            'Descrição',
            'Grau Participação',
            'Valor Informado',
            'Quanti. Executada',
            'Valor Processado',
            'Valor Liberado',
            'Valor Glosa',
            'Código da Glosa',
            'Valor Informado da Guia',
            'Valor Processado da Guia',
            'Valor Liberado da Guia',
            'Valor Glosa da Guia',
            'Valor Informado do Protocolo',
            'Valor Processado do Protocolo',
            'Valor Liberado do Protocolo',
            'Valor Glosa do Protocolo',
            'Valor Informado Geral',
            'Valor Processado Geral',
            'Valor Liberado Geral',
            'Valor Glosa Geral'
        );

        $file = fopen(storage_path('app/files/pdf_to_csv.csv'), 'wa+');
        fputcsv($file, $columns);

        $textExploded = explode(PHP_EOL, $pages[0]);

        $values = array(
            $textExploded[15],                              // Registro ANS
            $textExploded[17],                              // Nome Operadora
            $textExploded[29],                              // Codigo Operadora
            $textExploded[27],                              // Nome Contratado
            $textExploded[41],                              // Numero Lote
            $textExploded[39] . ' ' . $textExploded[38],    // Número Protocolo
            $textExploded[52],                              // Data Protocolo
            '',                                             // Código Glossa Protocolo
        );

        for ($i = 2; $i < count($pages); $i++) {
            $dadosPrestador = $this->getDadosPrestador($pages[$i]);
            foreach ($dadosPrestador as $value) {
                fputcsv(
                    $file,
                    array_merge(
                        array_merge(
                            $values,
                            $value
                        ),
                        array(
                            $textExploded[70], // Valor Informado do Protocolo
                            $textExploded[76], // Valor Processado do Protocolo
                            $textExploded[80], // Valor Liberado do Protocolo
                            $textExploded[82], // Valor Glosa do Protocolo
                            $textExploded[86], // Valor Informado Geral
                            $textExploded[89], // Valor Processado Geral
                            $textExploded[94], // Valor Liberado Geral
                            $textExploded[96], // Valor Glosa Geral
                        )
                    )
                );
            }
        }

        fclose($file);
    }

    private function getDadosTabela($text)
    {
        $indexInicial = array_search('Tabela /Item assistencial', $text) + 1;
        $value = str_replace('/', '-', $text[$indexInicial]);

        $dataRealizacao = array();

        while (strtotime($value)) {
            array_push($dataRealizacao, $value);
            $value = str_replace('/', '-', $text[++$indexInicial]);
        }

        $tabela = array();

        while (is_numeric($value)) {
            array_push($tabela, $value);
            $value = $text[++$indexInicial];
        }

        foreach ($text as $key => $value) {
            if (strpos($value, '27 -Grau de 28 -') === 0) {
                $indexInicial = $key + 3;
                break;
            }
        }

        $codigoProcedimento = array();
        $descricao = array();

        do {
            $codigoDescricao = explode(' ', $text[$indexInicial++]);
            $originalCount = count($codigoDescricao);

            if ($originalCount > 1) {
                array_push($codigoProcedimento, array_shift($codigoDescricao));
                array_push($descricao, implode(' ', $codigoDescricao));
            }
        } while ($originalCount != 1);

        $grauParticipacao = array_fill(0, count($dataRealizacao), '');

        $indexInicial -= 2;
        $valorInformado = array();

        do {
            $value = $text[++$indexInicial];
            if (is_numeric($value)) break;
            array_push($valorInformado, $value);
        } while (!is_numeric($value));

        $indexInicial -= 1;
        $quantidadeInicial = array();

        do {
            $value = $text[++$indexInicial];
            if (!is_numeric($value)) break;
            array_push($quantidadeInicial, $value);
        } while (is_numeric($value));

        $valorProcessado = array();

        for ($i = $indexInicial; $i < $indexInicial + count($dataRealizacao); $i++) {
            array_push($valorProcessado, $text[$i]);
        }

        $indexInicial += count($dataRealizacao);

        $valorLiberado = array();
        $valorGlosa = array();
        $codigoGlosa = array();

        if (trim($text[$indexInicial]) == '33 - Código da Glosa') {
            do {
                $valorCodigoGlosa = explode(' ', $text[++$indexInicial]);
                $originalCount = count($valorCodigoGlosa);
                if ($originalCount > 1) {
                    array_push($valorGlosa, array_shift($valorCodigoGlosa));
                    array_push($codigoGlosa, implode(' ', $valorCodigoGlosa));
                }
            } while ($originalCount > 1);

            for ($i = $indexInicial; $i < $indexInicial + count($dataRealizacao); $i++) {
                array_push($valorLiberado, $text[$i]);
            }
        } else {
            for ($i = $indexInicial; $i < $indexInicial + count($dataRealizacao); $i++) {
                array_push($valorGlosa, $text[$i]);
            }

            $indexInicial += count($dataRealizacao);
            for ($i = $indexInicial; $i < $indexInicial + count($dataRealizacao); $i++) {
                array_push($valorLiberado, $text[$i]);
            }

            $indexInicial = array_search('33 - Código da Glosa', $text) + 1;
            for ($i = $indexInicial; $i < $indexInicial + count($dataRealizacao); $i++) {
                array_push($codigoGlosa, $text[$i]);
            }
        }

        $indexInicial = array_search('34 - Valor Informado da Guia (R$)', $text) + 1;
        $valorInformadoGuia = $text[$indexInicial];
        $valorProcessadoGuia = '';
        $valorLiberadoGuia = '';
        $valorGlosaGuia = '';

        if ($valorInformadoGuia == '35 - Valor Processado da Guia (R$)') {
            $valorInformadoGuia = $text[++$indexInicial];
            $valorProcessadoGuia = $text[$indexInicial + 2];

            $indexInicial = array_search('37 - Valor Glosa da Guia (R$)', $text) + 1;
            $valorLiberadoGuia = $text[$indexInicial];
            $valorGlosaGuia = $text[$indexInicial + 1];
        } else {
            $valorProcessadoGuia = $this->formatDados('35 - Valor Processado da Guia (R$)', $text);
            $valorLiberadoGuia = $this->formatDados('36 - Valor Liberado da Guia (R$)', $text);
            $valorGlosaGuia = $this->formatDados('37 - Valor Glosa da Guia (R$)', $text);
        }

        $values = array();

        for ($i = 0; $i < count($dataRealizacao); $i++) {
            array_push(
                $values,
                array(
                    $dataRealizacao[$i],
                    $tabela[$i],
                    $codigoProcedimento[$i],
                    $descricao[$i],
                    $grauParticipacao[$i],
                    $valorInformado[$i],
                    $quantidadeInicial[$i],
                    $valorProcessado[$i],
                    $valorLiberado[$i],
                    $valorGlosa[$i],
                    $codigoGlosa[$i],
                    $valorInformadoGuia,
                    $valorProcessadoGuia,
                    $valorLiberadoGuia,
                    $valorGlosaGuia,
                )
            );
        }

        return $values;
    }

    private function formatDados($needle, $haystack, $unexpectedValue = '')
    {
        $value = $haystack[array_search($needle, $haystack) + 1];
        return ($value != $unexpectedValue) ? $value : '';
    }
}
