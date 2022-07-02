<?php
namespace Mrw\ReportPageGenerator\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\ReportPageResource;
use App\Models\App;
use App\Models\Attendee;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AppReportPageController extends Controller
{
    public function store(Request $request, Report $report)
    {
        $q = $request->query();

        $app = App::find($q['reportableId']);

        [$labels, $values] = match ($request->input('chart')) {
            'participants-by-country' => $this->appAttendeesByColumn($app, 'country'),
            'participants-by-company' => $this->appAttendeesByColumn($app, 'company'),
            'participants-by-profession' => $this->appAttendeesByColumn($app, 'profession'),
        };

        $reportPage = $report->reportPages()->create([
            'type' => 'chart',
            'heading' => $request->input('heading'),
            'content' => $request->input('content'),
            'include_header' => true,
            'include_footer' => true,
            'report_id' => $report->id,
            'meta' => [
                'chart' => [
                    'id' => \Illuminate\Support\Str::uuid(),
                    'type' => $request->input('type'),
                    'title' => $request->input('heading'),
                    'width' => 712,
                    'height' => 400,
                    'datasetIdKey' => 'label',
                    'data' => [
                        'labels' => $labels,
                        'datasets' => [
                            [
                                'label' => 'Participants',
                                'data' => $values,
                                'backgroundColor' => $request->input('color'),
                            ],
                        ],
                    ],
                ],
            ],
        ]);

        return ReportPageResource::make($reportPage);
    }

    public function appAttendeesByColumn(App $app, string $column)
    {
        $result = Attendee::join('app_attendee', 'attendees.id', '=', 'app_attendee.attendee_id')
            ->select("attendees.{$column}", DB::raw("COUNT(attendees.{$column}) as attendees_count"))
            ->groupBy("attendees.{$column}")
            ->where('app_attendee.app_id', $app->id)
            ->get();

        return [
            $result->pluck($column)->toArray(),
            $result->pluck('attendees_count')->toArray(),
        ];
    }
}
