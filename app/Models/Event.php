<?php
namespace App\Models;

use App\Traits\BelongsToClient;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Event extends Model
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

    protected $dates = [
        'timestamp',
    ];

    protected static function booted()
    {
        $setClientIdAndShowId = function ($event) {
            $app = App::find($event->app_id);

            $event->client_id = $app->client_id;

            $event->show_id = $app->show_id;
        };

        static::creating($setClientIdAndShowId);

        static::updating($setClientIdAndShowId);
    }

    public function show(): BelongsTo
    {
        return $this->belongsTo(Show::class);
    }

    public function app(): BelongsTo
    {
        return $this->belongsTo(App::class);
    }

    public function attendee(): BelongsTo
    {
        return $this->belongsTo(Attendee::class);
    }

    public function actionType()
    {
        return $this->belongsTo(ActionType::class, 'action_code');
    }

    public function eventType()
    {
        return $this->belongsTo(EventType::class, 'event_code');
    }
}
