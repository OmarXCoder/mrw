<?php

use App\Http\Resources\ReportPageResource;
use App\Models\ReportPage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Mrw\ReportPageGenerator\Controllers\ReportChartsController;
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

Route::get('/', function (Request $request) {
    $q = $request->query();

    $reportPages = ReportPage::where([
        'report_id' => (int) $q['resourceId'],
    ])->orderBy('page_order')->get();

    return ReportPageResource::collection($reportPages);
});

Route::post('/reports/{report}/charts', [ReportChartsController::class, 'store']);
Route::post('/reports/{report}/pages', [ReportPageController::class, 'store']);

Route::delete('/report-pages/{reportPage}', function (Request $request, ReportPage $reportPage) {
    return $reportPage->delete();
});

Route::patch('/report-pages/{reportPage}/up', function (Request $request, ReportPage $reportPage) {
    if ($prevReportPage = ReportPage::where('page_order', $reportPage->page_order - 1)->first()) {
        $prevReportPage->increment('page_order');
    }

    $reportPage->decrement('page_order');

    $reportPages = ReportPage::where('report_id', $reportPage->report_id)->orderBy('page_order')->get();

    return ReportPageResource::collection($reportPages);
});

Route::patch('/report-pages/{reportPage}/down', function (Request $request, ReportPage $reportPage) {
    if ($nextReportPage = ReportPage::where('page_order', $reportPage->page_order + 1)->first()) {
        $nextReportPage->decrement('page_order');
    }

    $reportPage->increment('page_order');

    $reportPages = ReportPage::where('report_id', $reportPage->report_id)->orderBy('page_order')->get();

    return ReportPageResource::collection($reportPages);
});
