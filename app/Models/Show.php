<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Show extends Model
{
    use HasFactory;

    protected $guarded = [];

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

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
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
