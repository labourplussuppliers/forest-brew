<?php

$pageTitle = "Special Offers";
$pageDescription = "View current Frost & Brew offers and promotions.";

require_once 'includes/header.php';
?>

<main class="py-5">

    <section class="page-header bg-dark text-white py-5 mt-5">

        <div class="container">

            <div class="row align-items-center">

                <div class="col-lg-8">

                    <h1 class="display-4 fw-bold">Special Offers</h1>

                    <p class="lead text-light mt-4">Enjoy seasonal deals, bundle discounts and exclusive cafe promotions.</p>

                </div>

                <div class="col-lg-4 text-lg-end">

                    <a href="<?= base_url('menu'); ?>" class="btn btn-warning btn-lg">Shop Menu</a>

                </div>

            </div>

        </div>

    </section>

    <section class="py-5 bg-light">

        <div class="container">

            <div class="row g-4">

                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm p-4 h-100">
                        <h3 class="fw-bold">Buy 2 Get 1 Free</h3>
                        <p class="text-muted">Order two coffees and get one complimentary drink.</p>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm p-4 h-100">
                        <h3 class="fw-bold">Weekend Dessert Bundle</h3>
                        <p class="text-muted">Get a dessert bundle discount every Friday through Sunday.</p>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm p-4 h-100">
                        <h3 class="fw-bold">Student Special</h3>
                        <p class="text-muted">Show your student ID and enjoy 10% off on drinks.</p>
                    </div>
                </div>

            </div>

        </div>

    </section>

</main>

<?php require_once 'includes/footer.php'; ?>
