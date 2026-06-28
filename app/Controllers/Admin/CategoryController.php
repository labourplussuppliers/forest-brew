<?php

class AdminCategoryController extends Controller
{
    private Category $categoryModel;
    private Upload $upload;

    public function __construct()
    {
        parent::__construct();

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
    | Category List
    |--------------------------------------------------------------------------
    */

    public function index(): void
    {
        $search = trim(
            $this->request->input('search')
        );

        $status = $this->request->input('status');

        $query = "
            SELECT *
            FROM categories
            WHERE 1=1
        ";

        $params = [];

        if ($search) {

            $query .= "
                AND name LIKE ?
            ";

            $params[] = "%{$search}%";
        }

        if ($status !== '' && $status !== null) {

            $query .= "
                AND status = ?
            ";

            $params[] = $status;
        }

        $query .= "
            ORDER BY sort_order ASC,
            name ASC
        ";

        $stmt = $this->db->prepare($query);

        $stmt->execute($params);

        $categories = $stmt->fetchAll();

        $this->view(
            'admin/categories/index',
            [

                'pageTitle' => 'Categories',

                'categories' => $categories

            ]
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
            'admin/categories/create',
            [

                'pageTitle' => 'Add Category'

            ]
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Store Category
    |--------------------------------------------------------------------------
    */

    public function store(): void
    {
        $image = null;

        if (!empty($_FILES['image']['name'])) {

            $image = $this->upload->upload(
                $_FILES['image'],
                'categories'
            );

        }

        $stmt = $this->db->prepare("
            INSERT INTO categories(

                name,
                slug,
                description,
                image,
                icon,
                sort_order,
                status

            )
            VALUES(

                ?,?,?,?,?,?,?

            )
        ");

        $stmt->execute([

            $_POST['name'],

            slug($_POST['name']),

            $_POST['description'],

            $image,

            $_POST['icon'],

            $_POST['sort_order'],

            $_POST['status']

        ]);

        setFlash(
            'success',
            'Category created successfully.'
        );

        $this->redirect(
            BASE_URL . '/admin/categories'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Edit Form
    |--------------------------------------------------------------------------
    */

    public function edit(
        int $id
    ): void
    {
        $category = $this->categoryModel
            ->find($id);

        if (!$category) {

            $this->response->abort(404);

        }

        $this->view(
            'admin/categories/edit',
            [

                'pageTitle' => 'Edit Category',

                'category' => $category

            ]
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Update Category
    |--------------------------------------------------------------------------
    */

    public function update(
        int $id
    ): void
    {
        $category = $this->categoryModel
            ->find($id);

        if (!$category) {

            $this->response->abort(404);

        }

        $image = $category['image'];

        if (!empty($_FILES['image']['name'])) {

            $image = $this->upload->replace(

                $_FILES['image'],

                $category['image'],

                'categories'

            );

        }

        $stmt = $this->db->prepare("
            UPDATE categories
            SET

                name=?,
                slug=?,
                description=?,
                image=?,
                icon=?,
                sort_order=?,
                status=?

            WHERE id=?
        ");

        $stmt->execute([

            $_POST['name'],

            slug($_POST['name']),

            $_POST['description'],

            $image,

            $_POST['icon'],

            $_POST['sort_order'],

            $_POST['status'],

            $id

        ]);

        setFlash(
            'success',
            'Category updated successfully.'
        );

        $this->redirect(
            BASE_URL . '/admin/categories'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Delete Category
    |--------------------------------------------------------------------------
    */

    public function destroy(
        int $id
    ): void
    {
        $category = $this->categoryModel
            ->find($id);

        if (!$category) {

            $this->response->abort(404);

        }

        if (!empty($category['image'])) {

            $this->upload->delete(
                $category['image']
            );

        }

        $stmt = $this->db->prepare("
            DELETE
            FROM categories
            WHERE id = ?
        ");

        $stmt->execute([$id]);

        setFlash(
            'success',
            'Category deleted successfully.'
        );

        $this->redirect(
            BASE_URL . '/admin/categories'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Change Status
    |--------------------------------------------------------------------------
    */

    public function changeStatus(
        int $id
    ): void
    {
        $category = $this->categoryModel
            ->find($id);

        if (!$category) {

            $this->response->abort(404);

        }

        $status = $category['status']
            ? 0
            : 1;

        $stmt = $this->db->prepare("
            UPDATE categories
            SET status=?
            WHERE id=?
        ");

        $stmt->execute([

            $status,

            $id

        ]);

        $this->redirect(
            BASE_URL . '/admin/categories'
        );
    }
}