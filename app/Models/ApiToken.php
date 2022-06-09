<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Support\Str;

class ApiToken extends PersonalAccessToken
{
    public $table = 'personal_access_tokens';

    protected static function booted()
    {
        static::creating(function($model) {
            $model->token = hash('sha256', Str::random(40));
        });
    }
}
