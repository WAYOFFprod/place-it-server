<?php

namespace App\Filament\Resources\Canvas\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class CanvaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('user_id')
                    ->numeric(),
                TextInput::make('name')
                    ->required(),
                TextInput::make('width')
                    ->required()
                    ->numeric(),
                TextInput::make('height')
                    ->required()
                    ->numeric(),
                Select::make('category')
                    ->options(['pixelwar' => 'Pixelwar', 'artistic' => 'Artistic', 'free' => 'Free']),
                Select::make('access')
                    ->options([
                        'open' => 'Open',
                        'invite_only' => 'Invite only',
                        'request_only' => 'Request only',
                        'closed' => 'Closed',
                    ]),
                Select::make('visibility')
                    ->options(['public' => 'Public', 'friends_only' => 'Friends only', 'private' => 'Private'])
                    ->required(),
                TextInput::make('colors'),
                TextInput::make('live_player_count')
                    ->required()
                    ->numeric()
                    ->default(0),
            ]);
    }
}
