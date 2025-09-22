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
    $password = $_POST['password'];

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = '유효한 이메일 주소를 입력해주세요.';
        header('Location: /register');
        exit;
    }

    if (strlen($password) < 6) {
        $_SESSION['error'] = '비밀번호는 최소 6자 이상이어야 합니다.';
        header('Location: /register');
        exit;
    }

    try {
        $stmt = $pdo->prepare("SELECT email, status FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && $user['status'] === 'verified') {
            $_SESSION['error'] = '이미 가입된 이메일 주소입니다.';
            header('Location: /register');
            exit;
        }

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $verificationToken = bin2hex(random_bytes(32));

        if ($user) {
            $stmt = $pdo->prepare("UPDATE users SET password = ?, token = ?, created_at = NOW() WHERE email = ?");
            $stmt->execute([$hashedPassword, $verificationToken, $email]);
        } else {
            $stmt = $pdo->prepare("INSERT INTO users (email, password, token) VALUES (?, ?, ?)");
            $stmt->execute([$email, $hashedPassword, $verificationToken]);
        }
        
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
        $mail->Subject = 'FreeLetter 회원가입 이메일 인증을 완료해주세요.';
        $verificationLink = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://{$_SERVER['HTTP_HOST']}/verify?email=" . urlencode($email) . "&token=" . urlencode($verificationToken) . "&type=user";

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
                            <h2 style='margin-top: 0; color: #3C6E52;'>회원가입 이메일 인증</h2>
                            <p>안녕하세요. 회원가입을 완료하시려면 아래 링크를 클릭해주세요.</p>
                            <a href='{$verificationLink}' style='display: inline-block; padding: 10px 20px; margin-top: 20px; background-color: #6C8E69; color: #ffffff; text-decoration: none; border-radius: 5px;'>인증하기</a>
                            <p style='margin-top: 20px; font-size: 12px; color: #999999;'>링크가 작동하지 않으면 아래 주소를 복사하여 브라우저에 붙여넣으세요:<br>{$verificationLink}</p>
                        </td>
                    </tr>
                </table>
            </div>
        ";
        $mail->Body = $htmlBody;
        $mail->send();

        $_SESSION['success'] = '회원가입을 위한 인증 메일이 발송되었습니다. 메일함을 확인해주세요.';
        
        // 리다이렉션 주소를 /register로 변경
        header('Location: /register'); 
        exit;

    } catch (Exception $e) {
        $_SESSION['error'] = '메일 발송에 실패했습니다. 잠시 후 다시 시도해주세요. Mailer Error: ' . $mail->ErrorInfo;
        header('Location: /register');
        exit;
    }
} else {
    header('Location: /register');
    exit;
}