<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AppResource;
use App\Models\App;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AppController extends Controller
{
    public function index()
    {
        $apps = App::paginate();

        return AppResource::collection($apps);
    }

    public function show(App $app)
    {
        return AppResource::make($app);
    }
}
