<?php

$pageTitle = "Home";
$pageDescription = "Welcome to Frost & Brew - Premium Coffee, Desserts & Cafe Experience.";

require_once 'includes/header.php';

/*
|--------------------------------------------------------------------------
| Demo Data
| Later this data will come from MySQL Database
|--------------------------------------------------------------------------
*/

$featuredCategories = [
    [
        'title' => 'Hot Coffee',
        'image' => asset('images/categories/hot-coffee.jpg'),
        'slug'  => 'hot-coffee'
    ],
    [
        'title' => 'Cold Coffee',
        'image' => asset('images/categories/cold-coffee.jpg'),
        'slug'  => 'cold-coffee'
    ],
    [
        'title' => 'Frappes',
        'image' => asset('images/categories/frappes.jpg'),
        'slug'  => 'frappes'
    ],
    [
        'title' => 'Milkshakes',
        'image' => asset('images/categories/milkshakes.jpg'),
        'slug'  => 'milkshakes'
    ]
];

?>

<main>

<!-- ==========================================
Hero Section
========================================== -->

<section class="hero-section py-5 mt-5">

    <div class="container">

        <div class="row align-items-center">

            <div class="col-lg-6" data-aos="fade-right">

                <span class="badge bg-warning text-dark mb-3">

                    Freshly Brewed Every Day

                </span>

                <h1 class="display-4 fw-bold mb-4">

                    Experience Premium Coffee
                    <span class="text-warning">

                        At Frost & Brew

                    </span>

                </h1>

                <p class="lead text-muted mb-4">

                    Discover handcrafted coffee, delicious desserts,
                    refreshing beverages and unforgettable café moments.

                </p>

                <div class="d-flex flex-wrap gap-3">

                    <a
                        href="<?= base_url('menu'); ?>"
                        class="btn btn-dark btn-lg">

                        Explore Menu

                    </a>

                    <a
                        href="<?= base_url('about'); ?>"
                        class="btn btn-outline-dark btn-lg">

                        Learn More

                    </a>

                </div>

            </div>

            <div class="col-lg-6 text-center" data-aos="fade-left">

                <img
                    src="<?= asset('images/hero/hero.png'); ?>"
                    class="img-fluid"
                    alt="Coffee">

            </div>

        </div>

    </div>

</section>

<!-- ==========================================
Featured Categories
========================================== -->

<section class="py-5">

    <div class="container">

        <div class="text-center mb-5">

            <h2 class="fw-bold">

                Browse Categories

            </h2>

            <p class="text-muted">

                Choose your favorite drinks and desserts.

            </p>

        </div>

        <div class="row g-4">

            <?php foreach($featuredCategories as $category): ?>

                <div class="col-lg-3 col-md-6">

                    <div class="card border-0 shadow-sm h-100">

                        <img
                            src="<?= $category['image']; ?>"
                            class="card-img-top"
                            alt="<?= e($category['title']); ?>">

                        <div class="card-body text-center">

                            <h5 class="fw-bold">

                                <?= e($category['title']); ?>

                            </h5>

                            <a
                                href="<?= base_url('category/'.$category['slug']); ?>"
                                class="btn btn-outline-dark mt-3">

                                View Menu

                            </a>

                        </div>

                    </div>

                </div>

            <?php endforeach; ?>

        </div>

    </div>

</section>

<!-- ==========================================
Welcome Section
========================================== -->

<section class="py-5 bg-light">

    <div class="container">

        <div class="row align-items-center">

            <div class="col-lg-6">

                <img
                    src="<?= asset('images/banners/about-home.jpg'); ?>"
                    class="img-fluid rounded shadow"
                    alt="Cafe">

            </div>

            <div class="col-lg-6">

                <h2 class="fw-bold mb-4">

                    Welcome To Frost & Brew

                </h2>

                <p class="text-muted">

                    Frost & Brew is more than just a café.
                    We serve premium quality coffee,
                    handcrafted beverages, delicious desserts,
                    and warm hospitality to create memorable experiences
                    for every customer.

                </p>

                <ul class="list-unstyled mt-4">

                    <li class="mb-3">

                        <i class="fa-solid fa-circle-check text-success me-2"></i>

                        Fresh Premium Coffee Beans

                    </li>

                    <li class="mb-3">

                        <i class="fa-solid fa-circle-check text-success me-2"></i>

                        Handmade Desserts

                    </li>

                    <li class="mb-3">

                        <i class="fa-solid fa-circle-check text-success me-2"></i>

                        Fast & Friendly Service

                    </li>

                    <li>

                        <i class="fa-solid fa-circle-check text-success me-2"></i>

                        Comfortable Café Environment

                    </li>

                </ul>

                <a
                    href="<?= base_url('about'); ?>"
                    class="btn btn-warning mt-3">

                    Read More

                </a>

            </div>

        </div>

    </div>

