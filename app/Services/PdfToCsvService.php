<?php

namespace App\Services;

use Smalot\PdfParser\Parser;

class PdfToCsvService
{
    function convert()
    {
        $filePath = storage_path('app/files') . '/LeituraPDF.pdf';
        $pdfParser = new Parser();
        $pdf = $pdfParser->parseFile($filePath)->getText();

        $columns = array(
            'Registro ANS',
            'Nome da Operadora'
        );

        $callback = function () use ($pdf, $columns) {
            $file = fopen(storage_path('app/files/pdf_to_csv.csv'), 'wa+');
            fputcsv($file, $columns);
            fputcsv(
                $file,
                array(
                    $this->formatRegistroAns($pdf),
                    $this->formatNomeOperadora($pdf),
                )
            );

            fclose($file);
        };

        $callback();
        return storage_path('app/files/pdf_to_csv.csv');
    }

    private function formatRegistroAns($pdf)
    {
        $indexInicial = strpos($pdf, 'Registro ANS') + 1;
        $indexFinal = strpos($pdf, 'Nome da Operadora');
        $registroAnsValor = substr($pdf, $indexInicial, $indexFinal - $indexInicial - 3); // -3 para remover os caracteres " - "

        return substr($registroAnsValor, strlen('Registro ANS'));
    }

    private function formatNomeOperadora($pdf)
    {
        $indexInicial = strpos($pdf, 'Nome da Operadora') + 1;
        $indexFinal = strpos($pdf, 'CNPJ da Operadora');
        $registroAnsValor = substr($pdf, $indexInicial, $indexFinal - $indexInicial - 3); // -3 para remover os caracteres " - "

        return substr($registroAnsValor, strlen('Nome da Operadora'));
    }
}
