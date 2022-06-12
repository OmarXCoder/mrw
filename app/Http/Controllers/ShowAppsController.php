<?php

namespace App\Http\Controllers;

use App\Http\Resources\AppResource;
use App\Models\Show;
use Illuminate\Http\Request;

class ShowAppsController extends Controller
{
    public function index(Show $show)
    {
        $apps = $show->apps()->paginate();

        return AppResource::collection($apps);
    }

    public function store(Request $request, Show $show)
    {
        $request->validate([
            'name' => ['required'],
            'kiosk_id' => ['required'],
            'machine_id' => ['required'],
        ]);

        return $show->apps()->create([
            'name' => $request->input('name'),
            'kiosk_id' => $request->input('kiosk_id'),
            'machine_id' => $request->input('machine_id'),
        ]);
    }
}
