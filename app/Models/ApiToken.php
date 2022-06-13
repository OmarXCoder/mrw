<?php
namespace App\Models;

use Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Support\Str;

class ApiToken extends PersonalAccessToken
{
    public $table = 'personal_access_tokens';

    protected static function booted()
    {
        static::creating(fn ($token) => $token->setTokenAndPlainText());
        static::updating(fn ($token) => $token->setTokenAndPlainText());
    }

    protected function setTokenAndPlainText()
    {
        $this->token = hash('sha256', $plainTextToken = Str::random(40));
        $this->plain_text = $plainTextToken;

        return $this;
    }

    public function regenerate()
    {
        return $this->setTokenAndPlainText()->save();
    }
}
