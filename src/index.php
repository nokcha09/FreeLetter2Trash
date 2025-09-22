<?php
session_start();

$request_uri = strtok($_SERVER["REQUEST_URI"], '?');
$page = ltrim($request_uri, '/');
$method = $_SERVER['REQUEST_METHOD'];

// 기본 페이지는 'home'으로 설정합니다.
if (empty($page)) {
    $page = 'home';
}

// 1. GET 요청일 때: 화면 표시
if ($method === 'GET') {
    // verify 요청은 액션 파일로 바로 처리하고, 뷰를 로드하지 않습니다.
    if ($page === 'verify') {
        require_once 'actions/verify.php';
        exit; // 리다이렉션이 발생하면 여기서 스크립트 실행을 종료합니다.
    }
    
    // 나머지 모든 GET 요청은 뷰 파일을 로드합니다.
    $page_title = 'FreeLetter: 모두의 뉴스레터';
    require_once 'includes/header.php';

    $view_path = 'views/' . $page . '.php';
    if (file_exists($view_path)) {
        require_once $view_path;
    } else {
        http_response_code(404);
        require_once 'views/404.php';
    }
    
    require_once 'includes/footer.php';
} 
// 2. POST 요청일 때: 기능 처리
else if ($method === 'POST') {
    $action_path = 'actions/' . $page . '.php';
    if (file_exists($action_path)) {
        require_once $action_path;
        exit;
    } else {
        http_response_code(404);
        echo "<h1>404 Not Found</h1><p>처리할 페이지를 찾을 수 없습니다.</p>";
        exit;
    }
}
?>