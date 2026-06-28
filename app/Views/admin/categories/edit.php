<?php

require APP_PATH . '/Views/admin/layouts/header.php';

?>

<div class="container-fluid py-4">

    <form
        action="<?= BASE_URL ?>/admin/categories/update/<?= $category['id'] ?>"
        method="POST"
        enctype="multipart/form-data">

        <?= csrf_field() ?>

        <div class="d-flex justify-content-between align-items-center mb-4">

            <div>

                <h2 class="mb-1">

                    Edit Category

                </h2>

                <p class="text-muted mb-0">

                    Update category information.

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

                    Update Category

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
                                    value="<?= e($category['name']) ?>"
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
                                    value="<?= e($category['icon']) ?>"
                                    class="form-control">

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
                                    class="form-control"><?= e($category['description']) ?></textarea>

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

                    <div class="card-body">

                        <div class="row align-items-center">

                            <div class="col-md-5 text-center">

                                <img
                                    id="previewImage"
                                    src="<?= asset($category['image']) ?>"
                                    class="img-fluid rounded border"
                                    style="max-height:220px;">

                            </div>

                            <div class="col-md-7">

                                <label class="form-label">

                                    Change Image

                                </label>

                                <input
                                    id="imageInput"
                                    type="file"
                                    name="image"
                                    class="form-control">

                                <small class="text-muted d-block mt-2">

                                    Leave empty to keep the existing image.

                                </small>

                            </div>

                        </div>

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
                                value="<?= $category['sort_order'] ?>"
                                class="form-control">

                        </div>

                        <div>

                            <label class="form-label">

                                Status

                            </label>

                            <select
                                name="status"
                                class="form-select">

                                <option
                                    value="1"
                                    <?= $category['status'] ? 'selected' : '' ?>>

                                    Active

                                </option>

                                <option
                                    value="0"
                                    <?= !$category['status'] ? 'selected' : '' ?>>

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
                                value="<?= e($category['meta_title']) ?>"
                                class="form-control">

                        </div>

                        <div>

                            <label class="form-label">

                                Meta Description

                            </label>

                            <textarea
                                name="meta_description"
                                rows="4"
                                class="form-control"><?= e($category['meta_description']) ?></textarea>

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

    };

    reader.readAsDataURL(this.files[0]);

});

</script>

<?php

require APP_PATH . '/Views/admin/layouts/footer.php';

?>