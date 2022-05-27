<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ShowResource;
use App\Models\Show;

class ShowController extends Controller
{
    public function index()
    {
        $shows = Show::paginate();

        return ShowResource::collection($shows);
    }

    public function show(Show $show)
    {
        return ShowResource::make($show);
    }
}
