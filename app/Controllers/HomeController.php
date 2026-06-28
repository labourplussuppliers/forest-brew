<?php

class HomeController extends Controller
{
    private Product $productModel;
    private Category $categoryModel;
    private Review $reviewModel;

    public function __construct()
    {
        parent::__construct();

        $this->productModel = new Product($this->db);
        $this->categoryModel = new Category($this->db);
        $this->reviewModel = new Review($this->db);
    }

    public function index(): void
    {
        $data = [

            'pageTitle' => 'Home',

            'featuredProducts' => $this->productModel->featured(),

            'latestProducts' => $this->productModel->latest(8),

            'popularProducts' => $this->productModel->popular(),

            'categories' => $this->categoryModel->featured(),

            'reviews' => $this->reviewModel->latest(6)

        ];

        $this->view(
            'home/index',
            $data
        );
    }
}