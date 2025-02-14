<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Link extends Model
{
    protected $fillable = [
        'url',
        'is_active',
        'user_id',
    ];

    public function __construct(array $attributes = [])
    {

        $this->attributes['user_id'] = Auth::id();
        parent::__construct($attributes);
    }
}
