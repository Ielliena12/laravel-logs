<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Логи приложения</title>
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

        .container {
            max-width: 1200px;
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

        .log-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .log-item {
            padding: 12px 15px;
            text-decoration: none;
            border-bottom: 1px solid var(--glass-border);
            transition: all 0.3s ease;
            color: #fff;

            display: flex;
            align-items: center;
            gap: 10px;
        }

        .log-item svg {
            height: 16px;
        }

        .log-item:hover {
            background: var(--glass-border);
            text-decoration: none;
        }

        .log-item:last-child {
            border-bottom: none;
        }

        .empty-state {
            text-align: center;
            padding: 40px 20px;
            color: #888;
        }

    </style>
</head>
<body class="dark-theme">
<div class="container">
    <div class="header">
        <h1>Логи приложения</h1>
    </div>

    <div class="card">
        <div class="card-header">
            <h3>Доступные логи ({{count($logStats ?? [])}})</h3>
        </div>
        <div class="card-body">
            @if($logStats->count() > 0)
                <div class="log-list">
                    @foreach($logStats as $log)
                        <a href="{{ route('laravel-logs.show', $log['name']) }}" class="log-item">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-scroll-icon lucide-scroll"><path d="M19 17V5a2 2 0 0 0-2-2H4"/><path d="M8 21h12a2 2 0 0 0 2-2v-1a1 1 0 0 0-1-1H11a1 1 0 0 0-1 1v1a2 2 0 1 1-4 0V5a2 2 0 1 0-4 0v2a1 1 0 0 0 1 1h3"/></svg>
                            {{ $log['display_name'] }}
                            ({{ $log['dates_count'] }})
                        </a>
                    @endforeach
                </div>
            @else
                <div class="empty-state">
                    <h3>Лог файлы не найдены</h3>
                    <p>В директории logs нет файлов логов</p>
                </div>
            @endif
        </div>
    </div>
</div>
</body>
</html>
