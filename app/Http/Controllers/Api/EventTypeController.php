<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\EventTypeResource;
use App\Models\EventType;

class EventTypeController extends Controller
{
    public function index()
    {
        $eventTypes = EventType::all();

        return EventTypeResource::collection($eventTypes);
    }
}
