<?php

namespace App\Filament\Resources\Canvas\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Schema;

class CanvaInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Fieldset::make('owner')
                    ->schema([
                        TextEntry::make('owner.name')
                            ->placeholder('-'),
                        TextEntry::make('owner.email')
                            ->placeholder('-'),
                    ]),
                TextEntry::make('name'),
                TextEntry::make('width')
                    ->numeric(),
                TextEntry::make('height')
                    ->numeric(),
                TextEntry::make('category')
                    ->badge()
                    ->placeholder('-'),
                TextEntry::make('access')
                    ->badge()
                    ->placeholder('-'),
                TextEntry::make('visibility')
                    ->badge(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('live_player_count')
                    ->numeric(),

            ]);
    }
}
