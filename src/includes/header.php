<?php
// 세션이 시작되지 않았다면 시작합니다.
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$page_title = isset($page_title) ? $page_title : 'FreeLetter: 모두의 뉴스레터';
?>
<!DOCTYPE html>
<html lang="ko">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FreeLetter</title>
    <link rel="stylesheet" href="public/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>
<body>
    <header class="header">
        <a href="/" class="logo">FreeLetter</a>
        <nav class="nav">
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="/send" class="nav-link">뉴스레터 전송</a>
                <a href="/mypage" class="nav-link">마이 페이지</a>
                <a href="/logout" class="nav-link">로그아웃</a>
            <?php else: ?>
                <a href="/register" class="nav-link">회원가입</a>
                <a href="/login" class="nav-link">로그인</a>
            <?php endif; ?>
        </nav>
    </header>