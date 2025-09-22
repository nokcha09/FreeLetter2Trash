<?php
session_start();
require_once 'config.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    // 관리자 계정 확인
    $is_admin = in_array($username, ADMIN_USERNAMES) && ($password === ADMIN_PASSWORD);

    // 일반 사용자 (현재는 admin만)
    $is_user = ($username === 'login' && $password === 'password') || $is_admin;

    if ($is_user) {
        // 로그인 성공 시 세션에 정보 저장
        $_SESSION['authenticated'] = true;
        $_SESSION['username'] = $username;
        $_SESSION['is_admin'] = $is_admin;

        if ($is_admin) {
            header('Location: admin.php');
        } else {
            header('Location: index.php');
        }
        exit;
    } else {
        $message = "아이디 또는 비밀번호가 틀렸습니다.";
    }
}

// 로그인 상태일 경우
if (isset($_SESSION['authenticated']) && $_SESSION['authenticated'] === true) {
    if ($_SESSION['is_admin']) {
        header('Location: admin.php');
    } else {
        header('Location: index.php');
    }
    exit;
}

?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title>로그인</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="login-box">
        <h1>로그인</h1>
        <?php if (!empty($message)): ?>
            <p class="message"><?= htmlspecialchars($message) ?></p>
        <?php endif; ?>
        <form action="login.php" method="POST">
            <input type="text" name="username" placeholder="아이디" required><br>
            <input type="password" name="password" placeholder="비밀번호" required><br>
            <button type="submit">로그인</button>
        </form>
    </div>
</body>
</html>