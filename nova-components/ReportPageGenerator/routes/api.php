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

/**
 * Get all reportPages of the current viewed report
 */
Route::middleware(['can:reports.view'])->get('/', [ReportPageController::class, 'index']);

/**
 * Delete a reportPage
 */
Route::middleware(['can:reports.delete'])->delete('/report-pages/{reportPage}', [ReportPageController::class, 'destroy']);

/**
 * Move a specific reportPage up withen the report document
 */
Route::middleware(['can:reports.edit'])->patch('/report-pages/{reportPage}/up', [ReportPageController::class, 'moveUp']);

/**
 * Move a specific reportPage down withen the report document
 */
Route::middleware(['can:reports.edit'])->patch('/report-pages/{reportPage}/down', [ReportPageController::class, 'moveDown']);

/**
 * Store a new ReportPage of content type: rich-text
 */
Route::middleware(['can:reports.create'])->post('/reports/{report}/pages', [ReportPagesController::class, 'store']);

/**
 * Store a new ReportPage of content type: chart
 */
Route::middleware(['can:reports.create'])->post('/reports/{report}/charts', [ReportChartsController::class, 'store']);

/**
 * Get a list of table columns
 *
 * @return array ['col1', 'col2', 'col3', ...]
 */
Route::middleware(['can:reports.create'])->get('/query-fields', function (Request $request) {
    $queryResource = $request->queryResource;

    $result = match ($queryResource) {
        'attendees' => ['company', 'profession', 'country', 'state'],
        'events' => (
            function ($request) {
                $meta = Event::select('event_code', 'meta')
                    ->where(['event_code' => $request->eventCode, "{$request->reportableType}_id" => $request->reportableId])
                    ->when($request->actionCode, fn ($query) => $query->where('action_code', $request->actionCode))
                    ->first()
                    ->meta;

                return array_keys($meta);
            }
        )($request),
    };
    return $result;
});

/**
 * Get a list of event_types of the events occured on a specific show/app
 *
 * @return array [['name' => 'video', 'value' => 7], [], ...]
 */
Route::middleware(['can:reports.create'])->get('/event-types', function (Request $request) {
    return Event::join('event_types', 'events.event_code', '=', 'event_types.code')
            ->where("{$request->reportableType}_id", $request->reportableId)
            ->pluck('event_types.name', 'event_types.code')
            ->unique()
            ->map(fn ($item, $key) => ['name' => $item, 'value' => $key])
            ->values();
});

/**
 * Get a list of action_types of the events occured on a specific show/app
 * limited by the event_code of the event
 *
 * @return array [['name' => 'viewed', 'value' => 0], [], ...]
 */
Route::middleware(['can:reports.create'])->get('/action-types', function (Request $request) {
    return Event::join('action_types', 'events.action_code', '=', 'action_types.code')
            ->where("{$request->reportableType}_id", $request->reportableId)
            ->where('event_code', $request->eventCode)
            ->pluck('action_types.name', 'action_types.code')
            ->unique()
            ->map(fn ($item, $key) => ['name' => $item, 'value' => $key])
            ->values();
});

/**
 * Get all possible values of a spicific database column
 *
 * @return array $values
 */
Route::middleware(['can:reports.create'])->get('/field-values', function (Request $request) {
    $model = match ($request->reportableType) {
        'app' => App::find($request->reportableId),
        'show' => Show::find($request->reportableId),
    };

    return match ($request->queryResource) {
        'attendees' => (
            fn () => $model
                ->attendees()
                ->select($request->field)
                ->pluck($request->field)
                ->unique()
                ->values()
        )(),
        'events' => (
            fn () => $model
                ->events()
                ->where([
                    'event_code' => $request->eventCode,
                    'action_code' => $request->actionCode,
                ])
                ->select("meta->{$request->field} as {$request->field}")
                ->pluck($request->field)
                ->unique()
                ->values()
        )()
    };
});

/**
 * Store a trix-editor attachment and return the attachment's url
 */
Route::middleware(['can:reports.create'])->post('/trix-attachment', function (Request $request) {
    $path = $request->file('attachment')->store('public/attachments');

    return [
        'url' => Storage::url($path),
    ];
});

/**
 * Delete a trix-editor attachment
 */
Route::middleware(['can:reports.create'])->delete('/trix-attachment', function (Request $request) {
    $deleted = Storage::delete(str_replace('/storage', 'public', $request->attachmentUrl));

    return $deleted ? 'Attachment has been deleted' : 'Attachment has not been deleted';
});
