<?php

require APP_PATH . '/Views/admin/layouts/header.php';

?>

<div class="container-fluid py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h2 class="mb-1">

                Stock In

            </h2>

            <p class="text-muted mb-0">

                Add inventory stock for the selected product.

            </p>

        </div>

        <a
            href="<?= BASE_URL ?>/admin/inventory"
            class="btn btn-outline-secondary">

            Back

        </a>

    </div>

    <div class="row justify-content-center">

        <div class="col-lg-8">

            <div class="card shadow-sm border-0">

                <div class="card-header bg-white">

                    <h5 class="mb-0">

                        Stock Entry

                    </h5>

                </div>

                <div class="card-body">

                    <form method="POST">

                        <?= csrf_field() ?>

                        <div class="row">

                            <div class="col-md-6 mb-3">

                                <label class="form-label">

                                    Product

                                </label>

                                <input
                                    type="text"
                                    class="form-control"
                                    value="<?= e($product['name']) ?>"
                                    readonly>

                            </div>

                            <div class="col-md-6 mb-3">

                                <label class="form-label">

                                    SKU

                                </label>

                                <input
                                    type="text"
                                    class="form-control"
                                    value="<?= e($product['sku']) ?>"
                                    readonly>

                            </div>

                        </div>

                        <div class="mb-3">

                            <label class="form-label">

                                Quantity

                            </label>

                            <input
                                type="number"
                                name="quantity"
                                class="form-control"
                                min="0.01"
                                step="0.01"
                                required>

                        </div>

                        <div class="mb-3">

                            <label class="form-label">

                                Purchase Cost (Optional)

                            </label>

                            <input
                                type="number"
                                name="purchase_cost"
                                class="form-control"
                                min="0"
                                step="0.01">

                        </div>

                        <div class="mb-3">

                            <label class="form-label">

                                Supplier

                            </label>

                            <input
                                type="text"
                                name="supplier"
                                class="form-control">

                        </div>

                        <div class="mb-3">

                            <label class="form-label">

                                Reference Number

                            </label>

                            <input
                                type="text"
                                name="reference"
                                class="form-control">

                        </div>

                        <div class="mb-4">

                            <label class="form-label">

                                Remarks

                            </label>

                            <textarea
                                name="remarks"
                                rows="4"
                                class="form-control"></textarea>

                        </div>

                        <div class="d-flex gap-2">

                            <button
                                type="submit"
                                class="btn btn-success">

                                <i class="fa-solid fa-floppy-disk"></i>

                                Save Stock

                            </button>

                            <a
                                href="<?= BASE_URL ?>/admin/inventory"
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

<?php

require APP_PATH . '/Views/admin/layouts/footer.php';

?>