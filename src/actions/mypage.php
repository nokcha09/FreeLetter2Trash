<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../config/config.php';

// 로그인 상태 확인
if (!isset($_SESSION['user_id'])) {
    header('Location: /login.php');
    exit;
}

$message = '';
$isSuccess = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_nickname = trim($_POST['nickname']);
    $user_id = $_SESSION['user_id'];

    if (empty($new_nickname)) {
        $message = '별명을 입력해주세요.';
    } else {
        try {
            // 데이터베이스에서 별명 업데이트
            $stmt = $pdo->prepare("UPDATE users SET nickname = ? WHERE id = ?");
            $stmt->execute([$new_nickname, $user_id]);

            // 세션 별명 업데이트
            $_SESSION['nickname'] = $new_nickname;

            $message = '별명이 성공적으로 변경되었습니다.';
            $isSuccess = true;

        } catch (PDOException $e) {
            $message = '별명 변경 중 오류가 발생했습니다. 다시 시도해주세요.';
            $isSuccess = false;
        }
    }
} else {
    $message = '잘못된 접근입니다.';
    $isSuccess = false;
}

header('Location: /mypage?message=' . urlencode($message) . '&isSuccess=' . ($isSuccess ? 'true' : 'false'));
exit;