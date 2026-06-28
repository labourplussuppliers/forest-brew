<?php

require APP_PATH . '/Views/layouts/header.php';

?>

<section class="page-header bg-light py-5">

    <div class="container">

        <h1 class="fw-bold">
            Checkout
        </h1>

        <p class="text-muted mb-0">
            Complete your order securely.
        </p>

    </div>

</section>

<section class="checkout-section py-5">

    <div class="container">

        <form
            action="<?= BASE_URL ?>/checkout"
            method="POST">

            <?= csrf_field(); ?>

            <div class="row">

                <div class="col-lg-8">

                    <div class="card shadow-sm border-0 mb-4">

                        <div class="card-header bg-white">

                            <h4 class="mb-0">

                                Delivery Information

                            </h4>

                        </div>

                        <div class="card-body">

                            <div class="row">

                                <div class="col-md-6 mb-3">

                                    <label class="form-label">

                                        Full Name

                                    </label>

                                    <input
                                        type="text"
                                        name="name"
                                        class="form-control"
                                        required>

                                </div>

                                <div class="col-md-6 mb-3">

                                    <label class="form-label">

                                        Phone Number

                                    </label>

                                    <input
                                        type="text"
                                        name="phone"
                                        class="form-control"
                                        required>

                                </div>

                                <div class="col-md-6 mb-3">

                                    <label class="form-label">

                                        Email Address

                                    </label>

                                    <input
                                        type="email"
                                        name="email"
                                        class="form-control">

                                </div>

                                <div class="col-md-6 mb-3">

                                    <label class="form-label">

                                        Order Type

                                    </label>

                                    <select
                                        name="order_type"
                                        id="orderType"
                                        class="form-select">

                                        <option value="Delivery">

                                            Delivery

                                        </option>

                                        <option value="Pickup">

                                            Pickup

                                        </option>

                                    </select>

                                </div>

                                <div class="col-12 mb-3">

                                    <label class="form-label">

                                        Delivery Address

                                    </label>

                                    <textarea
                                        name="address"
                                        rows="3"
                                        class="form-control"></textarea>

                                </div>

                                <div class="col-12">

                                    <label class="form-label">

                                        Order Notes

                                    </label>

                                    <textarea
                                        name="notes"
                                        rows="3"
                                        class="form-control"
                                        placeholder="Special instructions..."></textarea>

                                </div>

                            </div>

                        </div>

                    </div>

                    <div class="card shadow-sm border-0">

                        <div class="card-header bg-white">

                            <h4 class="mb-0">

                                Payment Method

                            </h4>

                        </div>

                        <div class="card-body">

                            <div class="form-check mb-3">

                                <input
                                    class="form-check-input"
                                    type="radio"
                                    name="payment_method"
                                    value="Cash On Delivery"
                                    checked>

                                <label class="form-check-label">

                                    Cash On Delivery

                                </label>

                            </div>

                            <div class="form-check mb-3">

                                <input
                                    class="form-check-input"
                                    type="radio"
                                    name="payment_method"
                                    value="Credit / Debit Card">

                                <label class="form-check-label">

                                    Credit / Debit Card

                                </label>

                            </div>

                            <div class="form-check mb-3">

                                <input
                                    class="form-check-input"
                                    type="radio"
                                    name="payment_method"
                                    value="JazzCash">

                                <label class="form-check-label">

                                    JazzCash

                                </label>

                            </div>

                            <div class="form-check">

                                <input
                                    class="form-check-input"
                                    type="radio"
                                    name="payment_method"
                                    value="EasyPaisa">

                                <label class="form-check-label">

                                    EasyPaisa

                                </label>

                            </div>

                        </div>

                    </div>

                </div>

                <div class="col-lg-4">

                    <div class="card shadow-sm border-0 sticky-top">

                        <div class="card-header bg-white">

                            <h4 class="mb-0">

                                Order Summary

                            </h4>

                        </div>

                        <div class="card-body">

                            <?php foreach($items as $item): ?>

                                <div class="d-flex justify-content-between mb-3">

                                    <div>

                                        <strong>

                                            <?= e($item['name']) ?>

                                        </strong>

                                        <br>

                                        <small class="text-muted">

                                            Qty :

                                            <?= $item['quantity'] ?>

                                        </small>

                                    </div>

                                    <strong>

                                        <?= CURRENCY_SYMBOL ?>

                                        <?= number_format($item['total_price']) ?>

                                    </strong>

                                </div>

                            <?php endforeach; ?>

                            <hr>

                            <div class="mb-3">

                                <label class="form-label">

                                    Coupon Code

                                </label>

                                <input
                                    type="text"
                                    name="coupon"
                                    class="form-control"
                                    placeholder="Optional">

                            </div>

                            <div class="d-flex justify-content-between mb-2">

                                <span>

                                    Subtotal

                                </span>

                                <strong>

                                    <?= CURRENCY_SYMBOL ?>

                                    <?= number_format($subtotal) ?>

                                </strong>

                            </div>

                            <div class="d-flex justify-content-between mb-2">

                                <span>

                                    Tax

                                </span>

                                <strong>

                                    <?= CURRENCY_SYMBOL ?>

                                    <?= number_format($tax) ?>

                                </strong>

                            </div>

                            <div class="d-flex justify-content-between mb-2">

                                <span>

                                    Delivery Charges

                                </span>

                                <strong>

                                    <?= CURRENCY_SYMBOL ?>

                                    <?= number_format($delivery) ?>

                                </strong>

                            </div>

                            <hr>

                            <div class="d-flex justify-content-between mb-4">

                                <h5>

                                    Grand Total

                                </h5>

                                <h5 class="text-primary">

                                    <?= CURRENCY_SYMBOL ?>

                                    <?= number_format($grandTotal) ?>

                                </h5>

                            </div>

                            <button
                                type="submit"
                                class="btn btn-success w-100">

                                <i class="fa-solid fa-lock me-2"></i>

                                Place Order

                            </button>

                        </div>

                    </div>

                </div>

            </div>

        </form>

    </div>

</section>

<script>

const orderType = document.getElementById("orderType");

const address = document.querySelector(
    "textarea[name='address']"
);

orderType.addEventListener("change", function(){

    if(this.value === "Pickup"){

        address.value = "";

        address.setAttribute("disabled","disabled");

    }else{

        address.removeAttribute("disabled");

    }

});

</script>

<?php

require APP_PATH . '/Views/layouts/footer.php';

?>