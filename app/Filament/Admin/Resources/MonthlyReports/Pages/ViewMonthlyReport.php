<?php

namespace App\Filament\Admin\Resources\MonthlyReports\Pages;

use App\Filament\Admin\Resources\MonthlyReports\MonthlyReportResource;
use App\Models\Transaction;
use Carbon\Carbon;
use Filament\Resources\Pages\Page;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;

class ViewMonthlyReport extends Page implements HasTable
{
    use InteractsWithRecord;
    use InteractsWithTable;

    protected static string $resource = MonthlyReportResource::class;

    protected string $view = 'filament.admin.resources.monthly-reports.pages.view-monthly-report';

    public function mount(int | string $record): void
    {
        $this->record = $this->resolveRecord($record);
    }

    public function table(Table $table): Table
    {
        $periodStart = Carbon::parse($this->record->period)->startOfMonth();
        $periodEnd = Carbon::parse($this->record->period)->endOfMonth();

        return $table
            ->query(
                Transaction::query()
                    ->where('user_id', $this->record->user_id)
                    ->whereBetween('date', [$periodStart, $periodEnd])
            )
            ->heading('Detail Transaksi - ' . $periodStart->translatedFormat('F Y'))
            ->columns([
                TextColumn::make('date')
                    ->label('Tanggal')
                    ->date('d M Y')
                    ->sortable(),
                TextColumn::make('name')
                    ->label('Nama Transaksi')
                    ->searchable(),
                TextColumn::make('category.name')
                    ->label('Kategori')
                    ->badge()
                    ->color(fn (string $state, $record): string => $record->category->type === 'IN' ? 'success' : 'danger'),
                TextColumn::make('amount')
                    ->label('Nominal')
                    ->money('IDR', locale: 'id_ID')
                    ->color(fn ($record): string => $record->category->type === 'IN' ? 'success' : 'danger')
                    ->sortable(),
                TextColumn::make('note')
                    ->label('Catatan')
                    ->limit(30),
            ])
            ->defaultSort('date', 'asc')
            ->actions([
                \Filament\Actions\EditAction::make()
                    ->form([
                        \Filament\Forms\Components\Select::make('user_id')
                            ->relationship('user', 'username', fn (\Illuminate\Database\Eloquent\Builder $query) => $query->whereNull('deleted_at'))
                            ->label('Pilih Investor')
                            ->visible(fn () => auth()->user() && auth()->user()->isAdmin())
                            ->required(fn () => auth()->user() && auth()->user()->isAdmin()),
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
                            ->required(),
                        \Filament\Forms\Components\Textarea::make('note')
                            ->label('Keterangan / Catatan')
                            ->nullable(),
                    ])
                    ->visible(fn () => auth()->check() && auth()->user()->isAdmin()),
                \Filament\Actions\DeleteAction::make()
                    ->visible(fn () => auth()->check() && auth()->user()->isAdmin()),
            ]);
    }

    protected function getHeaderWidgets(): array
    {
        return [
            \App\Filament\Admin\Resources\MonthlyReports\Widgets\MonthlyReportStatsWidget::class,
        ];
    }

    public function getHeaderWidgetsData(): array
    {
        return [
            'record' => $this->record,
        ];
    }
}
