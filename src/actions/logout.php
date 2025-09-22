<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// All session variables are cleared.
$_SESSION = array();

// If a session cookie exists, it's deleted.
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// The session is destroyed.
session_destroy();

// Redirect to the home page after logging out.
header('Location: /');
exit;
?>