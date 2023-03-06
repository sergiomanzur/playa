<?php

namespace App\Filament\Resources\BalancesResource\Pages;

use App\Filament\Resources\BalancesResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBalances extends ListRecords
{
    protected static string $resource = BalancesResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
