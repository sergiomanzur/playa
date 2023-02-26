<?php

namespace App\Filament\Resources\PromesasResource\Pages;

use App\Filament\Resources\PromesasResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPromesas extends ListRecords
{
    protected static string $resource = PromesasResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
