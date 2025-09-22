<?php
session_start();

$request_uri = strtok($_SERVER["REQUEST_URI"], '?');
$page = ltrim($request_uri, '/');
$method = $_SERVER['REQUEST_METHOD'];

// 기본 페이지는 'home'으로 설정합니다.
if (empty($page)) {
    $page = 'home';
}

$page_title = 'FreeLetter: 모두의 뉴스레터'; // 기본 제목

// 헤더를 포함합니다.
require_once 'includes/header.php';

// GET 요청일 때: 화면 표시
if ($method === 'GET') {
    $view_path = 'views/' . $page . '.php';
    if (file_exists($view_path)) {
        require_once $view_path;
    } else {
        http_response_code(404);
        require_once 'views/404.php';
    }
} 
// POST 요청일 때: 기능 처리
else if ($method === 'POST') {
    $action_path = 'actions/' . $page . '.php';
    if (file_exists($action_path)) {
        require_once $action_path;
    } else {
        http_response_code(404);
        echo "<h1>404 Not Found</h1><p>처리할 페이지를 찾을 수 없습니다.</p>";
    }
}

// 푸터를 포함합니다.
require_once 'includes/footer.php';
?>