</section>
<!-- ==========================================
Best Selling Products
========================================== -->

<?php

$bestSellingProducts = [

    [
        'name'  => 'Classic Cappuccino',
        'price' => 650,
        'image' => asset('images/products/cappuccino.jpg'),
        'slug'  => 'classic-cappuccino'
    ],

    [
        'name'  => 'Caramel Latte',
        'price' => 720,
        'image' => asset('images/products/caramel-latte.jpg'),
        'slug'  => 'caramel-latte'
    ],

    [
        'name'  => 'Chocolate Frappe',
        'price' => 850,
        'image' => asset('images/products/chocolate-frappe.jpg'),
        'slug'  => 'chocolate-frappe'
    ],

    [
        'name'  => 'Oreo Milkshake',
        'price' => 790,
        'image' => asset('images/products/oreo-milkshake.jpg'),
        'slug'  => 'oreo-milkshake'
    ]

];

?>

<section class="py-5">

    <div class="container">

        <div class="text-center mb-5">

            <h2 class="fw-bold">

                Best Selling Drinks

            </h2>

            <p class="text-muted">

                Our customers' favourite handcrafted beverages.

            </p>

        </div>

        <div class="row g-4">

            <?php foreach($bestSellingProducts as $product): ?>

            <div class="col-lg-3 col-md-6">

                <div class="card border-0 shadow-sm h-100">

                    <img
                        src="<?= $product['image']; ?>"
                        class="card-img-top"
                        alt="<?= e($product['name']); ?>">

                    <div class="card-body">

                        <h5 class="fw-bold">

                            <?= e($product['name']); ?>

                        </h5>

                        <p class="text-warning fw-bold fs-5">

                            <?= currency($product['price']); ?>

                        </p>

                        <div class="d-grid gap-2">

                            <a
                                href="<?= base_url('product/'.$product['slug']); ?>"
                                class="btn btn-outline-dark">

                                View Details

                            </a>

                            <button
                                class="btn btn-dark">

                                <i class="fa-solid fa-cart-shopping me-2"></i>

                                Add To Cart

                            </button>

                        </div>

                    </div>

                </div>

            </div>

            <?php endforeach; ?>

        </div>

    </div>

</section>

<!-- ==========================================
Special Offer
========================================== -->

<section class="py-5 bg-dark text-white">

    <div class="container">

        <div class="row align-items-center">

            <div class="col-lg-6">

                <span class="badge bg-warning text-dark mb-3">

                    Limited Time Offer

                </span>

                <h2 class="display-5 fw-bold">

                    Buy 2 Coffees
                    Get 1 Free

                </h2>

                <p class="lead mt-3">

                    Enjoy your favourite handcrafted coffee
                    with exclusive seasonal discounts.

                </p>

                <a
                    href="<?= base_url('offers'); ?>"
                    class="btn btn-warning btn-lg mt-3">

                    View Offers

                </a>

            </div>

            <div class="col-lg-6 text-center">

                <img
                    src="<?= asset('images/banners/offer.png'); ?>"
                    class="img-fluid"
                    alt="Offer">

            </div>

        </div>

    </div>

</section>

<!-- ==========================================
Why Choose Frost & Brew
========================================== -->

<section class="py-5">

    <div class="container">

        <div class="text-center mb-5">

            <h2 class="fw-bold">

                Why Choose Us

            </h2>

            <p class="text-muted">

                Everything is prepared with passion and quality.

            </p>

        </div>

        <div class="row text-center g-4">

            <div class="col-lg-3 col-md-6">

                <div class="p-4 shadow-sm rounded h-100">

                    <i class="fa-solid fa-mug-hot fa-3x text-warning mb-3"></i>

                    <h5 class="fw-bold">

                        Premium Coffee

                    </h5>

                    <p class="text-muted">

                        Freshly roasted beans with rich flavour.

                    </p>

                </div>

            </div>

            <div class="col-lg-3 col-md-6">

                <div class="p-4 shadow-sm rounded h-100">

                    <i class="fa-solid fa-burger fa-3x text-warning mb-3"></i>

                    <h5 class="fw-bold">

                        Fresh Food

                    </h5>

                    <p class="text-muted">

                        Prepared daily using quality ingredients.

                    </p>

                </div>

            </div>

            <div class="col-lg-3 col-md-6">

                <div class="p-4 shadow-sm rounded h-100">

                    <i class="fa-solid fa-truck-fast fa-3x text-warning mb-3"></i>

                    <h5 class="fw-bold">

                        Fast Delivery

                    </h5>

                    <p class="text-muted">

                        Quick doorstep delivery with care.

                    </p>

                </div>

            </div>

            <div class="col-lg-3 col-md-6">

                <div class="p-4 shadow-sm rounded h-100">

                    <i class="fa-solid fa-star fa-3x text-warning mb-3"></i>

                    <h5 class="fw-bold">

                        Customer Satisfaction

                    </h5>

                    <p class="text-muted">

                        Thousands of happy customers trust us.

                    </p>

                </div>

            </div>

        </div>

    </div>

