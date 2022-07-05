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

class ReportChartsController extends Controller
{
    public function store(Request $request, Report $report)
    {
        $request->validate([
            'contentType' => ['required'],
            'title' => ['required'],
            'type' => ['required'],
            'table' => ['required'],
            'column' => ['required'],
            'datasets.*.label' => ['required'],
        ]);

        $reportable = match ($request->reportableType) {
            Show::class => Show::find($request->reportableId),
            App::class => App::find($request->reportableId),
        };

        [$labels, $datasets] = $this->buildChart($request, $reportable);

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
            'content_type' => $request->contentType,
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

    protected function buildChart(Request $request, Model $reportable)
    {
        $datasets = [];
        $allLabels = [];
        $labelValues = [];

        $allLabels = match ($request->table) {
            'app-participants' => $this->getAppAttendeesLabelsByColumn($request, $reportable),
            'show-participants' => $this->getShowAttendeesLabelsByColumn($request, $reportable),
            'app-events', 'show-events' => $this->getEventLabelsByColumn($request, $reportable),
        };

        foreach ($request->datasets as $dataset) {
            $resultValues = match ($request->table) {
                'app-participants' => $this->appParticipantsByColumn($reportable, $request->column, $this->getConditionParameters($request, $dataset)),
                'show-participants' => $this->showParticipantsByColumn($reportable, $request->column, $this->getConditionParameters($request, $dataset)),
                'app-events', 'show-events' => $this->eventsByMetaColumn($reportable, $request->column, $this->getConditionParameters($request, $dataset)),
            };

            foreach ($allLabels as $label) {
                $labelValues[$label][] = isset($resultValues[$label]) ? $resultValues[$label] : null;
            }
        }

        $filteredLabelValues = collect($labelValues)->filter(function ($value, $key) {
            return collect($value)->some(fn ($item) => $item != null);
        })->toArray();

        foreach ($request->datasets as $index => $dataset) {
            $datasetData = [];

            foreach ($filteredLabelValues as $key => $value) {
                $datasetData[] = $value[$index];
            }

            $datasetColors = $dataset['colors'];

            if ($request->type === 'pie' && count($datasetColors) < count($datasetData)) {
                $diff = count($datasetData) - count($datasetColors);
                for ($x = 0; $x < $diff; $x++) {
                    array_push($datasetColors, $this->genColor());
                }
            }

            array_push($datasets, [
                'label' => $dataset['label'],
                'data' => $datasetData,
                'backgroundColor' => $datasetColors,
            ]);
        }

        return [
            $labels = array_keys($filteredLabelValues),
            $datasets,
        ];
    }

    protected function getAppAttendeesLabelsByColumn(Request $request, App $app)
    {
        $result = Attendee::join('app_attendee', 'attendees.id', '=', 'app_attendee.attendee_id')
            ->select('attendees.*')
            ->where('app_attendee.app_id', $app->id)
            ->when($request->whereKey, function ($query) use ($request) {
                foreach ($request->datasets as $key => $dataset) {
                    if (!$condition = $this->getConditionParameters($request, $dataset)) {
                        continue;
                    }

                    $whereMethod = $key === 0 ? 'where' : 'orWhere';

                    [$whereKey, $whereOperator, $whereValue] = $condition;

                    $query->{$whereMethod}("attendees.{$whereKey}", $whereOperator, $whereValue);
                }
            })
            ->get()->pluck($request->column)->unique()->values()->toArray();

        return $result;
    }

    protected function getShowAttendeesLabelsByColumn(Request $request, Show $show)
    {
        return Attendee::select($request->column)
            ->where('show_id', $show->id)
            ->when($request->whereKey, function ($query) use ($request) {
                foreach ($request->datasets as $key => $dataset) {
                    if (!$condition = $this->getConditionParameters($request, $dataset)) {
                        continue;
                    }

                    $whereMethod = $key === 0 ? 'where' : 'orWhere';

                    $query->{$whereMethod}(...$condition);
                }
            })
            ->get()->pluck($request->column)->unique()->values()->toArray();
    }

    protected function getEventLabelsByColumn(Request $request, Model $model)
    {
        $modelName = strtolower(class_basename($model));

        return Event::select("meta->{$request->column} as {$request->column}")
            ->whereNotNull("meta->{$request->column}")
            ->where("{$modelName}_id", $model->id)
            ->when($request->whereKey, function ($query) use ($request) {
                foreach ($request->datasets as $key => $dataset) {
                    if (!$condition = $this->getConditionParameters($request, $dataset)) {
                        continue;
                    }

                    $whereMethod = $key === 0 ? 'where' : 'orWhere';

                    [$whereKey, $whereOperator, $whereValue] = $condition;

                    $query->{$whereMethod}("meta->{$whereKey}", $whereOperator, $whereValue);
                }
            })
            ->get()->pluck($request->column)->unique()->values()->toArray();
    }

    protected function appParticipantsByColumn(Model $app, string $column, ?array $condition)
    {
        $result = Attendee::join('app_attendee', 'attendees.id', '=', 'app_attendee.attendee_id')
            ->select("attendees.{$column}", DB::raw("COUNT(attendees.{$column}) as attendees_count"))
            ->when($condition, fn ($query) => $query->where(...$condition))
            ->groupBy("attendees.{$column}")
            ->where('app_attendee.app_id', $app->id)
            ->get();

        return $result->pluck('attendees_count', $column)->toArray();
    }

    protected function showParticipantsByColumn(Model $show, string $column, ?array $condition)
    {
        $result = Attendee::where('show_id', $show->id)
            ->select($column, DB::raw("COUNT({$column}) as attendees_count"))
            ->when($condition, fn ($query) => $query->where(...$condition))
            ->groupBy($column)
            ->get();

        return $result->pluck('attendees_count', $column)->toArray();
    }

    protected function eventsByMetaColumn(Model $model, string $column, ?array $condition)
    {
        $modelName = strtolower(class_basename($model));

        $result = Event::where("{$modelName}_id", $model->id)
            ->whereNotNull("meta->{$column}")
            ->select("meta->{$column} as {$column}", DB::raw("COUNT(JSON_EXTRACT(meta, \"$.{$column}\")) as times_count"))
            ->when($condition, function ($query) use ($condition) {
                [$key, $operator, $value] = $condition;
                $query->where("meta->{$key}", $operator, $value);
            })
            ->groupBy("meta->{$column}")
            ->get();

        return $result->pluck('times_count', $column)->toArray();
    }

    protected function getConditionParameters(Request $request, ?array $dataset)
    {
        if (
            empty($request->whereKey) ||
            !is_array($dataset) ||
            !isset($dataset['whereValue']) ||
            !in_array($dataset['whereOperator'], ['=', '!=', '>', '<', '>=', '<=', 'like'])
        ) {
            return null;
        }

        return [
            $request->whereKey,
            $dataset['whereOperator'],
            $dataset['whereOperator'] === 'like' ? '%' . $dataset['whereValue'] . '%' : $dataset['whereValue'],
        ];
    }

    protected function genColor()
    {
        return sprintf('#%06X', mt_rand(0, 0xFFFFFF));
    }
}
