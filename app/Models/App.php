<?php
namespace App\Models;

use App\Traits\BelongsToClient;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Sanctum\HasApiTokens;

class App extends Model
{
    use HasFactory, BelongsToClient, HasApiTokens;

    protected $guarded = [];

    protected static function booted()
    {
        $setClientId = function ($model) {
            $show = Show::find($model->show_id);
            
            $model->client_id = $show->client_id;
        };

        static::creating($setClientId);
        
        static::updating($setClientId);
    }

    public function show(): BelongsTo
    {
        return $this->belongsTo(Show::class);
    }

    public function attendees(): BelongsToMany
    {
        return $this->belongsToMany(Attendee::class);
    }

    public function events(): HasMany
    {
        return $this->hasMany(Event::class);
    }

    public function lastEvent()
    {
        return $this->hasOne(Event::class)->latestOfMany();
    }
}
