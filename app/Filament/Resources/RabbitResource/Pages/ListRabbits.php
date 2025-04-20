<?php

namespace App\Filament\Resources\RabbitResource\Pages;

use App\Filament\Resources\RabbitResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRabbits extends ListRecords
{
    protected static string $resource = RabbitResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Ajouter un lapin'),
        ];
    }
}