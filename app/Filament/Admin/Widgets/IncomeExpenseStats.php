<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Transaction;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

use Filament\Widgets\Concerns\InteractsWithPageFilters;

class IncomeExpenseStats extends BaseWidget
{
    use InteractsWithPageFilters;

    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $now = Carbon::now();

        $startOfThisMonth = $now->copy()->startOfMonth();
        $startOfLastMonth = $now->copy()->subMonth()->startOfMonth();
        $endOfLastMonth   = $now->copy()->subMonth()->endOfMonth();

        $baseQuery = Transaction::query();
        $user = auth()->user();

        if ($user->isAdmin()) {
            $currentFilter = $this->filters['role_filter'] ?? 'all';

            if ($currentFilter === 'admin') {
                $baseQuery->where('transactions.user_id', $user->id);
            } elseif ($currentFilter === 'investors') {
                $baseQuery->whereHas('user', function ($q) {
                    $q->where('role', 'USER');
                });
            } elseif (str_starts_with($currentFilter, 'investor_')) {
                $investorId = str_replace('investor_', '', $currentFilter);
                $baseQuery->where('transactions.user_id', $investorId);
            }
        } else {
            $baseQuery->where('transactions.user_id', $user->id);
        }

        // Satu query untuk mendapatkan semua sum (income & expense, bulan ini & bulan lalu)
        // dengan CASE WHEN, menggantikan 4 query WHERE terpisah
        $results = (clone $baseQuery)
            ->join('categories', 'transactions.category_id', '=', 'categories.id')
            ->whereIn('categories.type', ['IN', 'OUT'])
            ->whereBetween('transactions.date', [$startOfLastMonth, $now])
            ->selectRaw("
                categories.type,
                SUM(CASE WHEN transactions.date >= ? THEN transactions.amount ELSE 0 END) as this_month,
                SUM(CASE WHEN transactions.date < ?  THEN transactions.amount ELSE 0 END) as last_month
            ", [$startOfThisMonth, $startOfThisMonth])
            ->groupBy('categories.type')
            ->get()
            ->keyBy('type');

        $incomeThisMonth  = $results->get('IN')?->this_month  ?? 0;
        $incomeLastMonth  = $results->get('IN')?->last_month  ?? 0;
        $expenseThisMonth = $results->get('OUT')?->this_month ?? 0;
        $expenseLastMonth = $results->get('OUT')?->last_month ?? 0;

        // --- LABA BERSIH ---
        $profitThisMonth = $incomeThisMonth - $expenseThisMonth;
        $profitLastMonth = $incomeLastMonth - $expenseLastMonth;

        // --- KALKULASI PERSENTASE ---
        $incomeDiff       = $incomeThisMonth - $incomeLastMonth;
        $incomePercentage = $incomeLastMonth > 0 ? ($incomeDiff / $incomeLastMonth) * 100 : ($incomeThisMonth > 0 ? 100 : 0);

        $expenseDiff       = $expenseThisMonth - $expenseLastMonth;
        $expensePercentage = $expenseLastMonth > 0 ? ($expenseDiff / $expenseLastMonth) * 100 : ($expenseThisMonth > 0 ? 100 : 0);

        $profitDiff = $profitThisMonth - $profitLastMonth;
        if ($profitLastMonth != 0) {
            $profitPercentage = ($profitDiff / abs($profitLastMonth)) * 100;
        } else {
            $profitPercentage = $profitThisMonth > 0 ? 100 : ($profitThisMonth < 0 ? -100 : 0);
        }

        // --- STYLING ---
        $incomeIcon  = $incomeDiff >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down';
        $incomeColor = $incomeDiff >= 0 ? 'success' : 'danger';
        $incomeTrend = $incomeDiff >= 0 ? 'Naik' : 'Turun';

        $expenseIcon  = $expenseDiff >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down';
        $expenseColor = $expenseDiff >= 0 ? 'danger' : 'success';
        $expenseTrend = $expenseDiff >= 0 ? 'Naik' : 'Turun';

        $profitIcon  = $profitDiff >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down';
        $profitColor = $profitDiff >= 0 ? 'success' : 'danger';
        $profitTrend = $profitDiff >= 0 ? 'Naik' : 'Turun';

        return [
            Stat::make('Pemasukan Bulan Ini', 'Rp ' . number_format($incomeThisMonth, 0, ',', '.'))
                ->description(abs(round($incomePercentage, 1)) . '% ' . $incomeTrend . ' dibanding bulan lalu')
                ->descriptionIcon($incomeIcon)
                ->color($incomeColor)
                ->chart([7, 2, 10, 3, 15, 4, 17]),

            Stat::make('Pengeluaran Bulan Ini', 'Rp ' . number_format($expenseThisMonth, 0, ',', '.'))
                ->description(abs(round($expensePercentage, 1)) . '% ' . $expenseTrend . ' dibanding bulan lalu')
                ->descriptionIcon($expenseIcon)
                ->color($expenseColor)
                ->chart([15, 4, 17, 7, 2, 10, 3]),

            Stat::make('Laba Bersih Bulan Ini', 'Rp ' . number_format($profitThisMonth, 0, ',', '.'))
                ->description(abs(round($profitPercentage, 1)) . '% ' . $profitTrend . ' dibanding bulan lalu')
                ->descriptionIcon($profitIcon)
                ->color($profitColor)
                ->chart([3, 10, 2, 7, 17, 4, 15]),
        ];
    }
}
