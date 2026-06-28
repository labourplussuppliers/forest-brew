<?php

require APP_PATH . '/Views/admin/layouts/header.php';

?>

<div class="container-fluid py-4">

    <form
        action="<?= BASE_URL ?>/admin/products/store"
        method="POST"
        enctype="multipart/form-data">

        <?= csrf_field() ?>

        <div class="d-flex justify-content-between align-items-center mb-4">

            <div>

                <h2 class="mb-1">

                    Add New Product

                </h2>

                <p class="text-muted mb-0">

                    Create a new menu item for Frost & Brew.

                </p>

            </div>

            <div>

                <a
                    href="<?= BASE_URL ?>/admin/products"
                    class="btn btn-outline-secondary">

                    Back

                </a>

                <button
                    type="submit"
                    class="btn btn-primary">

                    Save Product

                </button>

            </div>

        </div>

        <div class="row">

            <!-- Left Side -->

            <div class="col-lg-8">

                <div class="card shadow-sm border-0 mb-4">

                    <div class="card-header bg-white">

                        <h5 class="mb-0">

                            Product Information

                        </h5>

                    </div>

                    <div class="card-body">

                        <div class="row">

                            <div class="col-md-6 mb-3">

                                <label class="form-label">

                                    Product Name

                                </label>

                                <input
                                    type="text"
                                    name="name"
                                    class="form-control"
                                    required>

                            </div>

                            <div class="col-md-6 mb-3">

                                <label class="form-label">

                                    SKU

                                </label>

                                <input
                                    type="text"
                                    name="sku"
                                    class="form-control">

                            </div>

                            <div class="col-md-6 mb-3">

                                <label class="form-label">

                                    Category

                                </label>

                                <select
                                    name="category_id"
                                    class="form-select"
                                    required>

                                    <option value="">

                                        Select Category

                                    </option>

                                    <?php foreach($categories as $category): ?>

                                        <option value="<?= $category['id'] ?>">

                                            <?= e($category['name']) ?>

                                        </option>

                                    <?php endforeach; ?>

                                </select>

                            </div>

                            <div class="col-md-6 mb-3">

                                <label class="form-label">

                                    Barcode

                                </label>

                                <input
                                    type="text"
                                    name="barcode"
                                    class="form-control">

                            </div>

                            <div class="col-12 mb-3">

                                <label class="form-label">

                                    Short Description

                                </label>

                                <textarea
                                    name="short_description"
                                    rows="2"
                                    class="form-control"></textarea>

                            </div>

                            <div class="col-12">

                                <label class="form-label">

                                    Description

                                </label>

                                <textarea
                                    name="description"
                                    rows="6"
                                    class="form-control"></textarea>

                            </div>

                        </div>

                    </div>

                </div>

                <div class="card shadow-sm border-0 mb-4">

                    <div class="card-header bg-white">

                        <h5 class="mb-0">

                            Product Variants

                        </h5>

                    </div>

                    <div class="card-body">

                        <table class="table">

                            <thead>

                                <tr>

                                    <th>Size</th>

                                    <th>Price</th>

                                </tr>

                            </thead>

                            <tbody>

                                <tr>

                                    <td>

                                        <input
                                            type="hidden"
                                            name="size[]"
                                            value="1">

                                        Small

                                    </td>

                                    <td>

                                        <input
                                            type="number"
                                            name="price[]"
                                            class="form-control">

                                    </td>

                                </tr>

                                <tr>

                                    <td>

                                        <input
                                            type="hidden"
                                            name="size[]"
                                            value="2">

                                        Medium

                                    </td>

                                    <td>

                                        <input
                                            type="number"
                                            name="price[]"
                                            class="form-control">

                                    </td>

                                </tr>

                                <tr>

                                    <td>

                                        <input
                                            type="hidden"
                                            name="size[]"
                                            value="3">

                                        Large

                                    </td>

                                    <td>

                                        <input
                                            type="number"
                                            name="price[]"
                                            class="form-control">

                                    </td>

                                </tr>

                            </tbody>

                        </table>

                    </div>

                </div>

                <div class="card shadow-sm border-0">

                    <div class="card-header bg-white">

                        <h5 class="mb-0">

                            Product Image

                        </h5>

                    </div>

                    <div class="card-body">

                        <input
                            type="file"
                            name="image"
                            id="imageInput"
                            class="form-control">

                        <div class="mt-3">

                            <img
                                id="previewImage"
                                src="<?= asset('images/no-image.png') ?>"
                                class="img-thumbnail"
                                style="max-width:220px;">

                        </div>

                    </div>

                </div>

            </div>

            <!-- Right Side -->

            <div class="col-lg-4">

                <div class="card shadow-sm border-0 mb-4">

                    <div class="card-header bg-white">

                        <h5 class="mb-0">

                            Inventory

                        </h5>

                    </div>

                    <div class="card-body">

                        <div class="mb-3">

                            <label class="form-label">

                                Stock Quantity

                            </label>

                            <input
                                type="number"
                                name="stock"
                                class="form-control"
                                value="0">

                        </div>

                        <div class="mb-3">

                            <label class="form-label">

                                Minimum Stock

                            </label>

                            <input
                                type="number"
                                name="minimum_stock"
                                class="form-control"
                                value="5">

                        </div>

                    </div>

                </div>

                <div class="card shadow-sm border-0 mb-4">

                    <div class="card-header bg-white">

                        <h5 class="mb-0">

                            Product Options

                        </h5>

                    </div>

                    <div class="card-body">

                        <div class="form-check mb-3">

                            <input
                                class="form-check-input"
                                type="checkbox"
                                name="featured"
                                value="1">

                            <label class="form-check-label">

                                Featured Product

                            </label>

                        </div>

                        <div class="form-check mb-3">

                            <input
                                class="form-check-input"
                                type="checkbox"
                                name="popular"
                                value="1">

                            <label class="form-check-label">

                                Popular Product

                            </label>

                        </div>

                        <div>

                            <label class="form-label">

                                Status

                            </label>

                            <select
                                name="status"
                                class="form-select">

                                <option value="1">

                                    Active

                                </option>

                                <option value="0">

                                    Inactive

                                </option>

                            </select>

                        </div>

                    </div>

                </div>

                <div class="card shadow-sm border-0">

                    <div class="card-header bg-white">

                        <h5 class="mb-0">

                            SEO Information

                        </h5>

                    </div>

                    <div class="card-body">

                        <div class="mb-3">

                            <label class="form-label">

                                Meta Title

                            </label>

                            <input
                                type="text"
                                name="meta_title"
                                class="form-control">

                        </div>

                        <div>

                            <label class="form-label">

                                Meta Description

                            </label>

                            <textarea
                                name="meta_description"
                                rows="4"
                                class="form-control"></textarea>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </form>

</div>

<script>

document.getElementById("imageInput").addEventListener("change", function(){

    const file = this.files[0];

    if(!file){
        return;
    }

    const reader = new FileReader();

    reader.onload = function(e){

        document.getElementById("previewImage").src = e.target.result;

    };

    reader.readAsDataURL(file);

});

</script>

<?php

require APP_PATH . '/Views/admin/layouts/footer.php';

?>