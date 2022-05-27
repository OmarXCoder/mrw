<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ShowResource;
use App\Models\Client;
use App\Models\Show;

class ClientShowsController extends Controller
{
    public function index(Client $client)
    {
        $shows = $client->shows()->paginate();

        return ShowResource::collection($shows);
    }

    public function show(Client $client, Show $show)
    {
        return ShowResource::make($show);
    }
}
