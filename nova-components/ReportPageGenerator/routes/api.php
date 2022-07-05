<?php

use App\Http\Resources\ReportPageResource;
use App\Models\ReportPage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Mrw\ReportPageGenerator\Controllers\ReportChartsController;

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

Route::post('/reports/{report}/charts', [ReportChartsController::class, 'store']);

Route::delete('/report-pages/{reportPage}', function (Request $request, ReportPage $reportPage) {
    return $reportPage->delete();
});
