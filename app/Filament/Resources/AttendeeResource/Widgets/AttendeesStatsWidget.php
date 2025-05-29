<?php

namespace App\Filament\Resources\AttendeeResource\Widgets;

use App\Filament\Resources\AttendeeResource\Pages\ListAttendees;
use App\Models\Attendee;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class AttendeesStatsWidget extends BaseWidget
{
    use InteractsWithPageTable;

    protected function getTablePage(): string
    {
        return ListAttendees::class;
    }


    protected function getColumns(): int
    {
        return 3;
    }

    protected function getStats(): array
    {
        /*return [
            Stat::make('Attendees Count', Attendee::count())
                ->description('Total number of attendees')
                ->descriptionIcon('heroicon-s-users')

                ->chart([1, 2, 3, 4, 5, 3, 1, 8]) // Placeholder for chart data
                ->color('primary'),


            Stat::make('Total Revenue', Attendee::sum('ticket_cost'))
                ->description('Total revenue from ticket sales')
                ->descriptionIcon('heroicon-s-currency-dollar')
                ->color('success'),
        ];*/
        return [
            Stat::make('Attendees Count', $this->getPageTableQuery()->count())
                ->description('Total number of attendees')
                ->descriptionIcon('heroicon-s-users')
                /*->chart([
                    'type' => 'line',
                    'data' => [
                        'labels' => Attendee::selectRaw('DATE(created_at) as date')
                            ->groupBy('date')
                            ->pluck('date')
                            ->toArray(),
                        'datasets' => [
                            [
                                'label' => 'Attendees',
                                'data' => Attendee::selectRaw('COUNT(*) as count')
                                    ->groupBy('date')
                                    ->pluck('count')
                                    ->toArray(),
                                'borderColor' => '#4F46E5',
                                'backgroundColor' => '#E0E7FF',
                            ],
                        ],
                    ],
                ])*/
                ->chart([1, 2, 3, 4, 5, 3, 1, 8]) // Placeholder for chart data
                ->color('primary'),


            Stat::make('Total Revenue', $this->getPageTableQuery()->sum('ticket_cost'))
                ->description('Total revenue from ticket sales')
                ->descriptionIcon('heroicon-s-currency-dollar')
                ->color('success'),
        ];
    }
}
