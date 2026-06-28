<?php

class DashboardController extends Controller
{
    private Product $productModel;
    private Category $categoryModel;
    private Order $orderModel;
    private User $userModel;
    private Review $reviewModel;
    private Coupon $couponModel;

    public function __construct()
    {
        parent::__construct();

        $this->productModel = new Product($this->db);
        $this->categoryModel = new Category($this->db);
        $this->orderModel = new Order($this->db);
        $this->userModel = new User($this->db);
        $this->reviewModel = new Review($this->db);
        $this->couponModel = new Coupon($this->db);

        if (!$this->session->check()) {

            $this->redirect(BASE_URL . '/login');

        }

        if (!$this->session->user()['is_admin']) {

            $this->redirect(BASE_URL);

        }
    }

    public function index(): void
    {
        $data = [

            'pageTitle' => 'Dashboard',

            'totalProducts' => $this->productModel->count(),

            'totalCategories' => $this->categoryModel->count(),

            'totalCustomers' => $this->userModel->customerCount(),

            'totalOrders' => $this->orderModel->totalOrders(),

            'pendingOrders' => $this->orderModel->pendingOrders(),

            'completedOrders' => $this->orderModel->completedOrders(),

            'todaySales' => $this->orderModel->todaySales(),

            'monthlySales' => $this->orderModel->monthlySales(),

            'pendingReviews' => $this->reviewModel->pendingCount(),

            'activeCoupons' => $this->couponModel->activeCount(),

            'latestOrders' => array_slice(
                $this->orderModel->all(),
                0,
                10
            ),

            'lowStockProducts' => $this->productModel->lowStock()

        ];

        $this->view(
            'admin/dashboard/index',
            $data
        );
    }
}