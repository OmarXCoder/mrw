<?php
namespace Mrw\ReportPageGenerator\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\ReportPageResource;
use App\Models\App;
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
            'queryResource' => ['required'],
            'queryField' => ['required'],
            'datasets.*.label' => ['required'],
            'datasets.*.whereOperator' => ['required_with:whereKey'],
            'datasets.*.whereValue' => ['required_with:whereKey'],
        ]);

        $reportable = match ($request->reportableType) {
            'show' => Show::find($request->reportableId),
            'app' => App::find($request->reportableId),
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

        $allLabels = match ($request->queryResource) {
            'attendees' => $this->getAttendeeFieldValuesAsLabels($request, $reportable),
            'events' => $this->getEventFieldValuesAsLabels($request, $reportable),
        };

        foreach ($request->datasets as $dataset) {
            $resultValues = match ($request->queryResource) {
                'attendees' => $this->groupAttendeesByFieldValue($request, $reportable, $this->getConditionParameters($request, $dataset)),
                'events' => $this->groupEventsByFieldValue($request, $reportable, $this->getConditionParameters($request, $dataset)),
            };

            foreach ($allLabels as $label) {
                $labelValues[$label][] = isset($resultValues[$label]) ? $resultValues[$label] : null;
            }
        }

        $filteredLabelValues = collect($labelValues)
            ->filter(
                fn ($value) => collect($value)->some(fn ($item) => $item != null)
            )
            ->toArray();

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

    protected function getAttendeeFieldValuesAsLabels(Request $request, Model $model)
    {
        return $model->attendees()
            ->when($request->whereKey === 'app_id', function ($query) {
                $query->join('app_attendee', 'app_attendee.attendee_id', '=', 'attendees.id');
            })
            ->select($request->queryField)
            ->when($request->whereKey, function ($query) use ($request) {
                foreach ($request->datasets as $key => $dataset) {
                    if (!$condition = $this->getConditionParameters($request, $dataset)) {
                        continue;
                    }

                    $whereMethod = $key === 0 ? 'where' : 'orWhere';

                    $query->{$whereMethod}(...$condition);
                }
            })
            ->pluck($request->queryField)
            ->unique()
            ->values()
            ->toArray();
    }

    protected function getEventFieldValuesAsLabels(Request $request, Model $model)
    {
        $modelName = strtolower(class_basename($model));

        $metaField = $request->queryField;

        return Event::select("meta->{$metaField} as {$metaField}")
            ->whereNotNull("meta->{$metaField}")
            ->where(["{$modelName}_id" => $model->id, 'event_code' => $request->eventCode])
            ->when($request->whereKey, function ($query) use ($request) {
                foreach ($request->datasets as $key => $dataset) {
                    if (!$condition = $this->getConditionParameters($request, $dataset)) {
                        continue;
                    }

                    $whereMethod = $key === 0 ? 'where' : 'orWhere';

                    $query->{$whereMethod}(...$condition);
                }
            })
            ->pluck($metaField)
            ->unique()
            ->values()
            ->toArray();
    }

    protected function groupAttendeesByFieldValue(Request $request, Model $model, ?array $condition)
    {
        $column = $request->queryField;

        return $model->attendees()
            ->when($request->whereKey === 'app_id', function ($query) {
                $query->join('app_attendee', 'app_attendee.attendee_id', '=', 'attendees.id');
            })
            ->select($column, DB::raw("COUNT({$column}) as attendees_count"))
            ->when($condition, fn ($query) => $query->where(...$condition))
            ->groupBy($column)
            ->pluck('attendees_count', $column)
            ->toArray();
    }

    protected function groupEventsByFieldValue(Request $request, Model $model, ?array $condition)
    {
        $modelName = strtolower(class_basename($model));

        $metaField = $request->queryField;

        return Event::where(["{$modelName}_id" => $model->id, 'event_code' => $request->eventCode])
            ->whereNotNull("meta->{$metaField}")
            ->select("meta->{$metaField} as {$metaField}", DB::raw("COUNT(JSON_EXTRACT(meta, \"$.{$metaField}\")) as times_count"))
            ->when($condition, fn ($query) => $query->where(...$condition))
            ->groupBy("meta->{$metaField}")
            ->pluck('times_count', $metaField)
            ->toArray();
    }

    protected function getConditionParameters(Request $request, ?array $dataset)
    {
        if (
            empty($request->whereKey) ||
            !is_array($dataset) ||
            !isset($dataset['whereValue']) ||
            !in_array($dataset['whereOperator'], ['=', '!=', '>', '<', '>=', '<='])
        ) {
            return null;
        }

        $whereKey = $request->whereKey;

        if ($request->queryResource === 'attendees' && $whereKey === 'app_id') {
            $whereKey = 'app_attendee.app_id';
        }

        if ($request->queryResource === 'events' && $whereKey !== 'app_id') {
            $whereKey = "meta->{$whereKey}";
        }

        return [
            $whereKey,
            $dataset['whereOperator'],
            $dataset['whereValue'],
        ];
    }

    protected function genColor()
    {
        return sprintf('#%06X', mt_rand(0, 0xFFFFFF));
    }
}
