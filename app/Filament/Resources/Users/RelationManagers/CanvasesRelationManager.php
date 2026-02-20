<?php

namespace App\Filament\Resources\Users\RelationManagers;

use App\Filament\Resources\Canvas\CanvaResource;
use Filament\Actions\CreateAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;

class CanvasesRelationManager extends RelationManager
{
    protected static string $relationship = 'canvases';

    protected static ?string $relatedResource = CanvaResource::class;

    public function table(Table $table): Table
    {
        return $table
            ->headerActions([
                CreateAction::make(),
            ]);
    }
}
