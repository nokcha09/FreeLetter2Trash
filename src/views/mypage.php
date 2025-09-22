<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user_id'])) {
    $_SESSION['error'] = "로그인 후 이용할 수 있습니다.";
    header('Location: /login.php');
    exit;
}

// 세션에서 현재 별명 가져오기
$current_nickname = $_SESSION['nickname'] ?? '별명 없음';

// 메시지 표시
$message = isset($_GET['message']) ? htmlspecialchars($_GET['message']) : '';
$isSuccess = isset($_GET['isSuccess']) && $_GET['isSuccess'] === 'true';
?>
<div class="form-wrapper">
    <h2 class="section-title">마이 페이지</h2>

    <?php if ($message): ?>
        <div class="message-box <?= $isSuccess ? 'success' : 'error' ?>">
            <?= $message ?>
        </div>
    <?php endif; ?>

    <form action="/actions/mypage" method="POST" class="subscribe-form">
        <h3 class="subsection-title">별명 변경</h3>
        <div class="form-group">
            <label for="current_nickname">현재 별명</label>
            <input type="text" id="current_nickname" value="<?= htmlspecialchars($current_nickname) ?>" class="email-input" disabled>
        </div>
        <div class="form-group">
            <label for="new_nickname">새로운 별명</label>
            <input type="text" id="new_nickname" name="nickname" placeholder="새로운 별명을 입력하세요" class="email-input" required>
        </div>
        <button type="submit" class="action-button primary-button">별명 변경</button>
    </form>

    <hr class="divider">

    <form action="/password_change" method="POST" class="subscribe-form">
        <h3 class="subsection-title">비밀번호 변경</h3>
        <div class="form-group">
            <label for="current_password">현재 비밀번호</label>
            <input type="password" id="current_password" name="current_password" placeholder="현재 비밀번호를 입력하세요" class="email-input" required>
        </div>
        <div class="form-group">
            <label for="new_password">새 비밀번호</label>
            <input type="password" id="new_password" name="new_password" placeholder="새 비밀번호를 입력하세요" class="email-input" required>
        </div>
        <div class="form-group">
            <label for="confirm_password">새 비밀번호 확인</label>
            <input type="password" id="confirm_password" name="confirm_password" placeholder="새 비밀번호를 다시 입력하세요" class="email-input" required>
        </div>
        <button type="submit" class="action-button primary-button">비밀번호 변경</button>
    </form>

    <hr class="divider">
    
    <div class="delete-account-section">
        <h3 class="subsection-title">계정 삭제</h3>
        <p>계정을 삭제하면 모든 정보가 영구적으로 삭제되며, 복구할 수 없습니다.</p>
        <button id="delete-account-button" class="action-button delete-button">계정 삭제하기</button>
    </div>
</div>

<script>
document.getElementById('delete-account-button').addEventListener('click', function() {
    if (confirm('정말로 계정을 삭제하시겠습니까? 이 작업은 되돌릴 수 없습니다.')) {
        window.location.href = '/delete_account';
    }
});
</script>
