<?php

require APP_PATH . '/Views/admin/layouts/header.php';

?>

<div class="container-fluid py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h2 class="mb-1">

                Products

            </h2>

            <p class="text-muted mb-0">

                Manage your Frost & Brew menu products.

            </p>

        </div>

        <div>

            <a
                href="<?= BASE_URL ?>/admin/products/create"
                class="btn btn-primary">

                <i class="fa-solid fa-plus me-2"></i>

                Add Product

            </a>

        </div>

    </div>

    <div class="card shadow-sm border-0 mb-4">

        <div class="card-body">

            <form method="GET">

                <div class="row">

                    <div class="col-lg-4 mb-3">

                        <input
                            type="text"
                            name="search"
                            class="form-control"
                            placeholder="Search by product name or SKU..."
                            value="<?= e($_GET['search'] ?? '') ?>">

                    </div>

                    <div class="col-lg-3 mb-3">

                        <select
                            name="category"
                            class="form-select">

                            <option value="">

                                All Categories

                            </option>

                            <?php foreach($categories as $category): ?>

                            <option
                                value="<?= $category['id'] ?>"
                                <?= (($_GET['category'] ?? '') == $category['id']) ? 'selected' : '' ?>>

                                <?= e($category['name']) ?>

                            </option>

                            <?php endforeach; ?>

                        </select>

                    </div>

                    <div class="col-lg-3 mb-3">

                        <select
                            name="status"
                            class="form-select">

                            <option value="">

                                All Status

                            </option>

                            <option
                                value="1"
                                <?= (($_GET['status'] ?? '') == '1') ? 'selected' : '' ?>>

                                Active

                            </option>

                            <option
                                value="0"
                                <?= (($_GET['status'] ?? '') == '0') ? 'selected' : '' ?>>

                                Inactive

                            </option>

                        </select>

                    </div>

                    <div class="col-lg-2 mb-3 d-grid">

                        <button
                            class="btn btn-dark">

                            Search

                        </button>

                    </div>

                </div>

            </form>

        </div>

    </div>

    <div class="card shadow-sm border-0">

        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light">

                        <tr>

                            <th width="60">

                                #

                            </th>

                            <th width="80">

                                Image

                            </th>

                            <th>

                                Product

                            </th>

                            <th>

                                Category

                            </th>

                            <th>

                                SKU

                            </th>

                            <th>

                                Stock

                            </th>

                            <th>

                                Status

                            </th>

                            <th>

                                Featured

                            </th>

                            <th width="170">

                                Actions

                            </th>

                        </tr>

                    </thead>

                    <tbody>

                    <?php if(empty($products)): ?>

                        <tr>

                            <td
                                colspan="9"
                                class="text-center py-5">

                                No products found.

                            </td>

                        </tr>

                    <?php endif; ?>

                    <?php foreach($products as $product): ?>

                        <tr>

                            <td>

                                <?= $product['id'] ?>

                            </td>

                            <td>

                                <img
                                    src="<?= asset($product['image']) ?>"
                                    class="rounded"
                                    width="60"
                                    height="60"
                                    style="object-fit:cover;">

                            </td>

                            <td>

                                <strong>

                                    <?= e($product['name']) ?>

                                </strong>

                                <br>

                                <small class="text-muted">

                                    <?= e($product['short_description']) ?>

                                </small>

                            </td>

                            <td>

                                <?= e($product['category_name']) ?>

                            </td>

                            <td>

                                <?= e($product['sku']) ?>

                            </td>

                            <td>

                                <?php if($product['stock'] <= $product['minimum_stock']): ?>

                                    <span class="badge bg-danger">

                                        <?= $product['stock'] ?>

                                    </span>

                                <?php else: ?>

                                    <span class="badge bg-success">

                                        <?= $product['stock'] ?>

                                    </span>

                                <?php endif; ?>

                            </td>

                            <td>

                                <?php if($product['status']): ?>

                                    <span class="badge bg-success">

                                        Active

                                    </span>

                                <?php else: ?>

                                    <span class="badge bg-secondary">

                                        Inactive

                                    </span>

                                <?php endif; ?>

                            </td>

                            <td>

                                <?php if($product['featured']): ?>

                                    <span class="badge bg-warning text-dark">

                                        Featured

                                    </span>

                                <?php else: ?>

                                    —

                                <?php endif; ?>

                            </td>

                            <td>

                                <a
                                    href="<?= BASE_URL ?>/admin/products/edit/<?= $product['id'] ?>"
                                    class="btn btn-sm btn-primary">

                                    <i class="fa-solid fa-pen"></i>

                                </a>

                                <a
                                    href="<?= BASE_URL ?>/product/<?= $product['slug'] ?>"
                                    target="_blank"
                                    class="btn btn-sm btn-info text-white">

                                    <i class="fa-solid fa-eye"></i>

                                </a>

                                <button
                                    class="btn btn-sm btn-danger delete-product"
                                    data-id="<?= $product['id'] ?>">

                                    <i class="fa-solid fa-trash"></i>

                                </button>

                            </td>

                        </tr>

                    <?php endforeach; ?>

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>

<script>

document.querySelectorAll(".delete-product").forEach(function(button){

    button.addEventListener("click",function(){

        if(!confirm("Are you sure you want to delete this product?")){

            return;

        }

        window.location.href =
            "<?= BASE_URL ?>/admin/products/delete/" +
            this.dataset.id;

    });

});

</script>

<?php

require APP_PATH . '/Views/admin/layouts/footer.php';

?>