<?php

require APP_PATH . '/Views/admin/layouts/header.php';

?>

<div class="container-fluid py-4">

    <form
        action="<?= BASE_URL ?>/admin/categories/store"
        method="POST"
        enctype="multipart/form-data">

        <?= csrf_field() ?>

        <div class="d-flex justify-content-between align-items-center mb-4">

            <div>

                <h2 class="mb-1">

                    Add Category

                </h2>

                <p class="text-muted mb-0">

                    Create a new category for your menu.

                </p>

            </div>

            <div>

                <a
                    href="<?= BASE_URL ?>/admin/categories"
                    class="btn btn-outline-secondary">

                    Back

                </a>

                <button
                    type="submit"
                    class="btn btn-primary">

                    Save Category

                </button>

            </div>

        </div>

        <div class="row">

            <div class="col-lg-8">

                <div class="card shadow-sm border-0 mb-4">

                    <div class="card-header bg-white">

                        <h5 class="mb-0">

                            Category Information

                        </h5>

                    </div>

                    <div class="card-body">

                        <div class="row">

                            <div class="col-md-6 mb-3">

                                <label class="form-label">

                                    Category Name

                                </label>

                                <input
                                    type="text"
                                    name="name"
                                    class="form-control"
                                    required>

                            </div>

                            <div class="col-md-6 mb-3">

                                <label class="form-label">

                                    Font Awesome Icon

                                </label>

                                <input
                                    type="text"
                                    name="icon"
                                    class="form-control"
                                    placeholder="fa-solid fa-mug-hot">

                                <small class="text-muted">

                                    Example: fa-solid fa-mug-hot

                                </small>

                            </div>

                            <div class="col-12 mb-3">

                                <label class="form-label">

                                    Description

                                </label>

                                <textarea
                                    name="description"
                                    rows="5"
                                    class="form-control"></textarea>

                            </div>

                        </div>

                    </div>

                </div>

                <div class="card shadow-sm border-0">

                    <div class="card-header bg-white">

                        <h5 class="mb-0">

                            Category Image

                        </h5>

                    </div>

                    <div class="card-body text-center">

                        <img
                            id="previewImage"
                            src="<?= asset('images/no-image.png') ?>"
                            class="img-thumbnail mb-3"
                            style="max-width:220px;">

                        <input
                            type="file"
                            id="imageInput"
                            name="image"
                            class="form-control">

                    </div>

                </div>

            </div>

            <div class="col-lg-4">

                <div class="card shadow-sm border-0 mb-4">

                    <div class="card-header bg-white">

                        <h5 class="mb-0">

                            Display Settings

                        </h5>

                    </div>

                    <div class="card-body">

                        <div class="mb-3">

                            <label class="form-label">

                                Sort Order

                            </label>

                            <input
                                type="number"
                                name="sort_order"
                                value="0"
                                class="form-control">

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

    if(!this.files.length){

        return;

    }

    const reader = new FileReader();

    reader.onload = function(e){

        document.getElementById("previewImage").src = e.target.result;

    };

    reader.readAsDataURL(this.files[0]);

});

</script>

<?php

require APP_PATH . '/Views/admin/layouts/footer.php';

?>