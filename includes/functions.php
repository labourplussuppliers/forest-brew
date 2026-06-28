<?php

/**
 * ===========================================================
 * Frost & Brew
 * Global Helper Functions
 * ===========================================================
 */

if (!defined('BASE_URL')) {
    $scheme = (!empty($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) !== 'off') || 
              (!empty($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443)
              ? 'https' : 'http';

    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    $basePath = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'])), '/');
    $basePath = $basePath === '/' ? '' : $basePath;

    define('BASE_URL', $scheme . '://' . $host . $basePath . '/');
}

/**
 * Escape Output
 */
function e($string)
{
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}

/**
 * Sanitize Input
 */
function clean($data)
{
    if (is_array($data)) {
        return array_map('clean', $data);
    }

    return trim(strip_tags($data));
}

/**
 * Base URL
 */
function base_url($path = '')
{
    return BASE_URL . ltrim($path, '/');
}

/**
 * Asset URL
 */
function asset($path)
{
    return BASE_URL . 'assets/' . ltrim($path, '/');
}

/**
 * Redirect
 */
function redirect($url)
{
    header("Location: " . $url);
    exit;
}

/**
 * Back Redirect
 */
function back()
{
    redirect($_SERVER['HTTP_REFERER'] ?? base_url());
}

/**
 * Current URL
 */
function current_url()
{
    return strtok($_SERVER["REQUEST_URI"], '?');
}

/**
 * Check Active Menu
 */
function activeMenu($page)
{
    $current = basename(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

    return $current == $page ? 'active' : '';
}

/**
 * Flash Message
 */
function setFlash($type, $message)
{
    $_SESSION['flash'] = [

        'type' => $type,

        'message' => $message

    ];
}

/**
 * Get Flash Message
 */
function flash()
{
    if (!isset($_SESSION['flash'])) {
        return;
    }

    $flash = $_SESSION['flash'];

    unset($_SESSION['flash']);

    echo '

    <div class="alert alert-' . e($flash['type']) . ' alert-dismissible fade show">

        ' . e($flash['message']) . '

        <button class="btn-close" data-bs-dismiss="alert"></button>

    </div>

    ';
}

/**
 * Old Input
 */
function old($key)
{
    return $_SESSION['old'][$key] ?? '';
}

/**
 * Store Old Input
 */
function setOld($data)
{
    $_SESSION['old'] = $data;
}

/**
 * Clear Old Input
 */
function clearOld()
{
    unset($_SESSION['old']);
}
/**
 * -------------------------------------------------------
 * Generate CSRF Token
 * -------------------------------------------------------
 */
function csrf_token()
{
    if (!isset($_SESSION['_token'])) {

        $_SESSION['_token'] = bin2hex(random_bytes(32));

    }

    return $_SESSION['_token'];
}

/**
 * -------------------------------------------------------
 * CSRF Hidden Input
 * -------------------------------------------------------
 */
function csrf_field()
{
    return '<input type="hidden" name="_token" value="' . csrf_token() . '">';
}

/**
 * -------------------------------------------------------
 * Verify CSRF Token
 * -------------------------------------------------------
 */
function verify_csrf()
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        return;
    }

    if (
        !isset($_POST['_token']) ||
        !isset($_SESSION['_token']) ||
        !hash_equals($_SESSION['_token'], $_POST['_token'])
    ) {

        http_response_code(403);

        exit('Invalid CSRF Token');

    }
}

/**
 * -------------------------------------------------------
 * Check Login
 * -------------------------------------------------------
 */
function isLoggedIn()
{
    return isset($_SESSION['user']);
}

/**
 * -------------------------------------------------------
 * Check Guest
 * -------------------------------------------------------
 */
function isGuest()
{
    return !isLoggedIn();
}

/**
 * -------------------------------------------------------
 * Get Current User
 * -------------------------------------------------------
 */
function currentUser()
{
    return $_SESSION['user'] ?? null;
}

/**
 * -------------------------------------------------------
 * Current User ID
 * -------------------------------------------------------
 */
function userId()
{
    return $_SESSION['user']['id'] ?? null;
}

/**
 * -------------------------------------------------------
 * Current User Name
 * -------------------------------------------------------
 */
function userName()
{
    return $_SESSION['user']['name'] ?? '';
}

/**
 * -------------------------------------------------------
 * Current User Email
 * -------------------------------------------------------
 */
function userEmail()
{
    return $_SESSION['user']['email'] ?? '';
}

/**
 * -------------------------------------------------------
 * Check Admin
 * -------------------------------------------------------
 */
function isAdmin()
{
    return isset($_SESSION['admin']);
}

/**
 * -------------------------------------------------------
 * Redirect If Not Logged In
 * -------------------------------------------------------
 */
function auth()
{
    if (!isLoggedIn()) {

        setFlash('danger', 'Please login first.');

        redirect(base_url('login'));

    }
}

/**
 * -------------------------------------------------------
 * Redirect If Already Logged In
 * -------------------------------------------------------
 */
function guest()
{
    if (isLoggedIn()) {

        redirect(base_url());

    }
}

/**
 * -------------------------------------------------------
 * Admin Authentication
 * -------------------------------------------------------
 */
function adminAuth()
{
    if (!isAdmin()) {

        redirect(base_url('admin/login'));

    }
}
/**
 * -------------------------------------------------------
 * JSON Response
 * -------------------------------------------------------
 */
