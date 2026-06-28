<?php

require APP_PATH . '/Views/admin/layouts/header.php';

?>

<div class="container-fluid py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h2 class="mb-1">

                Stock Out

            </h2>

            <p class="text-muted mb-0">

                Remove stock from inventory.

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

                        Stock Out Form

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
                                type="text"
                                class="form-control"
                                value="<?= number_format($product['stock'],2) ?>"
                                readonly>

                        </div>

                        <div class="mb-3">

                            <label class="form-label">

                                Quantity

                            </label>

                            <input
                                id="quantity"
                                type="number"
                                name="quantity"
                                class="form-control"
                                min="0.01"
                                max="<?= $product['stock'] ?>"
                                step="0.01"
                                required>

                        </div>

                        <div class="mb-3">

                            <label class="form-label">

                                Reason

                            </label>

                            <select
                                name="reason"
                                class="form-select"
                                required>

                                <option value="Sale">

                                    Sale

                                </option>

                                <option value="Damage">

                                    Damage

                                </option>

                                <option value="Wastage">

                                    Wastage

                                </option>

                                <option value="Expired">

                                    Expired

                                </option>

                                <option value="Sample">

                                    Sample

                                </option>

                                <option value="Adjustment">

                                    Adjustment

                                </option>

                                <option value="Other">

                                    Other

                                </option>

                            </select>

                        </div>

                        <div class="mb-3">

                            <label class="form-label">

                                Reference Number

                            </label>

                            <input
                                type="text"
                                name="reference"
                                class="form-control"
                                placeholder="Invoice / Order / Document No">

                        </div>

                        <div class="mb-4">

                            <label class="form-label">

                                Remarks

                            </label>

                            <textarea
                                name="remarks"
                                rows="4"
                                class="form-control"
                                placeholder="Reason for stock removal..."></textarea>

                        </div>

                        <div class="alert alert-warning">

                            <i class="fa-solid fa-circle-exclamation"></i>

                            Stock removed from inventory cannot be recovered automatically.

                        </div>

                        <div class="d-flex gap-2">

                            <button
                                type="submit"
                                class="btn btn-danger">

                                <i class="fa-solid fa-minus"></i>

                                Remove Stock

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

const stock = <?= (float)$product['stock'] ?>;

const qty = document.getElementById(

    'quantity'

);

qty.addEventListener(

    'input',

    function(){

        if(

            parseFloat(this.value) > stock

        ){

            alert(

                'Quantity exceeds available stock.'

            );

            this.value = stock;

        }

    }

);

</script>

<?php

require APP_PATH . '/Views/admin/layouts/footer.php';

?>