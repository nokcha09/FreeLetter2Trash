<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../config/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    try {
        $stmt = $pdo->prepare("SELECT id, password, status, nickname FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user) {
            if ($user['status'] !== 'verified') {
                $_SESSION['error'] = '아직 이메일 인증이 완료되지 않았습니다.';
                header('Location: /login');
                exit;
            }

            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['email'] = $email;
                $_SESSION['nickname'] = $user['nickname']; // 별명을 세션에 저장
                
                $_SESSION['success'] = '로그인에 성공했습니다.';
                header('Location: /');
                exit;
            } else {
                $_SESSION['error'] = '잘못된 이메일 또는 비밀번호입니다.';
                header('Location: /login');
                exit;
            }
        } else {
            $_SESSION['error'] = '잘못된 이메일 또는 비밀번호입니다.';
            header('Location: /login');
            exit;
        }

    } catch (PDOException $e) {
        $_SESSION['error'] = '로그인 처리 중 오류가 발생했습니다. 잠시 후 다시 시도해주세요.';
        header('Location: /login');
        exit;
    }
} else {
    header('Location: /login');
    exit;
}