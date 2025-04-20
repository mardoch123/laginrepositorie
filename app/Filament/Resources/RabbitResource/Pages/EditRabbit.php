<?php

namespace App\Filament\Resources\RabbitResource\Pages;

use App\Filament\Resources\RabbitResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRabbit extends EditRecord
{
    protected static string $resource = RabbitResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}