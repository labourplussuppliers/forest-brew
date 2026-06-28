<?php

class MenuController extends Controller
{
    private Product $productModel;
    private Category $categoryModel;

    public function __construct()
    {
        parent::__construct();

        $this->productModel = new Product($this->db);
        $this->categoryModel = new Category($this->db);
    }

    public function index(): void
    {
        $search = trim(
            $this->request->input('search', '')
        );

        $categorySlug = trim(
            $this->request->input('category', '')
        );

        if (!empty($search)) {

            $products = $this->productModel->search(
                $search
            );

        } elseif (!empty($categorySlug)) {

            $category = $this->categoryModel
                ->findBySlug($categorySlug);

            if (!$category) {

                $this->response->abort(404);

                return;

            }

            $products = $this->productModel
                ->byCategory($category['id']);

        } else {

            $products = $this->productModel
                ->all();

        }

        $data = [

            'pageTitle' => 'Our Menu',

            'products' => $products,

            'categories' => $this->categoryModel
                ->active(),

            'selectedCategory' => $categorySlug,

            'search' => $search

        ];

        $this->view(
            'menu/index',
            $data
        );
    }
}