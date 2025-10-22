<?php

namespace Ielliena12\LaravelLogs\Http\Controllers;

use Ielliena12\LaravelLogs\Services\LogService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class LogController {
    public function __construct(private LogService $logService) {}

    public function index(): View {
        $logFiles = $this->logService->getLogFiles();
        $logStats = $logFiles->isEmpty()
            ? collect()
            : $this->logService->getLogStats($logFiles);

        return view('laravel-logs::index', compact('logStats'));
    }

    public function show(string $type, Request $request): View|RedirectResponse {
        $data = $this->logService->getData($type, $request->level);
        return view('laravel-logs::show', $data);
    }

    public function showByDate(string $type, string $date, Request $request): View|RedirectResponse {
        $data = $this->logService->getData($type, $request->level, $date);
        return view('laravel-logs::show', $data);
    }

    public function delete(string $logType, string $date): RedirectResponse {
        $success = $this->logService->deleteLogFile($logType, $date);

        if (!$success) {
            return redirect()->back()->with('error', 'Произошла ошибка во время удаления файла');
        }

        return redirect()->route('logs.show', $logType)->with('success', 'Файл успешно удален');
    }
}
