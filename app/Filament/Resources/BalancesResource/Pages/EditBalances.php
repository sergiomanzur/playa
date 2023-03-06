<?php

namespace App\Filament\Resources\BalancesResource\Pages;

use App\Filament\Resources\BalancesResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBalances extends EditRecord
{
    protected static string $resource = BalancesResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
