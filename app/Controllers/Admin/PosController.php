<?php

class PosController extends Controller
{
    private Product $productModel;
    private Category $categoryModel;
    private Customer $customerModel;
    private Order $orderModel;

    public function __construct()
    {
        parent::__construct();

        $this->productModel = new Product($this->db);
        $this->categoryModel = new Category($this->db);
        $this->customerModel = new Customer($this->db);
        $this->orderModel = new Order($this->db);

        if (!$this->session->check()) {

            $this->redirect(BASE_URL . '/login');

        }

        if (!$this->session->user()['is_admin']) {

            $this->redirect(BASE_URL);

        }
    }

    /*
    |--------------------------------------------------------------------------
    | POS Screen
    |--------------------------------------------------------------------------
    */

    public function index(): void
    {
        $data = [

            'pageTitle' => 'Point Of Sale',

            'categories' => $this->categoryModel->active(),

            'products' => $this->productModel->available(),

            'customers' => $this->customerModel->active(),

            'tables' => $this->db->query("
                SELECT *
                FROM restaurant_tables
                WHERE status='Available'
                ORDER BY table_number
            ")->fetchAll()

        ];

        $this->view(

            'admin/pos/index',

            $data

        );
    }

    /*
    |--------------------------------------------------------------------------
    | Product Search
    |--------------------------------------------------------------------------
    */

    public function search(): void
    {
        $keyword = trim(

            $this->request->input('search')

        );

        $products = $this->productModel->search(

            $keyword

        );

        $this->response->json(

            $products

        );
    }

    /*
    |--------------------------------------------------------------------------
    | Place POS Order
    |--------------------------------------------------------------------------
    */

    public function checkout(): void
    {
        if (!$this->request->isMethod('POST')) {

            $this->redirect(

                BASE_URL . '/admin/pos'

            );

        }

        try {

            $this->db->beginTransaction();

            $orderId = $this->orderModel->createPosOrder([

                'customer_id' => $_POST['customer_id'],

                'table_id' => $_POST['table_id'],

                'order_type' => $_POST['order_type'],

                'payment_method' => $_POST['payment_method'],

                'payment_status' => 'Paid',

                'order_status' => 'Completed',

                'cashier_id' => $this->session->user()['id'],

                'subtotal' => $_POST['subtotal'],

                'discount' => $_POST['discount'],

                'tax' => $_POST['tax'],

                'grand_total' => $_POST['grand_total']

            ]);

            foreach($_POST['items'] as $item){

                $this->orderModel->addItem([

                    'order_id' => $orderId,

                    'product_id' => $item['product_id'],

                    'variant_id' => $item['variant_id'],

                    'quantity' => $item['quantity'],

                    'unit_price' => $item['price'],

                    'total_price' => $item['total']

                ]);

                $this->productModel->updateStock(

                    $item['product_id'],

                    $item['quantity']

                );

            }

            $this->db->commit();

            $this->response->json([

                'status' => true,

                'order_id' => $orderId,

                'redirect' => BASE_URL .
                    '/admin/orders/print/' .
                    $orderId

            ]);

        } catch(Throwable $e){

            $this->db->rollBack();

            $this->response->json([

                'status' => false,

                'message' => APP_DEBUG
                    ? $e->getMessage()
                    : 'Unable to complete transaction.'

            ]);

        }
    }

    /*
    |--------------------------------------------------------------------------
    | Hold Order
    |--------------------------------------------------------------------------
    */

    public function hold(): void
    {
        $this->response->json([

            'status' => true,

            'message' => 'Order saved successfully.'

        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Resume Order
    |--------------------------------------------------------------------------
    */

    public function resume(): void
    {
        $orders = $this->orderModel->heldOrders();

        $this->response->json(

            $orders

        );
    }

    /*
    |--------------------------------------------------------------------------
    | Today's Sales
    |--------------------------------------------------------------------------
    */

    public function salesSummary(): void
    {
        $summary = [

            'orders' => $this->orderModel->todayOrders(),

            'sales' => $this->orderModel->todaySales(),

            'customers' => $this->customerModel->todayCustomers()

        ];

        $this->response->json(

            $summary

        );
    }
}