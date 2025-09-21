<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';
require_once 'config.php';

// 최소한의 인증
$password = "your_secret_password";
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || $_POST['password'] !== $password) {
    die("잘못된 접근 또는 비밀번호가 틀렸습니다.");
}

// 이메일 제목과 내용
$subject = $_POST['subject'] ?? '새로운 뉴스레터가 도착했습니다!';
$body = $_POST['body'] ?? '이것은 테스트 뉴스레터입니다.';

// PHPMailer 설정
$mail = new PHPMailer(true);
try {
    // SMTP 설정
    $mail->isSMTP();
    
    // 환경 변수를 사용하여 동적으로 SMTP 서버 설정
    $mail->Host = getenv('SMTP_HOST') ?: 'mailhog';
    $mail->Port = getenv('SMTP_PORT') ?: 1025;
    $mail->SMTPAuth = true; // 대부분의 실제 서버는 인증 필요

    // POP3/SMTP 사용자 정보
    $mail->Username = getenv('SMTP_USERNAME');
    $mail->Password = getenv('SMTP_PASSWORD');

    // TLS 암호화 설정 (대부분의 서버는 필요)
    $mail->SMTPSecure = 'tls'; 

    $mail->setFrom('noreply@freeletter.com', 'FreeLetter');
    $mail->isHTML(true);
    $mail->Subject = $subject;
    $mail->Body = $body;
    $mail->CharSet = 'UTF-8';

    // DB에서 구독자 목록 가져오기
    $stmt = $pdo->query("SELECT email FROM subscribers");
    $subscribers = $stmt->fetchAll(PDO::FETCH_COLUMN);

    if (empty($subscribers)) {
        echo "구독자가 없습니다. <a href='index.php'>홈으로</a>";
        exit;
    }
    
    // 모든 구독자에게 메일 보내기
    foreach ($subscribers as $email) {
        $mail->clearAddresses();
        $mail->addAddress($email);
        $mail->send();
    }
    
    echo "모든 뉴스레터가 전송되었습니다. <a href='index.php'>홈으로</a>";
    
} catch (Exception $e) {
    echo "메일 전송 실패: {$mail->ErrorInfo}";
}

?>