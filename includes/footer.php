    </main>

    <!-- ==========================================
         Newsletter Section
    =========================================== -->

    <section class="newsletter-section py-5 bg-dark text-white">

        <div class="container">

            <div class="row align-items-center">

                <div class="col-lg-6 mb-4 mb-lg-0">

                    <h2 class="fw-bold mb-3">

                        Stay Updated with Frost & Brew

                    </h2>

                    <p class="mb-0 text-light">

                        Subscribe to receive exclusive coffee deals,
                        new menu updates and seasonal offers.

                    </p>

                </div>

                <div class="col-lg-6">

                    <form
                        action="<?= base_url('newsletter'); ?>"
                        method="POST"
                        class="row g-2">

                        <?= csrf_field(); ?>

                        <div class="col-md-8">

                            <input
                                type="email"
                                name="email"
                                class="form-control form-control-lg"
                                placeholder="Enter your email"
                                required>

                        </div>

                        <div class="col-md-4">

                            <button
                                class="btn btn-warning btn-lg w-100">

                                Subscribe

                            </button>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    </section>
        <!-- ==========================================
         Footer
    =========================================== -->

    <footer class="bg-black text-white pt-5 pb-4">

        <div class="container">

            <div class="row">

                <!-- Company -->

                <div class="col-lg-4 mb-4">

                    <img
                        src="<?= asset('images/logo.png'); ?>"
                        width="70"
                        class="mb-3"
                        alt="Frost & Brew">

                    <h4 class="fw-bold">

                        Frost & Brew

                    </h4>

                    <p class="text-light mt-3">

                        Premium coffee, handcrafted beverages,
                        delicious desserts and unforgettable café
                        experiences.

                    </p>

                </div>

                <!-- Company Links -->

                <div class="col-lg-2 col-md-6 mb-4">

                    <h5 class="fw-bold mb-4">

                        Company

                    </h5>

                    <ul class="list-unstyled">

                        <li class="mb-2">

                            <a
                                href="<?= base_url(); ?>"
                                class="text-decoration-none text-light">

                                Home

                            </a>

                        </li>

                        <li class="mb-2">

                            <a
                                href="<?= base_url('about'); ?>"
                                class="text-decoration-none text-light">

                                About

                            </a>

                        </li>

                        <li class="mb-2">

                            <a
                                href="<?= base_url('contact'); ?>"
                                class="text-decoration-none text-light">

                                Contact

                            </a>

                        </li>

                        <li>

                            <a
                                href="<?= base_url('menu'); ?>"
                                class="text-decoration-none text-light">

                                Menu

                            </a>

                        </li>

                    </ul>

                </div>
                                <!-- Categories -->

                <div class="col-lg-3 col-md-6 mb-4">

                    <h5 class="fw-bold mb-4">

                        Categories

                    </h5>

                    <ul class="list-unstyled">

                        <li class="mb-2">

                            <a
                                href="<?= base_url('category/coffee'); ?>"
                                class="text-light text-decoration-none">

                                Hot Coffee

                            </a>

                        </li>

                        <li class="mb-2">

                            <a
                                href="<?= base_url('category/cold-coffee'); ?>"
                                class="text-light text-decoration-none">

                                Cold Coffee

                            </a>

                        </li>

                        <li class="mb-2">

                            <a
                                href="<?= base_url('category/frappes'); ?>"
                                class="text-light text-decoration-none">

                                Frappes

                            </a>

                        </li>

                        <li class="mb-2">

                            <a
                                href="<?= base_url('category/milkshakes'); ?>"
                                class="text-light text-decoration-none">

                                Milkshakes

                            </a>

                        </li>

                        <li>

                            <a
                                href="<?= base_url('category/desserts'); ?>"
                                class="text-light text-decoration-none">

                                Desserts

                            </a>

                        </li>

                    </ul>

                </div>
                                <!-- Contact -->

                <div class="col-lg-3">

                    <h5 class="fw-bold mb-4">

                        Contact Us

                    </h5>

                    <p>

                        <i class="fa-solid fa-location-dot me-2"></i>

                        Lahore, Pakistan

                    </p>

                    <p>

                        <i class="fa-solid fa-phone me-2"></i>

                        +92 300 0000000

                    </p>

                    <p>

                        <i class="fa-solid fa-envelope me-2"></i>

                        info@frostbrew.com

                    </p>

                    <div class="mt-4">

                        <a href="#" class="text-white me-3">

                            <i class="fab fa-facebook-f fa-lg"></i>

                        </a>

                        <a href="#" class="text-white me-3">

                            <i class="fab fa-instagram fa-lg"></i>

                        </a>

                        <a href="#" class="text-white me-3">

                            <i class="fab fa-youtube fa-lg"></i>

                        </a>

                        <a href="#" class="text-white">

                            <i class="fab fa-tiktok fa-lg"></i>

                        </a>

                    </div>

                </div>

            </div>

            <hr class="border-secondary my-4">

            <div
                class="d-flex flex-column flex-lg-row justify-content-between align-items-center">

                <p class="mb-2 mb-lg-0">

                    © <?= date('Y'); ?>

                    Frost & Brew.

                    All Rights Reserved.

                </p>

                <p class="mb-0">

                    Developed by Munazza Malik With ❤️

                </p>

            </div>

        </div>

    </footer>
        <!-- Back To Top -->

    <button
        id="backToTop"
        class="btn btn-warning rounded-circle">

        <i class="fa-solid fa-arrow-up"></i>

    </button>

    <!-- Bootstrap -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

    <!-- AOS -->

    <script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>

    <!-- SweetAlert -->

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Custom JS -->

    <script src="<?= asset('js/app.js'); ?>"></script>

    <script>

        AOS.init({

            duration: 800,

            once: true

        });

    </script>

</body>

</html>