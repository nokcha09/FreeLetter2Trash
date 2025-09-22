<?php
$page_title = isset($page_title) ? $page_title : 'FreeLetter: 모두의 뉴스레터';
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($page_title) ?></title>
    <link rel="stylesheet" href="public/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>
<body>
    <div class="header">
        <h1><a href="/" class="logo">FreeLetter</a></h1>
        <div>
            <?php if (isset($_SESSION['authenticated']) && $_SESSION['authenticated'] === true): ?>
                <a href="logout" class="nav-link">로그아웃</a>
            <?php else: ?>
                <a href="login" class="nav-link">뉴스레터 전송하기</a>
            <?php endif; ?>
        </div>
    </div>