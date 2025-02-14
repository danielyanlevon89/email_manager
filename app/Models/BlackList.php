<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class BlackList extends Model
{
    protected $fillable = [
        'word',
        'user_id',
    ];

    public function __construct(array $attributes = [])
    {

        $this->attributes['user_id'] = Auth::id();
        parent::__construct($attributes);
    }
}
