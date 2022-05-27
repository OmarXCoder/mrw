<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AppResource;
use App\Models\App;
use Illuminate\Http\Request;

class AppController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name'      => ['required'],
            'client_id' => ['required', 'exists:clients,id'],
            'show_id'   => ['required', 'exists:shows,id'],
        ]);

        $app = App::where([
            'client_id' => $request->get('client_id'),
            'show_id'   => $request->get('show_id'),
        ])->first();

        if (!$app) {
            $app = App::create([
                'name'       => $request->get('name'),
                'client_id'  => $request->get('client_id'),
                'show_id'    => $request->get('show_id'),
                'kiosk_id'   => $request->get('kiosk_id'),
                'machine_id' => $request->get('machine_id'),
            ]);
        }

        return AppResource::make($app);
    }
}
