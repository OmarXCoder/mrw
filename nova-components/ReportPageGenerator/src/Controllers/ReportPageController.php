<?php
namespace Mrw\ReportPageGenerator\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\ReportPageResource;
use App\Models\Report;
use Illuminate\Http\Request;

class ReportPageController extends Controller
{
    public function store(Request $request, Report $report)
    {
        $request->validate([
            'pageContent' => ['required'],
            'contentType' => ['required'],
        ]);

        $reportPage = $report->reportPages()->create([
            'content_type' => $request->contentType,
            'title' => $request->pageTitle,
            'content' => $request->pageContent,
            'include_header' => true,
            'include_footer' => true,
            'report_id' => $report->id,
            'meta' => [],
        ]);

        return ReportPageResource::make($reportPage);
    }
}
