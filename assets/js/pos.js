/*
|--------------------------------------------------------------------------
| POS Variables
|--------------------------------------------------------------------------
*/

let cart = [];

let currentProduct = null;

let selectedVariant = null;

let selectedExtras = [];

/*
|--------------------------------------------------------------------------
| Product Modal
|--------------------------------------------------------------------------
*/

const productModal = new bootstrap.Modal(

    document.getElementById("productModal")

);

/*
|--------------------------------------------------------------------------
| Open Product
|--------------------------------------------------------------------------
*/

document.querySelectorAll(".add-product").forEach(button => {

    button.addEventListener("click", function(){

        currentProduct = {

            id: this.dataset.id,

            name: this.dataset.name,

            image: this.dataset.image

        };

        loadProduct(

            currentProduct.id

        );

    });

});

/*
|--------------------------------------------------------------------------
| Load Product AJAX
|--------------------------------------------------------------------------
*/

function loadProduct(productId){

    fetch(

        BASE_URL + "/admin/pos/product/" + productId

    )

    .then(response => response.json())

    .then(product => {

        document.getElementById(

            "productTitle"

        ).innerHTML = product.name;

        document.getElementById(

            "productImage"

        ).src = product.image;

        renderVariants(

            product.variants

        );

        renderSugar(

            product.sugar_levels

        );

        renderIce(

            product.ice_levels

        );

        renderExtras(

            product.extras

        );

        calculateTotal();

        productModal.show();

    });

}
/*
|--------------------------------------------------------------------------
| Variants
|--------------------------------------------------------------------------
*/

function renderVariants(variants){

    let html = "";

    variants.forEach((variant,index)=>{

        html += `

        <label
            class="btn btn-outline-primary me-2 mb-2">

            <input

                class="variant-radio"

                type="radio"

                name="variant"

                value="${variant.id}"

                data-price="${variant.price}"

                ${index===0?'checked':''}

            >

            ${variant.name}

            <br>

            Rs. ${variant.price}

        </label>

        `;

    });

    document.getElementById(

        "variantContainer"

    ).innerHTML = html;

    document.querySelectorAll(

        ".variant-radio"

    ).forEach(radio=>{

        radio.onchange=function(){

            calculateTotal();

        }

    });

}
/*
|--------------------------------------------------------------------------
| Extras
|--------------------------------------------------------------------------
*/

function renderExtras(extras){

    let html="";

    extras.forEach(extra=>{

        html+=`

        <div class="form-check mb-2">

            <input

                class="form-check-input extra-checkbox"

                type="checkbox"

                value="${extra.id}"

                data-price="${extra.price}"

            >

            <label
                class="form-check-label">

                ${extra.name}

                (+ Rs.${extra.price})

            </label>

        </div>

        `;

    });

    document.getElementById(

        "extraContainer"

    ).innerHTML=html;

    document.querySelectorAll(

        ".extra-checkbox"

    ).forEach(item=>{

        item.onchange=function(){

            calculateTotal();

        }

    });

}
/*
|--------------------------------------------------------------------------
| Total Calculation
|--------------------------------------------------------------------------
*/

function calculateTotal(){

    let variantPrice = 0;

    const variant = document.querySelector(

        ".variant-radio:checked"

    );

    if(variant){

        variantPrice = parseFloat(

            variant.dataset.price

        );

    }

    let extras = 0;

    document.querySelectorAll(

        ".extra-checkbox:checked"

    ).forEach(item=>{

        extras += parseFloat(

            item.dataset.price

        );

    });

    const qty = parseInt(

        document.getElementById(

            "productQty"

        ).value

    );

    const total = (

        variantPrice + extras

    ) * qty;

    document.getElementById(

        "modalTotal"

    ).innerHTML =

        "Rs. " +

        total.toFixed(0);

}
/*
|--------------------------------------------------------------------------
| Quantity Change
|--------------------------------------------------------------------------
*/

document.getElementById(

    "productQty"

).addEventListener(

    "input",

    calculateTotal

);
/*
|--------------------------------------------------------------------------
| Live Cart
|--------------------------------------------------------------------------
*/

function renderCart(){

    let subtotal=0;

    let html="";

    cart.forEach((item,index)=>{

        subtotal +=

            item.price *

            item.quantity;

        html+=`

<div class="border-bottom p-3">

<div class="fw-bold">

${item.name}

</div>

<div class="small text-muted mb-2">

Qty : ${item.quantity}

</div>

<div class="small text-success mb-2">

${item.extras.map(e=>e.name).join("<br>")}

</div>

<div class="d-flex justify-content-between">

<strong>

Rs. ${(item.price*item.quantity).toFixed(0)}

</strong>

<button

class="btn btn-sm btn-outline-danger"

onclick="removeItem(${index})">

×

</button>

</div>

</div>

`;

    });

    document.getElementById(

        "cartItems"

    ).innerHTML=html;

    const tax=subtotal*0.05;

    const grand=subtotal+tax;

    document.getElementById(

        "subtotal"

    ).innerHTML="Rs. "+subtotal.toFixed(0);

    document.getElementById(

        "tax"

    ).innerHTML="Rs. "+tax.toFixed(0);

    document.getElementById(

        "grandTotal"

    ).innerHTML="Rs. "+grand.toFixed(0);

}
/*
|--------------------------------------------------------------------------
| Remove Item
|--------------------------------------------------------------------------
*/

function removeItem(index){

    cart.splice(

        index,

        1

    );

    renderCart();

}