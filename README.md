# Веб-застосунок PHP + API

## Запуск застосунку
- php -S localhost:8000 -t web
- в іншому вікні: php -S localhost:8001 -t api
- у браузері відкрийте: http://localhost:8000

## Файли
- api/config.php — місце збереження токену та інформації для БД (для роботи з БД потрібно змінити USE_MYSQL на true і встановити відповідні значення для DB_USER, DB_PASS)
- api/index.php — маршрутизатор для API
- api/info.php — логіка для GET/POST
- api/storage.php — для збереження на MySQL або JSON (стандартно вибрано на JSON)
- web/config.php — токен для веб-інтерфейсу
- web/index.php — головна сторінка + JS для роботи з API
- data/data.json — початкові тестові дані
- data/db.sqp - дамп для БД
