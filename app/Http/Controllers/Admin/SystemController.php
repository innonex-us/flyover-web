<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class SystemController extends Controller
{
    public function index()
    {
        return view('admin.system');
    }

    /** GET /cp/system/api/info */
    public function info()
    {
        $dbVersion = 'N/A';
        try {
            $row = DB::selectOne('SELECT VERSION() as v');
            $dbVersion = config('database.default') . ' ' . ($row->v ?? '');
        } catch (\Throwable) {}

        $memUsed  = memory_get_usage(true);
        $memLimit = $this->parseBytes(ini_get('memory_limit'));
        $diskFree  = disk_free_space(base_path());
        $diskTotal = disk_total_space(base_path());

        $uptime = 'N/A';
        if (PHP_OS_FAMILY === 'Linux' && file_exists('/proc/uptime')) {
            $seconds = (int) explode(' ', file_get_contents('/proc/uptime'))[0];
            $days    = intdiv($seconds, 86400);
            $hours   = intdiv($seconds % 86400, 3600);
            $uptime  = "{$days}d {$hours}h";
        }

        $logFile = storage_path('logs/laravel.log');
        $logSize = file_exists($logFile) ? filesize($logFile) : 0;

        return response()->json([
            'phpVersion'        => PHP_VERSION,
            'laravelVersion'    => app()->version(),
            'database'          => $dbVersion,
            'uptime'            => $uptime,
            'memoryUsage'       => $this->formatBytes($memUsed) . ' / ' . $this->formatBytes($memLimit),
            'diskUsage'         => $this->formatBytes($diskTotal - $diskFree) . ' / ' . $this->formatBytes($diskTotal),
            'logSize'           => $this->formatBytes($logSize),
            'environment'       => app()->environment(),
            'maintenanceMode'   => Setting::get('maintenance_mode', '0') === '1',
        ]);
    }

    /** GET /cp/system/api/logs */
    public function logs()
    {
        $logFile = storage_path('logs/laravel.log');
        $entries = [];

        if (!file_exists($logFile)) {
            return response()->json(['logs' => []]);
        }

        // Read last 200 lines
        $lines = array_filter(array_slice(file($logFile), -200));
        $id = 0;

        foreach ($lines as $line) {
            $line = rtrim($line);
            if (empty($line)) continue;

            // Match [timestamp] env.LEVEL: message
            if (preg_match('/^\[(\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})\] \w+\.(\w+): (.+)$/', $line, $m)) {
                $levelMap = [
                    'ERROR'     => 'error',
                    'EMERGENCY' => 'error',
                    'CRITICAL'  => 'error',
                    'ALERT'     => 'error',
                    'WARNING'   => 'warning',
                    'NOTICE'    => 'warning',
                    'INFO'      => 'info',
                    'DEBUG'     => 'debug',
                ];
                $level = $levelMap[strtoupper($m[2])] ?? 'info';
                $entries[] = [
                    'id'        => ++$id,
                    'timestamp' => $m[1],
                    'level'     => $level,
                    'message'   => mb_substr($m[3], 0, 300),
                ];
            } elseif ($id > 0 && !str_starts_with($line, '[')) {
                // Continuation line — append to last entry's message (truncated)
                $last = &$entries[count($entries) - 1];
                if (strlen($last['message']) < 300) {
                    $last['message'] .= ' ' . mb_substr(ltrim($line, " \t#"), 0, 100);
                }
            }
        }

        return response()->json(['logs' => array_reverse(array_values($entries))]);
    }

    /** POST /cp/system/api/logs/clear */
    public function clearLogs()
    {
        $logFile = storage_path('logs/laravel.log');
        if (file_exists($logFile)) {
            file_put_contents($logFile, '');
        }
        return response()->json(['success' => true]);
    }

    /** GET /cp/system/api/logs/download */
    public function downloadLogs()
    {
        $logFile = storage_path('logs/laravel.log');
        if (!file_exists($logFile)) {
            abort(404);
        }
        return response()->download($logFile, 'laravel-' . now()->format('Y-m-d') . '.log');
    }

    /** POST /cp/system/api/cache/clear  { type: app|config|route|view|all } */
    public function clearCache()
    {
        $type = request('type', 'all');

        $commands = match ($type) {
            'app'    => ['cache:clear'],
            'config' => ['config:clear'],
            'route'  => ['route:clear'],
            'view'   => ['view:clear'],
            default  => ['cache:clear', 'config:clear', 'route:clear', 'view:clear'],
        };

        foreach ($commands as $cmd) {
            Artisan::call($cmd);
        }

        return response()->json(['success' => true, 'message' => ucfirst($type) . ' cache cleared.']);
    }

    /** GET /cp/system/api/maintenance */
    public function maintenanceStatus()
    {
        return response()->json(['enabled' => Setting::get('maintenance_mode', '0') === '1']);
    }

    /** POST /cp/system/api/maintenance/toggle */
    public function toggleMaintenance()
    {
        $current = Setting::get('maintenance_mode', '0');
        $new = $current === '1' ? '0' : '1';
        Setting::set('maintenance_mode', $new, 'general');
        return response()->json(['enabled' => $new === '1']);
    }

    /** POST /cp/system/api/optimize */
    public function optimizeDb()
    {
        try {
            $tables = DB::select('SHOW TABLES');
            $dbName = config('database.connections.' . config('database.default') . '.database');
            $col    = 'Tables_in_' . $dbName;
            foreach ($tables as $row) {
                DB::statement('OPTIMIZE TABLE `' . $row->$col . '`');
            }
            return response()->json(['success' => true, 'message' => count($tables) . ' tables optimized.']);
        } catch (\Throwable $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /** POST /cp/system/api/sessions/cleanup */
    public function cleanupSessions()
    {
        try {
            $deleted = DB::table('sessions')
                ->where('last_activity', '<', now()->subDays(7)->timestamp)
                ->delete();
            return response()->json(['success' => true, 'message' => "{$deleted} expired sessions removed."]);
        } catch (\Throwable $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    // ─── Helpers ────────────────────────────────────────────────────────────────

    private function formatBytes(int $bytes): string
    {
        if ($bytes <= 0) return '0 B';
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $i = (int) floor(log($bytes, 1024));
        return round($bytes / pow(1024, $i), 1) . ' ' . $units[$i];
    }

    private function parseBytes(string $val): int
    {
        $val  = trim($val);
        $last = strtolower($val[-1]);
        $num  = (int) $val;
        return match ($last) {
            'g' => $num * 1024 * 1024 * 1024,
            'm' => $num * 1024 * 1024,
            'k' => $num * 1024,
            default => $num,
        };
    }
}
