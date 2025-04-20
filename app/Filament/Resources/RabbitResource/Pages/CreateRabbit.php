<?php

namespace App\Filament\Resources\RabbitResource\Pages;

use App\Filament\Resources\RabbitResource;
use Filament\Resources\Pages\CreateRecord;

class CreateRabbit extends CreateRecord
{
    protected static string $resource = RabbitResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}