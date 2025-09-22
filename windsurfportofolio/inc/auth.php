<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/helpers.php';

function is_logged_in(): bool {
    ensure_session();
    return !empty($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
}

function require_login(): void {
    if (!is_logged_in()) {
        redirect('login.php');
    }
}

function login(string $username, string $password): bool {
    ensure_session();
    $ok = hash_equals($username, ADMIN_USER) && hash_equals($password, ADMIN_PASSWORD);
    if ($ok) {
        $_SESSION['logged_in'] = true;
        $_SESSION['username'] = $username;
        return true;
    }
    return false;
}

function logout(): void {
    ensure_session();
    $_SESSION = [];
    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params['path'], $params['domain'],
            $params['secure'], $params['httponly']
        );
    }
    session_destroy();
}
?>
