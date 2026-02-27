<?php

namespace App\Filament\Resources\Canvas;

use App\Filament\Resources\Canvas\Pages\CreateCanva;
use App\Filament\Resources\Canvas\Pages\EditCanva;
use App\Filament\Resources\Canvas\Pages\ListCanvas;
use App\Filament\Resources\Canvas\Pages\ViewCanva;
use App\Filament\Resources\Canvas\RelationManagers\ParticipatesRelationManager;
use App\Filament\Resources\Canvas\Schemas\CanvaForm;
use App\Filament\Resources\Canvas\Schemas\CanvaInfolist;
use App\Filament\Resources\Canvas\Tables\CanvasTable;
use App\Models\Canva;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class CanvaResource extends Resource
{
    protected static ?string $model = Canva::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return CanvaForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return CanvaInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CanvasTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            'participations' => ParticipatesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCanvas::route('/'),
            'create' => CreateCanva::route('/create'),
            'view' => ViewCanva::route('/{record}'),
            'edit' => EditCanva::route('/{record}/edit'),
        ];
    }
}
