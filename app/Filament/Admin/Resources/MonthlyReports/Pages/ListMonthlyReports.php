<?php

namespace App\Filament\Admin\Resources\MonthlyReports\Pages;

use App\Filament\Admin\Resources\MonthlyReports\MonthlyReportResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListMonthlyReports extends ListRecords
{
    protected static string $resource = MonthlyReportResource::class;

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\Action::make('tambah_laporan')
                ->label('Tambah Transaksi')
                ->visible(fn () => auth()->user()->isAdmin())
                ->model(\App\Models\Transaction::class)
                ->form([
                    \Filament\Forms\Components\Select::make('user_id')
                        ->relationship('user', 'username', fn (\Illuminate\Database\Eloquent\Builder $query) => $query->whereNull('deleted_at'))
                        ->label('Pilih Investor')
                        ->visible(fn () => auth()->user()->isAdmin())
                        ->required(fn () => auth()->user()->isAdmin()),
                    \Filament\Forms\Components\Select::make('category_id')
                        ->relationship('category', 'name')
                        ->label('Kategori')
                        ->searchable()
                        ->preload()
                        ->allowHtml()
                        ->getOptionLabelFromRecordUsing(function (\App\Models\Category $record) {
                            return view('filament.components.category-badge', ['record' => $record])->render();
                        })
                        ->required(),
                    \Filament\Forms\Components\TextInput::make('name')
                        ->label('Nama Transaksi (Msl: Beli Piring)')
                        ->required(),
                    \Filament\Forms\Components\TextInput::make('amount')
                        ->label('Nominal')
                        ->numeric()
                        ->prefix('Rp')
                        ->required(),
                    \Filament\Forms\Components\DatePicker::make('date')
                        ->label('Tanggal')
                        ->default(now())
                        ->required(),
                    \Filament\Forms\Components\Textarea::make('note')
                        ->label('Keterangan / Catatan')
                        ->nullable(),
                ])
                ->action(function (array $data) {
                    if (! auth()->user()->isAdmin()) {
                        $data['user_id'] = auth()->id();
                    }
                    if (empty($data['note'])) {
                        $category = \App\Models\Category::find($data['category_id']);
                        $data['note'] = 'Ditambahkan otomatis (' . strtolower($category->name ?? 'transaksi') . ')';
                    }
                    \App\Models\Transaction::create($data);
                })
                ->successNotificationTitle('Transaksi Berhasil Dicatat!')
        ];
    }
}
