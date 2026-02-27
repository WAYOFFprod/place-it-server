<?php

namespace App\Filament\Resources\Canvas\Pages;

use App\Filament\Resources\Canvas\CanvaResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCanvas extends ListRecords
{
    protected static string $resource = CanvaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
