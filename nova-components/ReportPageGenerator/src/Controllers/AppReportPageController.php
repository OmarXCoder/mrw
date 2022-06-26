<?php
namespace Mrw\ReportPageGenerator\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\ReportPageResource;
use App\Models\App;
use App\Models\Attendee;
use App\Models\Report;
use App\Models\ReportPage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AppReportPageController extends Controller
{
    public function store(Request $request, Report $report)
    {
        $q = $request->query();

        $app = App::find($q['reportableId']);

        $chart = match ($request->input('chart')) {
            'participants-by-country' => $this->participantsByCountry($app),
            'participants-by-company' => $this->participantsByCompany($app),
            'participants-by-profession' => $this->participantsByProfession($app),
        };

        $hooks = array_merge($chart['hooks'], [
            'beginAtZero' => true,
            'title' => $request->input('heading'),
        ]);

        $hooks['datasets'] = match ($request->input('type')) {
            'line-chart' => [['type' => 'line', 'fill' => false]],
            'pie-chart' => 'doughnut',
            'bar-chart' => [['type' => 'bar', 'fill' => true]],
        };

        if ($request->input('type') == 'pie-chart') {
            $hooks['pieColors'] = null;
        }

        $reportPage = ReportPage::create([
            'type' => 'chart',
            'heading' => $request->input('heading'),
            'content' => $request->input('content'),
            'include_header' => true,
            'include_footer' => true,
            'report_id' => $report->id,
            'meta' => [
                'chart' => [
                    'chartId' => Str::uuid(),
                    'data' => $chart['data'],
                    'hooks' => $hooks,
                ],
            ],
        ]);

        return ReportPageResource::make($reportPage);
    }

    private function participantsByCountry(App $app)
    {
        $participantsByCountry = Attendee::join('app_attendee', 'attendees.id', '=', 'app_attendee.attendee_id')
            ->select('attendees.country', DB::raw('COUNT(attendees.country) as attendees_count'))
            ->groupBy('attendees.country')
            ->where('app_attendee.app_id', $app->id)
            ->get();

        return [
            'data' => [
                'chart' => [
                    'labels' => $participantsByCountry->pluck('country')->toArray(),
                ],
                'datasets' => [
                    [
                        'name' => 'Participants',
                        'values' => $participantsByCountry->pluck('attendees_count')->toArray(),
                    ],
                ],
            ],
            'hooks' => [],
        ];
    }

    private function participantsByCompany(App $app)
    {
        $participantsByCompany = Attendee::join('app_attendee', 'attendees.id', '=', 'app_attendee.attendee_id')
            ->select('attendees.company', DB::raw('COUNT(attendees.company) as attendees_count'))
            ->groupBy('attendees.company')
            ->where('app_attendee.app_id', $app->id)
            ->get();

        return [
            'data' => [
                'chart' => [
                    'labels' => $participantsByCompany->pluck('company')->toArray(),
                ],
                'datasets' => [
                    [
                        'name' => 'Participants',
                        'values' => $participantsByCompany->pluck('attendees_count')->toArray(),
                    ],
                ],
            ],
            'hooks' => [],
        ];
    }

    private function participantsByProfession(App $app)
    {
        $participantsByProfession = Attendee::join('app_attendee', 'attendees.id', '=', 'app_attendee.attendee_id')
            ->select('attendees.profession', DB::raw('COUNT(attendees.profession) as attendees_count'))
            ->groupBy('attendees.profession')
            ->where('app_attendee.app_id', $app->id)
            ->get();

        return [
            'data' => [
                'chart' => [
                    'labels' => $participantsByProfession->pluck('profession')->toArray(),
                ],
                'datasets' => [
                    [
                        'name' => 'Participants',
                        'values' => $participantsByProfession->pluck('attendees_count')->toArray(),
                    ],
                ],
            ],
            'hooks' => [],
        ];
    }
}
