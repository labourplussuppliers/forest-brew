<?php

class AdminOrderController extends Controller
{
    private Order $orderModel;
    private User $userModel;

    public function __construct()
    {
        parent::__construct();

        $this->orderModel = new Order($this->db);
        $this->userModel = new User($this->db);

        if (!$this->session->check()) {
            $this->redirect(BASE_URL . '/login');
        }

        if (!$this->session->user()['is_admin']) {
            $this->redirect(BASE_URL);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Orders List
    |--------------------------------------------------------------------------
    */

    public function index(): void
    {
        $search = trim(
            $this->request->input('search')
        );

        $status = trim(
            $this->request->input('status')
        );

        $paymentStatus = trim(
            $this->request->input('payment_status')
        );

        $orderType = trim(
            $this->request->input('order_type')
        );

        $fromDate = trim(
            $this->request->input('from')
        );

        $toDate = trim(
            $this->request->input('to')
        );

        $query = "
            SELECT
                o.*,
                CONCAT(
                    u.first_name,
                    ' ',
                    u.last_name
                ) AS customer_name
            FROM orders o
            INNER JOIN users u
                ON u.id = o.user_id
            WHERE 1=1
        ";

        $params = [];

        if ($search !== '') {

            $query .= "
                AND (
                    o.order_number LIKE ?
                    OR u.first_name LIKE ?
                    OR u.last_name LIKE ?
                )
            ";

            $params[] = "%{$search}%";
            $params[] = "%{$search}%";
            $params[] = "%{$search}%";
        }

        if ($status !== '') {

            $query .= "
                AND o.order_status = ?
            ";

            $params[] = $status;
        }

        if ($paymentStatus !== '') {

            $query .= "
                AND o.payment_status = ?
            ";

            $params[] = $paymentStatus;
        }

        if ($orderType !== '') {

            $query .= "
                AND o.order_type = ?
            ";

            $params[] = $orderType;
        }

        if ($fromDate !== '') {

            $query .= "
                AND DATE(o.created_at) >= ?
            ";

            $params[] = $fromDate;
        }

        if ($toDate !== '') {

            $query .= "
                AND DATE(o.created_at) <= ?
            ";

            $params[] = $toDate;
        }

        $query .= "
            ORDER BY
                o.id DESC
        ";

        $stmt = $this->db->prepare($query);

        $stmt->execute($params);

        $orders = $stmt->fetchAll();

        $this->view(
            'admin/orders/index',
            [

                'pageTitle' => 'Orders',

                'orders' => $orders

            ]
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Order Details
    |--------------------------------------------------------------------------
    */

    public function show(
        int $id
    ): void
    {
        $order = $this->orderModel->find($id);

        if (!$order) {

            $this->response->abort(404);

        }

        $items = $this->orderModel
            ->items($id);

        $timeline = $this->orderModel
            ->timeline($id);

        $customer = $this->userModel
            ->find($order['user_id']);

        $this->view(
            'admin/orders/show',
            [

                'pageTitle' => 'Order Details',

                'order' => $order,

                'items' => $items,

                'customer' => $customer,

                'timeline' => $timeline

            ]
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Update Order Status
    |--------------------------------------------------------------------------
    */

    public function updateStatus(
        int $id
    ): void
    {
        $status = $_POST['status'];

        $stmt = $this->db->prepare("
            UPDATE orders
            SET
                order_status = ?
            WHERE id = ?
        ");

        $stmt->execute([

            $status,

            $id

        ]);

        setFlash(
            'success',
            'Order status updated.'
        );

        $this->redirect(
            BASE_URL .
            '/admin/orders/' .
            $id
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Update Payment Status
    |--------------------------------------------------------------------------
    */

    public function updatePaymentStatus(
        int $id
    ): void
    {
        $status = $_POST['payment_status'];

        $stmt = $this->db->prepare("
            UPDATE orders
            SET
                payment_status = ?
            WHERE id = ?
        ");

        $stmt->execute([

            $status,

            $id

        ]);

        setFlash(
            'success',
            'Payment status updated.'
        );

        $this->redirect(
            BASE_URL .
            '/admin/orders/' .
            $id
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Cancel Order
    |--------------------------------------------------------------------------
    */

    public function cancel(
        int $id
    ): void
    {
        $stmt = $this->db->prepare("
            UPDATE orders
            SET
                order_status = 'Cancelled'
            WHERE id = ?
        ");

        $stmt->execute([$id]);

        setFlash(
            'success',
            'Order cancelled successfully.'
        );

        $this->redirect(
            BASE_URL .
            '/admin/orders'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Delete Order
    |--------------------------------------------------------------------------
    */

    public function destroy(
        int $id
    ): void
    {
        $stmt = $this->db->prepare("
            DELETE
            FROM orders
            WHERE id = ?
        ");

        $stmt->execute([$id]);

        setFlash(
            'success',
            'Order deleted successfully.'
        );

        $this->redirect(
            BASE_URL .
            '/admin/orders'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Print Invoice
    |--------------------------------------------------------------------------
    */

    public function print(
        int $id
    ): void
    {
        $order = $this->orderModel
            ->find($id);

        $items = $this->orderModel
            ->items($id);

        require APP_PATH .
            '/Views/admin/orders/print.php';
    }
}