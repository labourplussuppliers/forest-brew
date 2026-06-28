<?php

require APP_PATH . '/Views/admin/layouts/header.php';

?>

<div class="container-fluid py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h2 class="mb-1">

                Inventory History

            </h2>

            <p class="text-muted mb-0">

                Complete stock movement history for the selected product.

            </p>

        </div>

        <a
            href="<?= BASE_URL ?>/admin/inventory"
            class="btn btn-outline-secondary">

            Back

        </a>

    </div>

    <div class="card shadow-sm border-0 mb-4">

        <div class="card-body">

            <div class="row">

                <div class="col-md-4">

                    <strong>

                        Product

                    </strong>

                    <br>

                    <?= e($product['name']) ?>

                </div>

                <div class="col-md-4">

                    <strong>

                        SKU

                    </strong>

                    <br>

                    <?= e($product['sku']) ?>

                </div>

                <div class="col-md-4">

                    <strong>

                        Current Stock

                    </strong>

                    <br>

                    <?= number_format($product['stock'],2) ?>

                </div>

            </div>

        </div>

    </div>

    <?php if(empty($history)): ?>

        <div class="card shadow-sm border-0">

            <div class="card-body text-center py-5">

                <i class="fa-solid fa-clock-rotate-left fa-3x text-muted mb-3"></i>

                <h5>

                    No inventory history found.

                </h5>

                <p class="text-muted mb-0">

                    No stock movements have been recorded yet.

                </p>

            </div>

        </div>

    <?php else: ?>

        <div class="card shadow-sm border-0">

            <div class="card-header bg-white">

                <h5 class="mb-0">

                    Stock Movement Timeline

                </h5>

            </div>

            <div class="card-body">

                <div class="timeline">

                <?php foreach($history as $record): ?>

                    <?php

                    switch($record['transaction_type']){

                        case 'Stock In':

                            $badge='success';
                            $icon='fa-plus';

                            break;

                        case 'Stock Out':

                            $badge='danger';
                            $icon='fa-minus';

                            break;

                        case 'Adjustment':

                            $badge='primary';
                            $icon='fa-arrows-rotate';

                            break;

                        default:

                            $badge='secondary';
                            $icon='fa-box';

                    }

                    ?>

                    <div class="border-start border-3 border-<?= $badge ?> ps-4 ms-3 mb-4 position-relative">

                        <span
                            class="position-absolute top-0 start-0 translate-middle badge rounded-pill bg-<?= $badge ?>"
                            style="width:36px;height:36px;display:flex;align-items:center;justify-content:center;">

                            <i class="fa-solid <?= $icon ?>"></i>

                        </span>

                        <div class="card border-0 bg-light">

                            <div class="card-body">

                                <div class="d-flex justify-content-between align-items-start mb-3">

                                    <div>

                                        <h5 class="mb-1">

                                            <?= e($record['transaction_type']) ?>

                                        </h5>

                                        <small class="text-muted">

                                            <?= date(

                                                'd M Y h:i A',

                                                strtotime($record['created_at'])

                                            ) ?>

                                        </small>

                                    </div>

                                    <span class="badge bg-<?= $badge ?>">

                                        <?= e($record['transaction_type']) ?>

                                    </span>

                                </div>

                                <div class="row">

                                    <div class="col-md-3">

                                        <strong>

                                            Previous Stock

                                        </strong>

                                        <br>

                                        <?= number_format(

                                            $record['previous_stock'],

                                            2

                                        ) ?>

                                    </div>

                                    <div class="col-md-3">

                                        <strong>

                                            Quantity

                                        </strong>

                                        <br>

                                        <?= number_format(

                                            $record['quantity'],

                                            2

                                        ) ?>

                                    </div>

                                    <div class="col-md-3">

                                        <strong>

                                            Current Stock

                                        </strong>

                                        <br>

                                        <?= number_format(

                                            $record['current_stock'],

                                            2

                                        ) ?>

                                    </div>

                                    <div class="col-md-3">

                                        <strong>

                                            Staff

                                        </strong>

                                        <br>

                                        <?= !empty($record['staff_name'])
                                            ? e($record['staff_name'])
                                            : 'System'
                                        ?>

                                    </div>

                                </div>

                                <?php if(!empty($record['reference'])): ?>

                                    <hr>

                                    <strong>

                                        Reference

                                    </strong>

                                    <br>

                                    <?= e($record['reference']) ?>

                                <?php endif; ?>

                                <?php if(!empty($record['remarks'])): ?>

                                    <hr>

                                    <strong>

                                        Remarks

                                    </strong>

                                    <br>

                                    <?= nl2br(

                                        e($record['remarks'])

                                    ) ?>

                                <?php endif; ?>

                            </div>

                        </div>

                    </div>

                <?php endforeach; ?>

                </div>

            </div>

        </div>

    <?php endif; ?>

</div>

<?php

require APP_PATH . '/Views/admin/layouts/footer.php';

?>