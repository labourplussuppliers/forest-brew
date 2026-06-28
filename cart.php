<?php

$pageTitle = "Your Cart";
$pageDescription = "Review your Frost & Brew cart items and prepare for checkout.";

require_once 'includes/header.php';
?>

<main class="py-5">

    <section class="page-header bg-dark text-white py-5 mt-5">

        <div class="container">

            <div class="row align-items-center">

                <div class="col-lg-8">

                    <h1 class="display-4 fw-bold">Shopping Cart</h1>

                    <p class="lead text-light mt-4">Review your selected items before checkout.</p>

                </div>

                <div class="col-lg-4 text-lg-end">

                    <a href="<?= base_url('menu'); ?>" class="btn btn-warning btn-lg">Continue Shopping</a>

                </div>

            </div>

        </div>

    </section>

    <section class="py-5">

        <div class="container">

            <div class="card border-0 shadow-sm p-4 text-center">

                <h3 class="fw-bold">Your cart is currently empty</h3>

                <p class="text-muted">Add menu items to your cart and return here to continue.</p>

                <a href="<?= base_url('menu'); ?>" class="btn btn-dark">Browse Menu</a>

            </div>

        </div>

    </section>

</main>

<?php require_once 'includes/footer.php'; ?>
