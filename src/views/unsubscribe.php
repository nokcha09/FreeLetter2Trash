<?php
$page_title = '뉴스레터 구독 해제';
?>
<div class="container">
    <h1 class="main-title">뉴스레터 구독 해제</h1>
    <p>더 이상 뉴스레터를 받고 싶지 않으시면 이메일 주소를 입력하고 '구독 해제' 버튼을 눌러주세요.</p>
    <form action="unsubscribe" method="POST" class="subscribe-form">
        <input type="email" name="email" placeholder="이메일 주소를 입력하세요" class="email-input" required>
        <div class="main-buttons">
            <button type="submit" class="action-button">구독 해제</button>
        </div>
    </form>
    <a href="/" class="unsubscribe-link">홈으로 돌아가기</a>
</div>