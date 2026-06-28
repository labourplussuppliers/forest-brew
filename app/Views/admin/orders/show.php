<?php

require APP_PATH . '/Views/admin/layouts/header.php';

?>

<div class="container-fluid py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h2 class="mb-1">

                Order Details

            </h2>

            <p class="text-muted mb-0">

                Order #

                <strong>

                    <?= e($order['order_number']) ?>

                </strong>

            </p>

        </div>

        <div>

            <a
                href="<?= BASE_URL ?>/admin/orders"
                class="btn btn-outline-secondary">

                Back

            </a>

            <a
                href="<?= BASE_URL ?>/admin/orders/print/<?= $order['id'] ?>"
                target="_blank"
                class="btn btn-dark">

                <i class="fa-solid fa-print me-2"></i>

                Print Invoice

            </a>

        </div>

    </div>

    <div class="row">

        <div class="col-lg-8">

            <div class="card shadow-sm border-0 mb-4">

                <div class="card-header bg-white">

                    <h5 class="mb-0">

                        Ordered Items

                    </h5>

                </div>

                <div class="table-responsive">

                    <table class="table align-middle mb-0">

                        <thead>

                            <tr>

                                <th>Product</th>

                                <th>Variant</th>

                                <th>Qty</th>

                                <th>Price</th>

                                <th>Total</th>

                            </tr>

                        </thead>

                        <tbody>

                        <?php foreach($items as $item): ?>

                            <tr>

                                <td>

                                    <strong>

                                        <?= e($item['product_name']) ?>

                                    </strong>

                                    <br>

                                    <small class="text-muted">

                                        SKU :

                                        <?= e($item['sku']) ?>

                                    </small>

                                </td>

                                <td>

                                    <?= e($item['variant_name']) ?>

                                </td>

                                <td>

                                    <?= $item['quantity'] ?>

                                </td>

                                <td>

                                    <?= CURRENCY_SYMBOL ?>

                                    <?= number_format($item['unit_price']) ?>

                                </td>

                                <td>

                                    <strong>

                                        <?= CURRENCY_SYMBOL ?>

                                        <?= number_format($item['total_price']) ?>

                                    </strong>

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

                        Order Timeline

                    </h5>

                </div>

                <div class="card-body">

                    <?php if(empty($timeline)): ?>

                        <p class="text-muted mb-0">

                            No timeline available.

                        </p>

                    <?php endif; ?>

                    <?php foreach($timeline as $log): ?>

                        <div class="border-start border-3 ps-3 mb-4">

                            <strong>

                                <?= e($log['status']) ?>

                            </strong>

                            <br>

                            <small class="text-muted">

                                <?= date(
                                    'd M Y h:i A',
                                    strtotime($log['created_at'])
                                ) ?>

                            </small>

                            <?php if(!empty($log['notes'])): ?>

                                <p class="mb-0 mt-2">

                                    <?= e($log['notes']) ?>

                                </p>

                            <?php endif; ?>

                        </div>

                    <?php endforeach; ?>

                </div>

            </div>

        </div>

        <div class="col-lg-4">

            <div class="card shadow-sm border-0 mb-4">

                <div class="card-header bg-white">

                    <h5 class="mb-0">

                        Customer Information

                    </h5>

                </div>

                <div class="card-body">

                    <p>

                        <strong>Name</strong>

                        <br>

                        <?= e($customer['first_name']) ?>

                        <?= e($customer['last_name']) ?>

                    </p>

                    <p>

                        <strong>Email</strong>

                        <br>

                        <?= e($customer['email']) ?>

                    </p>

                    <p>

                        <strong>Phone</strong>

                        <br>

                        <?= e($customer['phone']) ?>

                    </p>

                    <p class="mb-0">

                        <strong>Delivery Address</strong>

                        <br>

                        <?= nl2br(
                            e($order['delivery_address'])
                        ) ?>

                    </p>

                </div>

            </div>

            <div class="card shadow-sm border-0 mb-4">

                <div class="card-header bg-white">

                    <h5 class="mb-0">

                        Order Summary

                    </h5>

                </div>

                <div class="card-body">

                    <div class="d-flex justify-content-between mb-2">

                        <span>

                            Subtotal

                        </span>

                        <strong>

                            <?= CURRENCY_SYMBOL ?>

                            <?= number_format($order['subtotal']) ?>

                        </strong>

                    </div>

                    <div class="d-flex justify-content-between mb-2">

                        <span>

                            Discount

                        </span>

                        <strong>

                            <?= CURRENCY_SYMBOL ?>

                            <?= number_format($order['discount']) ?>

                        </strong>

                    </div>

                    <div class="d-flex justify-content-between mb-2">

                        <span>

                            Delivery

                        </span>

                        <strong>

                            <?= CURRENCY_SYMBOL ?>

                            <?= number_format($order['delivery_charges']) ?>

                        </strong>

                    </div>

                    <div class="d-flex justify-content-between mb-2">

                        <span>

                            Tax

                        </span>

                        <strong>

                            <?= CURRENCY_SYMBOL ?>

                            <?= number_format($order['tax']) ?>

                        </strong>

                    </div>

                    <hr>

                    <div class="d-flex justify-content-between">

                        <h5>

                            Grand Total

                        </h5>

                        <h5 class="text-primary">

                            <?= CURRENCY_SYMBOL ?>

                            <?= number_format($order['grand_total']) ?>

                        </h5>

                    </div>

                </div>

            </div>

            <div class="card shadow-sm border-0">

                <div class="card-header bg-white">

                    <h5 class="mb-0">

                        Order Actions

                    </h5>

                </div>

                <div class="card-body">

                    <form
                        action="<?= BASE_URL ?>/admin/orders/status/<?= $order['id'] ?>"
                        method="POST"
                        class="mb-4">

                        <?= csrf_field() ?>

                        <label class="form-label">

                            Order Status

                        </label>

                        <select
                            name="status"
                            class="form-select mb-3">

                            <?php

                            $statuses = [

                                'Pending',

                                'Preparing',

                                'Ready',

                                'Out For Delivery',

                                'Completed',

                                'Cancelled'

                            ];

                            ?>

                            <?php foreach($statuses as $status): ?>

                                <option
                                    value="<?= $status ?>"
                                    <?= $order['order_status']===$status?'selected':'' ?>>

                                    <?= $status ?>

                                </option>

                            <?php endforeach; ?>

                        </select>

                        <button
                            class="btn btn-primary w-100">

                            Update Status

                        </button>

                    </form>

                    <form
                        action="<?= BASE_URL ?>/admin/orders/payment/<?= $order['id'] ?>"
                        method="POST">

                        <?= csrf_field() ?>

                        <label class="form-label">

                            Payment Status

                        </label>

                        <select
                            name="payment_status"
                            class="form-select mb-3">

                            <?php

                            $payments = [

                                'Pending',

                                'Paid',

                                'Failed',

                                'Refunded'

                            ];

                            ?>

                            <?php foreach($payments as $payment): ?>

                                <option
                                    value="<?= $payment ?>"
                                    <?= $order['payment_status']===$payment?'selected':'' ?>>

                                    <?= $payment ?>

                                </option>

                            <?php endforeach; ?>

                        </select>

                        <button
                            class="btn btn-success w-100">

                            Update Payment

                        </button>

                    </form>

                </div>

            </div>

        </div>

    </div>

</div>

<?php

require APP_PATH . '/Views/admin/layouts/footer.php';

?>