<?php
// 이 페이지는 처음 접속 시 폼을 보여주고,
// POST 요청이 왔을 때만 구독 해제 로직을 처리합니다.

require_once 'config.php';

$message = '';
$isSuccess = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];

    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        try {
            // subscribers 테이블에서 해당 이메일 삭제
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
    <style>
        body {
            font-family: sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #f4f4f9;
            text-align: center;
        }
        .container {
            padding: 40px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            color: #333;
            margin-bottom: 20px;
        }
        .message-box {
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            font-weight: bold;
        }
        .success { background-color: #d4edda; color: #155724; }
        .error { background-color: #f8d7da; color: #721c24; }
        input[type="email"] {
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 1em;
            width: 80%;
            margin-bottom: 10px;
        }
        button {
            padding: 12px 20px;
            border: none;
            background-color: #007bff;
            color: #fff;
            border-radius: 5px;
            font-size: 1em;
            cursor: pointer;
        }
        .home-link {
            display: inline-block;
            margin-top: 30px;
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
        }
    </style>
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