<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../config/config.php';

// 로그인 상태 확인. 로그인되지 않았으면 로그인 페이지로 리다이렉션.
if (!isset($_SESSION['user_id'])) {
    $_SESSION['error'] = "로그인 후 이용할 수 있습니다.";
    header('Location: /login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

try {
    // users 테이블에서 해당 사용자 삭제
    $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
    $stmt->execute([$user_id]);

    // 세션 파기
    $_SESSION = array();
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }
    session_destroy();
    
    // 계정 삭제 후 홈 페이지로 리다이렉션
    header('Location: /?message=' . urlencode('계정이 성공적으로 삭제되었습니다.'));
    exit;

} catch (PDOException $e) {
    // 오류 발생 시 다시 마이 페이지로 리다이렉션
    $_SESSION['error'] = '계정 삭제 중 오류가 발생했습니다. 다시 시도해주세요.';
    header('Location: /mypage.php?message=' . urlencode('계정 삭제 중 오류가 발생했습니다.'));
    exit;
}