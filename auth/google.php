<?php

// Minimal bootstrap for API endpoints: start session and load config/db/helpers
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../config/env.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';

// Simple Google OAuth handler
// Usage:
// - Redirect: /auth/google.php?action=redirect
// - Callback (Google will call this URL with ?code=...)

$action = $_GET['action'] ?? ($_GET['code'] ? 'callback' : 'redirect');

if ($action === 'redirect') {
    $url = google_get_auth_url();
    header('Location: ' . $url);
    exit;
}

if ($action === 'callback' || isset($_GET['code'])) {
    $code = $_GET['code'] ?? null;

    if (!$code) {
        setFlash('danger', 'Google login failed: missing code.');
        redirect(base_url('login'));
    }

    $tokenData = google_exchange_code_for_token($code);

    if (!$tokenData || empty($tokenData['access_token'])) {
        setFlash('danger', 'Google login failed: unable to get token.');
        redirect(base_url('login'));
    }

    $accessToken = $tokenData['access_token'];

    $userInfo = google_get_userinfo($accessToken);

    if (!$userInfo || empty($userInfo['email'])) {
        setFlash('danger', 'Google login failed: unable to retrieve user info.');
        redirect(base_url('login'));
    }

    // Lookup user by email
    $email = $userInfo['email'];

    try {
        $stmt = $conn->prepare("SELECT u.*, r.name AS role_name FROM users u INNER JOIN roles r ON r.id = u.role_id WHERE u.email = ? LIMIT 1");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user) {
            // Existing user: login
            $_SESSION['user'] = [
                'id' => $user['id'],
                'role_id' => $user['role_id'],
                'role' => $user['role_name'],
                'first_name' => $user['first_name'],
                'last_name' => $user['last_name'],
                'name' => trim($user['first_name'] . ' ' . $user['last_name']),
                'email' => $user['email'],
                'photo' => $user['profile_photo']
            ];

            $update = $conn->prepare("UPDATE users SET last_login = NOW() WHERE id = ?");
            $update->execute([$user['id']]);

            setFlash('success', 'Welcome back ' . $user['first_name'] . '!');

            redirect(base_url());

        } else {
            // Create new customer user
            $names = explode(' ', $userInfo['name'] ?? '');
            $first = $names[0] ?? '';
            $last = isset($names[1]) ? implode(' ', array_slice($names,1)) : '';
            $username = slug($email);
            $password = randomPassword(12);
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);

            $insert = $conn->prepare("INSERT INTO users (role_id, first_name, last_name, username, email, password, status, created_at) VALUES (5, ?, ?, ?, ?, ?, 'Active', NOW())");
            $insert->execute([
                $first,
                $last,
                $username,
                $email,
                $passwordHash
            ]);

            $id = $conn->lastInsertId();

            $_SESSION['user'] = [
                'id' => $id,
                'role_id' => 5,
                'role' => 'Customer',
                'first_name' => $first,
                'last_name' => $last,
                'name' => trim($first . ' ' . $last),
                'email' => $email,
                'photo' => null
            ];

            // Optionally send welcome email (uses SendGrid if configured)
            $subject = 'Welcome to Frost & Brew';
            $html = "<p>Welcome <strong>" . e($first) . "</strong>! Your account was created using Google sign-in.</p>";

            send_email_sendgrid($email, $subject, $html);

            setFlash('success', 'Account created. Welcome ' . $first . '!');

            redirect(base_url());
        }

    } catch (Exception $e) {
        writeLog('Google OAuth error: ' . $e->getMessage());
        setFlash('danger', 'Google login failed.');
        redirect(base_url('login'));
    }

}

// Fallback
setFlash('danger', 'Invalid Google OAuth request.');
redirect(base_url('login'));
