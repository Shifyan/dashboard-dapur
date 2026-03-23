<?php

namespace App\Providers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (config('app.debug')) {
            $this->enableQueryLogging();
        }
    }

    /**
     * Log setiap query yang berjalan.
     * Query >= 500ms → WARNING, query >= 100ms → INFO di log khusus.
     */
    private function enableQueryLogging(): void
    {
        $queryCount = 0;

        DB::listen(function ($query) use (&$queryCount) {
            $queryCount++;

            $timeMs = round($query->time, 2);

            // Ganti binding placeholder dengan nilai aslinya supaya mudah dibaca
            $sql = $query->sql;
            foreach ($query->bindings as $binding) {
                $value = is_string($binding) ? "'{$binding}'" : (is_null($binding) ? 'NULL' : $binding);
                $sql   = preg_replace('/\?/', (string) $value, $sql, 1);
            }

            $context = [
                'time_ms'     => $timeMs,
                'query_count' => $queryCount,
                'sql'         => $sql,
            ];

            if ($timeMs >= 500) {
                // Query sangat lambat → WARNING
                Log::channel('queries')->warning("🔴 SLOW QUERY ({$timeMs}ms)", $context);
            } elseif ($timeMs >= 100) {
                // Query agak lambat → INFO
                Log::channel('queries')->info("🟡 MODERATE QUERY ({$timeMs}ms)", $context);
            } else {
                // Query normal — hanya log jika perlu debug detail
                // Log::channel('queries')->debug("✅ QUERY ({$timeMs}ms)", $context);
            }
        });
    }
}
