<?php

namespace App\Filament\Admin\Resources\Users\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('username')
                    ->label('Username')
                    ->required()
                    ->unique(ignoreRecord: true),
                TextInput::make('email')
                    ->label('Email Address')
                    ->email()
                    ->nullable(),
                Select::make('role')
                    ->label('Peran (Role)')
                    ->options(fn () => auth()->user()->role === 'DEV' ? [
                        'USER' => 'Investor',
                        'ADMIN' => 'Administrator',
                        'DEV' => 'Developer',
                    ] : [
                        'USER' => 'Investor',
                        'ADMIN' => 'Administrator',
                    ])
                    ->default('USER')
                    ->required(),
                TextInput::make('password')
                    ->label('Password')
                    ->password()
                    ->dehydrated(fn ($state) => filled($state))
                    ->dehydrateStateUsing(fn ($state) => \Illuminate\Support\Facades\Hash::make($state))
                    ->required(fn (string $operation): bool => $operation === 'create'),
            ]);
    }
}
