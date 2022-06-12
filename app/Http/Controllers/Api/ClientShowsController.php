<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ShowResource;
use App\Models\Client;
use App\Models\Show;
use Illuminate\Http\Request;

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

    public function store(Request $request, Client $client)
    {
        $request->validate([
            'name' => ['required'],
            'organizer' => ['required'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date'],
        ]);

        return $client->shows()->create([
            'name' => $request->input('name'),
            'organizer' => $request->input('organizer'),
            'start_date' => $request->input('start_date'),
            'end_date' => $request->input('end_date'),
        ]);
    }
}
