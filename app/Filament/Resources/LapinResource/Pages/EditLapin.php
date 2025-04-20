<?php

namespace App\Filament\Resources\LapinResource\Pages;

use App\Filament\Resources\LapinResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLapin extends EditRecord
{
    protected static string $resource = LapinResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
