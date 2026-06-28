<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/includes/functions.php';

if (!isLoggedIn()) {
    redirect(base_url());
}

/*
|--------------------------------------------------------------------------
| Activity Log
|--------------------------------------------------------------------------
*/

if (function_exists('logActivity')) {
    logActivity(
        $_SESSION['user']['id'],
        'User logged out.'
    );
}

/*
|--------------------------------------------------------------------------
| Remove Remember Me Cookie
|--------------------------------------------------------------------------
*/

if (isset($_COOKIE['remember_token'])) {

    setcookie(
        'remember_token',
        '',
        time() - 3600,
        '/',
        '',
        isset($_SERVER['HTTPS']),
        true
    );

}

/*
|--------------------------------------------------------------------------
| Destroy Session
|--------------------------------------------------------------------------
*/

$_SESSION = [];

if (ini_get('session.use_cookies')) {

    $params = session_get_cookie_params();

    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params['path'],
        $params['domain'],
        $params['secure'],
        $params['httponly']
    );

}

session_destroy();

setFlash(
    'success',
    'You have been logged out successfully.'
);

redirect(base_url());
