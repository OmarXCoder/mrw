<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Attendee extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function apps(): BelongsToMany
    {
        return $this->belongsToMany(App::class);
    }
}
