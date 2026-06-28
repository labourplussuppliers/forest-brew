<?php

class CartController extends Controller
{
    private Cart $cartModel;
    private Product $productModel;
    private Coupon $couponModel;

    public function __construct()
    {
        parent::__construct();

        $this->cartModel = new Cart($this->db);
        $this->productModel = new Product($this->db);
        $this->couponModel = new Coupon($this->db);
    }

    /*
    |--------------------------------------------------------------------------
    | Cart Page
    |--------------------------------------------------------------------------
    */

    public function index(): void
    {
        if (!isset($_SESSION['user'])) {

            $this->redirect(
                BASE_URL . '/login'
            );

        }

        $userId = $_SESSION['user']['id'];

        $items = $this->cartModel->items(
            $userId
        );

        $subtotal = $this->cartModel->subtotal(
            $userId
        );

        $tax = $this->cartModel->tax(
            $userId
        );

        $delivery = $this->cartModel->delivery(
            $subtotal
        );

        $grandTotal = $this->cartModel->grandTotal(
            $userId
        );

        $this->view(
            'cart/index',
            [

                'pageTitle' => 'Shopping Cart',

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
    | Add Item
    |--------------------------------------------------------------------------
    */

    public function store(): void
    {
        if (!$this->request->isMethod('POST')) {

            $this->response->json([

                'status' => false

            ],405);

        }

        if (!isset($_SESSION['user'])) {

            $this->response->json([

                'status' => false,

                'message' => 'Please login first.'

            ],401);

        }

        $productId = (int)$this->request->input(
            'product_id'
        );

        $variantId = (int)$this->request->input(
            'variant_id'
        );

        $quantity = max(
            1,
            (int)$this->request->input(
                'quantity',
                1
            )
        );

        $product = $this->productModel
            ->find($productId);

        if (!$product) {

            $this->response->json([

                'status' => false,

                'message' => 'Invalid product.'

            ],404);

        }

        /*
        |--------------------------------------------------------------------------
        | Variant Price
        |--------------------------------------------------------------------------
        */

        $variant = $this->db
            ->prepare("
                SELECT *
                FROM product_variants
                WHERE id = ?
                LIMIT 1
            ");

        $variant->execute([
            $variantId
        ]);

        $variant = $variant->fetch();

        if (!$variant) {

            $this->response->json([

                'status' => false,

                'message' => 'Variant not found.'

            ],404);

        }

        $this->cartModel->add([

            'user_id' => $_SESSION['user']['id'],

            'product_id' => $productId,

            'variant_id' => $variantId,

            'sugar_level_id' => $this->request->input(
                'sugar_level_id'
            ),

            'ice_level_id' => $this->request->input(
                'ice_level_id'
            ),

            'quantity' => $quantity,

            'unit_price' => $variant['price']

        ]);

        $this->response->json([

            'status' => true,

            'message' => 'Added to cart.',

            'count' => $this->cartModel->count(
                $_SESSION['user']['id']
            )

        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Update Quantity
    |--------------------------------------------------------------------------
    */

    public function update(): void
    {
        $cartId = (int)$this->request->input(
            'cart_id'
        );

        $quantity = max(
            1,
            (int)$this->request->input(
                'quantity'
            )
        );

        $this->cartModel->update(
            $cartId,
            $quantity
        );

        $this->response->json([

            'status' => true,

            'message' => 'Cart updated.'

        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Remove Item
    |--------------------------------------------------------------------------
    */

    public function destroy(): void
    {
        $cartId = (int)$this->request->input(
            'cart_id'
        );

        $this->cartModel->remove(
            $cartId
        );

        $this->response->json([

            'status' => true,

            'message' => 'Item removed.'

        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Apply Coupon
    |--------------------------------------------------------------------------
    */

    public function coupon(): void
    {
        $userId = $_SESSION['user']['id'];

        $subtotal = $this->cartModel
            ->subtotal($userId);

        $coupon = trim(
            $this->request->input('coupon')
        );

        $result = $this->couponModel
            ->apply(
                $coupon,
                $subtotal
            );

        $this->response->json(
            $result
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Clear Cart
    |--------------------------------------------------------------------------
    */

    public function clear(): void
    {
        $this->cartModel->clear(
            $_SESSION['user']['id']
        );

        $this->response->json([

            'status' => true,

            'message' => 'Cart cleared.'

        ]);
    }
}