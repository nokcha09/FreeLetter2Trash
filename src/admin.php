<?php
session_start();

require_once 'config.php';

// 관리자 세션이 없으면 로그인 페이지로 리디렉션
if (!isset($_SESSION['authenticated']) || !$_SESSION['is_admin']) {
    header('Location: login.php');
    exit;
}

$subscribers = [];
try {
    $stmt = $pdo->query("SELECT email, subscribe_date FROM subscribers ORDER BY subscribe_date DESC");
    $subscribers = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("DB 오류: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title>관리자 페이지 - 구독자 목록</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>구독자 목록</h1>
        <?php if (empty($subscribers)): ?>
            <p>현재 구독자가 없습니다.</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>이메일 주소</th>
                        <th>구독일</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($subscribers as $subscriber): ?>
                        <tr>
                            <td><?= htmlspecialchars($subscriber['email']) ?></td>
                            <td><?= htmlspecialchars($subscriber['subscribe_date']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
        <a href="logout.php" class="logout-link">로그아웃</a>
    </div>
</body>
</html>