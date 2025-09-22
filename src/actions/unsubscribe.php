<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
// vendor와 config 파일을 올바르게 불러옵니다.
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// vendor와 config 파일을 올바르게 불러옵니다.
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = '유효한 이메일 주소를 입력해주세요.';
        header('Location: /unsubscribe');
        exit;
    }

    try {
        // 이메일이 데이터베이스에 존재하는지 확인
        $stmt = $pdo->prepare("SELECT email FROM subscribers WHERE email = ?");
        $stmt->execute([$email]);
        $subscriber = $stmt->fetch();

        if ($subscriber) {
            // 이메일이 존재하면 바로 데이터베이스에서 삭제
            $stmt = $pdo->prepare("DELETE FROM subscribers WHERE email = ?");
            $stmt->execute([$email]);
            
            // PHPMailer 설정
            $mail = new PHPMailer(true);
            $mail->isSMTP();
            $mail->Host       = getenv('SMTP_HOST') ?: 'mailhog';
            $mail->Port       = getenv('SMTP_PORT') ?: 1025;
            $mail->SMTPAuth   = false;
            $mail->SMTPSecure = false;
            $mail->SMTPAutoTLS = false;

            // 발신자 및 수신자 설정
            $mail->setFrom('noreply@freeletter.com', 'FreeLetter');
            $mail->addAddress($email);
            
            // 메일 내용 설정
            $mail->isHTML(true);
            $mail->Subject = 'FreeLetter 구독 취소 확인 메일입니다.';
            
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
                                <h2 style='margin-top: 0; color: #3C6E52;'>구독 취소 완료</h2>
                                <p>안녕하세요.</p>
                                <p>요청하신 이메일({$email})의 뉴스레터 구독이 성공적으로 취소되었습니다.</p>
                                <p>더 이상 FreeLetter에서 뉴스레터가 발송되지 않습니다.</p>
                                <p style='margin-top: 20px; font-size: 12px; color: #999999;'>언제든지 다시 구독할 수 있습니다. FreeLetter를 이용해주셔서 감사합니다.</p>
                            </td>
                        </tr>
                    </table>
                </div>
            ";
            $mail->Body = $htmlBody;
            $mail->send();

            $_SESSION['success'] = '구독이 성공적으로 취소되었으며, 확인 메일이 발송되었습니다.';

        } else {
            $_SESSION['error'] = '해당 이메일은 구독 목록에 없습니다.';
        }
    } catch (Exception $e) {
        $_SESSION['error'] = '메일 발송에 실패했습니다. 잠시 후 다시 시도해주세요. Mailer Error: ' . $mail->ErrorInfo;
    } catch (PDOException $e) {
        $_SESSION['error'] = '구독 취소 중 오류가 발생했습니다. 잠시 후 다시 시도해주세요.';
    }

    header('Location: /unsubscribe');
    exit;
}
?>