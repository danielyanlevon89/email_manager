<?php

namespace App\Imports;

use App\Models\BlackList;
use App\Models\EmailAccount;
use App\Models\EmailTemplate;
use App\Models\Link;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class TemplatesImport implements ToModel, WithHeadingRow
{

    public function model(array $row)
    {
        return new EmailTemplate([
            'name' => $row['name'],
            'text' => $row['text'],
        ]);
    }

    public function headingRow(): int
    {
        return 1;
    }
}
