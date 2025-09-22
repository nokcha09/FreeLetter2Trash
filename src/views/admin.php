<?php
session_start();
require_once __DIR__ . '/../../src/config/config.php';

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: /views/login.php');
    exit;
}

$message = '';
$subscribers = [];

try {
    $stmt = $pdo->query("SELECT id, email, subscribed_at FROM subscribers ORDER BY subscribed_at DESC");
    $subscribers = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $message = "구독자 정보를 불러오는 데 실패했습니다: " . $e->getMessage();
}

?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title>관리자 페이지</title>
    <link rel="stylesheet" href="/public/style.css">
</head>
<body>
    <?php require_once '../includes/header.php'; ?>

    <div class="container">
        <h1>관리자 페이지</h1>
        
        <?php if (!empty($message)): ?>
            <div class="message-box error-message">
                <?= htmlspecialchars($message) ?>
            </div>
        <?php endif; ?>

        <h2>구독자 목록</h2>
        <?php if (empty($subscribers)): ?>
            <p>현재 구독자가 없습니다.</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>이메일</th>
                        <th>구독일</th>
                        <th>작업</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($subscribers as $subscriber): ?>
                        <tr>
                            <td><?= htmlspecialchars($subscriber['id']) ?></td>
                            <td><?= htmlspecialchars($subscriber['email']) ?></td>
                            <td><?= htmlspecialchars($subscriber['subscribed_at']) ?></td>
                            <td>
                                <form action="/actions/unsubscribe.php" method="POST" style="display:inline;">
                                    <input type="hidden" name="email" value="<?= htmlspecialchars($subscriber['email']) ?>">
                                    <button type="submit" class="button-base action-button">구독 해제</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>

        <a href="/actions/send.php" class="button-base action-button" style="margin-top: 20px;">뉴스레터 보내기</a>
        <a href="/actions/logout.php" class="logout-link">로그아웃</a>
    </div>
    <?php require_once '../includes/footer.php'; ?>
</body>
</html>