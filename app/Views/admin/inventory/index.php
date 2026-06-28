<?php

require APP_PATH . '/Views/admin/layouts/header.php';

?>

<div class="container-fluid py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h2 class="mb-1">

                Inventory Management

            </h2>

            <p class="text-muted mb-0">

                Monitor stock levels and inventory movements.

            </p>

        </div>

        <div>

            <a
                href="<?= BASE_URL ?>/admin/inventory/export"
                class="btn btn-success">

                <i class="fa-solid fa-file-csv"></i>

                Export CSV

            </a>

        </div>

    </div>

    <div class="row mb-4">

        <div class="col-lg-4">

            <div class="card border-0 shadow-sm">

                <div class="card-body text-center">

                    <h2 class="text-primary">

                        <?= number_format($statistics['products']) ?>

                    </h2>

                    <p class="mb-0">

                        Inventory Products

                    </p>

                </div>

            </div>

        </div>

        <div class="col-lg-4">

            <div class="card border-0 shadow-sm">

                <div class="card-body text-center">

                    <h2 class="text-danger">

                        <?= number_format($statistics['low_stock']) ?>

                    </h2>

                    <p class="mb-0">

                        Low Stock Items

                    </p>

                </div>

            </div>

        </div>

        <div class="col-lg-4">

            <div class="card border-0 shadow-sm">

                <div class="card-body text-center">

                    <h2 class="text-success">

                        <?= CURRENCY_SYMBOL ?>

                        <?= number_format($statistics['inventory_value'],2) ?>

                    </h2>

                    <p class="mb-0">

                        Inventory Value

                    </p>

                </div>

            </div>

        </div>

    </div>

    <?php if(!empty($lowStock)): ?>

    <div class="alert alert-warning">

        <h5 class="mb-3">

            <i class="fa-solid fa-triangle-exclamation"></i>

            Low Stock Alert

        </h5>

        <ul class="mb-0">

            <?php foreach($lowStock as $item): ?>

                <li>

                    <strong>

                        <?= e($item['name']) ?>

                    </strong>

                    -

                    <?= $item['quantity'] ?>

                    remaining

                    (Minimum

                    <?= $item['minimum_stock'] ?>

                    )

                </li>

            <?php endforeach; ?>

        </ul>

    </div>

    <?php endif; ?>

    <div class="card shadow-sm border-0">

        <div class="table-responsive">

            <table class="table table-hover align-middle mb-0">

                <thead class="table-light">

                    <tr>

                        <th>

                            Product

                        </th>

                        <th>

                            SKU

                        </th>

                        <th>

                            Category

                        </th>

                        <th>

                            Quantity

                        </th>

                        <th>

                            Status

                        </th>

                        <th width="320">

                            Actions

                        </th>

                    </tr>

                </thead>

                <tbody>

                <?php if(empty($inventory)): ?>

                    <tr>

                        <td
                            colspan="6"
                            class="text-center py-5">

                            No inventory records found.

                        </td>

                    </tr>

                <?php endif; ?>

                <?php foreach($inventory as $item): ?>

                    <tr>

                        <td>

                            <strong>

                                <?= e($item['product_name']) ?>

                            </strong>

                        </td>

                        <td>

                            <?= e($item['sku']) ?>

                        </td>

                        <td>

                            <?= e($item['category_name']) ?>

                        </td>

                        <td>

                            <?= number_format($item['quantity'],2) ?>

                        </td>

                        <td>

                            <?php

                            if($item['quantity']<=0){

                                echo '<span class="badge bg-danger">Out of Stock</span>';

                            }elseif(

                                isset($item['minimum_stock']) &&

                                $item['quantity']<=$item['minimum_stock']

                            ){

                                echo '<span class="badge bg-warning text-dark">Low Stock</span>';

                            }else{

                                echo '<span class="badge bg-success">In Stock</span>';

                            }

                            ?>

                        </td>

                        <td>

                            <div class="btn-group">

                                <a
                                    href="<?= BASE_URL ?>/admin/inventory/stock-in/<?= $item['product_id'] ?>"
                                    class="btn btn-sm btn-success">

                                    <i class="fa-solid fa-plus"></i>

                                </a>

                                <a
                                    href="<?= BASE_URL ?>/admin/inventory/stock-out/<?= $item['product_id'] ?>"
                                    class="btn btn-sm btn-warning">

                                    <i class="fa-solid fa-minus"></i>

                                </a>

                                <a
                                    href="<?= BASE_URL ?>/admin/inventory/adjust/<?= $item['product_id'] ?>"
                                    class="btn btn-sm btn-primary">

                                    <i class="fa-solid fa-sliders"></i>

                                </a>

                                <a
                                    href="<?= BASE_URL ?>/admin/inventory/history/<?= $item['product_id'] ?>"
                                    class="btn btn-sm btn-info">

                                    <i class="fa-solid fa-clock-rotate-left"></i>

                                </a>

                                <a
                                    href="<?= BASE_URL ?>/admin/inventory/delete/<?= $item['id'] ?>"
                                    class="btn btn-sm btn-danger"
                                    onclick="return confirm('Delete inventory record?')">

                                    <i class="fa-solid fa-trash"></i>

                                </a>

                            </div>

                        </td>

                    </tr>

                <?php endforeach; ?>

                </tbody>

            </table>

        </div>

    </div>

</div>

<?php

require APP_PATH . '/Views/admin/layouts/footer.php';

?>