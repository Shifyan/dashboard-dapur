<?php

namespace App\Filament\Admin\Resources\MonthlyReports\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Table;

class MonthlyReportsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                \Filament\Tables\Columns\TextColumn::make('period')
                    ->label('Periode')
                    ->date('F Y')
                    ->sortable()
                    ->searchable(),
                \Filament\Tables\Columns\TextColumn::make('user.username')
                    ->label('Nama')
                    ->sortable()
                    ->searchable(),
                \Filament\Tables\Columns\TextColumn::make('income')
                    ->label('Pemasukan')
                    ->money('IDR', locale: 'id_ID')
                    ->sortable(),
                \Filament\Tables\Columns\TextColumn::make('expense')
                    ->label('Pengeluaran')
                    ->money('IDR', locale: 'id_ID')
                    ->sortable(),
                \Filament\Tables\Columns\TextColumn::make('net_profit')
                    ->label('Laba Bersih')
                    ->money('IDR', locale: 'id_ID')
                    ->sortable(),
            ])
            ->filters([
                \Filament\Tables\Filters\SelectFilter::make('user_id')
                    ->label('Pilihan User')
                    ->relationship('user', 'username')
                    ->visible(fn () => auth()->user()->role === 'ADMIN'),

                \Filament\Tables\Filters\Filter::make('period')
                    ->form([
                        \Filament\Forms\Components\DatePicker::make('published_from')
                            ->label('Dari Bulan'),
                        \Filament\Forms\Components\DatePicker::make('published_until')
                            ->label('Sampai Bulan'),
                    ])
                    ->query(function (\Illuminate\Database\Eloquent\Builder $query, array $data): \Illuminate\Database\Eloquent\Builder {
                        return $query
                            ->when(
                                $data['published_from'],
                                fn (\Illuminate\Database\Eloquent\Builder $query, $date): \Illuminate\Database\Eloquent\Builder => $query->whereDate('period', '>=', $date),
                            )
                            ->when(
                                $data['published_until'],
                                fn (\Illuminate\Database\Eloquent\Builder $query, $date): \Illuminate\Database\Eloquent\Builder => $query->whereDate('period', '<=', $date),
                            );
                    })
            ])
            ->recordActions([
                \Filament\Actions\ViewAction::make(),
                EditAction::make()
                    ->visible(fn () => auth()->user()->role === 'ADMIN'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
