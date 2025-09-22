<?php
$page_title = '뉴스레터 구독';
require_once __DIR__ . '/../includes/header.php';
?>
<div class="container">
    <h1 class="main-title">뉴스레터 구독하기</h1>
    <div class="message-box error">
        <p><strong>주의:</strong> 이 뉴스레터를 구독하면 자신의 메일함이 쓰레기통이 될 수 있습니다. 모든 이야기는 익명으로 자유롭게 발송되며, 사이트는 그 내용에 대해 책임지지 않습니다.</p>
    </div>
    <form action="subscribe" method="POST" class="subscribe-form">
        <input type="email" name="email" placeholder="이메일 주소를 입력하세요" class="email-input" required>
        <div class="main-buttons">
            <button type="submit" class="action-button">구독하기</button>
        </div>
    </form>
    <a href="unsubscribe" class="unsubscribe-link">구독 해제</a>
</div>
<?php
require_once __DIR__ . '/../includes/footer.php';
?>