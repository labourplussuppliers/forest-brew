<?php

require APP_PATH . '/Views/admin/layouts/header.php';

?>

<div class="container-fluid py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h2 class="mb-1">

                Customer Orders

            </h2>

            <p class="text-muted mb-0">

                <?= e($customer['first_name']) ?>

                <?= e($customer['last_name']) ?>

                Order History

            </p>

        </div>

        <a
            href="<?= BASE_URL ?>/admin/customers/<?= $customer['id'] ?>"
            class="btn btn-outline-secondary">

            Back to Profile

        </a>

    </div>

    <div class="card shadow-sm border-0">

        <div class="table-responsive">

            <table class="table table-hover align-middle mb-0">

                <thead class="table-light">

                    <tr>

                        <th>

                            Order #

                        </th>

                        <th>

                            Date

                        </th>

                        <th>

                            Type

                        </th>

                        <th>

                            Payment Method

                        </th>

                        <th>

                            Payment

                        </th>

                        <th>

                            Status

                        </th>

                        <th>

                            Total

                        </th>

                        <th width="180">

                            Actions

                        </th>

                    </tr>

                </thead>

                <tbody>

                <?php if(empty($orders)): ?>

                    <tr>

                        <td
                            colspan="8"
                            class="text-center py-5">

                            No orders found.

                        </td>

                    </tr>

                <?php endif; ?>

                <?php foreach($orders as $order): ?>

                    <tr>

                        <td>

                            <strong>

                                <?= e($order['order_number']) ?>

                            </strong>

                        </td>

                        <td>

                            <?= date(

                                'd M Y h:i A',

                                strtotime($order['created_at'])

                            ) ?>

                        </td>

                        <td>

                            <?php

                            switch($order['order_type']){

                                case 'delivery':

                                    echo '<span class="badge bg-primary">Delivery</span>';

                                    break;

                                case 'pickup':

                                    echo '<span class="badge bg-info">Pickup</span>';

                                    break;

                                case 'dine_in':

                                    echo '<span class="badge bg-success">Dine In</span>';

                                    break;

                                case 'pos':

                                    echo '<span class="badge bg-dark">POS</span>';

                                    break;

                                default:

                                    echo '<span class="badge bg-secondary">'
                                        .e($order['order_type']).
                                    '</span>';

                            }

                            ?>

                        </td>

                        <td>

                            <?= e($order['payment_method']) ?>

                        </td>

                        <td>

                            <?php if($order['payment_status']=='Paid'): ?>

                                <span class="badge bg-success">

                                    Paid

                                </span>

                            <?php elseif($order['payment_status']=='Pending'): ?>

                                <span class="badge bg-warning text-dark">

                                    Pending

                                </span>

                            <?php elseif($order['payment_status']=='Refunded'): ?>

                                <span class="badge bg-danger">

                                    Refunded

                                </span>

                            <?php else: ?>

                                <span class="badge bg-secondary">

                                    <?= e($order['payment_status']) ?>

                                </span>

                            <?php endif; ?>

                        </td>

                        <td>

                            <?php

                            $statusColor='secondary';

                            switch($order['order_status']){

                                case 'Pending':

                                    $statusColor='warning text-dark';

                                    break;

                                case 'Preparing':

                                    $statusColor='info';

                                    break;

                                case 'Ready':

                                    $statusColor='primary';

                                    break;

                                case 'Completed':

                                    $statusColor='success';

                                    break;

                                case 'Cancelled':

                                    $statusColor='danger';

                                    break;

                            }

                            ?>

                            <span class="badge bg-<?= $statusColor ?>">

                                <?= e($order['order_status']) ?>

                            </span>

                        </td>

                        <td>

                            <strong>

                                <?= CURRENCY_SYMBOL ?>

                                <?= number_format(

                                    $order['grand_total'],

                                    2

                                ) ?>

                            </strong>

                        </td>

                        <td>

                            <div class="btn-group">

                                <a
                                    href="<?= BASE_URL ?>/admin/orders/<?= $order['id'] ?>"
                                    class="btn btn-sm btn-primary"
                                    title="View">

                                    <i class="fa-solid fa-eye"></i>

                                </a>

                                <a
                                    href="<?= BASE_URL ?>/admin/orders/invoice/<?= $order['id'] ?>"
                                    class="btn btn-sm btn-success"
                                    target="_blank"
                                    title="Invoice">

                                    <i class="fa-solid fa-file-invoice"></i>

                                </a>

                                <a
                                    href="<?= BASE_URL ?>/admin/orders/receipt/<?= $order['id'] ?>"
                                    class="btn btn-sm btn-dark"
                                    target="_blank"
                                    title="Receipt">

                                    <i class="fa-solid fa-print"></i>

                                </a>

                            </div>

                        </td>

                    </tr>

                <?php endforeach; ?>

                </tbody>

                <?php if(!empty($orders)): ?>

                <tfoot class="table-light">

                    <tr>

                        <th colspan="6"
                            class="text-end">

                            Total Spending

                        </th>

                        <th>

                            <?= CURRENCY_SYMBOL ?>

                            <?= number_format(

                                array_sum(

                                    array_column(

                                        $orders,

                                        'grand_total'

                                    )

                                ),

                                2

                            ) ?>

                        </th>

                        <th></th>

                    </tr>

                </tfoot>

                <?php endif; ?>

            </table>

        </div>

    </div>

</div>

<?php

require APP_PATH . '/Views/admin/layouts/footer.php';

?>