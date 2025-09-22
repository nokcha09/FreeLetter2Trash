<?php
session_start();
require_once __DIR__ . '/../config/config.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($password)) {
        $message = "사용자 이름과 비밀번호를 모두 입력해주세요.";
    } else {
        try {
            $stmt = $pdo->prepare("SELECT id, username, password, is_admin FROM users WHERE username = :username");
            $stmt->bindParam(':username', $username);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['loggedin'] = true;
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $username;
                $_SESSION['is_admin'] = (bool)$user['is_admin'];

                header('Location: /views/admin.php');
                exit;
            } else {
                $message = "잘못된 사용자 이름 또는 비밀번호입니다.";
            }
        } catch (PDOException $e) {
            $message = "로그인 중 오류가 발생했습니다: " . $e->getMessage();
        }
    }
}

// 로그인 페이지로 리디렉션 (메시지 포함)
header('Location: /views/login.php?message=' . urlencode($message));
exit;

?>
