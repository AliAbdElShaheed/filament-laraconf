<?php

namespace App\Filament\Resources\AttendeeResource\Widgets;

use App\Filament\Resources\AttendeeResource\Pages\ListAttendees;
use App\Models\Attendee;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class AttendeesChartWidget extends ChartWidget
{
    use InteractsWithPageTable;

    protected static ?string $heading = 'Attendees SignUps Over Time';

    protected static ?string $maxHeight = '200px';

    protected int | string | array $columnSpan = 'full';


    //protected static ?string $pollingInterval = null //(if there are no interactive with table) Uncomment to enable live updates, but it may cause performance issues with large datasets
    protected static ?string $pollingInterval = '1s';


    public ?string $filter = '6month';
    protected function getFilters(): ?array
    {
        return [
            'week' => 'This Week',
            'month' => 'This Month',
            '3month3' => 'Last 3 Months',
            '6month' => 'Last 6 Months',
            'lastYear' => 'Last Year',
            'year' => 'This Year',
        ];
    }

    protected function getTablePage(): string
    {
        return ListAttendees::class;
    }

    protected function getData(): array
    {
        $filter = $this->filter ?? '6month';

        $query = $this->getPageTableQuery();
        $query->getQuery()->orders = [];

        match ($filter) {
            'week' => $data = Trend::query($query)
                    ->between(
                        start: now()->startOfWeek(),
                        end: now(),
                    )
                    ->perDay()
                    ->count(),
            'month' => $data = Trend::query($query)
                    ->between(
                        start: now()->startOfMonth(),
                        end: now(),
                    )
                    ->perDay()
                    ->count(),
            '3month3' => $data = Trend::query($query)
                    ->between(
                        start: now()->subMonths(3)->startOfMonth(),
                        end: now(),
                    )
                    ->perMonth()
                    ->count(),
            'lastYear' => $data = Trend::query($query)
                    ->between(
                        start: now()->subYear()->startOfYear(),
                        end: now(),
                    )
                    ->perMonth()
                    ->count(),
            'year' => $data = Trend::query($query)
                    ->between(
                        start: now()->startOfYear(),
                        end: now(),
                    )
                    ->perMonth()
                    ->count(),
            default => $data = Trend::query($query)
                    ->between(
                        start: now()->subMonths(6)->startOfMonth(),
                        end: now(),
                    )
                    ->perMonth()
                    ->count(),
        };
       /* $data = Trend::model(Attendee::class)
            ->between(
                start: now()->subMonths(6)->startOfMonth(),
                end: now(),
            )
            ->perMonth()
            ->count();*/

        return [
            'datasets' => [
                [
                    'label' => 'SignUps',
                    'data' => $data->map(fn (TrendValue $value) => $value->aggregate),
                ],
            ],
            'labels' => $data->map(fn (TrendValue $value) => $value->date),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
