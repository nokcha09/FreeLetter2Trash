<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<div class="form-wrapper">
<h2 class="section-title">회원가입</h2>
    
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

    <form action="/register" method="POST" class="subscribe-form">
        <div class="form-group">
            <input type="email" name="email" placeholder="이메일 주소" class="email-input" required>
        </div>
        <div class="form-group">
            <input type="password" name="password" placeholder="비밀번호" class="email-input" required>
        </div>
        <button type="submit" class="action-button primary-button">가입하기</button>
    </form>
</div>