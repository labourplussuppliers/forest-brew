<?php

class ProductController extends Controller
{
    private Product $productModel;
    private Category $categoryModel;
    private Upload $upload;
    private Pagination $pagination;

    public function __construct()
    {
        parent::__construct();

        $this->productModel = new Product($this->db);
        $this->categoryModel = new Category($this->db);
        $this->upload = new Upload();

        if (!$this->session->check()) {
            $this->redirect(BASE_URL . '/login');
        }

        if (!$this->session->user()['is_admin']) {
            $this->redirect(BASE_URL);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Products List
    |--------------------------------------------------------------------------
    */

    public function index(): void
    {
        $search = trim(
            $this->request->input('search')
        );

        $category = (int)$this->request->input(
            'category'
        );

        $status = $this->request->input(
            'status'
        );

        $query = "
            SELECT
                p.*,
                c.name AS category_name
            FROM products p
            INNER JOIN categories c
                ON c.id=p.category_id
            WHERE 1=1
        ";

        $params = [];

        if($search){

            $query .= "
                AND (
                    p.name LIKE ?
                    OR p.sku LIKE ?
                )
            ";

            $params[]="%{$search}%";
            $params[]="%{$search}%";

        }

        if($category){

            $query .= "
                AND p.category_id=?
            ";

            $params[]=$category;

        }

        if($status!==null && $status!==""){

            $query .= "
                AND p.status=?
            ";

            $params[]=$status;

        }

        $query .= "
            ORDER BY p.id DESC
        ";

        $stmt=$this->db->prepare($query);

        $stmt->execute($params);

        $products=$stmt->fetchAll();

        $data=[

            'pageTitle'=>'Products',

            'products'=>$products,

            'categories'=>$this->categoryModel
                ->active()

        ];

        $this->view(
            'admin/products/index',
            $data
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Create Form
    |--------------------------------------------------------------------------
    */

    public function create(): void
    {
        $this->view(
            'admin/products/create',
            [

                'pageTitle'=>'Add Product',

                'categories'=>$this->categoryModel
                    ->active()

            ]
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Store Product
    |--------------------------------------------------------------------------
    */

    public function store(): void
    {
        if(
            !$this->request->isMethod('POST')
        ){
            $this->redirect(
                BASE_URL.'/admin/products'
            );
        }

        $image=null;

        if(
            !empty($_FILES['image']['name'])
        ){

            $image=$this->upload->upload(
                $_FILES['image'],
                'products'
            );

        }

        $stmt=$this->db->prepare("
            INSERT INTO products(

                category_id,
                name,
                slug,
                sku,
                short_description,
                description,
                image,
                featured,
                popular,
                stock,
                minimum_stock,
                status

            )
            VALUES(

                ?,?,?,?,?,?,?,?,?,?,?,?

            )
        ");

        $stmt->execute([

            $_POST['category_id'],

            $_POST['name'],

            slug($_POST['name']),

            $_POST['sku'],

            $_POST['short_description'],

            $_POST['description'],

            $image,

            isset($_POST['featured'])?1:0,

            isset($_POST['popular'])?1:0,

            $_POST['stock'],

            $_POST['minimum_stock'],

            $_POST['status']

        ]);

        $productId=$this->db->lastInsertId();

        /*
        |--------------------------------------------------------------------------
        | Product Sizes
        |--------------------------------------------------------------------------
        */

        if(isset($_POST['size'])){

            foreach($_POST['size'] as $key=>$size){

                $variant=$this->db->prepare("
                    INSERT INTO product_variants(

                        product_id,
                        size_id,
                        price

                    )
                    VALUES(?,?,?)
                ");

                $variant->execute([

                    $productId,

                    $size,

                    $_POST['price'][$key]

                ]);

            }

        }

        setFlash(
            'success',
            'Product created successfully.'
        );

        $this->redirect(
            BASE_URL.'/admin/products'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Edit Product
    |--------------------------------------------------------------------------
    */

    public function edit(
        int $id
    ): void
    {
        $product=$this->productModel
            ->find($id);

        if(!$product){

            $this->response->abort(404);

        }

        $this->view(
            'admin/products/edit',
            [

                'pageTitle'=>'Edit Product',

                'product'=>$product,

                'categories'=>$this->categoryModel
                    ->active()

            ]
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Delete Product
    |--------------------------------------------------------------------------
    */

    public function destroy(
        int $id
    ): void
    {
        $stmt=$this->db->prepare("
            DELETE
            FROM products
            WHERE id=?
        ");

        $stmt->execute([$id]);

        setFlash(
            'success',
            'Product deleted.'
        );

        $this->redirect(
            BASE_URL.'/admin/products'
        );
    }
}