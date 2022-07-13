<?php

use App\Http\Resources\ReportPageResource;
use App\Models\Report;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Laravel\Nova\Http\Requests\NovaRequest;

/*
|--------------------------------------------------------------------------
| Tool Routes
|--------------------------------------------------------------------------
|
| Here is where you may register Inertia routes for your tool. These are
| loaded by the ServiceProvider of the tool. The routes are protected
| by your tool's "Authorize" middleware by default. Now - go build!
|
*/

Route::get('/reports/{report}/view', function (NovaRequest $request, Report $report) {
    $passedCheck = Hash::check(
        "{$report->id}-{$report->reportable_type}-{$report->reportable_id}",
        $request->uuid
    );

    if (!$passedCheck) {
        abort(403);
    }

    $reportPages = $report->reportPages;

    return inertia('ReportPreview', [
        'reportPages' => ReportPageResource::collection($reportPages),
    ]);
});
