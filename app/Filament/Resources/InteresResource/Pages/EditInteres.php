<?php

namespace App\Filament\Resources\InteresResource\Pages;

use App\Filament\Resources\InteresResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditInteres extends EditRecord
{
    protected static string $resource = InteresResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
