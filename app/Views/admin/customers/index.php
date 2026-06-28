<?php

require APP_PATH . '/Views/admin/layouts/header.php';

?>

<div class="container-fluid py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h2 class="mb-1">

                Customers

            </h2>

            <p class="text-muted mb-0">

                Manage customer accounts and order history.

            </p>

        </div>

    </div>

    <div class="card shadow-sm border-0 mb-4">

        <div class="card-body">

            <form method="GET">

                <div class="row">

                    <div class="col-lg-10">

                        <input
                            type="text"
                            name="search"
                            class="form-control"
                            placeholder="Search by name, email or phone..."
                            value="<?= e($_GET['search'] ?? '') ?>">

                    </div>

                    <div class="col-lg-2">

                        <button
                            class="btn btn-primary w-100">

                            Search

                        </button>

                    </div>

                </div>

            </form>

        </div>

    </div>

    <div class="card shadow-sm border-0">

        <div class="table-responsive">

            <table class="table table-hover align-middle mb-0">

                <thead class="table-light">

                    <tr>

                        <th width="70">

                            ID

                        </th>

                        <th>

                            Customer

                        </th>

                        <th>

                            Phone

                        </th>

                        <th>

                            Reward Points

                        </th>

                        <th>

                            Wallet

                        </th>

                        <th>

                            Status

                        </th>

                        <th>

                            Registered

                        </th>

                        <th width="240">

                            Actions

                        </th>

                    </tr>

                </thead>

                <tbody>

                <?php if(empty($customers)): ?>

                    <tr>

                        <td
                            colspan="8"
                            class="text-center py-5">

                            No customers found.

                        </td>

                    </tr>

                <?php endif; ?>

                <?php foreach($customers as $customer): ?>

                    <tr>

                        <td>

                            <?= $customer['id'] ?>

                        </td>

                        <td>

                            <div class="fw-semibold">

                                <?= e($customer['first_name']) ?>

                                <?= e($customer['last_name']) ?>

                            </div>

                            <small class="text-muted">

                                <?= e($customer['email']) ?>

                            </small>

                        </td>

                        <td>

                            <?= e($customer['phone']) ?>

                        </td>

                        <td>

                            <span class="badge bg-warning text-dark">

                                <?= (int)$customer['reward_points'] ?>

                            </span>

                        </td>

                        <td>

                            <?= CURRENCY_SYMBOL ?>

                            <?= number_format($customer['wallet_balance'],2) ?>

                        </td>

                        <td>

                            <?php if($customer['status']): ?>

                                <span class="badge bg-success">

                                    Active

                                </span>

                            <?php else: ?>

                                <span class="badge bg-danger">

                                    Blocked

                                </span>

                            <?php endif; ?>

                        </td>

                        <td>

                            <?= date(

                                'd M Y',

                                strtotime($customer['created_at'])

                            ) ?>

                        </td>

                        <td>

                            <a
                                href="<?= BASE_URL ?>/admin/customers/<?= $customer['id'] ?>"
                                class="btn btn-sm btn-primary">

                                <i class="fa-solid fa-eye"></i>

                            </a>

                            <a
                                href="<?= BASE_URL ?>/admin/customers/orders/<?= $customer['id'] ?>"
                                class="btn btn-sm btn-info">

                                <i class="fa-solid fa-cart-shopping"></i>

                            </a>

                            <?php if($customer['status']): ?>

                                <a
                                    href="<?= BASE_URL ?>/admin/customers/block/<?= $customer['id'] ?>"
                                    class="btn btn-sm btn-warning"
                                    onclick="return confirm('Block this customer?')">

                                    <i class="fa-solid fa-ban"></i>

                                </a>

                            <?php else: ?>

                                <a
                                    href="<?= BASE_URL ?>/admin/customers/activate/<?= $customer['id'] ?>"
                                    class="btn btn-sm btn-success"
                                    onclick="return confirm('Activate this customer?')">

                                    <i class="fa-solid fa-check"></i>

                                </a>

                            <?php endif; ?>

                            <a
                                href="<?= BASE_URL ?>/admin/customers/delete/<?= $customer['id'] ?>"
                                class="btn btn-sm btn-danger"
                                onclick="return confirm('Delete this customer permanently?')">

                                <i class="fa-solid fa-trash"></i>

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