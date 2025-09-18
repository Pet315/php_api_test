<?php
$storage = new Storage();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $data = $storage->all();
    echo json_encode(['data' => $data]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    if (!is_array($input)) {
        $input = $_POST;
    }
    $name = $input['name'] ?? '';
    $email = $input['email'] ?? '';

    $errors = [];
    if (strlen(trim($name)) < 2) $errors[] = 'Name is too short';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Invalid email';

    if ($errors) {
        http_response_code(400);
        echo json_encode(['error' => 'Validation failed', 'messages' => $errors]);
        exit;
    }

    $record = $storage->add($name, $email);
    http_response_code(201);
    echo json_encode(['data' => $record]);
    exit;
}

http_response_code(405);
echo json_encode(['error' => 'Method Not Allowed']);
