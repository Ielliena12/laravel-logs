# Laravel Logs Viewer

Пакет для просмотра и управления логами Laravel приложения.

## Установка

1. Установите пакет:
```bash
composer require ielliena12/laravel-logs
```

2. Опубликуйте конфигурацию (опционально):

```bash
php artisan vendor:publish --provider="Ielliena12\\LaravelLogs\\LogServiceProvider" --tag="laravel-logs-config"
```

## Использование

Перейдите по адресу: /admin/logs

## Конфигурация

Измените файл config/laravel-logs.php для настройки:

1. Маршруты
2. Middleware
3. Лимиты отображения
