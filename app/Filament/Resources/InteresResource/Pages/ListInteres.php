<?php

namespace App\Filament\Resources\InteresResource\Pages;

use App\Filament\Resources\InteresResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListInteres extends ListRecords
{
    protected static string $resource = InteresResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
