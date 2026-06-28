<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/functions.php';

$pageTitle = $pageTitle ?? 'Frost & Brew';
$pageDescription = $pageDescription ?? 'Premium Coffee, Desserts & Cafe Experience.';
$pageKeywords = $pageKeywords ?? 'coffee, cafe, desserts, milkshakes, frappes';

?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title><?= e($pageTitle); ?></title>

    <meta name="description" content="<?= e($pageDescription); ?>">

    <meta name="keywords" content="<?= e($pageKeywords); ?>">

    <meta name="author" content="Frost & Brew">

    <meta name="robots" content="index, follow">

    <meta name="theme-color" content="#5C3D2E">

    <link rel="icon" type="image/png" href="<?= asset('images/favicon.png'); ?>">
        <!-- Bootstrap 5 -->

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
        rel="stylesheet">

    <!-- Bootstrap Icons -->

    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">

    <!-- Font Awesome -->

    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    <!-- Google Fonts -->

    <link rel="preconnect" href="https://fonts.googleapis.com">

    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- AOS -->

    <link
        href="https://unpkg.com/aos@2.3.4/dist/aos.css"
        rel="stylesheet">

    <!-- SweetAlert2 -->

    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <!-- Custom CSS -->

    <link
        rel="stylesheet"
        href="<?= asset('css/style.css'); ?>">

    <link
        rel="stylesheet"
        href="<?= asset('css/responsive.css'); ?>">

</head>

<body>
    <div id="preloader">

        <div class="preloader-content text-center">

            <img
                src="<?= asset('images/logo.png'); ?>"
                alt="Frost & Brew"
                class="preloader-logo mb-4">

            <div class="spinner-border text-warning" role="status">

                <span class="visually-hidden">
                    Loading...
                </span>

            </div>

            <p class="preloader-text mt-4">
                Brewing your experience...
            </p>

        </div>

    </div>

<div id="app">

<?php

require_once __DIR__ . '/navbar.php';

?>

<!-- Page Content -->