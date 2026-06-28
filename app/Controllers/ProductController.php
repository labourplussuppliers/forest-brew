<?php

class ProductController extends Controller
{
    private Product $productModel;
    private Review $reviewModel;
    private Cart $cartModel;

    public function __construct()
    {
        parent::__construct();

        $this->productModel = new Product($this->db);
        $this->reviewModel = new Review($this->db);
        $this->cartModel = new Cart($this->db);
    }

    /*
    |--------------------------------------------------------------------------
    | Product Details
    |--------------------------------------------------------------------------
    */

    public function show(string $slug): void
    {
        $product = $this->productModel
            ->findBySlug($slug);

        if (!$product) {

            $this->response->abort(404);

            return;
        }

        $reviews = $this->reviewModel
            ->productReviews($product['id']);

        $averageRating = $this->reviewModel
            ->averageRating($product['id']);

        $ratingBreakdown = $this->reviewModel
            ->ratingBreakdown($product['id']);

        $relatedProducts = $this->productModel
            ->related(
                $product['category_id'],
                $product['id']
            );

        $data = [

            'pageTitle' => $product['name'],

            'product' => $product,

            'reviews' => $reviews,

            'averageRating' => $averageRating,

            'ratingBreakdown' => $ratingBreakdown,

            'relatedProducts' => $relatedProducts

        ];

        $this->view(
            'product/show',
            $data
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Add To Cart
    |--------------------------------------------------------------------------
    */

    public function addToCart(): void
    {
        if (!$this->request->isMethod('POST')) {

            $this->redirect(
                BASE_URL . '/menu'
            );

        }

        if (!isset($_SESSION['user'])) {

            $this->response->json([

                'status' => false,

                'message' => 'Please login first.'

            ],401);

        }

        $productId = (int)$this->request->input('product_id');

        $variantId = (int)$this->request->input('variant_id');

        $quantity = max(
            1,
            (int)$this->request->input('quantity',1)
        );

        $product = $this->productModel
            ->find($productId);

        if (!$product) {

            $this->response->json([

                'status'=>false,

                'message'=>'Product not found.'

            ],404);

        }

        $this->cartModel->add([

            'user_id'=>$_SESSION['user']['id'],

            'product_id'=>$productId,

            'variant_id'=>$variantId,

            'sugar_level_id'=>$this->request->input('sugar_level_id'),

            'ice_level_id'=>$this->request->input('ice_level_id'),

            'quantity'=>$quantity,

            'unit_price'=>$product['price']

        ]);

        $this->response->json([

            'status'=>true,

            'message'=>'Product added successfully.',

            'cart_count'=>$this->cartModel->count(
                $_SESSION['user']['id']
            )

        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Search
    |--------------------------------------------------------------------------
    */

    public function search(): void
    {
        $keyword = trim(
            $this->request->input('q')
        );

        $products = $this->productModel
            ->search($keyword);

        $this->view(
            'product/search',
            [

                'pageTitle'=>'Search',

                'keyword'=>$keyword,

                'products'=>$products

            ]
        );
    }
}