# Web Application PHP + API

## Description
Web app consisting of API (PHP) that returns data in JSON format and HTML/CSS (PHP) that accesses the API and displays data to the user

## Running the Application
- php -S localhost:8000 -t web
- in another terminal: php -S localhost:8001 -t api
- open in browser: http://localhost:8000

## Files
- api/config.php — place to save the token and information for the DB (to work with DB, you need to change USE_MYSQL to true and set the appropriate values ​​for DB_USER, DB_PASS)
- api/index.php — router for the API
- api/info.php — logic for GET/POST
- api/storage.php — for saving to MySQL or JSON (JSON is selected by default)
- web/config.php — token for the web interface
- web/index.php — main page + JS for working with the API
- data/data.json — initial test data
- data/db.sql - dump for the DB
