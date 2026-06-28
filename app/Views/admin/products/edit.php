<?php

require APP_PATH . '/Views/admin/layouts/header.php';

?>

<div class="container-fluid py-4">

    <form
        action="<?= BASE_URL ?>/admin/products/update/<?= $product['id'] ?>"
        method="POST"
        enctype="multipart/form-data">

        <?= csrf_field() ?>

        <div class="d-flex justify-content-between align-items-center mb-4">

            <div>

                <h2 class="mb-1">

                    Edit Product

                </h2>

                <p class="text-muted mb-0">

                    Update product information.

                </p>

            </div>

            <div>

                <a
                    href="<?= BASE_URL ?>/admin/products"
                    class="btn btn-outline-secondary">

                    Back

                </a>

                <button
                    class="btn btn-primary">

                    Update Product

                </button>

            </div>

        </div>

        <div class="row">

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
                                    value="<?= e($product['name']) ?>"
                                    required>

                            </div>

                            <div class="col-md-6 mb-3">

                                <label class="form-label">

                                    SKU

                                </label>

                                <input
                                    type="text"
                                    name="sku"
                                    class="form-control"
                                    value="<?= e($product['sku']) ?>">

                            </div>

                            <div class="col-md-6 mb-3">

                                <label class="form-label">

                                    Category

                                </label>

                                <select
                                    name="category_id"
                                    class="form-select">

                                    <?php foreach($categories as $category): ?>

                                    <option
                                        value="<?= $category['id'] ?>"
                                        <?= $product['category_id']==$category['id']?'selected':'' ?>>

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
                                    value="<?= e($product['barcode']) ?>"
                                    class="form-control">

                            </div>

                            <div class="col-12 mb-3">

                                <label class="form-label">

                                    Short Description

                                </label>

                                <textarea
                                    name="short_description"
                                    rows="2"
                                    class="form-control"><?= e($product['short_description']) ?></textarea>

                            </div>

                            <div class="col-12">

                                <label class="form-label">

                                    Description

                                </label>

                                <textarea
                                    name="description"
                                    rows="6"
                                    class="form-control"><?= e($product['description']) ?></textarea>

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

                        <table class="table align-middle">

                            <thead>

                                <tr>

                                    <th>Size</th>

                                    <th>Price</th>

                                </tr>

                            </thead>

                            <tbody>

                                <?php foreach($variants as $variant): ?>

                                <tr>

                                    <td>

                                        <input
                                            type="hidden"
                                            name="variant_id[]"
                                            value="<?= $variant['id'] ?>">

                                        <?= e($variant['size_name']) ?>

                                    </td>

                                    <td>

                                        <input
                                            type="number"
                                            step="0.01"
                                            name="price[]"
                                            value="<?= $variant['price'] ?>"
                                            class="form-control">

                                    </td>

                                </tr>

                                <?php endforeach; ?>

                            </tbody>

                        </table>

                    </div>

                </div>

                <div class="card shadow-sm border-0">

                    <div class="card-header bg-white">

                        <h5 class="mb-0">

                            Product Gallery

                        </h5>

                    </div>

                    <div class="card-body">

                        <div class="row">

                            <?php foreach($gallery as $image): ?>

                            <div class="col-md-3 mb-3">

                                <div class="border rounded p-2 text-center">

                                    <img
                                        src="<?= asset($image['image']) ?>"
                                        class="img-fluid rounded mb-2">

                                    <button
                                        type="button"
                                        class="btn btn-sm btn-danger delete-gallery"
                                        data-id="<?= $image['id'] ?>">

                                        Remove

                                    </button>

                                </div>

                            </div>

                            <?php endforeach; ?>

                        </div>

                        <hr>

                        <label class="form-label">

                            Upload New Images

                        </label>

                        <input
                            type="file"
                            name="gallery[]"
                            multiple
                            class="form-control">

                    </div>

                </div>

            </div>

            <div class="col-lg-4">

                <div class="card shadow-sm border-0 mb-4">

                    <div class="card-header bg-white">

                        <h5 class="mb-0">

                            Product Image

                        </h5>

                    </div>

                    <div class="card-body text-center">

                        <img
                            id="previewImage"
                            src="<?= asset($product['image']) ?>"
                            class="img-fluid rounded border mb-3"
                            style="max-height:220px;">

                        <input
                            id="imageInput"
                            type="file"
                            name="image"
                            class="form-control">

                    </div>

                </div>

                <div class="card shadow-sm border-0 mb-4">

                    <div class="card-header bg-white">

                        <h5 class="mb-0">

                            Inventory

                        </h5>

                    </div>

                    <div class="card-body">

                        <div class="mb-3">

                            <label class="form-label">

                                Stock

                            </label>

                            <input
                                type="number"
                                name="stock"
                                value="<?= $product['stock'] ?>"
                                class="form-control">

                        </div>

                        <div>

                            <label class="form-label">

                                Minimum Stock

                            </label>

                            <input
                                type="number"
                                name="minimum_stock"
                                value="<?= $product['minimum_stock'] ?>"
                                class="form-control">

                        </div>

                    </div>

                </div>

                <div class="card shadow-sm border-0 mb-4">

                    <div class="card-header bg-white">

                        <h5 class="mb-0">

                            Product Status

                        </h5>

                    </div>

                    <div class="card-body">

                        <div class="form-check mb-3">

                            <input
                                type="checkbox"
                                name="featured"
                                value="1"
                                class="form-check-input"
                                <?= $product['featured']?'checked':'' ?>>

                            <label class="form-check-label">

                                Featured Product

                            </label>

                        </div>

                        <div class="form-check mb-3">

                            <input
                                type="checkbox"
                                name="popular"
                                value="1"
                                class="form-check-input"
                                <?= $product['popular']?'checked':'' ?>>

                            <label class="form-check-label">

                                Popular Product

                            </label>

                        </div>

                        <label class="form-label">

                            Status

                        </label>

                        <select
                            name="status"
                            class="form-select">

                            <option
                                value="1"
                                <?= $product['status']?'selected':'' ?>>

                                Active

                            </option>

                            <option
                                value="0"
                                <?= !$product['status']?'selected':'' ?>>

                                Inactive

                            </option>

                        </select>

                    </div>

                </div>

                <div class="card shadow-sm border-0">

                    <div class="card-header bg-white">

                        <h5 class="mb-0">

                            SEO

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
                                value="<?= e($product['meta_title']) ?>"
                                class="form-control">

                        </div>

                        <div>

                            <label class="form-label">

                                Meta Description

                            </label>

                            <textarea
                                name="meta_description"
                                rows="4"
                                class="form-control"><?= e($product['meta_description']) ?></textarea>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </form>

</div>

<script>

document.getElementById("imageInput").addEventListener("change",function(){

    if(!this.files.length){

        return;

    }

    const reader=new FileReader();

    reader.onload=function(e){

        document.getElementById("previewImage").src=e.target.result;

    }

    reader.readAsDataURL(this.files[0]);

});

document.querySelectorAll(".delete-gallery").forEach(function(button){

    button.addEventListener("click",function(){

        if(!confirm("Delete this image?")){

            return;

        }

        window.location.href=
            "<?= BASE_URL ?>/admin/products/gallery/delete/"+this.dataset.id;

    });

});

</script>

<?php

require APP_PATH . '/Views/admin/layouts/footer.php';

?>