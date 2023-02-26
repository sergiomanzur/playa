<?php

namespace App\Filament\Resources\ManzanaResource\Pages;

use App\Filament\Resources\ManzanaResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditManzana extends EditRecord
{
    protected static string $resource = ManzanaResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
