<?php

use App\Http\Resources\ReportPageResource;
use App\Models\Report;
use App\Models\ReportPage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Mrw\ReportPageGenerator\Controllers\AppReportPageController;

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

    $reportPages = ReportPage::where([
        'report_id' => (int) $q['resourceId'],
    ])->oldest()->get();

    return ReportPageResource::collection($reportPages);
});

Route::post('/reports/{report}/pages', function (Request $request, Report $report) {
    $request->validate([
        'heading' => ['required'],
        'type' => ['required'],
        'chart' => ['required'],
    ]);

    return (new AppReportPageController)->store($request, $report);
});
