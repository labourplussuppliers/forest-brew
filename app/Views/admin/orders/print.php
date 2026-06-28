<?php

$totalQuantity = 0;

foreach($items as $item){

    $totalQuantity += $item['quantity'];

}

?>
<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<title>

Invoice <?= e($order['order_number']) ?>

</title>

<meta
    name="viewport"
    content="width=device-width, initial-scale=1">

<link
    href="<?= ASSET_URL ?>/css/bootstrap.min.css"
    rel="stylesheet">

<style>

body{

    background:#fff;

    color:#222;

    font-size:14px;

}

.invoice{

    max-width:850px;

    margin:40px auto;

}

.logo{

    max-height:70px;

}

.table td,
.table th{

    vertical-align:middle;

}

.summary td{

    padding:8px 0;

}

.print-btn{

    position:fixed;

    top:20px;

    right:20px;

}

@media print{

    .print-btn{

        display:none;

    }

    body{

        margin:0;

    }

}

</style>

</head>

<body>

<button
    class="btn btn-dark print-btn"
    onclick="window.print()">

    Print Invoice

</button>

<div class="container invoice">

<div class="card border-0">

<div class="card-body">

<div class="row align-items-center mb-5">

<div class="col-md-6">

<img
    src="<?= asset('images/logo.png') ?>"
    class="logo"
    alt="Frost & Brew">

<h3 class="mt-3">

Frost & Brew

</h3>

<p class="mb-0">

Premium Coffee & Café

</p>

<p>

Lahore, Pakistan

</p>

</div>

<div class="col-md-6 text-md-end">

<h2>

INVOICE

</h2>

<table class="table table-borderless">

<tr>

<td>

Invoice #

</td>

<td>

<strong>

<?= e($order['order_number']) ?>

</strong>

</td>

</tr>

<tr>

<td>

Order Date

</td>

<td>

<?= date(

'd M Y h:i A',

strtotime($order['created_at'])

) ?>

</td>

</tr>

<tr>

<td>

Payment

</td>

<td>

<?= e($order['payment_method']) ?>

</td>

</tr>

<tr>

<td>

Status

</td>

<td>

<?= e($order['payment_status']) ?>

</td>

</tr>

</table>

</div>

</div>

<hr>

<div class="row mb-5">

<div class="col-md-6">

<h5>

Bill To

</h5>

<p class="mb-1">

<strong>

<?= e($customer['first_name']) ?>

<?= e($customer['last_name']) ?>

</strong>

</p>

<p class="mb-1">

<?= e($customer['email']) ?>

</p>

<p class="mb-1">

<?= e($customer['phone']) ?>

</p>

<p>

<?= nl2br(

e($order['delivery_address'])

) ?>

</p>

</div>

<div class="col-md-6 text-md-end">

<h5>

Order Information

</h5>

<p>

Order Type :

<strong>

<?= e($order['order_type']) ?>

</strong>

</p>

<p>

Order Status :

<strong>

<?= e($order['order_status']) ?>

</strong>

</p>

<p>

Payment :

<strong>

<?= e($order['payment_method']) ?>

</strong>

</p>

</div>

</div>

<table class="table table-bordered">

<thead class="table-light">

<tr>

<th>

#

</th>

<th>

Product

</th>

<th>

Variant

</th>

<th>

Qty

</th>

<th>

Unit Price

</th>

<th>

Total

</th>

</tr>

</thead>

<tbody>

<?php foreach($items as $index=>$item): ?>

<tr>

<td>

<?= $index+1 ?>

</td>

<td>

<?= e($item['product_name']) ?>

</td>

<td>

<?= e($item['variant_name']) ?>

</td>

<td>

<?= $item['quantity'] ?>

</td>

<td>

<?= CURRENCY_SYMBOL ?>

<?= number_format($item['unit_price']) ?>

</td>

<td>

<?= CURRENCY_SYMBOL ?>

<?= number_format($item['total_price']) ?>

</td>

</tr>

<?php endforeach; ?>

</tbody>

</table>

<div class="row mt-5">

<div class="col-md-6">

<h5>

Thank You!

</h5>

<p>

Thank you for choosing Frost & Brew.

We appreciate your business and hope to serve you again soon.

</p>

</div>

<div class="col-md-6">

<table class="table summary">

<tr>

<td>

Total Items

</td>

<td class="text-end">

<?= $totalQuantity ?>

</td>

</tr>

<tr>

<td>

Subtotal

</td>

<td class="text-end">

<?= CURRENCY_SYMBOL ?>

<?= number_format($order['subtotal']) ?>

</td>

</tr>

<tr>

<td>

Discount

</td>

<td class="text-end">

<?= CURRENCY_SYMBOL ?>

<?= number_format($order['discount']) ?>

</td>

</tr>

<tr>

<td>

Delivery

</td>

<td class="text-end">

<?= CURRENCY_SYMBOL ?>

<?= number_format($order['delivery_charges']) ?>

</td>

</tr>

<tr>

<td>

Tax

</td>

<td class="text-end">

<?= CURRENCY_SYMBOL ?>

<?= number_format($order['tax']) ?>

</td>

</tr>

<tr>

<th>

Grand Total

</th>

<th class="text-end">

<?= CURRENCY_SYMBOL ?>

<?= number_format($order['grand_total']) ?>

</th>

</tr>

</table>

</div>

</div>

<hr>

<div class="text-center mt-5">

<small>

Generated on

<?= date('d M Y h:i A') ?>

|

Powered by Frost & Brew Management System

Version <?= APP_VERSION ?>

</small>

</div>

</div>

</div>

</div>

<script>

window.onload=function(){

    window.print();

}

</script>

</body>

</html>