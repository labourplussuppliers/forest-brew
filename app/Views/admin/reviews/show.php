<?php

require APP_PATH . '/Views/admin/layouts/header.php';

?>

<div class="container-fluid py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h2 class="mb-1">

                Review Details

            </h2>

            <p class="text-muted mb-0">

                View and manage customer review.

            </p>

        </div>

        <a
            href="<?= BASE_URL ?>/admin/reviews"
            class="btn btn-outline-secondary">

            Back

        </a>

    </div>

    <div class="row">

        <div class="col-lg-4">

            <div class="card shadow-sm border-0 mb-4">

                <div class="card-header bg-white">

                    <h5 class="mb-0">

                        Customer

                    </h5>

                </div>

                <div class="card-body">

                    <h5>

                        <?= e($review['first_name']) ?>

                        <?= e($review['last_name']) ?>

                    </h5>

                    <p class="mb-2">

                        <strong>Product</strong>

                        <br>

                        <?= e($review['product_name']) ?>

                    </p>

                    <p class="mb-2">

                        <strong>Review Date</strong>

                        <br>

                        <?= date(

                            'd M Y h:i A',

                            strtotime($review['created_at'])

                        ) ?>

                    </p>

                    <p class="mb-0">

                        <strong>Status</strong>

                        <br>

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

                    </p>

                </div>

            </div>

            <div class="card shadow-sm border-0">

                <div class="card-header bg-white">

                    <h5 class="mb-0">

                        Rating

                    </h5>

                </div>

                <div class="card-body text-center">

                    <div class="mb-3">

                        <?php

                        for($i=1;$i<=5;$i++):

                        ?>

                            <?php if($i<=$review['rating']): ?>

                                <i class="fa-solid fa-star fa-2x text-warning"></i>

                            <?php else: ?>

                                <i class="fa-regular fa-star fa-2x text-secondary"></i>

                            <?php endif; ?>

                        <?php endfor; ?>

                    </div>

                    <h3>

                        <?= $review['rating'] ?>

                        / 5

                    </h3>

                </div>

            </div>

        </div>

        <div class="col-lg-8">

            <div class="card shadow-sm border-0 mb-4">

                <div class="card-header bg-white">

                    <h5 class="mb-0">

                        Customer Review

                    </h5>

                </div>

                <div class="card-body">

                    <p class="mb-0">

                        <?= nl2br(

                            e($review['review'])

                        ) ?>

                    </p>

                </div>

            </div>

            <div class="card shadow-sm border-0 mb-4">

                <div class="card-header bg-white">

                    <h5 class="mb-0">

                        Admin Reply

                    </h5>

                </div>

                <div class="card-body">

                    <?php if(empty($review['admin_reply'])): ?>

                        <p class="text-muted mb-0">

                            No reply submitted yet.

                        </p>

                    <?php else: ?>

                        <div class="border rounded p-3 bg-light">

                            <?= nl2br(

                                e($review['admin_reply'])

                            ) ?>

                        </div>

                    <?php endif; ?>

                </div>

            </div>

            <div class="card shadow-sm border-0">

                <div class="card-body">

                    <div class="d-flex flex-wrap gap-2">

                        <?php if($review['status']==0): ?>

                            <a
                                href="<?= BASE_URL ?>/admin/reviews/approve/<?= $review['id'] ?>"
                                class="btn btn-success"
                                onclick="return confirm('Approve this review?')">

                                <i class="fa-solid fa-check"></i>

                                Approve

                            </a>

                            <a
                                href="<?= BASE_URL ?>/admin/reviews/reject/<?= $review['id'] ?>"
                                class="btn btn-warning"
                                onclick="return confirm('Reject this review?')">

                                <i class="fa-solid fa-ban"></i>

                                Reject

                            </a>

                        <?php endif; ?>

                        <a
                            href="<?= BASE_URL ?>/admin/reviews/reply/<?= $review['id'] ?>"
                            class="btn btn-primary">

                            <i class="fa-solid fa-reply"></i>

                            Reply

                        </a>

                        <a
                            href="<?= BASE_URL ?>/admin/reviews/delete/<?= $review['id'] ?>"
                            class="btn btn-danger"
                            onclick="return confirm('Delete this review permanently?')">

                            <i class="fa-solid fa-trash"></i>

                            Delete

                        </a>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

<?php

require APP_PATH . '/Views/admin/layouts/footer.php';

?>