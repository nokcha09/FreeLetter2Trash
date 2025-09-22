<?php
require_once 'config.php';

$message = '';
$isSuccess = false;

// GET 또는 POST 요청에서 이메일 주소가 있는 경우에만 로직 실행
if (isset($_GET['email']) || isset($_POST['email'])) {
    
    // GET 요청일 경우 URL 쿼리 파라미터에서 이메일 가져오기
    if (isset($_GET['email'])) {
        $email = urldecode($_GET['email']);
    } elseif (isset($_POST['email'])) {
        $email = $_POST['email'];
    }

    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        try {
            $stmt = $pdo->prepare("DELETE FROM subscribers WHERE email = :email");
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            
            if ($stmt->rowCount() > 0) {
                $message = "구독이 성공적으로 해제되었습니다.";
                $isSuccess = true;
            } else {
                $message = "해당 이메일 주소는 구독되어 있지 않습니다.";
            }
        } catch (PDOException $e) {
            $message = "오류가 발생했습니다: " . $e->getMessage();
        }
    } else {
        $message = "유효하지 않은 이메일 주소입니다.";
    }
}
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title>구독 해제</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>구독 해제</h1>
        
        <?php if (!empty($message)): ?>
            <div class="message-box <?= $isSuccess ? 'success' : 'error' ?>">
                <?= htmlspecialchars($message) ?>
            </div>
        <?php endif; ?>

        <p>구독 해제를 원하시면 이메일 주소를 입력해주세요.</p>
        <form action="unsubscribe.php" method="POST">
            <input type="email" name="email" placeholder="이메일 주소" required>
            <button type="submit">구독 해제하기</button>
        </form>
        
        <a href="index.php" class="home-link">홈으로 돌아가기</a>
    </div>
</body>
</html>