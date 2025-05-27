<?php

namespace App\Filament\Resources\TalkResource\Pages;

use App\Enums\TalkStatus;
use App\Filament\Resources\TalkResource;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;

class ListTalks extends ListRecords
{
    protected static string $resource = TalkResource::class;

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('All Talks')
                ->icon('heroicon-m-star'),
            'approved' => Tab::make('Approved')
                ->icon('heroicon-o-check-circle')
                ->modifyQueryUsing(function ($query) {
                    return $query->where('status', TalkStatus::APPROVED);
                }),
            'rejected' => Tab::make('Rejected')
                ->icon('heroicon-o-x-circle')
                ->modifyQueryUsing(function ($query) {
                    return $query->where('status', TalkStatus::REJECTED);
                }),
            'submitted' => Tab::make('Submitted')
                ->icon('heroicon-o-paper-airplane')
                ->modifyQueryUsing(function ($query) {
                    return $query->where('status', TalkStatus::SUBMITTED);
                }),
        ];
    }

    /*public function getTabs(): array
    {
        return [
            'all' => Tab::make('All Talks'),
            'active' => Tab::make('Active customers')
                ->modifyQueryUsing(fn(Builder $query) => $query->where('active', true)),
            'inactive' => Tab::make('Inactive customers')
                ->modifyQueryUsing(fn(Builder $query) => $query->where('active', false)),
        ];
    }*/

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
