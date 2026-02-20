<?php

namespace App\Filament\Resources\Canvas\Pages;

use App\Filament\Resources\Canvas\CanvaResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditCanva extends EditRecord
{
    protected static string $resource = CanvaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
