<div class="form-wrapper">
    <h2 class="section-title">비밀번호 찾기</h2>
    <p>가입한 이메일 주소를 입력하시면, 비밀번호 재설정 링크를 보내드립니다.</p>

    <?php
    if (isset($_SESSION['success'])) {
        echo '<div class="message-box success">' . htmlspecialchars($_SESSION['success']) . '</div>';
        unset($_SESSION['success']);
    }

    if (isset($_SESSION['error'])) {
        echo '<div class="message-box error">' . htmlspecialchars($_SESSION['error']) . '</div>';
        unset($_SESSION['error']);
    }
    ?>

    <form action="/repassword" method="POST" class="subscribe-form">
        <div class="form-group">
            <input type="email" name="email" placeholder="이메일 주소" class="email-input" required>
        </div>
        <button type="submit" class="action-button primary-button">비밀번호 재설정 링크 보내기</button>
    </form>
</div>
