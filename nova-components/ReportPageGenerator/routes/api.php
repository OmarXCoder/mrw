<?php

use App\Models\App;
use App\Models\Event;
use App\Models\Show;
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
        'app-events','show-events' => (
            function ($request) {
                $modelName = strtolower(class_basename($request->reportableType));

                $meta = Event::select('event_code', 'meta')
                    ->where(['event_code' => $request->eventCode, "{$modelName}_id" => $request->reportableId])
                    ->first()
                    ->meta;

                return array_keys($meta);
            }
        )($request),
    };
    return $result;
});

Route::get('/event-types', function (Request $request) {
    $modelName = strtolower(class_basename($request->reportableType));

    return Event::join('event_types', 'events.event_code', '=', 'event_types.code')
            ->where("{$modelName}_id", $request->reportableId)
            ->get()
            ->pluck('name', 'code')
            ->unique()
            ->map(fn ($item, $key) => ['name' => $item, 'value' => $key])
            ->values();
});

Route::get('/field-values', function (Request $request) {
    $model = match ($request->reportableType) {
        'app' => App::find($request->reportableId),
        'show' => Show::find($request->reportableId),
    };

    return match ($request->queryResource) {
        'show-participants', 'app-participants' => (
            fn () => $model
                ->attendees()
                ->select($request->field)
                ->pluck($request->field)
                ->unique()
                ->values()
        )(),
        'show-events', 'app-events' => (
            fn () => $model
                ->events()
                ->where('event_code', $request->eventCode)
                ->select("meta->{$request->field} as {$request->field}")
                ->pluck($request->field)
                ->unique()
                ->values()
        )()
    };
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
