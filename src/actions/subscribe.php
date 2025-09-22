<?php
require_once __DIR__ . '/../config/config.php';

$message = '';
$isSuccess = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';

    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "유효한 이메일 주소를 입력해주세요.";
    } else {
        try {
            $stmt = $pdo->prepare("INSERT INTO subscribers (email, created_at) VALUES (?, NOW())");
            $stmt->execute([$email]);
            $message = "구독이 성공적으로 완료되었습니다!";
            $isSuccess = true;
        } catch (PDOException $e) {
            if ($e->getCode() == '23000') {
                $message = "이미 구독된 이메일 주소입니다.";
            } else {
                $message = "구독 중 오류가 발생했습니다: " . $e->getMessage();
            }
        }
    }
} else {
    $message = "잘못된 접근입니다.";
}

header('Location: /?message=' . urlencode($message) . '&isSuccess=' . ($isSuccess ? 'true' : 'false'));
exit;