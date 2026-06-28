<?php

require APP_PATH . '/Views/layouts/header.php';

?>

<section class="page-header">

    <div class="container">

        <h1>Our Menu</h1>

        <p>
            Freshly prepared coffee, beverages and desserts.
        </p>

    </div>

</section>

<section class="py-5">

    <div class="container">

        <div class="row mb-4">

            <div class="col-lg-4">

                <form method="GET">

                    <input
                        type="text"
                        name="search"
                        value="<?= e($search) ?>"
                        class="form-control"
                        placeholder="Search menu...">

                </form>

            </div>

            <div class="col-lg-4">

                <form method="GET">

                    <select
                        name="category"
                        class="form-select"
                        onchange="this.form.submit()">

                        <option value="">
                            All Categories
                        </option>

                        <?php foreach($categories as $category): ?>

                            <option
                                value="<?= e($category['slug']) ?>"
                                <?= $selectedCategory == $category['slug'] ? 'selected' : '' ?>>

                                <?= e($category['name']) ?>

                            </option>

                        <?php endforeach; ?>

                    </select>

                </form>

            </div>

        </div>

        <div class="row">

            <?php if(empty($products)): ?>

                <div class="col-12">

                    <div class="alert alert-warning">

                        No products found.

                    </div>

                </div>

            <?php endif; ?>

            <?php foreach($products as $product): ?>

                <?php include APP_PATH . '/Views/components/product-card.php'; ?>

            <?php endforeach; ?>

        </div>

    </div>

</section>

<?php

require APP_PATH . '/Views/layouts/footer.php';

?>