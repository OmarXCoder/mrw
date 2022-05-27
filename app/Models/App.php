<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class App extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function show(): BelongsTo
    {
        return $this->belongsTo(Show::class);
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function attendees(): BelongsToMany
    {
        return $this->belongsToMany(Attendee::class);
    }

    public function events(): HasMany
    {
        return $this->hasMany(Event::class);
    }
}
