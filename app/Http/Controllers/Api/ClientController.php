<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ClientResource;
use App\Models\Client;

class ClientController extends Controller
{
    public function index()
    {
        $clients = Client::paginate();

        return ClientResource::collection($clients);
    }

    public function show(Client $client)
    {
        return ClientResource::make($client);
    }
}
