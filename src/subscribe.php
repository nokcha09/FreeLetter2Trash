<?php
require_once 'config.php';

$message = '';
$email = '';
$isSuccess = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];

    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        try {
            $stmt = $pdo->prepare("INSERT INTO subscribers (email) VALUES (:email)");
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            $message = "구독이 완료되었습니다. 감사합니다!";
            $isSuccess = true;
        } catch (PDOException $e) {
            if ($e->getCode() == '23000') {
                $message = "이미 구독된 이메일 주소입니다.";
            } else {
                $message = "오류가 발생했습니다: " . $e->getMessage();
            }
        }
    } else {
        $message = "유효하지 않은 이메일 주소입니다.";
    }
} else {
    $message = "잘못된 접근입니다.";
}

?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title>구독 완료!</title>
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
        p {
            color: #666;
            font-size: 1.1em;
            line-height: 1.5;
        }
        .email {
            font-weight: bold;
            color: #007bff;
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
        <h1><?= htmlspecialchars($message) ?></h1>
        <?php if ($isSuccess && !empty($email)) : ?>
            <p><strong><span class="email"><?= htmlspecialchars($email) ?></span></strong> 이메일로<br>소식이 전달될 예정입니다.</p>
        <?php endif; ?>
        <a href="index.php" class="home-link">홈으로 돌아가기</a>
    </div>
</body>
</html>