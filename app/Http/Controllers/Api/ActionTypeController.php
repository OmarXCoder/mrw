<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ActionTypeResource;
use App\Models\ActionType;

class ActionTypeController extends Controller
{
    public function index()
    {
        $actionTypes = ActionType::all();

        return ActionTypeResource::collection($actionTypes);
    }
}
