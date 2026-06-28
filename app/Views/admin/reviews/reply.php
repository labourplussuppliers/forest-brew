<?php

require APP_PATH . '/Views/admin/layouts/header.php';

?>

<div class="container-fluid py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h2 class="mb-1">

                Reply to Review

            </h2>

            <p class="text-muted mb-0">

                Respond to customer feedback professionally.

            </p>

        </div>

        <a
            href="<?= BASE_URL ?>/admin/reviews/<?= $review['id'] ?>"
            class="btn btn-outline-secondary">

            Back

        </a>

    </div>

    <div class="row">

        <div class="col-lg-4">

            <div class="card shadow-sm border-0 mb-4">

                <div class="card-header bg-white">

                    <h5 class="mb-0">

                        Review Summary

                    </h5>

                </div>

                <div class="card-body">

                    <p class="mb-3">

                        <strong>

                            Customer

                        </strong>

                        <br>

                        <?= e($review['first_name']) ?>

                        <?= e($review['last_name']) ?>

                    </p>

                    <p class="mb-3">

                        <strong>

                            Product

                        </strong>

                        <br>

                        <?= e($review['product_name']) ?>

                    </p>

                    <p class="mb-3">

                        <strong>

                            Rating

                        </strong>

                        <br>

                        <?php for($i=1;$i<=5;$i++): ?>

                            <?php if($i<=$review['rating']): ?>

                                <i class="fa-solid fa-star text-warning"></i>

                            <?php else: ?>

                                <i class="fa-regular fa-star text-secondary"></i>

                            <?php endif; ?>

                        <?php endfor; ?>

                    </p>

                    <p class="mb-0">

                        <strong>

                            Date

                        </strong>

                        <br>

                        <?= date(

                            'd M Y h:i A',

                            strtotime($review['created_at'])

                        ) ?>

                    </p>

                </div>

            </div>

            <div class="card shadow-sm border-0">

                <div class="card-header bg-white">

                    <h5 class="mb-0">

                        Customer Review

                    </h5>

                </div>

                <div class="card-body">

                    <?= nl2br(

                        e($review['review'])

                    ) ?>

                </div>

            </div>

        </div>

        <div class="col-lg-8">

            <div class="card shadow-sm border-0">

                <div class="card-header bg-white">

                    <h5 class="mb-0">

                        Write Reply

                    </h5>

                </div>

                <div class="card-body">

                    <form
                        method="POST">

                        <?= csrf_field() ?>

                        <div class="mb-3">

                            <label
                                class="form-label">

                                Reply

                            </label>

                            <textarea
                                id="reply"
                                name="reply"
                                rows="8"
                                maxlength="1000"
                                class="form-control"
                                required><?= e($review['admin_reply'] ?? '') ?></textarea>

                            <div class="d-flex justify-content-between mt-2">

                                <small class="text-muted">

                                    Respond politely and professionally.

                                </small>

                                <small
                                    id="characterCount"
                                    class="text-muted">

                                    0 / 1000

                                </small>

                            </div>

                        </div>

                        <div class="d-flex gap-2">

                            <button
                                type="submit"
                                class="btn btn-primary">

                                <i class="fa-solid fa-paper-plane"></i>

                                Save Reply

                            </button>

                            <a
                                href="<?= BASE_URL ?>/admin/reviews/<?= $review['id'] ?>"
                                class="btn btn-outline-secondary">

                                Cancel

                            </a>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    </div>

</div>

<script>

const textarea = document.getElementById(

    'reply'

);

const counter = document.getElementById(

    'characterCount'

);

function updateCounter(){

    counter.innerHTML =

        textarea.value.length +

        " / 1000";

}

updateCounter();

textarea.addEventListener(

    "input",

    updateCounter

);

</script>

<?php

require APP_PATH . '/Views/admin/layouts/footer.php';

?>