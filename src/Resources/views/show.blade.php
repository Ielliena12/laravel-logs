<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Лог: {{ ucfirst($type) }}</title>
    <style>
        :root {
            --background: oklch(0.145 0 0);
            --glass-bg: rgba(0, 0, 0, 0.4);
            --glass-border: rgba(255, 255, 255, 0.1);
            --accent: #8b00ff;

            --error: #ff6b6b;
            --warning: #ffd93d;
            --info: #6bc6ff;
            --debug: #a0a0a0;
        }

        .dark-theme {
            background-color: var(--background);
            color: #e0e0e0;
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        button, a {
            text-decoration: none;
            border-radius: 8px;
            background: var(--glass-bg);
            color: #e0e0e0;
            border: 1px solid var(--glass-border);
            cursor: pointer;
            font-weight: 600;

            display: flex;
            align-items: center;
            justify-content: center;
        }

        button svg, a svg {
            height: 16px;
        }

        button:hover, a:hover {
            background: var(--glass-border);
            text-decoration: none;
        }

        button.active, a.active {
            background: var(--accent);
            color: #ffffff;
            border-color: var(--accent);
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            background: var(--glass-bg);
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            border: 1px solid var(--glass-border);

            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 8px;
        }

        .header h1 {
            margin: 0 0 5px 0;
            font-size: 28px;
        }

        .card {
            background: var(--glass-bg);
            border-radius: 8px;
            border: 1px solid var(--glass-border);
            margin-bottom: 20px;
            overflow: hidden;
        }

        .card-header {
            background: var(--glass-bg);
            padding: 15px 20px;
            border-bottom: 1px solid var(--glass-border);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .card-header h3 {
            margin: 0;
            font-size: 18px;
            flex: 1;
        }

        .card-body {
            padding: 20px;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .buttons_container {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .date-btn {
            padding: 8px 16px;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .toolbar {
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .btn {
            padding: 8px 16px;
            font-size: 14px;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .log-content {
            background-color: var(--background);
            border: 1px solid var(--glass-border);
            border-radius: 4px;
            padding: 15px;
            max-height: 70vh;
            overflow-y: auto;
            font-family: 'Courier New', monospace;
            font-size: 13px;
            line-height: 1.4;
        }

        .log-entry {
            margin-bottom: 8px;
            border-radius: 4px;
            overflow: hidden;
        }

        .log-header {
            padding: 10px 12px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 12px;
            transition: background-color 0.2s ease;
            border-left: 3px solid transparent;
        }

        .log-header:hover, .log-header.expanded {
            background: var(--glass-border);
        }

        .expand-icon {
            font-size: 12px;
            transition: transform 0.2s ease;
            min-width: 16px;
            text-align: center;
        }

        .log-header.expanded .expand-icon {
            transform: rotate(90deg);
        }

        .log-details {
            display: none;
            padding: 0 12px 12px 40px;
            background: #252525;
            border-left: 3px solid;
        }

        .log-details.expanded {
            display: block;
        }

        .detail-line {
            padding: 2px 0;
            font-family: 'Courier New', monospace;
            font-size: 12px;
            color: #b0b0b0;
            border-bottom: 1px solid #333;
        }

        .detail-line:last-child {
            border-bottom: none;
        }

        .log-error .log-header {
            border-left-color: var(--error);
            color: var(--error);
        }

        .log-warning .log-header {
            border-left-color: var(--warning);
            color: var(--warning);
        }

        .log-info .log-header {
            border-left-color: var(--info);
            color: var(--info);
        }

        .log-debug .log-header {
            border-left-color: var(--debug);
            color: var(--debug);
        }

        .log-error .log-details, .card-error {
            border-left-color: var(--error);
        }

        .log-warning .log-details {
            border-left-color: var(--warning);
        }

        .log-info .log-details {
            border-left-color: var(--info);
        }

        .log-debug .log-details {
            border-left-color: var(--debug);
        }

        .timestamp {
            color: #888;
            font-weight: bold;
            min-width: 150px;
        }

        .level {
            font-weight: bold;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 11px;
        }

        .level-error {
            background: var(--error);
            color: #000;
        }

        .level-warning {
            background: var(--warning);
            color: #000;
        }

        .level-info {
            background: var(--info);
            color: #000;
        }

        .level-debug {
            background: var(--debug);
            color: #000;
        }

        .message {
            flex: 1;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .filter-btn {
            padding: 6px 12px;
            font-size: 12px;
            transition: all 0.3s ease;
        }

        .empty-state {
            text-align: center;
            padding: 40px 20px;
            color: #888;
        }

        .batch-btn {
            padding: 6px 12px;
            font-size: 12px;
            transition: all 0.3s ease;
        }

        .card-error .card-body {
            flex-direction: row;
            align-items: center;
        }
    </style>
</head>
<body class="dark-theme">
<div class="container">
    <div class="header">
        <h1>Лог: {{ ucfirst($type) }}</h1>
        <a href="{{ route('laravel-logs.index') }}" class="btn">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                 stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                 class="lucide lucide-arrow-left-icon lucide-arrow-left">
                <path d="m12 19-7-7 7-7"/>
                <path d="M19 12H5"/>
            </svg>
            Назад
        </a>
    </div>

    @if(session('error'))
        <div class="card card-error">
            <div class="card-body">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                     class="lucide lucide-bug-icon lucide-bug">
                    <path d="M12 20v-9"/>
                    <path d="M14 7a4 4 0 0 1 4 4v3a6 6 0 0 1-12 0v-3a4 4 0 0 1 4-4z"/>
                    <path d="M14.12 3.88 16 2"/>
                    <path d="M21 21a4 4 0 0 0-3.81-4"/>
                    <path d="M21 5a4 4 0 0 1-3.55 3.97"/>
                    <path d="M22 13h-4"/>
                    <path d="M3 21a4 4 0 0 1 3.81-4"/>
                    <path d="M3 5a4 4 0 0 0 3.55 3.97"/>
                    <path d="M6 13H2"/>
                    <path d="m8 2 1.88 1.88"/>
                    <path d="M9 7.13V6a3 3 0 1 1 6 0v1.13"/>
                </svg>
                {{ session('error') }} 123123123
            </div>
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <div class="buttons_container">
                @foreach($availableDates as $date)
                    <a href="{{ route('laravel-logs.show.date', [$type, $date]) }}"
                       class="date-btn {{ $date === $selectedDate ? 'active' : '' }}">
                        {{ $date }}
                    </a>
                @endforeach
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3>Содержание лога ({{ $selectedDate }})</h3>
            <div class="toolbar">
                <div class="buttons_container">
                    <a href="?" class="filter-btn {{ !request()->has('level') ? 'active' : '' }}">Все</a>
                    <a href="?level=error"
                       class="filter-btn {{ request('level') === 'error' ? 'active' : '' }}">ERROR</a>
                    <a href="?level=warning" class="filter-btn {{ request('level') === 'warning' ? 'active' : '' }}">WARNING</a>
                    <a href="?level=info" class="filter-btn {{ request('level') === 'info' ? 'active' : '' }}">INFO</a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="buttons_container">
                <button class="batch-btn" onclick="expandAll()">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                         class="lucide lucide-book-open-icon lucide-book-open">
                        <path d="M12 7v14"/>
                        <path d="M3 18a1 1 0 0 1-1-1V4a1 1 0 0 1 1-1h5a4 4 0 0 1 4 4 4 4 0 0 1 4-4h5a1 1 0 0 1 1 1v13a1 1 0 0 1-1 1h-6a3 3 0 0 0-3 3 3 3 0 0 0-3-3z"/>
                    </svg>
                    Развернуть все
                </button>
                <button class="batch-btn" onclick="collapseAll()">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                         class="lucide lucide-book-icon lucide-book">
                        <path d="M4 19.5v-15A2.5 2.5 0 0 1 6.5 2H19a1 1 0 0 1 1 1v18a1 1 0 0 1-1 1H6.5a1 1 0 0 1 0-5H20"/>
                    </svg>
                    Свернуть все
                </button>
                <button class="batch-btn" onclick="expandErrors()">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                         class="lucide lucide-bug-icon lucide-bug">
                        <path d="M12 20v-9"/>
                        <path d="M14 7a4 4 0 0 1 4 4v3a6 6 0 0 1-12 0v-3a4 4 0 0 1 4-4z"/>
                        <path d="M14.12 3.88 16 2"/>
                        <path d="M21 21a4 4 0 0 0-3.81-4"/>
                        <path d="M21 5a4 4 0 0 1-3.55 3.97"/>
                        <path d="M22 13h-4"/>
                        <path d="M3 21a4 4 0 0 1 3.81-4"/>
                        <path d="M3 5a4 4 0 0 0 3.55 3.97"/>
                        <path d="M6 13H2"/>
                        <path d="m8 2 1.88 1.88"/>
                        <path d="M9 7.13V6a3 3 0 1 1 6 0v1.13"/>
                    </svg>
                    Только ошибки
                </button>
                <form action="{{ route('laravel-logs.delete', [$type, $date ?? ' ']) }}"
                      method="POST"
                      style="display: inline;">
                    @csrf
                    <button type="submit" class="batch-btn" title="Удалить лог">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                             class="lucide lucide-trash-icon lucide-trash">
                            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6"/>
                            <path d="M3 6h18"/>
                            <path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                        </svg>
                        Удалить
                    </button>
                </form>
            </div>

            <div class="log-content" id="logContent">
                @if(!empty($parsedLogs))
                    @foreach($parsedLogs as $logEntry)
                        <div class="log-entry log-{{ $logEntry['level'] ?? 'info' }}"
                             data-level="{{ $logEntry['level'] ?? 'info' }}">
                            <div class="log-header" onclick="toggleDetails(this)">
                                @if(!empty($logEntry['details']))
                                    <span class="expand-icon">▶</span>
                                @endif
                                <span class="timestamp">{{ $logEntry['timestamp'] ?? '' }}</span>
                                <span class="level level-{{ $logEntry['level'] ?? 'info' }}">
                                        {{ strtoupper($logEntry['level'] ?? 'INFO') }}
                                    </span>
                                <span class="message" title="{{ $logEntry['message'] ?? '' }}">
                                        {{ $logEntry['message'] ?? '' }}
                                    </span>
                            </div>
                            @if(!empty($logEntry['details']))
                                <div class="log-details">
                                    @foreach($logEntry['details'] as $detail)
                                        <div class="detail-line">{{ $detail }}</div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @endforeach
                @else
                    <div class="empty-state">
                        <h3>Лог файл пуст или не найден</h3>
                        <p>Нет данных для отображения</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
    function toggleDetails(header) {
        const entry = header.parentElement;
        const details = entry.querySelector('.log-details');

        if (details) {
            header.classList.toggle('expanded');
            details.classList.toggle('expanded');
        }
    }

    function expandAll() {
        document.querySelectorAll('.log-header').forEach(header => {
            if (!header.classList.contains('expanded')) {
                header.classList.add('expanded');
                const details = header.parentElement.querySelector('.log-details');
                if (details) details.classList.add('expanded');
            }
        });
    }

    function collapseAll() {
        document.querySelectorAll('.log-header').forEach(header => {
            header.classList.remove('expanded');
            const details = header.parentElement.querySelector('.log-details');
            if (details) details.classList.remove('expanded');
        });
    }

    function expandErrors() {
        collapseAll();
        document.querySelectorAll('.log-error .log-header').forEach(header => {
            header.classList.add('expanded');
            const details = header.parentElement.querySelector('.log-details');
            if (details) details.classList.add('expanded');
        });
    }

    document.addEventListener('DOMContentLoaded', function () {
        const logContent = document.getElementById('logContent');
        if (logContent) {
            logContent.scrollTop = logContent.scrollHeight;
        }

        setTimeout(expandErrors, 100);
    });

    document.querySelectorAll('.date-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            document.querySelectorAll('.date-btn').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
        });
    });
</script>
</body>
</html>
