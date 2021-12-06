<?php

namespace App\Services;

use App\Models\HtmlTableRow;
use App\Pages\HtmlTablePage;

class HtmlTableRowService
{
    public function saveTableToDatabase()
    {
        $rows = $this->getTableRows();
        HtmlTableRow::insert($rows);

        return HtmlTableRow::all();
    }

    private function getTableRows()
    {
        $tableRowValues = HtmlTablePage::get()->getTableRowValues();

        return array_map(
            fn ($tableRowValue) => $this->create($tableRowValue['Name'], $tableRowValue['Amount']),
            $tableRowValues
        );
    }

    private function create($name, $amount)
    {
        return [
            'name' => $name,
            'amount' => $amount
        ];
    }
}
