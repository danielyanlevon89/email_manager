<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;


class OutgoingEmail extends Model
{
    use HasFactory;

    protected $fillable = ['*'];

    public function __construct(array $attributes = [])
    {
        $this->attributes['user_id'] = Auth::id();
        parent::__construct($attributes);
    }

}
