<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class LogRequestPerformance
{
    public function handle(Request $request, Closure $next): Response
    {
        $startTime = microtime(true);
        $path      = $request->path();

        // Abaikan asset statis
        foreach (['livewire-7f2d539b', 'fonts/', 'js/', 'css/', 'favicon'] as $ignored) {
            if (str_contains($path, $ignored)) {
                return $next($request);
            }
        }

        $requestInfo = [
            'method'  => $request->method(),
            'path'    => '/' . $path,
            'user_id' => auth()->id(),
            'started' => now()->toTimeString(),
        ];

        // Tulis log START segera — muncul di log bahkan jika PHP timeout
        Log::channel('performance')->info("▶ START {$requestInfo['method']} /{$path}", $requestInfo);

        // Register shutdown function — satu-satunya cara menangkap PHP Fatal Error
        register_shutdown_function(function () use ($startTime, $requestInfo) {
            $error = error_get_last();

            if ($error && in_array($error['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR])) {
                $durationMs = round((microtime(true) - $startTime) * 1000, 2);

                $logLine = sprintf(
                    "[%s] 💀 FATAL (%sms) %s %s | %s in %s:%d\n",
                    date('Y-m-d H:i:s'),
                    $durationMs,
                    $requestInfo['method'],
                    $requestInfo['path'],
                    $error['message'],
                    basename($error['file']),
                    $error['line'],
                );

                // Gunakan nama file harian agar tidak menumpuk selamanya
                $fatalLogPath = storage_path('logs/fatal-' . date('Y-m-d') . '.log');
                file_put_contents($fatalLogPath, $logLine, FILE_APPEND | LOCK_EX);
            }
        });

        DB::enableQueryLog();

        $response = $next($request);

        $durationMs   = round((microtime(true) - $startTime) * 1000, 2);
        $queryLog     = DB::getQueryLog();
        $queryCount   = count($queryLog);
        $queryTotalMs = round(array_sum(array_column($queryLog, 'time')), 2);

        DB::disableQueryLog();

        $context = [
            'method'        => $requestInfo['method'],
            'path'          => $requestInfo['path'],
            'duration_ms'   => $durationMs,
            'query_count'   => $queryCount,
            'query_time_ms' => $queryTotalMs,
            'php_time_ms'   => round($durationMs - $queryTotalMs, 2),
            'status'        => $response->getStatusCode(),
            'user_id'       => auth()->id(),
        ];

        if ($durationMs >= 5000) {
            Log::channel('performance')->error("🔴 SLOW ({$durationMs}ms) [{$queryCount} queries]", $context);
        } elseif ($durationMs >= 1000) {
            Log::channel('performance')->warning("🟡 MODERATE ({$durationMs}ms) [{$queryCount} queries]", $context);
        } else {
            Log::channel('performance')->info("✅ DONE ({$durationMs}ms) [{$queryCount} queries]", $context);
        }

        return $response;
    }
}
