<?php

namespace Ielliena12\LaravelLogs\Services;

use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;

class LogService {
    protected string $path;
    protected int $lines;

    public function __construct() {
        $this->path = config('laravel-logs.log_storage_path', storage_path('logs'));
        $this->lines = config('laravel-logs.max_lines', 1000);
    }

    public function getData(string $type, ?string $level, ?string $selectedDate = null): array {
        $logFiles = $this->getLogFiles();

        if ($logFiles->isEmpty()) {
            abort(404);
        }

        $availableDates = $this->getAvailableDates($logFiles, $type);

        if ($availableDates->isEmpty() || $selectedDate && !$availableDates->contains($selectedDate)) {
            abort(404);
        }

        $selectedDate = $selectedDate ?? $availableDates->first();
        $logContent = $this->getLogContent($type, $selectedDate);
        $parsedLogs = $this->parseLogContent($logContent);

        if ($level) {
            $parsedLogs = $parsedLogs->filter(function ($logEntry) use ($level) {
                return strtolower($logEntry['level'] ?? '') === strtolower($level);
            });
        }

        return compact(
            'type',
            'availableDates',
            'selectedDate',
            'parsedLogs'
        );
    }

    public function getLogFiles(): Collection {
        if (!File::exists($this->path)) {
            return collect();
        }

        return collect(File::glob($this->path . '/*.log'))
            ->map(fn($file) => basename($file))
            ->filter(fn($file) => pathinfo($file, PATHINFO_EXTENSION) == 'log')
            ->values();
    }

    public function getLogStats(Collection $logFiles): Collection {
        return $logFiles
            ->groupBy(fn($filename) => $this->extractLogName($filename))
            ->map(fn($files, $name) => [
                'name' => $name,
                'dates_count' => $files->count(),
                'display_name' => $this->formatLogName($name),
            ])
            ->values()
            ->sortBy('name');
    }

    public function getAvailableDates(Collection $logFiles, string $logType): Collection {
        $escapedType = preg_quote($logType, '/');

        return $logFiles
            ->filter(fn($name) => preg_match("/^{$escapedType}-\d{4}-\d{2}-\d{2}\.log$/", $name))
            ->map(fn($name) => preg_replace("/^{$escapedType}-|\.log$/", '', $name))
            ->sortDesc()
            ->values();
    }

    public function getLogContent(string $logType, ?string $date): string {
        $file = $this->getLogFilePath($logType, $date);

        if (!File::exists($file)) {
            return '';
        }

        $lines = explode("\n", File::get($file));

        return implode("\n", array_slice($lines, -$this->lines));
    }

    public function parseLogContent(string $content): Collection {
        if (!$content) {
            return collect();
        }

        $entries = [];
        $current = null;

        foreach (explode("\n", $content) as $line) {
            $line = trim($line);
            if ($line == '')
                continue;

            if (preg_match('/^\[(\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})\] (\w+)\.(\w+):\s*(.*)$/', $line, $m)) {
                if ($current) {
                    $entries[] = $current;
                }

                $current = [
                    'timestamp' => $m[1],
                    'environment' => $m[2],
                    'level' => strtolower($m[3]),
                    'message' => $m[4],
                    'details' => []
                ];
            } elseif ($current) {
                $isDetail = str_starts_with($line, '#') || str_starts_with($line, ' ') || str_starts_with($line, 'Stack trace:');

                if ($isDetail || !empty($current['details'])) {
                    $current['details'][] = $line;
                } else {
                    $current['message'] .= ' ' . $line;
                }
            }
        }

        if ($current) {
            $entries[] = $current;
        }

        return collect($entries);
    }

    public function deleteLogFile(string $logType, ?string $date = null): bool {
        try {
            $file = $this->getLogFilePath($logType, $date);
            return File::exists($file) ? File::delete($file) : false;
        } catch (Exception) {
            return false;
        }
    }

    private function getLogFilePath(string $logType, ?string $date = null): string {
        $filename = trim($logType . ($date ? '-' . $date : '') . '.log');
        return $this->path . '/' . $filename;
    }

    private function extractLogName(string $filename): string {
        return preg_match('/^([a-zA-Z0-9_.-]+)-\d{4}-\d{2}-\d{2}\.log$/', $filename, $m)
            ? $m[1]
            : pathinfo($filename, PATHINFO_FILENAME);
    }

    private function formatLogName(string $name): string {
        return ucfirst(str_replace(['.', '-'], ' ', $name));
    }
}
