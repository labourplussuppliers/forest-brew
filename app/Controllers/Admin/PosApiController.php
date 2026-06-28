<?php

class PosApiController extends Controller
{
    private Product $productModel;
    private Customer $customerModel;
    private Order $orderModel;
    private Coupon $couponModel;

    public function __construct()
    {
        parent::__construct();

        $this->productModel = new Product($this->db);
        $this->customerModel = new Customer($this->db);
        $this->orderModel = new Order($this->db);
        $this->couponModel = new Coupon($this->db);

        if (
            !$this->session->check() ||
            !$this->session->user()['is_admin']
        ) {
            $this->response->json([
                'status' => false,
                'message' => 'Unauthorized'
            ],401);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Product Details
    |--------------------------------------------------------------------------
    */

    public function product(int $id): void
    {
        $product = $this->productModel->find($id);

        if(!$product){

            $this->response->json([
                'status'=>false,
                'message'=>'Product not found.'
            ]);

            return;
        }

        $variants = $this->db->prepare("
            SELECT *
            FROM product_variants
            WHERE product_id=?
            ORDER BY sort_order ASC
        ");

        $variants->execute([$id]);

        $extras = $this->db->prepare("
            SELECT *
            FROM extras
            WHERE status=1
            ORDER BY name ASC
        ");

        $extras->execute();

        $this->response->json([

            'status'=>true,

            'id'=>$product['id'],

            'name'=>$product['name'],

            'image'=>asset($product['image']),

            'description'=>$product['short_description'],

            'variants'=>$variants->fetchAll(),

            'extras'=>$extras->fetchAll(),

            'sugar_levels'=>[
                '0%',
                '25%',
                '50%',
                '75%',
                '100%'
            ],

            'ice_levels'=>[
                'No Ice',
                'Less Ice',
                'Regular',
                'Extra Ice'
            ]

        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Product Search
    |--------------------------------------------------------------------------
    */

    public function searchProducts(): void
    {
        $keyword = trim(
            $this->request->input('keyword')
        );

        $stmt = $this->db->prepare("
            SELECT
                id,
                name,
                image,
                slug
            FROM products
            WHERE
                status=1
            AND
            (
                name LIKE ?
                OR sku LIKE ?
            )
            ORDER BY name
            LIMIT 20
        ");

        $stmt->execute([

            "%{$keyword}%",

            "%{$keyword}%"

        ]);

        $this->response->json(

            $stmt->fetchAll()

        );
    }

    /*
    |--------------------------------------------------------------------------
    | Customer Search
    |--------------------------------------------------------------------------
    */

    public function searchCustomers(): void
    {
        $keyword = trim(
            $this->request->input('keyword')
        );

        $stmt = $this->db->prepare("
            SELECT
                id,
                first_name,
                last_name,
                phone
            FROM users
            WHERE
                role='customer'
            AND
            (
                first_name LIKE ?
                OR last_name LIKE ?
                OR phone LIKE ?
            )
            LIMIT 15
        ");

        $stmt->execute([

            "%{$keyword}%",

            "%{$keyword}%",

            "%{$keyword}%"

        ]);

        $this->response->json(

            $stmt->fetchAll()

        );
    }

    /*
    |--------------------------------------------------------------------------
    | Validate Coupon
    |--------------------------------------------------------------------------
    */

    public function coupon(): void
    {
        $code = strtoupper(

            trim(
                $this->request->input('code')
            )

        );

        $coupon = $this->couponModel
            ->findByCode($code);

        if(!$coupon){

            $this->response->json([

                'status'=>false,

                'message'=>'Invalid coupon.'

            ]);

            return;
        }

        if(!$coupon['status']){

            $this->response->json([

                'status'=>false,

                'message'=>'Coupon inactive.'

            ]);

            return;
        }

        $this->response->json([

            'status'=>true,

            'coupon'=>$coupon

        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Save Hold Order
    |--------------------------------------------------------------------------
    */

    public function hold(): void
    {
        $stmt = $this->db->prepare("
            INSERT INTO held_orders(

                cashier_id,
                customer_id,
                cart_json

            )
            VALUES(?,?,?)
        ");

        $stmt->execute([

            $this->session->user()['id'],

            $_POST['customer_id'],

            json_encode($_POST['cart'])

        ]);

        $this->response->json([

            'status'=>true,

            'message'=>'Order saved.'

        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Resume Hold Orders
    |--------------------------------------------------------------------------
    */

    public function heldOrders(): void
    {
        $stmt = $this->db->prepare("
            SELECT *
            FROM held_orders
            ORDER BY id DESC
        ");

        $stmt->execute();

        $this->response->json(

            $stmt->fetchAll()

        );
    }

    /*
    |--------------------------------------------------------------------------
    | Delete Hold Order
    |--------------------------------------------------------------------------
    */

    public function deleteHold(
        int $id
    ): void
    {
        $stmt = $this->db->prepare("
            DELETE
            FROM held_orders
            WHERE id=?
        ");

        $stmt->execute([$id]);

        $this->response->json([

            'status'=>true

        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Today's Sales
    |--------------------------------------------------------------------------
    */

    public function todaySummary(): void
    {
        $this->response->json([

            'orders'=>$this->orderModel
                ->todayOrders(),

            'sales'=>$this->orderModel
                ->todaySales(),

            'customers'=>$this->customerModel
                ->todayCustomers(),

            'average'=>$this->orderModel
                ->todayAverage()

        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Dashboard Cards
    |--------------------------------------------------------------------------
    */

    public function dashboard(): void
    {
        $this->response->json([

            'pending'=>$this->orderModel
                ->pendingOrders(),

            'completed'=>$this->orderModel
                ->completedOrders(),

            'cancelled'=>$this->orderModel
                ->cancelledOrders(),

            'sales'=>$this->orderModel
                ->todaySales()

        ]);
    }
}