<?php
namespace Mrw\ReportPageGenerator\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\ReportPageResource;
use App\Models\ReportPage;
use Illuminate\Http\Request;

class ReportPageController extends Controller
{
    public function index(Request $request)
    {
        $reportPages = ReportPage::where('report_id', $request->resourceId)->orderBy('page_order')->get();

        return ReportPageResource::collection($reportPages);
    }

    public function moveUp(ReportPage $reportPage)
    {
        if ($prevReportPage = ReportPage::where('page_order', '<', $reportPage->page_order)->orderBy('page_order', 'DESC')->limit(1)->first()) {
            $prevReportPage->increment('page_order');
        }

        $reportPage->decrement('page_order');

        $reportPages = ReportPage::where('report_id', $reportPage->report_id)->orderBy('page_order')->get();

        return ReportPageResource::collection($reportPages);
    }

    public function moveDown(ReportPage $reportPage)
    {
        if ($nextReportPage = ReportPage::where('page_order', '>', $reportPage->page_order)->orderBy('page_order', 'ASC')->limit(1)->first()) {
            $nextReportPage->decrement('page_order');
        }

        $reportPage->increment('page_order');

        $reportPages = ReportPage::where('report_id', $reportPage->report_id)->orderBy('page_order')->get();

        return ReportPageResource::collection($reportPages);
    }

    public function destroy(ReportPage $reportPage)
    {
        return $reportPage->delete();
    }
}
