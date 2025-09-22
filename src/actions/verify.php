<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once 'config/config.php';

// type 파라미터가 'user'인지 확인하여 회원가입 인증인지 구분
$verificationType = isset($_GET['type']) && $_GET['type'] === 'user' ? 'user' : 'subscriber';

$email = $_GET['email'] ?? '';
$hash = $_GET['hash'] ?? '';
$token = $_GET['token'] ?? '';

if (empty($email)) {
    $_SESSION['error'] = '유효하지 않은 접근입니다.';
    header('Location: /');
    exit;
}

try {
    if ($verificationType === 'user') {
        // 회원가입 인증 로직
        if (empty($token)) {
            $_SESSION['error'] = '유효하지 않은 토큰입니다.';
            header('Location: /');
            exit;
        }

        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ? AND token = ? AND status = 'pending'");
        $stmt->execute([$email, $token]);
        $user = $stmt->fetch();

        if ($user) {
            $stmt = $pdo->prepare("UPDATE users SET status = 'verified', token = NULL WHERE id = ?");
            $stmt->execute([$user['id']]);
            $_SESSION['success'] = '회원가입이 완료되었습니다. 이제 로그인할 수 있습니다.';
            header('Location: /login');
            exit;
        } else {
            $_SESSION['error'] = '인증 링크가 유효하지 않거나 이미 사용되었습니다.';
            header('Location: /login');
            exit;
        }

    } else {
        // 기존 뉴스레터 구독 인증 로직
        $verificationHash = hash_hmac('sha256', $email, VERIFICATION_SECRET_KEY);

        if ($hash === $verificationHash) {
            $stmt = $pdo->prepare("UPDATE subscribers SET status = 'verified', verified_at = NOW() WHERE email = ? AND status = 'pending'");
            $stmt->execute([$email]);

            if ($stmt->rowCount() > 0) {
                $_SESSION['success'] = '뉴스레터 구독이 확인되었습니다. 감사합니다!';
            } else {
                $_SESSION['error'] = '이미 인증되었거나 유효하지 않은 구독입니다.';
            }
        } else {
            $_SESSION['error'] = '유효하지 않은 인증 링크입니다.';
        }
        header('Location: /subscribe');
        exit;
    }

} catch (PDOException $e) {
    $_SESSION['error'] = '인증 처리 중 오류가 발생했습니다. 잠시 후 다시 시도해주세요.';
    if ($verificationType === 'user') {
        header('Location: /login');
    } else {
        header('Location: /subscribe');
    }
    exit;
}
?>