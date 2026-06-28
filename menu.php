<?php

$pageTitle = "Our Menu";
$pageDescription = "Explore our premium coffee, desserts and handcrafted beverages.";

require_once 'includes/header.php';

$search = trim($_GET['search'] ?? '');
$selectedCategory = trim($_GET['category'] ?? '');
$priceRange = (int) ($_GET['price'] ?? 1000);
$sortBy = $_GET['sort'] ?? 'newest';

$categories = [

    ['name'=>'All','slug'=>'all'],

    ['name'=>'Hot Coffee','slug'=>'hot-coffee'],

    ['name'=>'Cold Coffee','slug'=>'cold-coffee'],

    ['name'=>'Frappes','slug'=>'frappes'],

    ['name'=>'Milkshakes','slug'=>'milkshakes'],

    ['name'=>'Desserts','slug'=>'desserts']

];

$products = [

[
'id'=>1,
'name'=>'Classic Cappuccino',
'category'=>'Hot Coffee',
'category_slug'=>'hot-coffee',
'price'=>650,
'rating'=>5,
'image'=>asset('images/2KD5M8DPzOsE7IP2NnzGsHD4RFpJCRD3jdH25EH.png'),
'slug'=>'classic-cappuccino',
'badge'=>'Best Seller'
],

[
'id'=>2,
'name'=>'Caramel Latte',
'category'=>'Hot Coffee',
'category_slug'=>'hot-coffee',
'price'=>720,
'rating'=>4,
'image'=>asset('images/4EopM6XnwTdVa30hby4yeKfowXszpIxRbfEqho.png'),
'slug'=>'caramel-latte',
'badge'=>'Popular'
],

[
'id'=>3,
'name'=>'Chocolate Frappe',
'category'=>'Frappes',
'category_slug'=>'frappes',
'price'=>850,
'rating'=>5,
'image'=>asset('images/9onHcJAwSASx9MLHhq1sc07PtjC176Mb2vrT3qvL.png'),
'slug'=>'chocolate-frappe',
'badge'=>'New'
],

[
'id'=>4,
'name'=>'Oreo Milkshake',
'category'=>'Milkshakes',
'category_slug'=>'milkshakes',
'price'=>790,
'rating'=>5,
'image'=>asset('images/55IbJ2Umb4xAOtgjYb1rFLE0cCJYg5uziSDfyaE.png'),
'slug'=>'oreo-milkshake',
'badge'=>'Trending'
],

[
'id'=>5,
'name'=>'Mocha Coffee',
'category'=>'Hot Coffee',
'category_slug'=>'hot-coffee',
'price'=>690,
'rating'=>5,
'image'=>asset('images/aS77f7Jfwcy8wIasUDRauZ0igWDvuvMBTRgaptd9.png'),
'slug'=>'mocha-coffee',
'badge'=>'Hot'
],

[
'id'=>6,
'name'=>'Iced Latte',
'category'=>'Cold Coffee',
'category_slug'=>'cold-coffee',
'price'=>760,
'rating'=>4,
'image'=>asset('images/BeUfI6pNLLOrmzc54MnSptOSRd3W0U64o7TmCHH.png'),
'slug'=>'iced-latte',
'badge'=>'Cold'
]

];

$filteredProducts = array_filter($products, function ($product) use ($search, $selectedCategory, $priceRange) {
    if ($selectedCategory && $selectedCategory !== 'all' && $product['category_slug'] !== $selectedCategory) {
        return false;
    }

    if (!empty($search)) {
        $needle = mb_strtolower($search);
        return str_contains(mb_strtolower($product['name']), $needle)
            || str_contains(mb_strtolower($product['category']), $needle);
    }

    if ($priceRange > 0 && $product['price'] > $priceRange) {
        return false;
    }

    return true;
});

usort($filteredProducts, function ($a, $b) use ($sortBy) {
    return match ($sortBy) {
        'price_low' => $a['price'] <=> $b['price'],
        'price_high' => $b['price'] <=> $a['price'],
        'top_rated' => $b['rating'] <=> $a['rating'],
        'best_seller' => $b['rating'] <=> $a['rating'],
        default => $b['id'] <=> $a['id'],
    };
});

$products = array_values($filteredProducts);

?>

<main>

<section class="page-header bg-dark text-white py-5 mt-5">

<div class="container">

<div class="row align-items-center">

<div class="col-lg-6">

<h1 class="display-4 fw-bold">

Our Menu

</h1>

<p class="lead">

Freshly brewed coffee, handcrafted drinks and delicious desserts.

</p>

</div>

<div class="col-lg-6 text-lg-end">

<nav>

<ol class="breadcrumb justify-content-lg-end mb-0">

<li class="breadcrumb-item">

<a class="text-white" href="<?= base_url(); ?>">

Home

</a>

</li>

<li class="breadcrumb-item active text-warning">

Menu

</li>

</ol>

</nav>

</div>

</div>

</div>

</section>

<section class="section-padding">

<div class="container">

<div class="row">

<!-- Sidebar -->

<div class="col-lg-3">

<div class="menu-sidebar">

<form action="<?= base_url('menu'); ?>" method="GET">

<div class="menu-search mb-4">

