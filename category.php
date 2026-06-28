<?php

$pageTitle = "Category";
$pageDescription = "Browse Frost & Brew menu categories.";

require_once 'includes/header.php';

$slug = trim($_GET['slug'] ?? '');
$categoryName = $slug ? ucwords(str_replace('-', ' ', $slug)) : 'Category';

?>

<main class="py-5">

    <section class="page-header bg-dark text-white py-5 mt-5">

        <div class="container">

            <div class="row align-items-center">

                <div class="col-lg-8">

                    <h1 class="display-4 fw-bold"><?= e($categoryName); ?></h1>

                    <p class="lead text-light mt-4">Explore the best drinks and desserts in this category.</p>

                </div>

                <div class="col-lg-4 text-lg-end">

                    <a href="<?= base_url('menu'); ?>" class="btn btn-warning btn-lg">View All Menu</a>

                </div>

            </div>

        </div>

    </section>

    <section class="py-5">

        <div class="container">

            <div class="alert alert-info">
                Category pages are coming soon. For now, explore our full menu and search for the perfect drink.
            </div>

            <a href="<?= base_url('menu'); ?>" class="btn btn-dark">Browse Menu</a>

        </div>

    </section>

</main>

<?php require_once 'includes/footer.php'; ?>
