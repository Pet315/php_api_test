<?php
declare(strict_types=1);

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Authorization, Content-Type");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/storage.php';

function get_authorization_header() {
    $headers = null;
    if (isset($_SERVER['HTTP_AUTHORIZATION'])) {
        $headers = trim($_SERVER['HTTP_AUTHORIZATION']);
    } elseif (function_exists('apache_request_headers')) {
        $requestHeaders = apache_request_headers();
        if (isset($requestHeaders['Authorization'])) {
            $headers = trim($requestHeaders['Authorization']);
        }
    }
    return $headers;
}

$auth = get_authorization_header();
if (!$auth || stripos($auth, 'Bearer ') !== 0) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized - missing token']);
    exit;
}
$token = substr($auth, 7);
if ($token !== API_TOKEN) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized - invalid token']);
    exit;
}

$method = $_SERVER['REQUEST_METHOD'];
$path = $_SERVER['REQUEST_URI'];

$basePath = strtok($_SERVER['REQUEST_URI'], '?');
if (preg_match('@/api/info/?$@', $basePath)) {
    require_once __DIR__ . '/info.php';
} else {
    http_response_code(404);
    echo json_encode(['error' => 'Not Found']);
}
