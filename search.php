<?php

$pageTitle = "Search Results";
$pageDescription = "Search Frost & Brew menu and cafe content.";

require_once 'includes/header.php';

$query = trim($_GET['search'] ?? '');
?>

<main class="py-5">

    <section class="page-header bg-dark text-white py-5 mt-5">

        <div class="container">

            <h1 class="display-4 fw-bold">Search Results</h1>

            <p class="lead text-light mt-4">You searched for: <?= e($query); ?></p>

        </div>

    </section>

    <section class="py-5">

        <div class="container">

            <div class="alert alert-info">
                Searching is not fully implemented yet, but this page confirms your query was received.
            </div>

            <a href="<?= base_url('menu'); ?>" class="btn btn-dark">Browse Menu</a>

        </div>

    </section>

</main>

<?php require_once 'includes/footer.php'; ?>
