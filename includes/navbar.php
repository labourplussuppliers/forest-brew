<?php

$currentUser = currentUser();

$cartCount = $_SESSION['cart_count'] ?? 0;

$wishlistCount = $_SESSION['wishlist_count'] ?? 0;

?>

<header>

    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm fixed-top">

        <div class="container">

            <!-- Logo -->

            <a class="navbar-brand fw-bold d-flex align-items-center"
               href="<?= base_url(); ?>">

                <img
                    src="<?= asset('images/logo.png'); ?>"
                    alt="Frost & Brew"
                    width="55"
                    class="me-2">

                <span class="fs-4">

                    Frost & Brew

                </span>

            </a>

            <!-- Mobile Toggle -->

            <button
                class="navbar-toggler"
                type="button"
                data-bs-toggle="offcanvas"
                data-bs-target="#mobileMenu">

                <span class="navbar-toggler-icon"></span>

            </button>

            <div class="collapse navbar-collapse">

                <ul class="navbar-nav mx-auto">

                    <li class="nav-item">

                        <a class="nav-link <?= activeMenu(''); ?>"
                           href="<?= base_url(); ?>">

                            Home

                        </a>

                    </li>

                    <li class="nav-item">

                        <a class="nav-link <?= activeMenu('menu'); ?>"
                           href="<?= base_url('menu'); ?>">

                            Menu

                        </a>

                    </li>

                    <li class="nav-item">

                        <a class="nav-link <?= activeMenu('about'); ?>"
                           href="<?= base_url('about'); ?>">

                            About

                        </a>

                    </li>

                    <li class="nav-item">

                        <a class="nav-link <?= activeMenu('contact'); ?>"
                           href="<?= base_url('contact'); ?>">

                            Contact

                        </a>

                    </li>

                </ul>
                                <form
                    class="d-flex me-3"
                    action="<?= base_url('search'); ?>"
                    method="GET">

                    <div class="input-group">

                        <input
                            type="text"
                            class="form-control"
                            name="search"
                            placeholder="Search coffee...">

                        <button
                            class="btn btn-dark"
                            type="submit">

                            <i class="fa-solid fa-magnifying-glass"></i>

                        </button>

                    </div>

                </form>

                <div class="d-flex align-items-center">

                    <!-- Wishlist -->

                    <a
                        href="<?= base_url('wishlist'); ?>"
                        class="btn btn-light position-relative me-2">

                        <i class="fa-regular fa-heart"></i>

                        <span
                            class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">

                            <?= $wishlistCount; ?>

                        </span>

                    </a>

                    <!-- Cart -->

                    <a
                        href="<?= base_url('cart'); ?>"
                        class="btn btn-light position-relative me-3">

                        <i class="fa-solid fa-cart-shopping"></i>

                        <span
                            class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">

                            <?= $cartCount; ?>

                        </span>

                    </a>
                                        <?php if (isLoggedIn()) : ?>

                        <div class="dropdown">

                            <button
                                class="btn btn-dark dropdown-toggle"
                                data-bs-toggle="dropdown">

                                <i class="fa-solid fa-user me-2"></i>

                                <?= e(userName()); ?>

                            </button>

                            <ul class="dropdown-menu dropdown-menu-end">

                                <li>

                                    <a
                                        class="dropdown-item"
                                        href="<?= base_url('profile'); ?>">

                                        <i class="fa-solid fa-user me-2"></i>

                                        My Profile

                                    </a>

                                </li>

                                <li>

                                    <a
                                        class="dropdown-item"
                                        href="<?= base_url('orders'); ?>">

                                        <i class="fa-solid fa-box me-2"></i>

                                        My Orders

                                    </a>

                                </li>

                                <li>

                                    <hr class="dropdown-divider">

                                </li>

                                <li>

                                    <a
                                        class="dropdown-item text-danger"
                                        href="<?= base_url('logout'); ?>">

                                        <i class="fa-solid fa-right-from-bracket me-2"></i>

                                        Logout

                                    </a>

                                </li>

                            </ul>

                        </div>

                    <?php else : ?>

                        <a
                            href="<?= base_url('login'); ?>"
                            class="btn btn-dark">

                            Login

                        </a>

                    <?php endif; ?>

                </div>

            </div>

        </div>

    </nav>
        <!-- Mobile Offcanvas -->

    <div
        class="offcanvas offcanvas-start"
        tabindex="-1"
        id="mobileMenu">

        <div class="offcanvas-header">

            <h5 class="offcanvas-title">

                Frost & Brew

            </h5>

            <button
                class="btn-close"
                data-bs-dismiss="offcanvas">
            </button>

        </div>

        <div class="offcanvas-body p-3 p-lg-4">

            <form
                action="<?= base_url('search'); ?>"
                method="GET"
                class="mb-4">

                <div class="input-group rounded overflow-hidden shadow-sm">

                    <input
                        type="text"
                        class="form-control border-0 px-3"
                        name="search"
                        placeholder="Search coffee...">

                    <button
                        class="btn btn-dark px-4"
                        type="submit">

                        <i class="fa-solid fa-magnifying-glass"></i>

                    </button>

                </div>

            </form>

            <ul class="navbar-nav mb-4">

                <li class="nav-item">

                    <a
                        class="nav-link <?= activeMenu(''); ?>"
                        href="<?= base_url(); ?>">

                        <i class="fa-solid fa-house me-2"></i>

                        Home

                    </a>

                </li>

                <li class="nav-item">

                    <a
                        class="nav-link <?= activeMenu('menu'); ?>"
                        href="<?= base_url('menu'); ?>">

                        <i class="fa-solid fa-mug-hot me-2"></i>

                        Menu

                    </a>

                </li>

                <li class="nav-item">

                    <a
                        class="nav-link <?= activeMenu('about'); ?>"
                        href="<?= base_url('about'); ?>">

                        <i class="fa-solid fa-circle-info me-2"></i>

                        About

                    </a>

                </li>

                <li class="nav-item">

                    <a
                        class="nav-link <?= activeMenu('contact'); ?>"
                        href="<?= base_url('contact'); ?>">

                        <i class="fa-solid fa-envelope me-2"></i>

                        Contact

                    </a>

                </li>

            </ul>

            <hr>

            <h6 class="fw-bold mb-3">

                Popular Categories

            </h6>

            <div class="list-group mb-4">

                <a
                    href="<?= base_url('category/coffee'); ?>"
                    class="list-group-item list-group-item-action rounded-3 mb-2">

                    ☕

                    Hot Coffee

                </a>

                <a
                    href="<?= base_url('category/cold-coffee'); ?>"
                    class="list-group-item list-group-item-action rounded-3 mb-2">

                    🧋

                    Cold Coffee

                </a>

                <a
                    href="<?= base_url('category/frappes'); ?>"
                    class="list-group-item list-group-item-action rounded-3 mb-2">

                    🥤

                    Frappes

                </a>

                <a
                    href="<?= base_url('category/milkshakes'); ?>"
                    class="list-group-item list-group-item-action rounded-3 mb-2">

                    🍨

                    Milkshakes

                </a>

                <a
                    href="<?= base_url('category/desserts'); ?>"
                    class="list-group-item list-group-item-action rounded-3">

                    🍰

                    Desserts

                </a>

            </div>

            <h6 class="fw-bold">

                Quick Links

            </h6>

            <div class="d-grid gap-2 mb-4">

                <a
                    href="<?= base_url('wishlist'); ?>"
                    class="btn btn-outline-dark">

                    <i class="fa-regular fa-heart me-2"></i>

                    Wishlist

                </a>

                <a
                    href="<?= base_url('cart'); ?>"
                    class="btn btn-outline-dark">

                    <i class="fa-solid fa-cart-shopping me-2"></i>

                    Cart

                </a>

                <a
                    href="<?= base_url('offers'); ?>"
                    class="btn btn-outline-dark">

                    <i class="fa-solid fa-tags me-2"></i>

                    Today's Offers

                </a>

            </div>

            <?php if (isLoggedIn()) : ?>

                <div class="card border-0 bg-light mb-4 rounded-4 shadow-sm">

                    <div class="card-body">

                        <h6 class="fw-bold mb-2">

                            <?= e(userName()); ?>

                        </h6>

                        <p class="text-muted small mb-3">

                            <?= e(userEmail()); ?>

                        </p>

                        <div class="d-grid gap-2">

                            <a
                                href="<?= base_url('profile'); ?>"
                                class="btn btn-dark">

                                My Profile

                            </a>

                            <a
                                href="<?= base_url('orders'); ?>"
                                class="btn btn-outline-dark">

                                My Orders

                            </a>

                            <a
                                href="<?= base_url('logout'); ?>"
                                class="btn btn-danger">

                                Logout

                            </a>

                        </div>

                    </div>

                </div>

            <?php else : ?>

                <div class="d-grid gap-2 mb-4">

                    <a
                        href="<?= base_url('login'); ?>"
                        class="btn btn-dark">

                        Login

                    </a>

                    <a
                        href="<?= base_url('register'); ?>"
                        class="btn btn-outline-dark">

                        Create Account

                    </a>

                </div>

            <?php endif; ?>

            <hr>

            <h6 class="fw-bold mb-3">

                Follow Us

            </h6>

            <div class="d-flex gap-3 mb-4">

                <a
                    href="#"
                    class="text-dark fs-5">

                    <i class="fab fa-facebook-f"></i>

                </a>

                <a
                    href="#"
                    class="text-dark fs-5">

                    <i class="fab fa-instagram"></i>

                </a>

                <a
                    href="#"
                    class="text-dark fs-5">

                    <i class="fab fa-tiktok"></i>

                </a>

                <a
                    href="#"
                    class="text-dark fs-5">

                    <i class="fab fa-youtube"></i>

                </a>

            </div>

            <div class="mt-3 text-center">

                <small class="text-muted">

                    © <?= date('Y'); ?>

                    Frost & Brew

                    All Rights Reserved.

                </small>

            </div>

        </div>

    </div>

</header>