<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/config.php';

// 로그인 상태 확인 (폼이 직접 POST될 경우를 대비)
if (!isset($_SESSION['user_id'])) {
    header('Location: /login.php');
    exit;
}

$message = '';
$isSuccess = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $subject = $_POST['subject'] ?? '새로운 뉴스레터';
    $raw_body = $_POST['body'] ?? '내용이 없습니다.';

    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = getenv('SMTP_HOST') ?: 'mailhog';
        $mail->Port = getenv('SMTP_PORT') ?: 1025;
        $mail->SMTPAuth = false;
        $mail->SMTPSecure = false;
        $mail->SMTPAutoTLS = false;

        $mail->setFrom('noreply@freeletter.com', 'FreeLetter');
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->CharSet = 'UTF-8';

        // 'verified' 상태인 구독자만 선택
        $stmt = $pdo->query("SELECT email FROM subscribers WHERE status = 'verified'");
        $subscribers = $stmt->fetchAll(PDO::FETCH_COLUMN);

        if (empty($subscribers)) {
            $message = "인증된 구독자가 없습니다. 메일을 보낼 수 없습니다.";
        } else {
            foreach ($subscribers as $email) {
                $unsubscribeLink = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://{$_SERVER['HTTP_HOST']}/unsubscribe?email=" . urlencode($email);

                $htmlBody = "
                <div style='font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px;'>
                    <table style='width: 100%; max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 8px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);'>
                        <tr>
                            <td style='padding: 20px; text-align: center; background-color: #6C8E69; border-top-left-radius: 8px; border-top-right-radius: 8px;'>
                                <h1 style='color: #ffffff; margin: 0;'>FreeLetter</h1>
                            </td>
                        </tr>
                        <tr>
                            <td style='padding: 20px; color: #333333;'>
                                <h2>{$subject}</h2>
                                <p>{$raw_body}</p>
                            </td>
                        </tr>
                        <tr>
                            <td style='padding: 20px; text-align: center; border-top: 1px solid #eeeeee;'>
                                <p style='font-size: 12px; color: #999999;'>더 이상 뉴스레터를 받고 싶지 않으시면 <a href='{$unsubscribeLink}' style='color: #6C8E69; text-decoration: none;'>여기</a>를 클릭하세요.</p>
                            </td>
                        </tr>
                    </table>
                </div>
                ";

                $mail->clearAddresses();
                $mail->addAddress($email);
                $mail->Body = $htmlBody;
                $mail->send();
            }
            $message = "뉴스레터가 모든 구독자에게 성공적으로 전송되었습니다.";
            $isSuccess = true;
        }

    } catch (Exception $e) {
        $message = "메일 전송 실패: {$mail->ErrorInfo}";
        $isSuccess = false;
    }
    
    header('Location: /send?message=' . urlencode($message) . '&isSuccess=' . ($isSuccess ? 'true' : 'false'));
    exit;
}