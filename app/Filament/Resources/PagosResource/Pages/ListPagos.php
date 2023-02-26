<?php

namespace App\Filament\Resources\PagosResource\Pages;

use App\Filament\Resources\PagosResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPagos extends ListRecords
{
    protected static string $resource = PagosResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
