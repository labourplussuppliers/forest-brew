<?php

require APP_PATH . '/Views/admin/layouts/header.php';

?>

<div class="container-fluid py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h2 class="mb-1">

                Categories

            </h2>

            <p class="text-muted mb-0">

                Manage your product categories.

            </p>

        </div>

        <div>

            <a
                href="<?= BASE_URL ?>/admin/categories/create"
                class="btn btn-primary">

                <i class="fa-solid fa-plus me-2"></i>

                Add Category

            </a>

        </div>

    </div>

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-body">

            <form method="GET">

                <div class="row">

                    <div class="col-lg-5 mb-3">

                        <input
                            type="text"
                            name="search"
                            class="form-control"
                            placeholder="Search category..."
                            value="<?= e($_GET['search'] ?? '') ?>">

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
                                <?= (($_GET['status'] ?? '')=='1')?'selected':'' ?>>

                                Active

                            </option>

                            <option
                                value="0"
                                <?= (($_GET['status'] ?? '')=='0')?'selected':'' ?>>

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

                    <div class="col-lg-2 mb-3 d-grid">

                        <a
                            href="<?= BASE_URL ?>/admin/categories"
                            class="btn btn-outline-secondary">

                            Reset

                        </a>

                    </div>

                </div>

            </form>

        </div>

    </div>

    <div class="card border-0 shadow-sm">

        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light">

                        <tr>

                            <th width="60">

                                #

                            </th>

                            <th width="90">

                                Image

                            </th>

                            <th>

                                Category

                            </th>

                            <th>

                                Icon

                            </th>

                            <th>

                                Products

                            </th>

                            <th>

                                Sort Order

                            </th>

                            <th>

                                Status

                            </th>

                            <th width="220">

                                Actions

                            </th>

                        </tr>

                    </thead>

                    <tbody>

                    <?php if(empty($categories)): ?>

                        <tr>

                            <td
                                colspan="8"
                                class="text-center py-5">

                                No categories found.

                            </td>

                        </tr>

                    <?php endif; ?>

                    <?php foreach($categories as $category): ?>

                    <tr>

                        <td>

                            <?= $category['id'] ?>

                        </td>

                        <td>

                            <?php if(!empty($category['image'])): ?>

                                <img
                                    src="<?= asset($category['image']) ?>"
                                    width="60"
                                    height="60"
                                    class="rounded"
                                    style="object-fit:cover;">

                            <?php else: ?>

                                <div
                                    class="bg-light rounded d-flex justify-content-center align-items-center"
                                    style="width:60px;height:60px;">

                                    <i class="fa-solid fa-image text-muted"></i>

                                </div>

                            <?php endif; ?>

                        </td>

                        <td>

                            <strong>

                                <?= e($category['name']) ?>

                            </strong>

                            <br>

                            <small class="text-muted">

                                <?= e($category['slug']) ?>

                            </small>

                        </td>

                        <td>

                            <?php if($category['icon']): ?>

                                <i class="<?= e($category['icon']) ?> fs-4"></i>

                            <?php else: ?>

                                —

                            <?php endif; ?>

                        </td>

                        <td>

                            <span class="badge bg-info">

                                <?= $category['products_count'] ?? 0 ?>

                            </span>

                        </td>

                        <td>

                            <?= $category['sort_order'] ?>

                        </td>

                        <td>

                            <?php if($category['status']): ?>

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

                            <a
                                href="<?= BASE_URL ?>/admin/categories/status/<?= $category['id'] ?>"
                                class="btn btn-sm btn-warning">

                                <i class="fa-solid fa-power-off"></i>

                            </a>

                            <a
                                href="<?= BASE_URL ?>/admin/categories/edit/<?= $category['id'] ?>"
                                class="btn btn-sm btn-primary">

                                <i class="fa-solid fa-pen"></i>

                            </a>

                            <button
                                type="button"
                                class="btn btn-sm btn-danger delete-category"
                                data-id="<?= $category['id'] ?>">

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

document.querySelectorAll(".delete-category").forEach(function(button){

    button.addEventListener("click",function(){

        if(!confirm("Are you sure you want to delete this category?")){

            return;

        }

        window.location.href =
            "<?= BASE_URL ?>/admin/categories/delete/" +
            this.dataset.id;

    });

});

</script>

<?php

require APP_PATH . '/Views/admin/layouts/footer.php';

?>