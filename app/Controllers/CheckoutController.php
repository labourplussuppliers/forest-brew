<?php

class CheckoutController extends Controller
{
    private Cart $cartModel;
    private Order $orderModel;
    private Product $productModel;
    private Coupon $couponModel;

    public function __construct()
    {
        parent::__construct();

        $this->cartModel = new Cart($this->db);
        $this->orderModel = new Order($this->db);
        $this->productModel = new Product($this->db);
        $this->couponModel = new Coupon($this->db);
    }

    /*
    |--------------------------------------------------------------------------
    | Checkout Page
    |--------------------------------------------------------------------------
    */

    public function index(): void
    {
        if (!isset($_SESSION['user'])) {

            $this->redirect(BASE_URL . '/login');

        }

        $userId = $_SESSION['user']['id'];

        $items = $this->cartModel->items($userId);

        if (empty($items)) {

            $this->redirect(BASE_URL . '/cart');

        }

        $subtotal = $this->cartModel->subtotal($userId);

        $tax = $this->cartModel->tax($userId);

        $delivery = $this->cartModel->delivery($subtotal);

        $grandTotal = $this->cartModel->grandTotal($userId);

        $this->view(
            'checkout/index',
            [

                'pageTitle' => 'Checkout',

                'items' => $items,

                'subtotal' => $subtotal,

                'tax' => $tax,

                'delivery' => $delivery,

                'grandTotal' => $grandTotal

            ]
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Place Order
    |--------------------------------------------------------------------------
    */

    public function store(): void
    {
        if (!$this->request->isMethod('POST')) {

            $this->redirect(BASE_URL . '/checkout');

        }

        if (!isset($_SESSION['user'])) {

            $this->redirect(BASE_URL . '/login');

        }

        $userId = $_SESSION['user']['id'];

        $items = $this->cartModel->items($userId);

        if (empty($items)) {

            setFlash(
                'error',
                'Your cart is empty.'
            );

            $this->redirect(BASE_URL . '/cart');
        }

        try {

            $this->db->beginTransaction();

            $subtotal = $this->cartModel->subtotal($userId);

            $tax = $this->cartModel->tax($userId);

            $delivery = $this->cartModel->delivery($subtotal);

            $grandTotal = $subtotal + $tax + $delivery;

            $couponId = null;

            $discount = 0;

            if (!empty($_POST['coupon'])) {

                $coupon = $this->couponModel->apply(
                    $_POST['coupon'],
                    $subtotal
                );

                if ($coupon['status']) {

                    $couponId = $coupon['coupon_id'];

                    $discount = $coupon['discount'];

                    $grandTotal -= $discount;

                    $this->couponModel->increaseUsage(
                        $couponId
                    );

                }

            }

            $orderId = $this->orderModel->create([

                'order_number' => $this->orderModel
                    ->generateOrderNumber(),

                'user_id' => $userId,

                'address_id' => $_POST['address_id'],

                'coupon_id' => $couponId,

                'subtotal' => $subtotal,

                'discount' => $discount,

                'delivery_charges' => $delivery,

                'tax' => $tax,

                'grand_total' => $grandTotal,

                'payment_method' => $_POST['payment_method'],

                'payment_status' => 'Pending',

                'order_type' => $_POST['order_type'],

                'order_status' => 'Pending',

                'notes' => $_POST['notes']

            ]);

            foreach ($items as $item) {

                $this->orderModel->addItem([

                    'order_id' => $orderId,

                    'product_id' => $item['product_id'],

                    'variant_id' => $item['variant_id'],

                    'sugar_level_id' => $item['sugar_level_id'],

                    'ice_level_id' => $item['ice_level_id'],

                    'quantity' => $item['quantity'],

                    'unit_price' => $item['unit_price'],

                    'total_price' => $item['total_price']

                ]);

                $this->productModel->updateStock(

                    $item['product_id'],

                    $item['quantity']

                );

            }

            $this->cartModel->clear($userId);

            $this->db->commit();

            setFlash(

                'success',

                'Your order has been placed successfully.'

            );

            $this->redirect(

                BASE_URL . '/orders/' . $orderId

            );

        } catch (Throwable $e) {

            $this->db->rollBack();

            if (APP_DEBUG) {

                die($e->getMessage());

            }

            setFlash(

                'error',

                'Unable to process your order.'

            );

            $this->redirect(

                BASE_URL . '/checkout'

            );

        }
    }
}