function jsonResponse($status, $message, $data = [])
{
    header('Content-Type: application/json');

    echo json_encode([

        'status' => $status,

        'message' => $message,

        'data' => $data

    ]);

    exit;
}

/**
 * -------------------------------------------------------
 * AJAX Request Check
 * -------------------------------------------------------
 */
function isAjax()
{
    return isset($_SERVER['HTTP_X_REQUESTED_WITH']) &&
        strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
}

/**
 * -------------------------------------------------------
 * Generate Slug
 * -------------------------------------------------------
 */
function slug($text)
{
    $text = strtolower($text);

    $text = preg_replace('/[^a-z0-9]+/', '-', $text);

    return trim($text, '-');
}

/**
 * -------------------------------------------------------
 * Currency Formatter
 * -------------------------------------------------------
 */
function currency($amount)
{
    return 'Rs. ' . number_format($amount, 2);
}

/**
 * -------------------------------------------------------
 * Date Formatter
 * -------------------------------------------------------
 */
function formatDate($date)
{
    return date('d M Y', strtotime($date));
}

/**
 * -------------------------------------------------------
 * Date & Time Formatter
 * -------------------------------------------------------
 */
function formatDateTime($date)
{
    return date('d M Y h:i A', strtotime($date));
}
/**
 * -------------------------------------------------------
 * Generate Random File Name
 * -------------------------------------------------------
 */
function randomFileName($extension)
{
    return uniqid('fb_', true) . '_' . time() . '.' . strtolower($extension);
}

/**
 * -------------------------------------------------------
 * Allowed Image Extensions
 * -------------------------------------------------------
 */
function allowedImageExtensions()
{
    return [
        'jpg',
        'jpeg',
        'png',
        'gif',
        'webp'
    ];
}

/**
 * -------------------------------------------------------
 * Validate Image
 * -------------------------------------------------------
 */
function validateImage($file)
{
    if (!isset($file['name']) || empty($file['name'])) {
        return false;
    }

    $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

    if (!in_array($extension, allowedImageExtensions())) {
        return false;
    }

    if ($file['size'] > 5 * 1024 * 1024) {
        return false;
    }

    return true;
}

/**
 * -------------------------------------------------------
 * Upload Single Image
 * -------------------------------------------------------
 */
function uploadImage($file, $directory = 'uploads/')
{
    if (!validateImage($file)) {
        return false;
    }

    if (!is_dir($directory)) {
        mkdir($directory, 0777, true);
    }

    $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

    $fileName = randomFileName($extension);

    $destination = $directory . $fileName;

    if (move_uploaded_file($file['tmp_name'], $destination)) {
        return $fileName;
    }

    return false;
}

/**
 * -------------------------------------------------------
 * Delete Image
 * -------------------------------------------------------
 */
function deleteImage($directory, $fileName)
{
    $file = $directory . $fileName;

    if (file_exists($file)) {
        unlink($file);
    }
}
/**
 * -------------------------------------------------------
 * Upload Multiple Images
 * -------------------------------------------------------
 */
function uploadMultipleImages($files, $directory = 'uploads/')
{
    $uploaded = [];

    foreach ($files['name'] as $key => $name) {

        $file = [

            'name' => $files['name'][$key],

            'type' => $files['type'][$key],

            'tmp_name' => $files['tmp_name'][$key],

            'error' => $files['error'][$key],

            'size' => $files['size'][$key]

        ];

        $image = uploadImage($file, $directory);

        if ($image) {
            $uploaded[] = $image;
        }
    }

    return $uploaded;
}

/**
 * -------------------------------------------------------
 * Generate Order Number
 * -------------------------------------------------------
 */
function generateOrderNumber()
{
    return 'FB-' . strtoupper(date('Ymd')) . '-' . rand(100000, 999999);
}

/**
 * -------------------------------------------------------
 * Generate Invoice Number
 * -------------------------------------------------------
 */
function generateInvoiceNumber()
{
    return 'INV-' . strtoupper(date('Ym')) . rand(1000, 9999);
}

/**
 * -------------------------------------------------------
 * Generate Random Password
 * -------------------------------------------------------
 */
function randomPassword($length = 10)
{
    $characters = 'ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnopqrstuvwxyz23456789@#$';

    $password = '';

    for ($i = 0; $i < $length; $i++) {

        $password .= $characters[random_int(0, strlen($characters) - 1)];

    }

    return $password;
}
/**
 * -------------------------------------------------------
 * Pagination Offset
 * -------------------------------------------------------
 */
function paginate($perPage = 10)
{
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

    if ($page < 1) {
        $page = 1;
    }

    return ($page - 1) * $perPage;
}

/**
 * -------------------------------------------------------
 * Search Keyword
 * -------------------------------------------------------
 */
function searchKeyword()
{
    return isset($_GET['search']) ? clean($_GET['search']) : '';
}

/**
 * -------------------------------------------------------
 * Debug Helper
 * -------------------------------------------------------
 */
function dd($data)
{
    echo "<pre>";

    print_r($data);

    echo "</pre>";

    die();
}

/**
 * -------------------------------------------------------
 * Write Log File
 * -------------------------------------------------------
 */
function writeLog($message)
{
    $directory = 'storage/logs/';

    if (!is_dir($directory)) {
        mkdir($directory, 0777, true);
    }

    $file = $directory . date('Y-m-d') . '.log';

    $content = '[' . date('Y-m-d H:i:s') . '] ' . $message . PHP_EOL;

    file_put_contents($file, $content, FILE_APPEND);
}