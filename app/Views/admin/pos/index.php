<?php

require APP_PATH . '/Views/admin/layouts/header.php';

?>

<div class="container-fluid py-3">

<div class="row">

<!-- ===========================
LEFT SIDEBAR
============================ -->

<div class="col-lg-2">

<div class="card border-0 shadow-sm h-100">

<div class="card-header bg-white">

<h5 class="mb-0">

Categories

</h5>

</div>

<div
class="list-group list-group-flush category-list">

<button
class="list-group-item list-group-item-action active"
data-category="all">

All Items

</button>

<?php foreach($categories as $category): ?>

<button
class="list-group-item list-group-item-action"
data-category="<?= $category['id'] ?>">

<?php if($category['icon']): ?>

<i class="<?= e($category['icon']) ?> me-2"></i>

<?php endif; ?>

<?= e($category['name']) ?>

</button>

<?php endforeach; ?>

</div>

</div>

</div>

<!-- ===========================
CENTER
============================ -->

<div class="col-lg-7">

<div class="card border-0 shadow-sm mb-3">

<div class="card-body">

<div class="row">

<div class="col-lg-8">

<input

id="productSearch"

type="text"

class="form-control form-control-lg"

placeholder="Search products...">

</div>

<div class="col-lg-4">

<select

id="customer"

class="form-select form-select-lg">

<option value="">

Walk-in Customer

</option>

<?php foreach($customers as $customer): ?>

<option value="<?= $customer['id'] ?>">

<?= e($customer['first_name']) ?>

<?= e($customer['last_name']) ?>

</option>

<?php endforeach; ?>

</select>

</div>

</div>

</div>

</div>

<div class="row" id="productGrid">

<?php foreach($products as $product): ?>

<div

class="col-xl-3 col-lg-4 col-md-6 mb-4 product-card"

data-category="<?= $product['category_id'] ?>">

<div class="card h-100 border-0 shadow-sm">

<img

src="<?= asset($product['image']) ?>"

class="card-img-top"

style="height:180px;object-fit:cover;">

<div class="card-body">

<h6>

<?= e($product['name']) ?>

</h6>

<p class="text-muted small mb-2">

<?= e($product['category_name']) ?>

</p>

<button

class="btn btn-primary w-100 add-product"

data-id="<?= $product['id'] ?>"

data-name="<?= e($product['name']) ?>"

data-price="<?= $product['starting_price'] ?>">

Add

</button>

</div>

</div>

</div>

<?php endforeach; ?>

</div>

</div>

<!-- ===========================
RIGHT CART
============================ -->

<div class="col-lg-3">

<div class="card border-0 shadow-sm">

<div class="card-header bg-dark text-white">

<h5 class="mb-0">

Current Order

</h5>

</div>

<div
class="card-body p-0">

<div

id="cartItems"

style="height:400px;overflow:auto;">

<div class="text-center text-muted py-5">

Cart is empty

</div>

</div>

</div>

<div class="card-footer bg-white">

<div class="mb-3">

<label>

Order Type

</label>

<select

id="orderType"

class="form-select">

<option>

Dine In

</option>

<option>

Takeaway

</option>

<option>

Delivery

</option>

</select>

</div>

<div class="mb-3">

<label>

Table

</label>

<select

id="table"

class="form-select">

<option value="">

Select Table

</option>

<?php foreach($tables as $table): ?>

<option value="<?= $table['id'] ?>">

Table <?= $table['table_number'] ?>

</option>

<?php endforeach; ?>

</select>

</div>

<hr>

<div class="d-flex justify-content-between">

<span>

Subtotal

</span>

<strong id="subtotal">

Rs. 0

</strong>

</div>

<div class="d-flex justify-content-between">

<span>

Discount

</span>

<strong id="discount">

Rs. 0

</strong>

</div>

<div class="d-flex justify-content-between">

<span>

Tax

</span>

<strong id="tax">

Rs. 0

</strong>

</div>

<hr>

<div class="d-flex justify-content-between mb-3">

<h5>

Total

</h5>

<h5 id="grandTotal">

Rs. 0

</h5>

</div>

<div class="mb-3">

<label>

Payment Method

</label>

<select

id="paymentMethod"

class="form-select">

<option>

Cash

</option>

<option>

Card

</option>

<option>

JazzCash

</option>

<option>

EasyPaisa

</option>

</select>

</div>

<div class="row">

<div class="col-6">

<button

id="holdOrder"

class="btn btn-warning w-100">

Hold

</button>

</div>

<div class="col-6">

<button

id="clearCart"

class="btn btn-secondary w-100">

Clear

</button>

</div>

</div>

<button

id="checkout"

class="btn btn-success w-100 mt-3">

Complete Sale

</button>

</div>

</div>

</div>

</div>

</div>

<script>

let cart=[];

document.querySelectorAll(".add-product").forEach(button=>{

button.addEventListener("click",function(){

const id=this.dataset.id;

const name=this.dataset.name;

const price=parseFloat(this.dataset.price);

const existing=cart.find(item=>item.id==id);

if(existing){

existing.qty++;

}else{

cart.push({

id,

name,

price,

qty:1

});

}

renderCart();

});

});

function renderCart(){

const container=document.getElementById("cartItems");

container.innerHTML="";

let subtotal=0;

cart.forEach((item,index)=>{

subtotal+=item.price*item.qty;

container.innerHTML+=`

<div class="border-bottom p-3">

<strong>${item.name}</strong>

<div class="d-flex justify-content-between mt-2">

<div>

<button class="btn btn-sm btn-light" onclick="decrease(${index})">-</button>

<span class="mx-2">${item.qty}</span>

<button class="btn btn-sm btn-light" onclick="increase(${index})">+</button>

</div>

<strong>

Rs. ${(item.price*item.qty).toFixed(0)}

</strong>

</div>

</div>

`;

});

const tax=subtotal*0.05;

const total=subtotal+tax;

document.getElementById("subtotal").innerHTML="Rs. "+subtotal.toFixed(0);

document.getElementById("tax").innerHTML="Rs. "+tax.toFixed(0);

document.getElementById("grandTotal").innerHTML="Rs. "+total.toFixed(0);

if(cart.length===0){

container.innerHTML='<div class="text-center text-muted py-5">Cart is empty</div>';

}

}

function increase(i){

cart[i].qty++;

renderCart();

}

function decrease(i){

cart[i].qty--;

if(cart[i].qty<=0){

cart.splice(i,1);

}

renderCart();

}

document.getElementById("clearCart").onclick=function(){

cart=[];

renderCart();

};

</script>
<?php

include APP_PATH .
'/Views/admin/pos/components/product-modal.php';

?>
<?php

require APP_PATH . '/Views/admin/layouts/footer.php';

?>