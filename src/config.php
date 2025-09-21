<?php
// DB 연결 정보
define('DB_HOST', 'db');         // docker-compose.yml에 정의된 DB 서비스명
define('DB_NAME', 'newsletter_db');
define('DB_USER', 'root');
define('DB_PASS', 'your_root_password');

// DB 연결
try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME,
        DB_USER,
        DB_PASS
    );
    // PDO 오류 모드를 예외(Exception)로 설정하여 오류 발생 시 즉시 감지
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // 문자셋을 UTF-8로 설정
    $pdo->exec("SET NAMES 'utf8mb4'");
} catch (PDOException $e) {
    die("DB 연결 실패: " . $e->getMessage());
}
?>