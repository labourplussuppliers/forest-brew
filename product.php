<?php

$pageTitle = "Product Details";
$pageDescription = "Explore the details of Frost & Brew products.";

require_once 'includes/header.php';

$slug = trim($_GET['slug'] ?? '');
$productName = $slug ? ucwords(str_replace('-', ' ', $slug)) : 'Product';
$image = asset('images/hero/hero.png');

?>

<main class="py-5">

    <section class="page-header bg-dark text-white py-5 mt-5">

        <div class="container">

            <div class="row align-items-center">

                <div class="col-lg-8">

                    <h1 class="display-4 fw-bold"><?= e($productName); ?></h1>

                    <p class="lead text-light mt-4">Discover details about our handcrafted beverage and dessert selection.</p>

                </div>

                <div class="col-lg-4 text-lg-end">

                    <a href="<?= base_url('menu'); ?>" class="btn btn-warning btn-lg">Back to Menu</a>

                </div>

            </div>

        </div>

    </section>

    <section class="py-5">

        <div class="container">

            <div class="row g-4">

                <div class="col-lg-5">

                    <img src="<?= $image; ?>" class="img-fluid rounded shadow" alt="<?= e($productName); ?>">

                </div>

                <div class="col-lg-7">

                    <div class="card border-0 shadow-sm p-4">

                        <h2 class="fw-bold mb-3"><?= e($productName); ?></h2>

                        <p class="text-muted">A delicious item from Frost & Brew, made with care and premium ingredients.</p>

                        <div class="mb-4">
                            <span class="h4 fw-bold text-warning">Rs. 650</span>
                            <span class="badge bg-success ms-3">In Stock</span>
                        </div>

                        <p class="mb-4">Savor the rich flavor and perfect balance of sweetness and aroma.</p>

                        <div class="d-flex gap-2 flex-wrap">
                            <a href="<?= base_url('cart'); ?>" class="btn btn-dark">Add to Cart</a>
                            <a href="<?= base_url('checkout'); ?>" class="btn btn-outline-dark">Go to Checkout</a>
                        </div>

                    </div>

                </div>

            </div>

        </div>

    </section>

</main>

<?php require_once 'includes/footer.php'; ?>
