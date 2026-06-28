<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once 'includes/functions.php';

$email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);

if ($email) {
    setFlash('success', 'Thank you for subscribing to Frost & Brew updates.');
} else {
    setFlash('danger', 'Please enter a valid email address.');
}

redirect($_SERVER['HTTP_REFERER'] ?? base_url());
