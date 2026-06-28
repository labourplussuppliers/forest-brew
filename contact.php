<?php

$pageTitle = "Contact Frost & Brew";
$pageDescription = "Get in touch with Frost & Brew for reservations, orders, and cafe support.";

require_once 'includes/header.php';
?>

<main class="py-5">

    <section class="page-header bg-dark text-white py-5 mt-5">

        <div class="container">

            <div class="row align-items-center">

                <div class="col-lg-7">

                    <h1 class="display-4 fw-bold">Contact Us</h1>

                    <p class="lead text-light mt-4">
                        We are here to help with orders, reservations and any questions you have.
                    </p>

                </div>

                <div class="col-lg-5 text-lg-end">

                    <a href="mailto:info@frostbrew.com" class="btn btn-warning btn-lg">Email Us</a>

                </div>

            </div>

        </div>

    </section>

    <section class="py-5">

        <div class="container">

            <div class="row g-4">

                <div class="col-lg-6">

                    <div class="card border-0 shadow-sm p-4 h-100">

                        <h3 class="fw-bold mb-3">Reach Out</h3>

                        <p class="text-muted">Call or email us any time for menu questions, catering or store hours.</p>

                        <p><strong>Phone:</strong> +92 300 1234567</p>
                        <p><strong>Email:</strong> info@frostbrew.com</p>
                        <p><strong>Location:</strong> Lahore, Pakistan</p>

                    </div>

                </div>

                <div class="col-lg-6">

                    <div class="card border-0 shadow-sm p-4 h-100">

                        <h3 class="fw-bold mb-3">Visit Us</h3>

                        <p class="text-muted">Come by our cafe for premium coffee, desserts and a relaxing atmosphere.</p>

                        <p><strong>Address:</strong> Main Street, Lahore</p>
                        <p><strong>Hours:</strong> Mon-Sun 8am - 10pm</p>

                        <a href="<?= base_url('menu'); ?>" class="btn btn-dark mt-3">View Menu</a>

                    </div>

                </div>

            </div>

        </div>

    </section>

</main>

<?php require_once 'includes/footer.php'; ?>
