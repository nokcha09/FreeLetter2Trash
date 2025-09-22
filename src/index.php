<?php
session_start();
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title>FreeLetter: 모두의 뉴스레터</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="header">
        <h1>FreeLetter</h1>
        <div>
            <?php if (isset($_SESSION['authenticated']) && $_SESSION['authenticated'] === true): ?>
                <span>환영합니다, <?= htmlspecialchars($_SESSION['username']) ?>님!</span>
                <?php if ($_SESSION['is_admin']): ?>
                    <a href="admin.php">관리자 페이지</a>
                <?php endif; ?>
                <a href="logout.php">로그아웃</a>
            <?php else: ?>
                <a href="login.php">로그인</a>
            <?php endif; ?>
        </div>
    </div>
    
    <div class="container">
        <h1 class="main-title">뉴스레터</h1>
        
        <div class="about">
            <p><strong>FreeLetter</strong>는 누구나 쉽게 뉴스레터를 구독하고 발송할 수 있는 오픈소스 프로젝트입니다. <br>PHP, Nginx, MySQL, Docker를 이용해 최소한의 설정만으로 뉴스레터 발송 시스템을 구축할 수 있습니다.</p>
        </div>

        <div class="action-container">
            <form action="subscribe.php" method="POST" style="width: 100%;">
                <input type="email" name="email" placeholder="이메일 주소를 입력하세요" class="email-input" required>
                <div class="main-buttons">
                    <button type="submit" class="action-button">구독하기</button>
                    <a href="send.php" class="action-button">뉴스레터 보내기</a>
                </div>
            </form>
        </div>
        
        <a href="unsubscribe.php" class="unsubscribe-link">구독 해제</a>
    </div>
</body>
</html>