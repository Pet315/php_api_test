CREATE DATABASE IF NOT EXISTS api_app_db;
USE api_app_db;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE
);

INSERT INTO users (name, email) VALUES
('Maria Sun', 'ms@mail.com'),
('George Kane', 'gk@mail.com');