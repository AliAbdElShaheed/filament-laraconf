<?php

namespace App\Filament\Resources\SpeakerResource\Pages;

use App\Filament\Resources\SpeakerResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewSpeaker extends ViewRecord
{
    protected static string $resource = SpeakerResource::class;


    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
            Actions\DeleteAction::make(),
        ];
    } // end getHeaderActions
}
