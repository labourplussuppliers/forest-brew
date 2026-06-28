<?php

require APP_PATH . '/Views/admin/layouts/header.php';

?>

<div class="container-fluid py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h2 class="mb-1">

                Product Reviews

            </h2>

            <p class="text-muted mb-0">

                Manage customer reviews and ratings.

            </p>

        </div>

    </div>

    <div class="card shadow-sm border-0 mb-4">

        <div class="card-body">

            <form
                method="GET">

                <div class="row">

                    <div class="col-lg-4">

                        <select
                            name="status"
                            class="form-select">

                            <option value="">

                                All Reviews

                            </option>

                            <option
                                value="pending"
                                <?= ($_GET['status'] ?? '')=='pending' ? 'selected' : '' ?>>

                                Pending

                            </option>

                            <option
                                value="approved"
                                <?= ($_GET['status'] ?? '')=='approved' ? 'selected' : '' ?>>

                                Approved

                            </option>

                        </select>

                    </div>

                    <div class="col-lg-2">

                        <button
                            class="btn btn-primary w-100">

                            Filter

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

                        <th width="80">

                            ID

                        </th>

                        <th>

                            Customer

                        </th>

                        <th>

                            Product

                        </th>

                        <th width="130">

                            Rating

                        </th>

                        <th>

                            Review

                        </th>

                        <th width="120">

                            Status

                        </th>

                        <th width="130">

                            Date

                        </th>

                        <th width="240">

                            Actions

                        </th>

                    </tr>

                </thead>

                <tbody>

                <?php if(empty($reviews)): ?>

                    <tr>

                        <td
                            colspan="8"
                            class="text-center py-5">

                            No reviews found.

                        </td>

                    </tr>

                <?php endif; ?>

                <?php foreach($reviews as $review): ?>

                    <tr>

                        <td>

                            <?= $review['id'] ?>

                        </td>

                        <td>

                            <strong>

                                <?= e($review['first_name']) ?>

                                <?= e($review['last_name']) ?>

                            </strong>

                        </td>

                        <td>

                            <?= e($review['product_name']) ?>

                        </td>

                        <td>

                            <?php

                            $rating=(int)$review['rating'];

                            for($i=1;$i<=5;$i++):

                            ?>

                                <?php if($i<=$rating): ?>

                                    <i class="fa-solid fa-star text-warning"></i>

                                <?php else: ?>

                                    <i class="fa-regular fa-star text-secondary"></i>

                                <?php endif; ?>

                            <?php endfor; ?>

                        </td>

                        <td>

                            <?= e(

                                mb_strimwidth(

                                    $review['review'],

                                    0,

                                    80,

                                    '...'

                                )

                            ) ?>

                        </td>

                        <td>

                            <?php

                            switch($review['status']){

                                case 0:

                                    echo '<span class="badge bg-warning text-dark">Pending</span>';

                                    break;

                                case 1:

                                    echo '<span class="badge bg-success">Approved</span>';

                                    break;

                                case 2:

                                    echo '<span class="badge bg-danger">Rejected</span>';

                                    break;

                            }

                            ?>

                        </td>

                        <td>

                            <?= date(

                                'd M Y',

                                strtotime(

                                    $review['created_at']

                                )

                            ) ?>

                        </td>

                        <td>

                            <div class="btn-group">

                                <a
                                    href="<?= BASE_URL ?>/admin/reviews/<?= $review['id'] ?>"
                                    class="btn btn-sm btn-primary"
                                    title="View">

                                    <i class="fa-solid fa-eye"></i>

                                </a>

                                <?php if($review['status']==0): ?>

                                <a
                                    href="<?= BASE_URL ?>/admin/reviews/approve/<?= $review['id'] ?>"
                                    class="btn btn-sm btn-success"
                                    onclick="return confirm('Approve this review?')"
                                    title="Approve">

                                    <i class="fa-solid fa-check"></i>

                                </a>

                                <a
                                    href="<?= BASE_URL ?>/admin/reviews/reject/<?= $review['id'] ?>"
                                    class="btn btn-sm btn-warning"
                                    onclick="return confirm('Reject this review?')"
                                    title="Reject">

                                    <i class="fa-solid fa-ban"></i>

                                </a>

                                <?php endif; ?>

                                <a
                                    href="<?= BASE_URL ?>/admin/reviews/reply/<?= $review['id'] ?>"
                                    class="btn btn-sm btn-info"
                                    title="Reply">

                                    <i class="fa-solid fa-reply"></i>

                                </a>

                                <a
                                    href="<?= BASE_URL ?>/admin/reviews/delete/<?= $review['id'] ?>"
                                    class="btn btn-sm btn-danger"
                                    onclick="return confirm('Delete this review permanently?')"
                                    title="Delete">

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