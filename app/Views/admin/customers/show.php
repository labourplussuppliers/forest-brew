<?php

require APP_PATH . '/Views/admin/layouts/header.php';

?>

<div class="container-fluid py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h2 class="mb-1">

                Customer Profile

            </h2>

            <p class="text-muted mb-0">

                Complete customer information and activity.

            </p>

        </div>

        <div>

            <a
                href="<?= BASE_URL ?>/admin/customers"
                class="btn btn-outline-secondary">

                Back

            </a>

            <a
                href="<?= BASE_URL ?>/admin/customers/orders/<?= $customer['id'] ?>"
                class="btn btn-primary">

                View Orders

            </a>

        </div>

    </div>

    <div class="row">

        <!-- Left Column -->

        <div class="col-lg-4">

            <div class="card shadow-sm border-0 mb-4">

                <div class="card-body text-center">

                    <img
                        src="<?= !empty($customer['avatar']) ? asset($customer['avatar']) : asset('images/default-avatar.png') ?>"
                        class="rounded-circle mb-3"
                        width="120"
                        height="120"
                        style="object-fit:cover;">

                    <h4>

                        <?= e($customer['first_name']) ?>

                        <?= e($customer['last_name']) ?>

                    </h4>

                    <p class="text-muted mb-3">

                        Customer

                    </p>

                    <?php if($customer['status']): ?>

                        <span class="badge bg-success">

                            Active

                        </span>

                    <?php else: ?>

                        <span class="badge bg-danger">

                            Blocked

                        </span>

                    <?php endif; ?>

                </div>

            </div>

            <div class="card shadow-sm border-0 mb-4">

                <div class="card-header bg-white">

                    <h5 class="mb-0">

                        Contact Information

                    </h5>

                </div>

                <div class="card-body">

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

                    <p>

                        <strong>Joined</strong>

                        <br>

                        <?= date(

                            'd M Y',

                            strtotime($customer['created_at'])

                        ) ?>

                    </p>

                    <p class="mb-0">

                        <strong>Last Login</strong>

                        <br>

                        <?= !empty($customer['last_login'])
                            ? date('d M Y h:i A', strtotime($customer['last_login']))
                            : 'Never'
                        ?>

                    </p>

                </div>

            </div>

            <div class="card shadow-sm border-0">

                <div class="card-header bg-white">

                    <h5 class="mb-0">

                        Saved Addresses

                    </h5>

                </div>

                <div class="card-body">

                    <?php if(empty($addresses)): ?>

                        <p class="text-muted mb-0">

                            No saved addresses.

                        </p>

                    <?php endif; ?>

                    <?php foreach($addresses as $address): ?>

                        <div class="border rounded p-3 mb-3">

                            <?php if(!empty($address['label'])): ?>

                                <strong>

                                    <?= e($address['label']) ?>

                                </strong>

                                <br>

                            <?php endif; ?>

                            <?= nl2br(e($address['address'])) ?>

                            <?php if(!empty($address['city'])): ?>

                                <br>

                                <?= e($address['city']) ?>

                            <?php endif; ?>

                            <?php if(!empty($address['is_default'])): ?>

                                <div class="mt-2">

                                    <span class="badge bg-primary">

                                        Default

                                    </span>

                                </div>

                            <?php endif; ?>

                        </div>

                    <?php endforeach; ?>

                </div>

            </div>

        </div>

        <!-- Right Column -->

        <div class="col-lg-8">

            <div class="row">

                <div class="col-md-3 mb-4">

                    <div class="card shadow-sm border-0 text-center">

                        <div class="card-body">

                            <h3 class="text-primary">

                                <?= $statistics['orders'] ?>

                            </h3>

                            <small>

                                Total Orders

                            </small>

                        </div>

                    </div>

                </div>

                <div class="col-md-3 mb-4">

                    <div class="card shadow-sm border-0 text-center">

                        <div class="card-body">

                            <h3 class="text-success">

                                <?= CURRENCY_SYMBOL ?>

                                <?= number_format($statistics['spent'],2) ?>

                            </h3>

                            <small>

                                Total Spending

                            </small>

                        </div>

                    </div>

                </div>

                <div class="col-md-3 mb-4">

                    <div class="card shadow-sm border-0 text-center">

                        <div class="card-body">

                            <h3 class="text-warning">

                                <?= number_format($statistics['points']) ?>

                            </h3>

                            <small>

                                Reward Points

                            </small>

                        </div>

                    </div>

                </div>

                <div class="col-md-3 mb-4">

                    <div class="card shadow-sm border-0 text-center">

                        <div class="card-body">

                            <h3 class="text-info">

                                <?= CURRENCY_SYMBOL ?>

                                <?= number_format($statistics['wallet'],2) ?>

                            </h3>

                            <small>

                                Wallet Balance

                            </small>

                        </div>

                    </div>

                </div>

            </div>

            <div class="card shadow-sm border-0 mb-4">

                <div class="card-header bg-white">

                    <h5 class="mb-0">

                        Recent Orders

                    </h5>

                </div>

                <div class="table-responsive">

                    <table class="table align-middle mb-0">

                        <thead>

                            <tr>

                                <th>

                                    Order #

                                </th>

                                <th>

                                    Date

                                </th>

                                <th>

                                    Status

                                </th>

                                <th>

                                    Payment

                                </th>

                                <th>

                                    Total

                                </th>

                                <th>

                                    Action

                                </th>

                            </tr>

                        </thead>

                        <tbody>

                        <?php if(empty($orders)): ?>

                            <tr>

                                <td
                                    colspan="6"
                                    class="text-center py-4">

                                    No orders available.

                                </td>

                            </tr>

                        <?php endif; ?>

                        <?php foreach($orders as $order): ?>

                            <tr>

                                <td>

                                    <?= e($order['order_number']) ?>

                                </td>

                                <td>

                                    <?= date(

                                        'd M Y',

                                        strtotime($order['created_at'])

                                    ) ?>

                                </td>

                                <td>

                                    <span class="badge bg-primary">

                                        <?= e($order['order_status']) ?>

                                    </span>

                                </td>

                                <td>

                                    <?= e($order['payment_status']) ?>

                                </td>

                                <td>

                                    <?= CURRENCY_SYMBOL ?>

                                    <?= number_format($order['grand_total'],2) ?>

                                </td>

                                <td>

                                    <a
                                        href="<?= BASE_URL ?>/admin/orders/<?= $order['id'] ?>"
                                        class="btn btn-sm btn-outline-primary">

                                        View

                                    </a>

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

                        Last Order

                    </h5>

                </div>

                <div class="card-body">

                    <?php if(empty($statistics['lastOrder'])): ?>

                        <p class="text-muted mb-0">

                            Customer has not placed any orders yet.

                        </p>

                    <?php else: ?>

                        <div class="row">

                            <div class="col-md-4">

                                <strong>

                                    Order #

                                </strong>

                                <br>

                                <?= e($statistics['lastOrder']['order_number']) ?>

                            </div>

                            <div class="col-md-4">

                                <strong>

                                    Date

                                </strong>

                                <br>

                                <?= date(

                                    'd M Y h:i A',

                                    strtotime($statistics['lastOrder']['created_at'])

                                ) ?>

                            </div>

                            <div class="col-md-4">

                                <strong>

                                    Total

                                </strong>

                                <br>

                                <?= CURRENCY_SYMBOL ?>

                                <?= number_format($statistics['lastOrder']['grand_total'],2) ?>

                            </div>

                        </div>

                    <?php endif; ?>

                </div>

            </div>

        </div>

    </div>

</div>

<?php

require APP_PATH . '/Views/admin/layouts/footer.php';

?>