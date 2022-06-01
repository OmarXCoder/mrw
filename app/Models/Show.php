<?php
namespace App\Models;

use App\Traits\BelongsToClient;
use Carbon\Carbon;
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
        static::creating(function ($show) {
            $show->status = static::determineStatus($show->start_date, $show->end_date);
        });

        static::updating(function ($show) {
            $show->status = static::determineStatus($show->start_date, $show->end_date);
        });
    }

    public static function determineStatus(Carbon $start_date, Carbon $end_date)
    {
        return match (true) {
            $start_date->isFuture() => self::STATUS_UPCOMMING,
            $end_date->isPast() => self::STATUS_ENDED,
            default => self::STATUS_ACTIVE
        };
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
