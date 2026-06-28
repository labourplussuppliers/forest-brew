<?php

$pageTitle = "About Frost & Brew";
$pageDescription = "Discover our story, coffee philosophy and cafe experience.";

require_once 'includes/header.php';
?>

<main class="py-5">

    <section class="page-header bg-dark text-white py-5 mt-5">

        <div class="container">

            <div class="row align-items-center">

                <div class="col-lg-7">

                    <h1 class="display-4 fw-bold">About Frost & Brew</h1>

                    <p class="lead text-light mt-4">
                        We are a premium cafe serving freshly brewed coffee, handcrafted beverages and delicious desserts.
                    </p>

                </div>

                <div class="col-lg-5 text-center">

                    <img src="<?= asset('images/banners/about-home.jpg'); ?>" class="img-fluid rounded shadow" alt="About Frost & Brew">

                </div>

            </div>

        </div>

    </section>

    <section class="py-5">

        <div class="container">

            <div class="row gy-4 align-items-center">

                <div class="col-lg-6">

                    <h2 class="fw-bold">Our Mission</h2>

                    <p class="text-muted">
                        Frost & Brew was created to deliver warm hospitality and memorable cafe moments, one cup at a time.
                    </p>

                    <ul class="list-unstyled mt-4">

                        <li class="mb-3"><i class="fa-solid fa-check text-warning me-2"></i> Freshly roasted coffee beans</li>
                        <li class="mb-3"><i class="fa-solid fa-check text-warning me-2"></i> Handmade desserts and snacks</li>
                        <li><i class="fa-solid fa-check text-warning me-2"></i> Fast, friendly service in a cozy atmosphere</li>

                    </ul>

                </div>

                <div class="col-lg-6">

                    <div class="row g-3">

                        <div class="col-6">
                            <div class="card border-0 shadow-sm p-4 h-100">
                                <h3 class="fw-bold">Quality</h3>
                                <p class="small text-muted">Only premium ingredients in every drink and dessert.</p>
                            </div>
                        </div>

                        <div class="col-6">
                            <div class="card border-0 shadow-sm p-4 h-100">
                                <h3 class="fw-bold">Taste</h3>
                                <p class="small text-muted">Bold flavors crafted to delight every palate.</p>
                            </div>
                        </div>

                        <div class="col-6">
                            <div class="card border-0 shadow-sm p-4 h-100">
                                <h3 class="fw-bold">Service</h3>
                                <p class="small text-muted">Friendly staff ready to serve your perfect order.</p>
                            </div>
                        </div>

                        <div class="col-6">
                            <div class="card border-0 shadow-sm p-4 h-100">
                                <h3 class="fw-bold">Comfort</h3>
                                <p class="small text-muted">A cozy cafe setting ideal for work, study or catching up.</p>
                            </div>
                        </div>

                    </div>

                </div>

            </div>

        </div>

    </section>

</main>

<?php require_once 'includes/footer.php'; ?>
