<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\EventResource;
use App\Models\App;

class AppEventsController extends Controller
{
    public function index(App $app)
    {
        $events = $app->events()->paginate();

        return EventResource::collection($events);
    }
}