</section>

<!-- ==========================================
Counter Section
========================================== -->

<section class="py-5 bg-light">

    <div class="container">

        <div class="row text-center">

            <div class="col-lg-3 col-6 mb-4">

                <h2 class="display-5 fw-bold text-warning">

                    10+

                </h2>

                <p>Cafe Branches</p>

            </div>

            <div class="col-lg-3 col-6 mb-4">

                <h2 class="display-5 fw-bold text-warning">

                    25K+

                </h2>

                <p>Happy Customers</p>

            </div>

            <div class="col-lg-3 col-6 mb-4">

                <h2 class="display-5 fw-bold text-warning">

                    120+

                </h2>

                <p>Menu Items</p>

            </div>

            <div class="col-lg-3 col-6 mb-4">

                <h2 class="display-5 fw-bold text-warning">

                    4.9★

                </h2>

                <p>Average Rating</p>

            </div>

        </div>

    </div>

</section>
<!-- ==========================================
Customer Testimonials
========================================== -->

<?php

$testimonials = [

    [
        'name' => 'Ali Raza',
        'review' => 'Amazing coffee, beautiful ambiance and excellent customer service.',
        'image' => asset('images/testimonials/user-1.jpg')
    ],

    [
        'name' => 'Ayesha Khan',
        'review' => 'Their desserts are absolutely delicious. Highly recommended.',
        'image' => asset('images/testimonials/user-2.jpg')
    ],

    [
        'name' => 'Usman Ahmed',
        'review' => "One of the best cafés I've visited. Great quality and friendly staff.",
        'image' => asset('images/testimonials/user-3.jpg')
    ]

];

?>

<section class="py-5">

    <div class="container">

        <div class="text-center mb-5">

            <h2 class="fw-bold">

                What Our Customers Say

            </h2>

            <p class="text-muted">

                Real feedback from our happy customers.

            </p>

        </div>

        <div class="row g-4">

            <?php foreach($testimonials as $testimonial): ?>

            <div class="col-lg-4">

                <div class="card border-0 shadow-sm h-100">

                    <div class="card-body text-center">

                        <img
                            src="<?= $testimonial['image']; ?>"
                            width="90"
                            height="90"
                            class="rounded-circle mb-3"
                            alt="<?= e($testimonial['name']); ?>">

                        <h5 class="fw-bold">

                            <?= e($testimonial['name']); ?>

                        </h5>

                        <div class="text-warning mb-3">

                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>

                        </div>

                        <p class="text-muted">

                            "<?= e($testimonial['review']); ?>"

                        </p>

                    </div>

                </div>

            </div>

            <?php endforeach; ?>

        </div>

    </div>

</section>

<!-- ==========================================
Gallery
========================================== -->

<section class="py-5 bg-light">

    <div class="container">

        <div class="text-center mb-5">

            <h2 class="fw-bold">

                Gallery

            </h2>

            <p class="text-muted">

                A glimpse of our café and delicious menu.

            </p>

        </div>

        <div class="row g-3">

            <?php

            for($i = 1; $i <= 8; $i++) :

            ?>

            <div class="col-lg-3 col-md-4 col-6">

                <img
                    src="<?= asset("images/gallery/$i.jpg"); ?>"
                    class="img-fluid rounded shadow-sm"
                    alt="Gallery Image">

            </div>

            <?php endfor; ?>

        </div>

    </div>

</section>

<!-- ==========================================
Reservation CTA
========================================== -->

<section class="py-5 bg-dark text-white">

    <div class="container text-center">

        <h2 class="display-5 fw-bold mb-4">

            Reserve Your Table Today

        </h2>

        <p class="lead mb-4">

            Enjoy premium coffee and delicious meals with your friends and family.

        </p>

        <a
            href="<?= base_url('contact'); ?>"
            class="btn btn-warning btn-lg px-5">

            Book Now

        </a>

    </div>

</section>

<!-- ==========================================
Instagram Feed
========================================== -->

<section class="py-5">

    <div class="container">

        <div class="text-center mb-5">

            <h2 class="fw-bold">

                Follow Us On Instagram

            </h2>

            <p class="text-muted">

                @frostbrew

            </p>

        </div>

        <div class="row g-3">

            <?php

            for($i = 1; $i <= 6; $i++) :

            ?>

            <div class="col-lg-2 col-md-4 col-6">

                <img
                    src="<?= asset("images/instagram/$i.jpg"); ?>"
                    class="img-fluid rounded"
                    alt="Instagram">

            </div>

            <?php endfor; ?>

        </div>

    </div>

</section>

</main>

<?php require_once 'includes/footer.php'; ?>