<?php

namespace App\Imports;

use App\Models\EmailAccount;
use App\Models\Link;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class LinkImport implements ToModel, WithHeadingRow
{

    public function model(array $row)
    {
        return new Link([
            'url' => $row['url'],
            'is_active' => $row['is_active'],
        ]);
    }

    public function headingRow(): int
    {
        return 1;
    }
}
