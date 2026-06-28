<?php

$pageTitle = "Checkout";
$pageDescription = "Complete your Frost & Brew order at checkout.";

require_once 'includes/header.php';
?>

<main class="py-5">

    <section class="page-header bg-dark text-white py-5 mt-5">

        <div class="container">

            <div class="row align-items-center">

                <div class="col-lg-8">

                    <h1 class="display-4 fw-bold">Checkout</h1>

                    <p class="lead text-light mt-4">Finish your order and choose delivery or pickup.</p>

                </div>

                <div class="col-lg-4 text-lg-end">

                    <a href="<?= base_url('cart'); ?>" class="btn btn-warning btn-lg">View Cart</a>

                </div>

            </div>

        </div>

    </section>

    <section class="py-5">

        <div class="container">

            <div class="card border-0 shadow-sm p-4 text-center">

                <h3 class="fw-bold">Checkout is not yet available</h3>

                <p class="text-muted">We are preparing the best checkout experience for you. Please try again soon.</p>

                <a href="<?= base_url('menu'); ?>" class="btn btn-dark">Browse Menu</a>

            </div>

        </div>

    </section>

</main>

<?php require_once 'includes/footer.php'; ?>
