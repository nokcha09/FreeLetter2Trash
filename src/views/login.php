<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<div class="form-wrapper">
    <h2 class="section-title">로그인</h2>

    <?php
    // 성공 메시지가 있을 경우 표시
    if (isset($_SESSION['success'])) {
        echo '<div class="message-box success">' . htmlspecialchars($_SESSION['success']) . '</div>';
        unset($_SESSION['success']);
    }
    // 에러 메시지가 있을 경우 표시
    if (isset($_SESSION['error'])) {
        echo '<div class="message-box error">' . htmlspecialchars($_SESSION['error']) . '</div>';
        unset($_SESSION['error']);
    }
    ?>

    <form action="/login" method="POST" class="subscribe-form">
        <div class="form-group">
            <input type="email" name="email" placeholder="이메일 주소" class="email-input" required>
        </div>
        <div class="form-group">
            <input type="password" name="password" placeholder="비밀번호" class="email-input" required>
        </div>
        <button type="submit" class="action-button primary-button">로그인</button>
    </form>
    <div class="link-group" style="margin-top: 15px;">
        <a href="/repassword" class="secondary-link">비밀번호 찾기</a>
    </div>
</div>
