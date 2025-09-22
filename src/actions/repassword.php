<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../vendor/autoload.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = '유효한 이메일 주소를 입력해주세요.';
        header('Location: /forgot_password');
        exit;
    }

    try {
        // 1. 이메일 주소로 사용자 존재 여부 확인
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ? AND status = 'verified'");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user) {
            // 2. 임시 비밀번호 생성 (8자리 랜덤 문자열)
            $temp_password = bin2hex(random_bytes(4));
            $hashedPassword = password_hash($temp_password, PASSWORD_DEFAULT);

            // 3. 데이터베이스에 임시 비밀번호로 업데이트
            $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
            $stmt->execute([$hashedPassword, $user['id']]);

            // 4. PHPMailer를 사용하여 이메일 발송
            $mail = new PHPMailer(true);
            $mail->isSMTP();
            $mail->Host       = getenv('SMTP_HOST') ?: 'mailhog';
            $mail->Port       = getenv('SMTP_PORT') ?: 1025;
            $mail->SMTPAuth   = false;
            $mail->SMTPSecure = false;
            $mail->SMTPAutoTLS = false;

            $mail->setFrom('noreply@freeletter.com', 'FreeLetter');
            $mail->addAddress($email);

            $mail->isHTML(true);
            $mail->Subject = 'FreeLetter 임시 비밀번호 발급';
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
                                <h2 style='margin-top: 0; color: #3C6E52;'>임시 비밀번호가 발급되었습니다</h2>
                                <p>안녕하세요. 비밀번호 재설정 요청에 따라 새로운 임시 비밀번호가 발급되었습니다.</p>
                                <p style='font-size: 24px; font-weight: bold; text-align: center; color: #6C8E69; background-color: #f0f0f0; padding: 15px; border-radius: 8px;'>
                                    {$temp_password}
                                </p>
                                <p>로그인 후 마이 페이지에서 비밀번호를 변경해주시기 바랍니다.</p>
                                <a href='" . (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://{$_SERVER['HTTP_HOST']}/login' style='display: inline-block; padding: 10px 20px; margin-top: 20px; background-color: #6C8E69; color: #ffffff; text-decoration: none; border-radius: 5px;'>로그인 페이지로 이동</a>
                            </td>
                        </tr>
                    </table>
                </div>
            ";
            $mail->Body = $htmlBody;
            $mail->send();

            $_SESSION['success'] = '임시 비밀번호가 이메일로 발송되었습니다. 메일함을 확인해주세요.';
            header('Location: /login');
            exit;
        } else {
            // 사용자에게 계정이 존재하지 않는다는 사실을 노출하지 않기 위해 성공 메시지를 보냅니다.
            $_SESSION['success'] = '임시 비밀번호가 이메일로 발송되었습니다. 메일함을 확인해주세요.';
            header('Location: /login');
            exit;
        }

    } catch (Exception $e) {
        $_SESSION['error'] = '메일 발송에 실패했습니다. 잠시 후 다시 시도해주세요. Mailer Error: ' . $mail->ErrorInfo;
        header('Location: /forgot_password');
        exit;
    }
}