<?php

require APP_PATH . '/Views/layouts/header.php';

?>

<section class="page-header bg-light py-5">

    <div class="container">

        <h1 class="fw-bold">
            Shopping Cart
        </h1>

        <p class="text-muted mb-0">
            Review your selected items before checkout.
        </p>

    </div>

</section>

<section class="cart-section py-5">

    <div class="container">

        <?php if(empty($items)): ?>

            <div class="text-center py-5">

                <img
                    src="<?= asset('images/empty-cart.svg') ?>"
                    class="img-fluid mb-4"
                    style="max-width:220px;"
                    alt="Empty Cart">

                <h3>Your cart is empty</h3>

                <p class="text-muted">

                    Looks like you haven't added anything yet.

                </p>

                <a
                    href="<?= BASE_URL ?>/menu"
                    class="btn btn-primary">

                    Browse Menu

                </a>

            </div>

        <?php else: ?>

        <div class="row">

            <div class="col-lg-8">

                <?php foreach($items as $item): ?>

                <div
                    class="card border-0 shadow-sm mb-4 cart-item"
                    data-id="<?= $item['id'] ?>">

                    <div class="card-body">

                        <div class="row align-items-center">

                            <div class="col-lg-2">

                                <img
                                    src="<?= asset($item['image']) ?>"
                                    class="img-fluid rounded"
                                    alt="<?= e($item['name']) ?>">

                            </div>

                            <div class="col-lg-4">

                                <h5 class="mb-1">

                                    <?= e($item['name']) ?>

                                </h5>

                                <small class="text-muted d-block">

                                    Size :
                                    <?= e($item['size_name']) ?>

                                </small>

                                <small class="text-muted d-block">

                                    Sugar :
                                    <?= e($item['sugar_level']) ?>

                                </small>

                                <small class="text-muted d-block">

                                    Ice :
                                    <?= e($item['ice_level']) ?>

                                </small>

                            </div>

                            <div class="col-lg-2">

                                <strong>

                                    <?= CURRENCY_SYMBOL ?>

                                    <?= number_format($item['unit_price']) ?>

                                </strong>

                            </div>

                            <div class="col-lg-2">

                                <input
                                    type="number"
                                    min="1"
                                    value="<?= $item['quantity'] ?>"
                                    class="form-control quantity-input">

                            </div>

                            <div class="col-lg-2 text-end">

                                <strong class="d-block mb-2">

                                    <?= CURRENCY_SYMBOL ?>

                                    <?= number_format($item['total_price']) ?>

                                </strong>

                                <button
                                    class="btn btn-sm btn-danger remove-item">

                                    <i class="fa-solid fa-trash"></i>

                                </button>

                            </div>

                        </div>

                    </div>

                </div>

                <?php endforeach; ?>

            </div>

            <div class="col-lg-4">

                <div class="card shadow-sm border-0">

                    <div class="card-body">

                        <h4 class="mb-4">

                            Order Summary

                        </h4>

                        <div class="mb-3">

                            <label class="form-label">

                                Coupon Code

                            </label>

                            <div class="input-group">

                                <input
                                    type="text"
                                    id="coupon"
                                    class="form-control"
                                    placeholder="Enter coupon">

                                <button
                                    id="applyCoupon"
                                    class="btn btn-primary">

                                    Apply

                                </button>

                            </div>

                        </div>

                        <hr>

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

                                Delivery

                            </span>

                            <strong>

                                <?= CURRENCY_SYMBOL ?>

                                <?= number_format($delivery) ?>

                            </strong>

                        </div>

                        <hr>

                        <div class="d-flex justify-content-between mb-4">

                            <h5>

                                Total

                            </h5>

                            <h5>

                                <?= CURRENCY_SYMBOL ?>

                                <?= number_format($grandTotal) ?>

                            </h5>

                        </div>

                        <a
                            href="<?= BASE_URL ?>/checkout"
                            class="btn btn-success w-100 mb-3">

                            Proceed To Checkout

                        </a>

                        <a
                            href="<?= BASE_URL ?>/menu"
                            class="btn btn-outline-secondary w-100">

                            Continue Shopping

                        </a>

                    </div>

                </div>

            </div>

        </div>

        <?php endif; ?>

    </div>

</section>

<script>

document.querySelectorAll(".quantity-input").forEach(function(input){

    input.addEventListener("change",function(){

        const cart=this.closest(".cart-item");

        fetch("<?= BASE_URL ?>/cart/update",{

            method:"POST",

            headers:{
                "Content-Type":"application/x-www-form-urlencoded"
            },

            body:new URLSearchParams({

                cart_id:cart.dataset.id,

                quantity:this.value

            })

        }).then(()=>{

            location.reload();

        });

    });

});

document.querySelectorAll(".remove-item").forEach(function(button){

    button.addEventListener("click",function(){

        if(!confirm("Remove this item?")){

            return;

        }

        const cart=this.closest(".cart-item");

        fetch("<?= BASE_URL ?>/cart/remove",{

            method:"POST",

            headers:{
                "Content-Type":"application/x-www-form-urlencoded"
            },

            body:new URLSearchParams({

                cart_id:cart.dataset.id

            })

        }).then(()=>{

            location.reload();

        });

    });

});

const coupon=document.getElementById("applyCoupon");

if(coupon){

coupon.addEventListener("click",function(){

    fetch("<?= BASE_URL ?>/cart/coupon",{

        method:"POST",

        headers:{
            "Content-Type":"application/x-www-form-urlencoded"
        },

        body:new URLSearchParams({

            coupon:document.getElementById("coupon").value

        })

    })

    .then(response=>response.json())

    .then(data=>{

        alert(data.message);

        if(data.status){

            location.reload();

        }

    });

});

}

</script>

<?php

require APP_PATH . '/Views/layouts/footer.php';

?>