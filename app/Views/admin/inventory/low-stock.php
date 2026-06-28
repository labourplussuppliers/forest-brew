<?php

require APP_PATH . '/Views/admin/layouts/header.php';

?>

<div class="container-fluid py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h2 class="mb-1">

                Low Stock Report

            </h2>

            <p class="text-muted mb-0">

                Products that require immediate restocking.

            </p>

        </div>

        <div class="d-flex gap-2">

            <a
                href="<?= BASE_URL ?>/admin/inventory/export"
                class="btn btn-success">

                <i class="fa-solid fa-file-csv"></i>

                Export CSV

            </a>

            <button
                type="button"
                onclick="window.print()"
                class="btn btn-outline-primary">

                <i class="fa-solid fa-print"></i>

                Print

            </button>

        </div>

    </div>

    <div class="card shadow-sm border-0 mb-4">

        <div class="card-body">

            <div class="row text-center">

                <div class="col-md-4">

                    <h2 class="text-danger">

                        <?= count($items) ?>

                    </h2>

                    <p class="mb-0">

                        Low Stock Products

                    </p>

                </div>

                <div class="col-md-4">

                    <h2 class="text-warning">

                        <?= array_sum(
                            array_column(
                                $items,
                                'quantity'
                            )
                        ) ?>

                    </h2>

                    <p class="mb-0">

                        Available Units

                    </p>

                </div>

                <div class="col-md-4">

                    <h2 class="text-primary">

                        <?= array_sum(
                            array_map(
                                fn($item) => max(
                                    0,
                                    $item['minimum_stock'] - $item['quantity']
                                ),
                                $items
                            )
                        ) ?>

                    </h2>

                    <p class="mb-0">

                        Units Required

                    </p>

                </div>

            </div>

        </div>

    </div>

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

                            Current Stock

                        </th>

                        <th>

                            Minimum Stock

                        </th>

                        <th>

                            Shortage

                        </th>

                        <th>

                            Status

                        </th>

                        <th width="140">

                            Action

                        </th>

                    </tr>

                </thead>

                <tbody>

                <?php if(empty($items)): ?>

                    <tr>

                        <td
                            colspan="8"
                            class="text-center py-5">

                            <i class="fa-solid fa-circle-check fa-3x text-success mb-3"></i>

                            <h5>

                                Great!

                            </h5>

                            <p class="text-muted mb-0">

                                No low stock items found.

                            </p>

                        </td>

                    </tr>

                <?php endif; ?>

                <?php foreach($items as $item): ?>

                    <?php

                    $shortage = max(

                        0,

                        $item['minimum_stock'] -

                        $item['quantity']

                    );

                    ?>

                    <tr>

                        <td>

                            <strong>

                                <?= e($item['name']) ?>

                            </strong>

                        </td>

                        <td>

                            <?= e($item['sku']) ?>

                        </td>

                        <td>

                            <?= e($item['category_name']) ?>

                        </td>

                        <td>

                            <?= number_format(

                                $item['quantity'],

                                2

                            ) ?>

                        </td>

                        <td>

                            <?= number_format(

                                $item['minimum_stock'],

                                2

                            ) ?>

                        </td>

                        <td>

                            <span class="fw-bold text-danger">

                                <?= number_format(

                                    $shortage,

                                    2

                                ) ?>

                            </span>

                        </td>

                        <td>

                            <?php if($item['quantity']<=0): ?>

                                <span class="badge bg-danger">

                                    Out of Stock

                                </span>

                            <?php else: ?>

                                <span class="badge bg-warning text-dark">

                                    Low Stock

                                </span>

                            <?php endif; ?>

                        </td>

                        <td>

                            <a
                                href="<?= BASE_URL ?>/admin/inventory/stock-in/<?= $item['product_id'] ?>"
                                class="btn btn-sm btn-success">

                                <i class="fa-solid fa-plus"></i>

                                Restock

                            </a>

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