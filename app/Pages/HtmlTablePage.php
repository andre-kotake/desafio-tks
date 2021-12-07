<?php

namespace App\Pages;

use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverElement;

class HtmlTablePage extends BasePage
{
    function getUrl(): string
    {
        return 'https://testpages.herokuapp.com/styled/tag/table.html';
    }

    /**
     * Retorna array de arrays contendo todos os valores da tabela.
     *
     * @return array
     */
    function getTableRowValues(): array
    {
        $retorno = array_map(
            function ($row) {
                $rowValues = $row->findElements(WebDriverBy::tagName('td'));
                $tableHeaders = $this->getTableColumnHeaders();

                return array_combine(
                    $tableHeaders,
                    array_map(
                        fn ($rowValue) => $rowValue->getText(),
                        $rowValues
                    )
                );
            },
            $this->getTableRows()
        );

        return $retorno;
    }

    private function getTableElement(): WebDriverElement
    {
        return $this->findElement('//table[@id="mytable"]');
    }

    private function getTableColumnHeaders(): array
    {
        return array_map(
            fn ($tableHeader) => $tableHeader->getText(),
            $this->getTableElement()->findElements(WebDriverBy::xpath('.//tr/th'))
        );
    }

    private function getTableRows(): array
    {
        return $this->getTableElement()->findElements(WebDriverBy::xpath('.//tr[./td]'));
    }
}
