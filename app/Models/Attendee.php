<?php
namespace App\Models;

use App\Traits\BelongsToClient;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Attendee extends Model
{
    use HasFactory, BelongsToClient;

    protected $guarded = [];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'meta' => 'json',
    ];

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

    public function apps(): BelongsToMany
    {
        return $this->belongsToMany(App::class);
    }

    public function events(): HasMany
    {
        return $this->hasMany(Event::class);
    }

    public function lastEvent()
    {
        return $this->hasOne(Event::class)->latestOfMany();
    }

    public function lastAppUsed()
    {
        return $this->apps()->latest()->first();
    }
}
