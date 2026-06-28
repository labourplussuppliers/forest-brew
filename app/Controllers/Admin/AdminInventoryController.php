<?php

class AdminInventoryController extends Controller
{
    private Inventory $inventoryModel;
    private Product $productModel;

    public function __construct()
    {
        parent::__construct();

        $this->inventoryModel = new Inventory($this->db);
        $this->productModel = new Product($this->db);

        if (
            !$this->session->check() ||
            !$this->session->user()['is_admin']
        ) {
            $this->redirect(BASE_URL . '/login');
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Inventory Dashboard
    |--------------------------------------------------------------------------
    */

    public function index(): void
    {
        $inventory = $this->inventoryModel->all();

        $statistics = $this->inventoryModel
            ->statistics();

        $lowStock = $this->inventoryModel
            ->lowStock();

        $this->view(

            'admin/inventory/index',

            [

                'pageTitle' => 'Inventory',

                'inventory' => $inventory,

                'statistics' => $statistics,

                'lowStock' => $lowStock

            ]

        );
    }

    /*
    |--------------------------------------------------------------------------
    | Stock In
    |--------------------------------------------------------------------------
    */

    public function stockIn(
        int $productId
    ): void
    {
        $product = $this->productModel
            ->find($productId);

        if (!$product) {

            $this->response->abort(404);

        }

        if ($this->request->isPost()) {

            $qty = (float)$this->request
                ->input('quantity');

            $remarks = trim(

                $this->request
                    ->input('remarks')

            );

            $this->inventoryModel
                ->stockIn(

                    $productId,

                    $qty,

                    $remarks

                );

            setFlash(

                'success',

                'Stock added successfully.'

            );

            $this->redirect(

                BASE_URL .
                '/admin/inventory'

            );

        }

        $this->view(

            'admin/inventory/stock-in',

            [

                'pageTitle' => 'Stock In',

                'product' => $product

            ]

        );
    }

    /*
    |--------------------------------------------------------------------------
    | Stock Out
    |--------------------------------------------------------------------------
    */

    public function stockOut(
        int $productId
    ): void
    {
        $product = $this->productModel
            ->find($productId);

        if (!$product) {

            $this->response->abort(404);

        }

        if ($this->request->isPost()) {

            $qty = (float)$this->request
                ->input('quantity');

            $remarks = trim(

                $this->request
                    ->input('remarks')

            );

            $this->inventoryModel
                ->stockOut(

                    $productId,

                    $qty,

                    $remarks

                );

            setFlash(

                'success',

                'Stock removed successfully.'

            );

            $this->redirect(

                BASE_URL .
                '/admin/inventory'

            );

        }

        $this->view(

            'admin/inventory/stock-out',

            [

                'pageTitle' => 'Stock Out',

                'product' => $product

            ]

        );
    }

    /*
    |--------------------------------------------------------------------------
    | Stock Adjustment
    |--------------------------------------------------------------------------
    */

    public function adjust(
        int $productId
    ): void
    {
        $product = $this->productModel
            ->find($productId);

        if (!$product) {

            $this->response->abort(404);

        }

        if ($this->request->isPost()) {

            $qty = (float)$this->request
                ->input('quantity');

            $remarks = trim(

                $this->request
                    ->input('remarks')

            );

            $this->inventoryModel
                ->adjust(

                    $productId,

                    $qty,

                    $remarks

                );

            setFlash(

                'success',

                'Inventory adjusted successfully.'

            );

            $this->redirect(

                BASE_URL .
                '/admin/inventory'

            );

        }

        $this->view(

            'admin/inventory/adjust',

            [

                'pageTitle' => 'Stock Adjustment',

                'product' => $product

            ]

        );
    }

    /*
    |--------------------------------------------------------------------------
    | Product History
    |--------------------------------------------------------------------------
    */

    public function history(
        int $productId
    ): void
    {
        $product = $this->productModel
            ->find($productId);

        if (!$product) {

            $this->response->abort(404);

        }

        $history = $this->inventoryModel
            ->historyList($productId);

        $this->view(

            'admin/inventory/history',

            [

                'pageTitle' => 'Inventory History',

                'product' => $product,

                'history' => $history

            ]

        );
    }

    /*
    |--------------------------------------------------------------------------
    | Low Stock Report
    |--------------------------------------------------------------------------
    */

    public function lowStock(): void
    {
        $items = $this->inventoryModel
            ->lowStock();

        $this->view(

            'admin/inventory/low-stock',

            [

                'pageTitle' => 'Low Stock',

                'items' => $items

            ]

        );
    }

    /*
    |--------------------------------------------------------------------------
    | Inventory Statistics
    |--------------------------------------------------------------------------
    */

    public function statistics(): void
    {
        $this->response->json(

            $this->inventoryModel
                ->statistics()

        );
    }

    /*
    |--------------------------------------------------------------------------
    | Export Inventory
    |--------------------------------------------------------------------------
    */

    public function export(): void
    {
        $inventory = $this->inventoryModel
            ->all();

        header(
            'Content-Type: text/csv'
        );

        header(
            'Content-Disposition: attachment; filename=inventory.csv'
        );

        $output = fopen(

            'php://output',

            'w'

        );

        fputcsv(

            $output,

            [

                'Product',

                'SKU',

                'Category',

                'Quantity'

            ]

        );

        foreach ($inventory as $item) {

            fputcsv(

                $output,

                [

                    $item['product_name'],

                    $item['sku'],

                    $item['category_name'],

                    $item['quantity']

                ]

            );

        }

        fclose($output);

        exit;

    }

    /*
    |--------------------------------------------------------------------------
    | Delete Inventory Record
    |--------------------------------------------------------------------------
    */

    public function destroy(
        int $id
    ): void
    {
        $this->inventoryModel
            ->delete($id);

        setFlash(

            'success',

            'Inventory record deleted successfully.'

        );

        $this->redirect(

            BASE_URL .
            '/admin/inventory'

        );
    }
}