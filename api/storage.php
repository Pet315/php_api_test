<?php

require_once __DIR__ . '/config.php';

class Storage {
    private $useMysql;
    private $pdo;
    private $jsonFile;

    public function __construct() {
        $this->useMysql = defined('USE_MYSQL') && USE_MYSQL;
        $this->jsonFile = __DIR__ . '/../data/data.json';
        if ($this->useMysql) {
            try {
                $this->pdo = new PDO(DB_DSN, DB_USER, DB_PASS, [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                ]);
                $this->initMysql();
            } catch (Exception $e) {
                // fallback to json
                $this->useMysql = false;
            }
        }
        if (!$this->useMysql) {
            if (!file_exists($this->jsonFile)) {
                file_put_contents($this->jsonFile, json_encode([
                    ['id' => 1, 'name' => 'Ivan Ivanov', 'email' => 'ivan@example.com'],
                    ['id' => 2, 'name' => 'Anna Petrova', 'email' => 'anna@example.com']
                ], JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE));
            }
        }
    }

    private function initMysql() {
        $this->pdo->exec("CREATE TABLE IF NOT EXISTS users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            email VARCHAR(255) NOT NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

        $stmt = $this->pdo->query('SELECT COUNT(*) FROM users');
        if ($stmt && $stmt->fetchColumn() == 0) {
            $ins = $this->pdo->prepare('INSERT INTO users (name,email) VALUES (?,?)');
            $ins->execute(['Ivan Ivanov', 'ivan@example.com']);
            $ins->execute(['Anna Petrova', 'anna@example.com']);
        }
    }

    public function all(): array {
        if ($this->useMysql) {
            $stmt = $this->pdo->query('SELECT id, name, email FROM users ORDER BY id');
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } else {
            $data = json_decode(file_get_contents($this->jsonFile), true);
            return $data ?: [];
        }
    }

    public function add(string $name, string $email): array {
        $name = trim($name);
        $email = trim($email);
        if ($this->useMysql) {
            $stmt = $this->pdo->prepare('INSERT INTO users (name,email) VALUES (?,?)');
            $stmt->execute([$name, $email]);
            $id = (int)$this->pdo->lastInsertId();
            return ['id' => $id, 'name' => $name, 'email' => $email];
        } else {
            $data = $this->all();
            $ids = array_column($data, 'id');
            $id = $ids ? max($ids) + 1 : 1;
            $record = ['id' => $id, 'name' => $name, 'email' => $email];
            $data[] = $record;
            file_put_contents($this->jsonFile, json_encode($data, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE));
            return $record;
        }
    }
}
