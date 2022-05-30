<?php
namespace App\Traits;

use App\Models\Client;
use App\Scopes\ClientScope;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait BelongsToClient
{
    protected static function bootBelongsToClient()
    {
        static::addGlobalScope(new ClientScope);

        static::creating(function ($model) {
            if (session()->has('client_id')) {
                $model->client_id = session()->get('client_id');
            }
        });
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }
}
