<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
// 로그인 상태 확인
if (!isset($_SESSION['user_id'])) {
    // JavaScript alert를 띄운 후 홈 페이지로 리다이렉션
    echo "<script>alert('로그인 후 이용할 수 있습니다.'); window.location.href='/';</script>";
    exit;
}

$message = isset($_GET['message']) ? htmlspecialchars($_GET['message']) : '';
$isSuccess = isset($_GET['isSuccess']) && $_GET['isSuccess'] === 'true';
?>

<div class="form-wrapper">
    <h1 class="main-title">뉴스레터 보내기</h1>

    <?php if ($message): ?>
        <div class="message-box <?= $isSuccess ? 'success' : 'error' ?>">
            <?= $message ?>
        </div>
    <?php endif; ?>

    <form action="/actions/send.php" method="POST" class="newsletter-form newsletter-send-form" style="max-width: 900px;">
        <div class="form-group">
            <input type="text" name="subject" placeholder="제목" class="text-input" required>
        </div>
        <div class="form-group">
            <textarea name="body" placeholder="내용" class="textarea-input" rows="10" required></textarea>
        </div>
        <div class="form-actions">
            <button type="submit" class="action-button primary-button">전송</button>
        </div>
    </form>
</div>