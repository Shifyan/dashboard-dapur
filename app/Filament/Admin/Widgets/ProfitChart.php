<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Transaction;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageFilters;

class ProfitChart extends ChartWidget
{
    use InteractsWithPageFilters;

    protected ?string $heading = 'Tren Laba Bersih (1 Tahun Terakhir)';
    protected static ?int $sort = 4;
    protected int | string | array $columnSpan = 'full';

    protected function getData(): array
    {
        $baseQuery = Transaction::query();
        $user = auth()->user();

        if ($user->role === 'ADMIN') {
            $currentFilter = $this->filters['role_filter'] ?? 'all';
            if ($currentFilter === 'admin') {
                $baseQuery->where('transactions.user_id', $user->id);
            } elseif ($currentFilter === 'investors') {
                $baseQuery->whereHas('user', function ($q) {
                    $q->where('role', 'USER');
                });
            } elseif (str_starts_with($currentFilter, 'investor_')) {
                $baseQuery->where('transactions.user_id', str_replace('investor_', '', $currentFilter));
            }
        } else {
            $baseQuery->where('transactions.user_id', $user->id);
        }

        $startDate = Carbon::now()->subMonths(11)->startOfMonth();
        $endDate   = Carbon::now()->endOfMonth();

        // Satu query per tipe, bukan 24 query terpisah (12 income + 12 expense)
        $incomeResults = (clone $baseQuery)
            ->join('categories', 'transactions.category_id', '=', 'categories.id')
            ->where('categories.type', 'IN')
            ->whereBetween('transactions.date', [$startDate, $endDate])
            ->selectRaw('YEAR(transactions.date) as year, MONTH(transactions.date) as month, SUM(transactions.amount) as total')
            ->groupByRaw('YEAR(transactions.date), MONTH(transactions.date)')
            ->get()
            ->keyBy(fn($r) => $r->year . '-' . $r->month);

        $expenseResults = (clone $baseQuery)
            ->join('categories', 'transactions.category_id', '=', 'categories.id')
            ->where('categories.type', 'OUT')
            ->whereBetween('transactions.date', [$startDate, $endDate])
            ->selectRaw('YEAR(transactions.date) as year, MONTH(transactions.date) as month, SUM(transactions.amount) as total')
            ->groupByRaw('YEAR(transactions.date), MONTH(transactions.date)')
            ->get()
            ->keyBy(fn($r) => $r->year . '-' . $r->month);

        $data   = [];
        $labels = [];

        for ($i = 11; $i >= 0; $i--) {
            $month   = Carbon::now()->subMonths($i)->startOfMonth();
            $key     = $month->year . '-' . $month->month;
            $income  = $incomeResults->get($key)?->total ?? 0;
            $expense = $expenseResults->get($key)?->total ?? 0;
            $data[]  = $income - $expense;
            $labels[] = $month->translatedFormat('M Y');
        }

        return [
            'datasets' => [
                [
                    'label'           => 'Total Laba Bersih (Rp)',
                    'data'            => $data,
                    'borderColor'     => '#3b82f6',
                    'backgroundColor' => 'rgba(59, 130, 246, 0.2)',
                    'fill'            => true,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
