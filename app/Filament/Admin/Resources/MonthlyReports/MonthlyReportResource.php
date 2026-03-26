<?php

namespace App\Filament\Admin\Resources\MonthlyReports;

use App\Filament\Admin\Resources\MonthlyReports\Pages\CreateMonthlyReport;
use App\Filament\Admin\Resources\MonthlyReports\Pages\EditMonthlyReport;
use App\Filament\Admin\Resources\MonthlyReports\Pages\ListMonthlyReports;
use App\Filament\Admin\Resources\MonthlyReports\Pages\ViewMonthlyReport;
use App\Filament\Admin\Resources\MonthlyReports\Schemas\MonthlyReportForm;
use App\Filament\Admin\Resources\MonthlyReports\Schemas\MonthlyReportInfolist;
use App\Filament\Admin\Resources\MonthlyReports\Tables\MonthlyReportsTable;
use App\Models\MonthlyReport;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class MonthlyReportResource extends Resource
{
    protected static ?string $model = MonthlyReport::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-document-text';

    public static function getModelLabel(): string
    {
        return 'Laporan Keuangan';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Laporan Keuangan';
    }

    public static function form(Schema $schema): Schema
    {
        return MonthlyReportForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return MonthlyReportsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListMonthlyReports::route('/'),
            // 'create' => CreateMonthlyReport::route('/create'),
            'view' => ViewMonthlyReport::route('/{record}/detail'),
            // 'edit' => EditMonthlyReport::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        $query = parent::getEloquentQuery();
        
        $user = auth()->user();
        if (! $user->isAdmin()) {
            // Investor hanya melihat laporannya sendiri
            $query->where('user_id', $user->id);
        }

        return $query;
    }
}
