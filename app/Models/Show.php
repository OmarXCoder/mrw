<?php
namespace App\Models;

use App\Traits\BelongsToClient;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Show extends Model
{
    use HasFactory, BelongsToClient;

    protected $guarded = [];

    protected $with = ['client'];

    protected $dates = ['start_date', 'end_date'];

    const STATUS_UPCOMMING = 'upcomming';
    const STATUS_ACTIVE = 'active';
    const STATUS_ENDED = 'ended';

    public static function statuses()
    {
        return [
            self::STATUS_UPCOMMING,
            self::STATUS_ACTIVE,
            self::STATUS_ENDED,
        ];
    }

    public static function booted()
    {
        static::creating(fn ($show) => static::determineStatus($show));

        static::updating(fn ($show) => static::determineStatus($show));
    }

    public static function determineStatus($show)
    {
        match (true) {
            $show->start_date->isFuture() => $show->status = self::STATUS_UPCOMMING,
            $show->end_date->isPast() => $show->status = self::STATUS_ENDED,
            now()->isBetween($show->start_date, $show->end_date) => $show->status = self::STATUS_ACTIVE
        };

        return $show;
    }

    public function apps(): HasMany
    {
        return $this->hasMany(App::class);
    }

    public function attendees(): HasMany
    {
        return $this->hasMany(Attendee::class);
    }
}
