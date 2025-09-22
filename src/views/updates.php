<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$page_title = 'FreeLetter 업데이트 히스토리';
// updates.json 파일의 경로 설정
$updates_file = __DIR__ . '/../data/updates.json';

// 파일이 존재하는지 확인하고 내용 불러오기
if (file_exists($updates_file)) {
    $json_data = file_get_contents($updates_file);
    $updates = json_decode($json_data, true);

    // 업데이트 내용을 최신순으로 정렬 (만약 JSON 파일에 순서가 뒤섞여 있다면)
    usort($updates, function($a, $b) {
        return strtotime($b['date']) - strtotime($a['date']);
    });

} else {
    // 파일이 없을 경우 오류 메시지
    $updates = [];
    $error_message = "업데이트 파일을 찾을 수 없습니다.";
}
?>

<h1 class="main-title" style="text-align: center;">FreeLetter 업데이트 히스토리</h1>

<?php if (isset($error_message)): ?>
    <p class="error-message"><?= htmlspecialchars($error_message) ?></p>
<?php else: ?>
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
<?php endif; ?>