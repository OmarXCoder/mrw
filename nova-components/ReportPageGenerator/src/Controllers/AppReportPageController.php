<?php
namespace Mrw\ReportPageGenerator\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\ReportPageResource;
use App\Models\App;
use App\Models\Attendee;
use App\Models\Event;
use App\Models\Report;
use App\Models\Show;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AppReportPageController extends Controller
{
    public function store(Request $request, Report $report)
    {
        // 1. Get the reportable by type and id
        $reportable = match ($request->reportableType) {
            Show::class => Show::find($request->reportableId),
            App::class => App::find($request->reportableId),
        };

        [$labels, $datasetLabel, $datasetValues] = match ($request->table) {
            'app-participants' => $this->appParticipantsByColumn($reportable, $request->column),
            'show-participants' => $this->showParticipantsByColumn($reportable, $request->column),
            'app-events' => $this->eventsByMetaColumn($reportable, $request->column),
            'show-events' => $this->eventsByMetaColumn($reportable, $request->column),
        };

        $datasetColors = $request->colors;

        if ($request->type === 'pie' && count($datasetColors) < count($datasetValues)) {
            $diff = count($datasetValues) - count($datasetColors);
            for ($x = 0; $x < $diff; $x++) {
                array_push($datasetColors, $this->genColor());
            }
        }

        $datasets = [
            [
                'label' => $datasetLabel,
                'data' => $datasetValues,
                'backgroundColor' => $datasetColors,
            ],
        ];

        $chart = [
            'id' => \Illuminate\Support\Str::uuid(),
            'type' => $request->type,
            'title' => $request->title,
            'width' => 712,
            'height' => 400,
            'datasetIdKey' => 'label',
            'data' => [
                'labels' => $labels,
                'datasets' => $datasets,
            ],
        ];

        $reportPage = $report->reportPages()->create([
            'content_type' => 'chart',
            'title' => $request->pageTitle,
            'content' => $request->pageContent,
            'include_header' => true,
            'include_footer' => true,
            'report_id' => $report->id,
            'meta' => [
                'chart' => $chart,
            ],
        ]);

        return ReportPageResource::make($reportPage);
    }

    protected function appParticipantsByColumn(Model $app, string $column)
    {
        $result = Attendee::join('app_attendee', 'attendees.id', '=', 'app_attendee.attendee_id')
            ->select("attendees.{$column}", DB::raw("COUNT(attendees.{$column}) as attendees_count"))
            ->groupBy("attendees.{$column}")
            ->where('app_attendee.app_id', $app->id)
            ->get();

        return [
            $result->pluck($column)->toArray(),
            'Participants',
            $result->pluck('attendees_count')->toArray(),
        ];
    }

    protected function showParticipantsByColumn(Model $show, string $column)
    {
        $result = Attendee::where('show_id', $show->id)
            ->select($column, DB::raw("COUNT({$column}) as attendees_count"))
            ->groupBy($column)
            ->get();

        return [
            $result->pluck($column)->toArray(),
            'Participants',
            $result->pluck('attendees_count')->toArray(),
        ];
    }

    protected function eventsByMetaColumn(Model $model, string $column)
    {
        $modelName = strtolower(class_basename($model));

        $result = Event::where("{$modelName}_id", $model->id)
            ->whereNotNull('meta->video')
            ->select("meta->{$column} as {$column}", DB::raw("COUNT(JSON_EXTRACT(meta, \"$.{$column}\")) as times_count"))
            ->groupBy("meta->{$column}")
            ->get();

        return [
            $result->pluck($column)->toArray(),
            'times',
            $result->pluck('times_count')->toArray(),
        ];
    }

    protected function genColor()
    {
        return sprintf('#%06X', mt_rand(0, 0xFFFFFF));
    }
}
