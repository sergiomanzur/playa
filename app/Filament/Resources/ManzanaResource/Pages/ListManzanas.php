<?php

namespace App\Filament\Resources\ManzanaResource\Pages;

use App\Filament\Resources\ManzanaResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListManzanas extends ListRecords
{
    protected static string $resource = ManzanaResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
