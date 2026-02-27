<?php

namespace App\Filament\Resources\Canvas\RelationManagers;

use App\Filament\Resources\Users\UserResource;
use Filament\Actions\AttachAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DetachAction;
use Filament\Actions\DetachBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ParticipatesRelationManager extends RelationManager
{
    protected static string $relationship = 'participates';

    protected static ?string $relatedResource = UserResource::class;

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('id')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('id'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                TextColumn::make('id')
                    ->searchable(),
                TextColumn::make('status'),
                TextColumn::make('name'),
            ]);
        // ->filters([
        //     TrashedFilter::make(),
        // ])
        // ->headerActions([
        //     CreateAction::make(),
        //     AttachAction::make(),
        // ])
        // ->recordActions([
        //     ViewAction::make(),
        //     EditAction::make(),
        //     DetachAction::make(),
        //     DeleteAction::make(),
        //     ForceDeleteAction::make(),
        //     RestoreAction::make(),
        // ])
        // ->toolbarActions([
        //     BulkActionGroup::make([
        //         DetachBulkAction::make(),
        //         DeleteBulkAction::make(),
        //         ForceDeleteBulkAction::make(),
        //         RestoreBulkAction::make(),
        //     ]),
        // ])
        // ->modifyQueryUsing(fn (Builder $query) => $query
        //     ->withoutGlobalScopes([
        //         SoftDeletingScope::class,
        //     ]));
    }
}
