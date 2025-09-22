<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// vendor와 config 파일을 올바르게 불러옵니다.
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/config.php';

$message = '';
$isSuccess = false;

// POST 요청일 때만 메일 전송 로직을 실행합니다.
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

        $stmt = $pdo->query("SELECT email FROM subscribers");
        $subscribers = $stmt->fetchAll(PDO::FETCH_COLUMN);

        if (empty($subscribers)) {
            $message = "구독자가 없습니다. 메일을 보낼 수 없습니다.";
        } else {
            foreach ($subscribers as $email) {
                // 구독 해제 링크를 동적으로 생성 (도메인과 포트 자동 포함)
                $unsubscribeLink = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://{$_SERVER['HTTP_HOST']}/unsubscribe.php?email=" . urlencode($email);

                // HTML 메일 템플릿
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
    }
    
    // 메일 전송 후 admin 페이지로 리디렉션
    header('Location: /admin?message=' . urlencode($message) . '&isSuccess=' . ($isSuccess ? 'true' : 'false'));
    exit;
}
?>

<div class="container">
    <h1 class="main-title">뉴스레터 보내기</h1>
    <form action="/send" method="POST">
        <input type="text" name="subject" placeholder="제목" required>
        <textarea name="body" placeholder="본문" required></textarea>
        <div class="main-buttons">
            <button type="submit" class="action-button">전송</button>
        </div>
    </form>
    <a href="/admin" class="unsubscribe-link">관리자 페이지로 돌아가기</a>
</div>