<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\EventResource;
use App\Models\Show;

class ShowEventsController extends Controller
{
    public function index(Show $show)
    {
        $events = $show->events()->paginate();

        return EventResource::collection($events);
    }
}
