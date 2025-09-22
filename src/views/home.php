<?php
// 기존에 있던 config.php 파일 포함
require_once __DIR__ . '/../config/config.php';

// 구독자 수를 가져오는 함수
function getSubscriberCount($pdo) {
    try {
        $stmt = $pdo->query("SELECT COUNT(*) FROM subscribers");
        return $stmt->fetchColumn();
    } catch (PDOException $e) {
        // 오류 발생 시 0 또는 오류 메시지 반환
        return 0;
    }
}

// 구독자 수 변수에 저장
$subscriberCount = getSubscriberCount($pdo);

// updates.json 파일의 경로 설정
$updates_file = __DIR__ . '/../data/updates.json';
$updates = [];
if (file_exists($updates_file)) {
    $json_data = file_get_contents($updates_file);
    $all_updates = json_decode($json_data, true);
    // 최신 업데이트 2개만 가져오기
    $updates = array_slice($all_updates, 0, 2);
}
?>

<div class="main-hero-section">
    <div class="hero-content">
        <h1>자유롭게 뉴스레터를 보내고, 받으세요</h1>
        <p>이 프로젝트는 자유와 혼돈이 공존하는 작은 사회 실험입니다. 자신의 메일함을 쓰레기통으로 만들 용기가 있는 사람들만이 이 곳의 뉴스레터를 구독합니다. 어떤 이야기가 도착할지는 아무도 알 수 없죠.</p>
        <div class="main-buttons">
            <a href="subscribe" class="action-button primary-button">뉴스레터 구독하기</a>
            <a href="login" class="action-button secondary-button">뉴스레터 전송하기</a>
        </div>
    </div>
</div>

<div class="updates-section">
    <h2 class="section-title">업데이트 내용</h2>
    <ul class="timeline">
        <?php foreach ($updates as $update): ?>
        <li>
            <div class="timeline-content">
                <h3 class="update-date"><?= htmlspecialchars($update['date']) ?></h3>
                <p><b><?= htmlspecialchars($update['title']) ?></b></p>
                <p><?= htmlspecialchars($update['content']) ?></p>
            </div>
        </li>
        <?php endforeach; ?>
    </ul>
    <div style="text-align: center; margin-top: 20px;">
        <a href="updates" class="action-button secondary-button">업데이트 히스토리 보기</a>
    </div>
</div>

<div class="features-section">
    <h2>기능</h2>
    <div class="features-grid">
        <div class="feature-card">
            <i class="fas fa-envelope-open-text"></i>
            <h3>간편한 구독/해제</h3>
            <p>이메일 주소만으로 뉴스레터를 쉽게 구독하고 해제할 수 있습니다.</p>
            <a href="subscribe" class="feature-link">더 알아보기 →</a>
        </div>
        <div class="feature-card">
            <i class="fas fa-paper-plane"></i>
            <h3>손쉬운 뉴스레터 발송</h3>
            <p>직관적인 로그인 후, 뉴스레터 내용을 작성하고 보낼 수 있습니다.</p>
            <a href="login" class="feature-link">더 알아보기 →</a>
        </div>
        <div class="feature-card">
            <i class="fas fa-code"></i>
            <h3>오픈 소스 기반</h3>
            <p>PHP, Nginx, Docker로 구성된 오픈 소스 프로젝트로 자유롭게 수정 가능합니다.</p>
            <a href="https://github.com/your-username/your-repo" class="feature-link">더 알아보기 →</a>
        </div>
    </div>
</div>

<div class="developer-message-section">
    <div class="speech-bubble-container">
        <h2 class="section-title">개발자 이야기</h2>
        <div class="speech-bubble">
            <p>안녕하세요. 개발자 <b>NOKCHA</b>입니다. 🍵</p>
            <p>무료하고 심심한 당신의 메일함에 격렬한 변화를 주고 싶어 이 '쓰레기 같은' 프로젝트를 기획했습니다. 이곳은 예측 불가능한 뉴스레터가 당신의 메일함을 채우는, 그야말로 미지의 영역입니다.</p>
            <p>이 서비스는 순전히 재미를 위해 운영됩니다. 그래서 언제 종료될지 저도 알 수 없어요. 서버가 <b>"더 이상은 무리!"</b>라고 비명을 지르는 순간, 프로젝트는 예고 없이 끝날 수 있습니다. 그러니 너무 원망 마세요. 그냥 즐기세요!</p>
        </div>
    </div>
</div>

<div class="cta-section">
    <div class="cta-content">
        <h2>지금 바로 구독하세요!</h2>
        <p>이 서비스는 <b>완전히 무료</b>로 제공됩니다. 뉴스레터의 모든 이야기는 구독자에게 즉시 전달됩니다.</p>
        <p style="font-size: 1.1em; margin-top: 20px;">이 프로젝트에 <b><?= htmlspecialchars($subscriberCount) ?></b>명이 참여하고 있습니다.</p>
        <a href="subscribe" class="action-button primary-button">뉴스레터 구독하기</a>
    </div>
</div>