<input

type="search"

name="search"

class="form-control"

value="<?= e($search); ?>"

placeholder="Search Coffee...">

</div>

<h5>

Categories

</h5>

<select name="category" class="form-select mb-4">

<option value="all" <?= $selectedCategory === 'all' ? 'selected' : ''; ?>>All Categories</option>

<?php foreach($categories as $category): ?>

<option value="<?= e($category['slug']); ?>" <?= $selectedCategory === $category['slug'] ? 'selected' : ''; ?>>

<?= e($category['name']); ?>

</option>

<?php endforeach; ?>

</select>

<hr>

<h5>

Price Range

</h5>

<div class="mb-3">

<input

type="range"

name="price"

class="form-range"

min="100"

max="1000"

step="50"

value="<?= e($priceRange); ?>">

</div>

<div class="d-flex justify-content-between">

<small>Rs.100</small>

<small>Rs.1000</small>

</div>

<div class="text-muted small mb-3">

Max price: Rs.<?= e($priceRange ?: 1000); ?>

</div>

<hr>

<h5>

Sort By

</h5>

<select name="sort" class="form-select">

<option value="newest" <?= $sortBy === 'newest' ? 'selected' : ''; ?>>Newest</option>

<option value="price_low" <?= $sortBy === 'price_low' ? 'selected' : ''; ?>>Price Low To High</option>

<option value="price_high" <?= $sortBy === 'price_high' ? 'selected' : ''; ?>>Price High To Low</option>

<option value="best_seller" <?= $sortBy === 'best_seller' ? 'selected' : ''; ?>>Best Selling</option>

<option value="top_rated" <?= $sortBy === 'top_rated' ? 'selected' : ''; ?>>Top Rated</option>

</select>

<button

class="btn btn-primary w-100 mt-4">

Apply Filters

</button>

</form>

</div>

</div>

<!-- Products -->

<div class="col-lg-9">

<div class="d-flex justify-content-between align-items-center mb-4">

<h3>

<?= count($products); ?>

Products Found

</h3>

<div>

<button class="btn btn-outline-primary">

<i class="fa-solid fa-grip"></i>

</button>

<button class="btn btn-outline-primary">

<i class="fa-solid fa-list"></i>

</button>

</div>

</div>

<div class="row g-4">
    <?php foreach($products as $product): ?>

<div class="col-xl-4 col-lg-6 col-md-6">

    <div class="product-card h-100">

        <div class="position-relative overflow-hidden">

            <img
                src="<?= $product['image']; ?>"
                class="img-fluid w-100"
                alt="<?= e($product['name']); ?>">

            <span class="discount-badge">

                <?= e($product['badge']); ?>

            </span>

            <div class="position-absolute top-0 end-0 p-3">

                <button
                    class="btn btn-light rounded-circle shadow-sm mb-2">

                    <i class="fa-regular fa-heart"></i>

                </button>

            </div>

        </div>

        <div class="card-body">

            <small class="text-muted">

                <?= e($product['category']); ?>

            </small>

            <h5 class="product-title mt-2">

                <?= e($product['name']); ?>

            </h5>

            <div class="product-rating">

                <?php for($i=1;$i<=5;$i++): ?>

                    <?php if($i <= $product['rating']): ?>

                        <i class="fa-solid fa-star text-warning"></i>

                    <?php else: ?>

                        <i class="fa-regular fa-star text-warning"></i>

                    <?php endif; ?>

                <?php endfor; ?>

            </div>

            <div class="d-flex justify-content-between align-items-center my-3">

                <span class="product-price">

                    <?= currency($product['price']); ?>

                </span>

                <span class="badge bg-success">

                    In Stock

                </span>

            </div>

            <div class="d-grid gap-2">

                <a
                    href="<?= base_url('product/'.$product['slug']); ?>"
                    class="btn btn-outline-primary">

                    View Details

                </a>

                <button
                    class="btn btn-primary">

                    <i class="fa-solid fa-cart-shopping me-2"></i>

                    Add To Cart

                </button>

            </div>

        </div>

    </div>

</div>

<?php endforeach; ?>

</div>

<!-- Empty State -->

<?php if(empty($products)): ?>

<div class="text-center py-5">

    <img
        src="<?= asset('images/empty/menu-empty.svg'); ?>"
        width="220"
        class="mb-4"
        alt="No Products">

    <h3>

        No Products Found

    </h3>

    <p class="text-muted">

        We couldn't find any products matching your search.

    </p>

</div>

<?php endif; ?>

<!-- Pagination -->

<nav class="mt-5">

    <ul class="pagination justify-content-center">

        <li class="page-item disabled">

            <a class="page-link">

                Previous

            </a>

        </li>

        <li class="page-item active">

            <a class="page-link">

                1

            </a>

        </li>

        <li class="page-item">

            <a class="page-link">

                2

            </a>

        </li>

        <li class="page-item">

            <a class="page-link">

                3

            </a>

        </li>

        <li class="page-item">

            <a class="page-link">

                Next

            </a>

        </li>

    </ul>

</nav>

</div>

</div>

</div>

</section>

</main>

<?php require_once 'includes/footer.php'; ?>