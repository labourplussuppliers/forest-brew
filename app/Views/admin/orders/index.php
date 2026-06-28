<?php

require APP_PATH . '/Views/admin/layouts/header.php';

?>

<div class="container-fluid py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h2 class="mb-1">

                Orders

            </h2>

            <p class="text-muted mb-0">

                Manage customer orders and track their progress.

            </p>

        </div>

        <div>

            <a
                href="<?= BASE_URL ?>/admin/orders/export/excel"
                class="btn btn-success">

                <i class="fa-solid fa-file-excel me-2"></i>

                Export Excel

            </a>

            <a
                href="<?= BASE_URL ?>/admin/orders/export/pdf"
                class="btn btn-danger">

                <i class="fa-solid fa-file-pdf me-2"></i>

                Export PDF

            </a>

        </div>

    </div>

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-body">

            <form method="GET">

                <div class="row">

                    <div class="col-lg-3 mb-3">

                        <input
                            type="text"
                            name="search"
                            class="form-control"
                            placeholder="Order # or Customer"
                            value="<?= e($_GET['search'] ?? '') ?>">

                    </div>

                    <div class="col-lg-2 mb-3">

                        <select
                            name="status"
                            class="form-select">

                            <option value="">Order Status</option>

                            <option value="Pending">Pending</option>

                            <option value="Preparing">Preparing</option>

                            <option value="Ready">Ready</option>

                            <option value="Completed">Completed</option>

                            <option value="Cancelled">Cancelled</option>

                        </select>

                    </div>

                    <div class="col-lg-2 mb-3">

                        <select
                            name="payment_status"
                            class="form-select">

                            <option value="">Payment</option>

                            <option value="Pending">Pending</option>

                            <option value="Paid">Paid</option>

                            <option value="Failed">Failed</option>

                        </select>

                    </div>

                    <div class="col-lg-2 mb-3">

                        <select
                            name="order_type"
                            class="form-select">

                            <option value="">Order Type</option>

                            <option value="Delivery">

                                Delivery

                            </option>

                            <option value="Pickup">

                                Pickup

                            </option>

                            <option value="Dine In">

                                Dine In

                            </option>

                        </select>

                    </div>

                    <div class="col-lg-3 mb-3">

                        <button
                            class="btn btn-dark w-100">

                            Search

                        </button>

                    </div>

                </div>

            </form>

        </div>

    </div>

    <div class="card border-0 shadow-sm">

        <div class="table-responsive">

            <table class="table table-hover align-middle mb-0">

                <thead class="table-light">

                    <tr>

                        <th>#</th>

                        <th>Order No</th>

                        <th>Customer</th>

                        <th>Type</th>

                        <th>Payment</th>

                        <th>Status</th>

                        <th>Total</th>

                        <th>Date</th>

                        <th width="220">

                            Actions

                        </th>

                    </tr>

                </thead>

                <tbody>

                <?php if(empty($orders)): ?>

                    <tr>

                        <td
                            colspan="9"
                            class="text-center py-5">

                            No orders found.

                        </td>

                    </tr>

                <?php endif; ?>

                <?php foreach($orders as $order): ?>

                    <tr>

                        <td>

                            <?= $order['id'] ?>

                        </td>

                        <td>

                            <strong>

                                <?= e($order['order_number']) ?>

                            </strong>

                        </td>

                        <td>

                            <?= e($order['customer_name']) ?>

                        </td>

                        <td>

                            <span class="badge bg-info">

                                <?= e($order['order_type']) ?>

                            </span>

                        </td>

                        <td>

                            <?php if($order['payment_status']=="Paid"): ?>

                                <span class="badge bg-success">

                                    Paid

                                </span>

                            <?php elseif($order['payment_status']=="Pending"): ?>

                                <span class="badge bg-warning text-dark">

                                    Pending

                                </span>

                            <?php else: ?>

                                <span class="badge bg-danger">

                                    Failed

                                </span>

                            <?php endif; ?>

                        </td>

                        <td>

                            <?php

                            $badge='secondary';

                            switch($order['order_status']){

                                case 'Pending':

                                    $badge='warning';

                                    break;

                                case 'Preparing':

                                    $badge='primary';

                                    break;

                                case 'Ready':

                                    $badge='info';

                                    break;

                                case 'Completed':

                                    $badge='success';

                                    break;

                                case 'Cancelled':

                                    $badge='danger';

                                    break;

                            }

                            ?>

                            <span class="badge bg-<?= $badge ?>">

                                <?= e($order['order_status']) ?>

                            </span>

                        </td>

                        <td>

                            <?= CURRENCY_SYMBOL ?>

                            <?= number_format($order['grand_total']) ?>

                        </td>

                        <td>

                            <?= date(
                                'd M Y h:i A',
                                strtotime($order['created_at'])
                            ) ?>

                        </td>

                        <td>

                            <a
                                href="<?= BASE_URL ?>/admin/orders/<?= $order['id'] ?>"
                                class="btn btn-sm btn-primary">

                                <i class="fa-solid fa-eye"></i>

                            </a>

                            <a
                                href="<?= BASE_URL ?>/admin/orders/print/<?= $order['id'] ?>"
                                target="_blank"
                                class="btn btn-sm btn-secondary">

                                <i class="fa-solid fa-print"></i>

                            </a>

                            <?php if($order['order_status']!="Cancelled"): ?>

                            <button
                                class="btn btn-sm btn-warning cancel-order"
                                data-id="<?= $order['id'] ?>">

                                <i class="fa-solid fa-ban"></i>

                            </button>

                            <?php endif; ?>

                            <button
                                class="btn btn-sm btn-danger delete-order"
                                data-id="<?= $order['id'] ?>">

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

<script>

document.querySelectorAll(".cancel-order").forEach(function(button){

    button.addEventListener("click",function(){

        if(!confirm("Cancel this order?")){

            return;

        }

        window.location.href=
            "<?= BASE_URL ?>/admin/orders/cancel/"+
            this.dataset.id;

    });

});

document.querySelectorAll(".delete-order").forEach(function(button){

    button.addEventListener("click",function(){

        if(!confirm("Delete this order permanently?")){

            return;

        }

        window.location.href=
            "<?= BASE_URL ?>/admin/orders/delete/"+
            this.dataset.id;

    });

});

</script>

<?php

require APP_PATH . '/Views/admin/layouts/footer.php';

?>