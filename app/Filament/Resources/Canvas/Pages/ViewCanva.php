<?php

namespace App\Filament\Resources\Canvas\Pages;

use App\Filament\Resources\Canvas\CanvaResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewCanva extends ViewRecord
{
    protected static string $resource = CanvaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
