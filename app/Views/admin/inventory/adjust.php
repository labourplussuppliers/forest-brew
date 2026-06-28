<?php

require APP_PATH . '/Views/admin/layouts/header.php';

?>

<div class="container-fluid py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h2 class="mb-1">

                Stock Adjustment

            </h2>

            <p class="text-muted mb-0">

                Update inventory stock after physical stock verification.

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

                        Inventory Adjustment

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

                                Current Stock

                            </label>

                            <input
                                id="currentStock"
                                type="number"
                                class="form-control"
                                value="<?= number_format($product['stock'],2,'.','') ?>"
                                readonly>

                        </div>

                        <div class="mb-3">

                            <label class="form-label">

                                New Stock Quantity

                            </label>

                            <input
                                id="newStock"
                                type="number"
                                name="quantity"
                                class="form-control"
                                min="0"
                                step="0.01"
                                value="<?= number_format($product['stock'],2,'.','') ?>"
                                required>

                        </div>

                        <div class="mb-3">

                            <label class="form-label">

                                Difference

                            </label>

                            <input
                                id="difference"
                                type="text"
                                class="form-control"
                                readonly>

                        </div>

                        <div class="mb-3">

                            <label class="form-label">

                                Adjustment Reason

                            </label>

                            <select
                                name="reason"
                                class="form-select"
                                required>

                                <option value="Physical Count">

                                    Physical Count

                                </option>

                                <option value="Inventory Audit">

                                    Inventory Audit

                                </option>

                                <option value="Damaged Items">

                                    Damaged Items

                                </option>

                                <option value="Expired Items">

                                    Expired Items

                                </option>

                                <option value="Stock Correction">

                                    Stock Correction

                                </option>

                                <option value="System Error">

                                    System Error

                                </option>

                                <option value="Other">

                                    Other

                                </option>

                            </select>

                        </div>

                        <div class="mb-4">

                            <label class="form-label">

                                Remarks

                            </label>

                            <textarea
                                name="remarks"
                                rows="4"
                                class="form-control"
                                placeholder="Enter adjustment remarks..."></textarea>

                        </div>

                        <div
                            id="adjustmentAlert"
                            class="alert alert-info">

                            No stock adjustment detected.

                        </div>

                        <div class="d-flex gap-2">

                            <button
                                type="submit"
                                class="btn btn-primary">

                                <i class="fa-solid fa-arrows-rotate"></i>

                                Save Adjustment

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

<script>

const currentStock = parseFloat(

    document.getElementById(

        'currentStock'

    ).value

);

const newStock = document.getElementById(

    'newStock'

);

const difference = document.getElementById(

    'difference'

);

const alertBox = document.getElementById(

    'adjustmentAlert'

);

function calculateDifference(){

    const value = parseFloat(

        newStock.value || 0

    );

    const diff = value - currentStock;

    if(diff > 0){

        difference.value =

            "+" +

            diff.toFixed(2);

        alertBox.className =

            "alert alert-success";

        alertBox.innerHTML =

            "<strong>Increase:</strong> " +

            diff.toFixed(2) +

            " units will be added.";

    }

    else if(diff < 0){

        difference.value =

            diff.toFixed(2);

        alertBox.className =

            "alert alert-warning";

        alertBox.innerHTML =

            "<strong>Decrease:</strong> " +

            Math.abs(diff).toFixed(2) +

            " units will be removed.";

    }

    else{

        difference.value =

            "0.00";

        alertBox.className =

            "alert alert-info";

        alertBox.innerHTML =

            "No stock adjustment detected.";

    }

}

calculateDifference();

newStock.addEventListener(

    "input",

    calculateDifference

);

</script>

<?php

require APP_PATH . '/Views/admin/layouts/footer.php';

?>