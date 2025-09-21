<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';
require_once 'config.php';

$message = '';
$isSuccess = false;

// 메일 제목과 내용 설정
$subject = "테스트 뉴스레터";
$body = "<h1>안녕하세요! FreeLetter 테스트 메일입니다.</h1><p>이 메일은 로컬에서 정상적으로 전송되었는지 확인하기 위한 용도입니다.</p>";

// PHPMailer 설정
$mail = new PHPMailer(true);
try {
    $mail->isSMTP();
    $mail->Host = getenv('SMTP_HOST') ?: 'mailhog';
    $mail->Port = getenv('SMTP_PORT') ?: 1025;

    // MailHog는 인증과 암호화가 필요 없도록 설정
    $mail->SMTPAuth = false;
    $mail->SMTPSecure = false; // 암호화 비활성화
    $mail->SMTPAutoTLS = false; // 자동 TLS 사용 비활성화

    $mail->setFrom('noreply@freeletter.com', 'FreeLetter');
    $mail->addAddress('test@example.com'); // 테스트용 주소
    $mail->isHTML(true);
    $mail->Subject = $subject;
    $mail->Body = $body;
    $mail->CharSet = 'UTF-8';

    $mail->send();
    $message = "테스트 메일이 성공적으로 전송되었습니다.";
    $isSuccess = true;

} catch (Exception $e) {
    $message = "메일 전송 실패: {$mail->ErrorInfo}";
}

?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title>메일 전송 테스트</title>
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
            font-weight: bold;
        }
        .success { background-color: #d4edda; color: #155724; }
        .error { background-color: #f8d7da; color: #721c24; }
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
        <h1>메일 전송 테스트</h1>
        <div class="message-box <?= $isSuccess ? 'success' : 'error' ?>">
            <?= htmlspecialchars($message) ?>
        </div>
        <a href="index.php" class="home-link">홈으로 돌아가기</a>
    </div>
</body>
</html>