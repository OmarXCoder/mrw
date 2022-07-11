<?php

use App\Models\EventType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Mrw\ReportPageGenerator\Controllers\ReportChartsController;
use Mrw\ReportPageGenerator\Controllers\ReportPagesController;
use Mrw\ReportPageGenerator\Controllers\ReportPageController;

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

Route::get('/', [ReportPageController::class, 'index']);
Route::delete('/report-pages/{reportPage}', [ReportPageController::class, 'destroy']);
Route::patch('/report-pages/{reportPage}/up', [ReportPageController::class, 'moveUp']);
Route::patch('/report-pages/{reportPage}/down', [ReportPageController::class, 'moveDown']);

Route::post('/reports/{report}/pages', [ReportPagesController::class, 'store']);
Route::post('/reports/{report}/charts', [ReportChartsController::class, 'store']);

Route::get('/query-fields', function (Request $request) {
    $queryResource = $request->queryResource;

    $result = match ($queryResource) {
        'app-participants','show-participants' => [
            'company',
            'profession',
            'country',
            'state',
        ],
        'app-events','show-events' => EventType::all()
            ->map(fn ($item) => [
                'name' => $item->name,
                'value' => $item->code,
            ]),
    };
    return $result;
});

Route::post('/trix-attachment', function (Request $request) {
    $path = $request->file('attachment')->store('public/attachments');

    return [
        'url' => Storage::url($path),
    ];
});

Route::delete('/trix-attachment', function (Request $request) {
    $deleted = Storage::delete(str_replace('/storage', 'public', $request->attachmentUrl));

    return $deleted ? 'File has been deleted' : 'File has not been deleted';
});
