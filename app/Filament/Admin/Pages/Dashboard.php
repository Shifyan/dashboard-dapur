<?php

namespace App\Filament\Admin\Pages;

use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;
use Filament\Pages\Dashboard\Concerns\HasFiltersForm;

class Dashboard extends \Filament\Pages\Dashboard
{
    use HasFiltersForm;

    public function filtersForm(Schema $schema): Schema
    {
        $options = [
            'all' => 'Semua Data',
            'admin' => 'Data Pelaku Usaha',
            'investors' => 'Data Semua Investor',
        ];

        // Tambahkan pilihan nama masing-masing investor (optimized pluck)
        $investorOptions = \App\Models\User::where('role', 'USER')->pluck('username', 'id');
        foreach ($investorOptions as $id => $username) {
            $options['investor_' . $id] = 'Investor: ' . $username;
        }

        return $schema
            ->components([
                Select::make('role_filter')
                    ->label('Tampilan Data')
                    ->options($options)
                    ->default('all')
                    // Hanya ditampilkan untuk admin
                    ->visible(fn () => auth()->user()->isAdmin()),
            ]);
    }
}
