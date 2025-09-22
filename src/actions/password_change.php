<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../config/config.php';

// 로그인 상태 확인
if (!isset($_SESSION['user_id'])) {
    $_SESSION['error'] = "로그인 후 이용할 수 있습니다.";
    header('Location: /login');
    exit;
}

$message = '';
$isSuccess = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    $user_id = $_SESSION['user_id'];

    if ($new_password !== $confirm_password) {
        $message = '새 비밀번호와 비밀번호 확인이 일치하지 않습니다.';
    } else if (strlen($new_password) < 6) {
        $message = '새 비밀번호는 6자 이상이어야 합니다.';
    } else {
        try {
            // 1. 현재 비밀번호 확인
            $stmt = $pdo->prepare("SELECT password FROM users WHERE id = ?");
            $stmt->execute([$user_id]);
            $user = $stmt->fetch();

            if ($user && password_verify($current_password, $user['password'])) {
                // 2. 새 비밀번호 해시 후 업데이트
                $hashed_new_password = password_hash($new_password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
                $stmt->execute([$hashed_new_password, $user_id]);

                $message = '비밀번호가 성공적으로 변경되었습니다.';
                $isSuccess = true;
            } else {
                $message = '현재 비밀번호가 올바르지 않습니다.';
            }

        } catch (PDOException $e) {
            $message = '비밀번호 변경 중 오류가 발생했습니다. 다시 시도해주세요.';
        }
    }
} else {
    $message = '잘못된 접근입니다.';
}

header('Location: /mypage?message=' . urlencode($message) . '&isSuccess=' . ($isSuccess ? 'true' : 'false'));
exit;