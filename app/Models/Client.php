<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Client extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function shows(): HasMany
    {
        return $this->hasMany(Show::class);
    }

    public function apps(): HasMany
    {
        return $this->hasMany(App::class);
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
