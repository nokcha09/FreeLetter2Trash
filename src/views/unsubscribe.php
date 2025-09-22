<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$page_title = '뉴스레터 구독 해제';
?>
<div class="form-wrapper">
    <h2 class="section-title">뉴스레터 구독 취소</h2>

    <?php
    // 성공 메시지가 있을 경우 표시
    if (isset($_SESSION['success'])) {
        echo '<div class="message-box success">' . htmlspecialchars($_SESSION['success']) . '</div>';
        unset($_SESSION['success']); // 메시지를 한 번 표시한 후 세션에서 제거합니다.
    }

    // 에러 메시지가 있을 경우 표시
    if (isset($_SESSION['error'])) {
        echo '<div class="message-box error">' . htmlspecialchars($_SESSION['error']) . '</div>';
        unset($_SESSION['error']); // 메시지를 한 번 표시한 후 세션에서 제거합니다.
    }
    ?>

    <form action="unsubscribe" method="POST" class="unsubscribe-form">
        <div class="form-group">
            <input type="email" id="email" name="email" placeholder="이메일 주소" class="email-input" required>
            <button type="submit" class="action-button secondary-button">구독 취소하기</button>
        </div>
        <p class="warning-text">구독을 취소하면 더 이상 뉴스레터가 발송되지 않습니다.</p>
    </form>
</div>