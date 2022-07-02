<?php
namespace App\Models;

use App\Traits\BelongsToClient;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Report extends Model
{
    use HasFactory, BelongsToClient;

    protected static function booted()
    {
        $setClientId = function ($model) {
            $reportable = match ($model->reportable_type) {
                Show::class => Show::find($model->reportable_id),
                App::class => App::find($model->reportable_id),
            };

            $model->client_id = $reportable->client_id;
        };

        static::creating($setClientId);

        static::updating($setClientId);
    }

    public function reportable(): MorphTo
    {
        return $this->morphTo();
    }

    public function reportPages(): HasMany
    {
        return $this->hasMany(ReportPage::class);
    }
}
