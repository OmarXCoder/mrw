<?php

use App\Models\App;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Laravel\Sanctum\PersonalAccessToken;

/*
|--------------------------------------------------------------------------
| Tool API Routes
|--------------------------------------------------------------------------
|
| Here is where you may register API routes for your tool. These routes
| are loaded by the ServiceProvider of your tool. You're free to add
| as many additional routes to this file as your tool may require.
|
*/

Route::get('/', function (Request $request) {
    $q = $request->query();

    $tokenableTypes = [
        'apps' => \App\Models\App::class,
        'users' => \App\Models\User::class,
    ];

    return PersonalAccessToken::where([
        'tokenable_type' => $tokenableTypes[$q['resourceName']],
        'tokenable_id' => (int) $q['resourceId'],
    ])->latest()->get();
});

Route::post('/', function (Request $request) {
    $q = $request->query();

    if ($q['resourceName'] === 'users') {
        $model = User::find((int) $q['resourceId']);
    } elseif ($q['resourceName'] === 'apps') {
        $model = App::find((int) $q['resourceId']);
    }

    $newToken = $model->createToken($request->input('name'));

    $accessToken = PersonalAccessToken::find($newToken->accessToken->id);

    $accessToken->plain_text = $newToken->plainTextToken;

    $accessToken->save();
});

Route::delete('/tokens/{id}', function (Request $request, $id) {
    $accessToken = PersonalAccessToken::find($id);

    return $accessToken->delete();
});
