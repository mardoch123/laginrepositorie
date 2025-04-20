<?php

namespace App\Filament\Resources\LapinResource\Pages;

use App\Filament\Resources\LapinResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListLapins extends ListRecords
{
    protected static string $resource = LapinResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Ajouter un lapin'),
        ];
    }
}