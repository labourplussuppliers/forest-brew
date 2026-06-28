<?php

$pageTitle = "Wishlist";
$pageDescription = "Your favorite Frost & Brew items saved for later.";

require_once 'includes/header.php';
?>

<main class="py-5">

    <section class="page-header bg-dark text-white py-5 mt-5">

        <div class="container">

            <div class="row align-items-center">

                <div class="col-lg-8">

                    <h1 class="display-4 fw-bold">Wishlist</h1>

                    <p class="lead text-light mt-4">Your favorite drinks and treats saved for later.</p>

                </div>

                <div class="col-lg-4 text-lg-end">

                    <a href="<?= base_url('menu'); ?>" class="btn btn-warning btn-lg">Shop More</a>

                </div>

            </div>

        </div>

    </section>

    <section class="py-5">

        <div class="container">

            <div class="card border-0 shadow-sm p-4 text-center">

                <h3 class="fw-bold">Your wishlist is empty</h3>

                <p class="text-muted">Save items here to revisit them later.</p>

                <a href="<?= base_url('menu'); ?>" class="btn btn-dark">Browse Menu</a>

            </div>

        </div>

    </section>

</main>

<?php require_once 'includes/footer.php'; ?>
