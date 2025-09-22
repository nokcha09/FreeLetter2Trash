<?php
require_once __DIR__ . '/../config/config.php';

$message = '';
$isSuccess = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';

    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "유효한 이메일 주소입니다.";
    } else {
        try {
            $stmt = $pdo->prepare("DELETE FROM subscribers WHERE email = ?");
            $stmt->execute([$email]);
            if ($stmt->rowCount() > 0) {
                $message = "구독이 성공적으로 해제되었습니다.";
                $isSuccess = true;
            } else {
                $message = "해당 이메일 주소는 구독 목록에 없습니다.";
            }
        } catch (PDOException $e) {
            $message = "구독 해제 중 오류가 발생했습니다: " . $e->getMessage();
        }
    }
} else {
    // GET 요청 시 URL 매개변수로 이메일을 받아 바로 처리
    $email = $_GET['email'] ?? '';
    if (!empty($email) && filter_var($email, FILTER_VALIDATE_EMAIL)) {
        try {
            $stmt = $pdo->prepare("DELETE FROM subscribers WHERE email = ?");
            $stmt->execute([$email]);
            if ($stmt->rowCount() > 0) {
                $message = "구독이 성공적으로 해제되었습니다.";
                $isSuccess = true;
            } else {
                $message = "해당 이메일 주소는 구독 목록에 없습니다.";
            }
        } catch (PDOException $e) {
            $message = "구독 해제 중 오류가 발생했습니다: " . $e->getMessage();
        }
    } else {
        $message = "이메일 주소가 유효하지 않습니다.";
    }
}

header('Location: /?message=' . urlencode($message) . '&isSuccess=' . ($isSuccess ? 'true' : 'false'));
exit;