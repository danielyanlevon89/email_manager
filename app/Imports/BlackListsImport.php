<?php

namespace App\Imports;

use App\Models\BlackList;
use App\Models\EmailAccount;
use App\Models\Link;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class BlackListsImport implements ToModel, WithHeadingRow
{

    public function model(array $row)
    {
        return new BlackList([
            'word' => $row['word'],
        ]);
    }

    public function headingRow(): int
    {
        return 1;
    }
}
