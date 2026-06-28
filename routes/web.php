<?php

// =========================
// Frontend
// =========================

$router->get('/', [HomeController::class, 'index']);

$router->get('/menu', [MenuController::class, 'index']);

$router->get('/product/{slug}', [ProductController::class, 'show']);

$router->get('/category/{slug}', [CategoryController::class, 'show']);

$router->get('/cart', [CartController::class, 'index']);

$router->post('/cart/add', [CartController::class, 'add']);

$router->post('/cart/update', [CartController::class, 'update']);

$router->post('/cart/remove', [CartController::class, 'remove']);

$router->get('/checkout', [CheckoutController::class, 'index']);

$router->post('/checkout', [CheckoutController::class, 'store']);


// =========================
// Authentication
// =========================

$router->get('/login', [AuthController::class, 'login']);

$router->post('/login', [AuthController::class, 'authenticate']);

$router->get('/register', [AuthController::class, 'register']);

$router->post('/register', [AuthController::class, 'store']);

$router->get('/logout', [AuthController::class, 'logout']);


// =========================
// Static Pages
// =========================

$router->get('/about', [HomeController::class, 'about']);

$router->get('/contact', [HomeController::class, 'contact']);

$router->post('/contact', [HomeController::class, 'sendContact']);

$router->get('/offers', [HomeController::class, 'offers']);


// =========================
// Admin
// =========================

$router->get('/admin', [DashboardController::class, 'index']);

$router->get('/admin/products', [ProductController::class, 'index']);

$router->get('/admin/categories', [CategoryController::class, 'index']);

$router->get('/admin/orders', [AdminOrderController::class, 'index']);

$router->get('/admin/customers', [AdminCustomerController::class, 'index']);

$router->get('/admin/reviews', [AdminReviewController::class, 'index']);

$router->get('/admin/inventory', [AdminInventoryController::class, 'index']);

$router->get('/admin/staff', [AdminStaffController::class, 'index']);


// =========================
// POS
// =========================

$router->get('/admin/pos', [PosController::class, 'index']);

$router->post('/admin/pos/add', [PosController::class, 'add']);

$router->post('/admin/pos/checkout', [PosController::class, 'checkout']);