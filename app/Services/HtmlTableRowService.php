<?php

namespace App\Services;

use App\Models\HtmlTableRow;
use App\Pages\HtmlTablePage;
use Error;
use Exception;
use Illuminate\Support\Facades\Log;
use Throwable;

class HtmlTableRowService
{
    function saveTableToDatabase()
    {
        $rows = $this->getTableRows();

        try {
            if (HtmlTableRow::insert($rows)) {
                return $rows;
            }

            throw new Exception('Erro ao salvar valores.');
        } catch (Throwable $th) {
            throw $th;
        }
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
