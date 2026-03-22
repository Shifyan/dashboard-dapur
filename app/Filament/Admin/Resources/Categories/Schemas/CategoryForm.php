<?php

namespace App\Filament\Admin\Resources\Categories\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class CategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nama Kategori')
                    ->required(),
                Select::make('type')
                    ->label('Tipe')
                    ->options([
                        'IN' => 'Pendapatan (IN)',
                        'OUT' => 'Pengeluaran (OUT)',
                    ])
                    ->required(),
            ]);
    }
}
