<?php

namespace App\Enums;


enum Boolean
{
    const true = 'Yes';
    const false = 'No';

    public static function toArray(): array
    {
        return [
            'true' => 'Yes',
            'false' => 'No',
        ];
    }
}
