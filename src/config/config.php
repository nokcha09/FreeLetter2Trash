<?php
// =======================================
// 데이터베이스 설정
// =======================================
define('DB_HOST', 'db');         // docker-compose.yml에 정의된 DB 서비스명
define('DB_NAME', 'newsletter_db');
define('DB_USER', 'root');
define('DB_PASS', 'your_root_password');

// =======================================
// 애플리케이션 보안 및 관리자 설정
// =======================================
// 세션 보안을 위한 키 (쿠키 및 세션 데이터 암호화에 사용)
define('SESSION_SECRET_KEY', 'your_super_long_and_random_session_key');

// 이메일 인증 및 구독 관리를 위한 보안 키
define('VERIFICATION_SECRET_KEY', 'your_long_and_complex_secret_key_here');

// 초기 관리자 계정 설정 (추후 DB 관리 테이블로 이전 가능)
define('ADMIN_USERNAMES', ['admin']);
define('ADMIN_PASSWORD', 'password123!');

// =======================================
// 데이터베이스 연결
// =======================================
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