<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReportPage extends Model
{
    use HasFactory;

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'meta' => 'array',
        'page_order' => 'integer',
    ];

    protected $guarded = [];

    protected static function booted()
    {
        static::creating(function ($page) {
            $max = static::where('report_id', $page->report_id)->max('page_order');
            $page->page_order = $max + 1;
        });
    }

    public function report(): BelongsTo
    {
        return $this->belongsTo(Report::class);
    }
}
