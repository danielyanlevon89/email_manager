<?php

namespace App\Enums;

enum Encryption
{

    public static function toArray(): array
    {
        return [
            'ssl' => 'ssl',
            'tls' => 'tls',
            'starttls' => 'starttls',
        ];
    }
}
