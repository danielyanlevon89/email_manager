<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;


class ImportService
{

    public function  import($importType,Request $request)
    {

        // Validate the uploaded file
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
        ]);

        // Get the uploaded file
        $file = $request->file('file');
        $className = "App\\Imports\\".Str::ucfirst(Str::camel($importType)).'Import';
        $importClass = new $className();

        // Process the Excel file
        Excel::import($importClass, $file);

        $result['message'] = __('Data Uploaded Successfully');
        $result['type'] = 'success';

        exit(json_encode($result));
    }


}
