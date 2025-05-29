<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\AttendeeResource;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use Filament\Widgets\Widget;

class TestCustomWidget extends Widget implements HasActions, HasForms
{
    use InteractsWithActions, InteractsWithForms;

    protected int | string | array $columnSpan = 'full';

    protected static string $view = 'filament.widgets.test-custom-widget';


    public function callNotification() : Action
    {
        return Action::make('callNotification')
            ->label('Call Notification')
            ->button()
            ->icon('heroicon-o-bell')
            ->color('warning')
            ->action(function () {
                Notification::make()->warning()->title('Test Notification')
                    ->body('This is a test notification from the custom widget.')
                    //->duration(1500)
                    ->persistent()
                    //->icon('heroicon-o-bell')
                        ->actions([
                            \Filament\Notifications\Actions\Action::make('goToAttendance')
                                ->label('Attendance')
                                ->button()
                                ->url(AttendeeResource::getUrl('index')),
                        \Filament\Notifications\Actions\Action::make('goToFirstAttendee')
                            ->label('1\'st Attendee')
                            ->button()
                            ->color('warning')
                            /*->url(AttendeeResource::getUrl('edit', [
                                'record' => AttendeeResource::getModel()::query()->first()->id,
                            ])),*/
                            ->url(AttendeeResource::getUrl('edit', ['record' => 1])),
                            \Filament\Notifications\Actions\Action::make('dismiss')
                                ->label('Dismiss')
                                ->color('secondary')
                        ->action(function () {
                            //  want to dismiss the notification})
                            Notification::make()->dismiss();
                        }),
                        ])->send();
            });

    }
}
