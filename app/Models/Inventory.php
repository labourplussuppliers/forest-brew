<?php

class Inventory extends Model
{
    protected string $table = 'inventory';

    public function __construct(PDO $db)
    {
        parent::__construct($db);
    }

    /*
    |--------------------------------------------------------------------------
    | Find Inventory Item
    |--------------------------------------------------------------------------
    */

    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare("
            SELECT
                i.*,
                p.name AS product_name,
                c.name AS category_name
            FROM inventory i
            LEFT JOIN products p
                ON p.id=i.product_id
            LEFT JOIN categories c
                ON c.id=p.category_id
            WHERE i.id=?
            LIMIT 1
        ");

        $stmt->execute([$id]);

        $item = $stmt->fetch(PDO::FETCH_ASSOC);

        return $item ?: null;
    }

    /*
    |--------------------------------------------------------------------------
    | All Inventory
    |--------------------------------------------------------------------------
    */

    public function all(): array
    {
        $stmt = $this->db->query("
            SELECT
                i.*,
                p.name product_name,
                p.sku,
                c.name category_name
            FROM inventory i
            INNER JOIN products p
                ON p.id=i.product_id
            LEFT JOIN categories c
                ON c.id=p.category_id
            ORDER BY p.name
        ");

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /*
    |--------------------------------------------------------------------------
    | Current Stock
    |--------------------------------------------------------------------------
    */

    public function currentStock(
        int $productId
    ): float
    {
        $stmt = $this->db->prepare("
            SELECT quantity
            FROM inventory
            WHERE product_id=?
            LIMIT 1
        ");

        $stmt->execute([$productId]);

        return (float)$stmt->fetchColumn();
    }

    /*
    |--------------------------------------------------------------------------
    | Stock In
    |--------------------------------------------------------------------------
    */

    public function stockIn(
        int $productId,
        float $quantity,
        string $remarks=''
    ): bool
    {
        $stmt = $this->db->prepare("
            UPDATE inventory
            SET quantity=quantity+?
            WHERE product_id=?
        ");

        $status = $stmt->execute([

            $quantity,

            $productId

        ]);

        if($status){

            $this->history(

                $productId,

                'IN',

                $quantity,

                $remarks

            );

        }

        return $status;
    }

    /*
    |--------------------------------------------------------------------------
    | Stock Out
    |--------------------------------------------------------------------------
    */

    public function stockOut(
        int $productId,
        float $quantity,
        string $remarks=''
    ): bool
    {
        $stmt = $this->db->prepare("
            UPDATE inventory
            SET quantity=quantity-?
            WHERE product_id=?
        ");

        $status = $stmt->execute([

            $quantity,

            $productId

        ]);

        if($status){

            $this->history(

                $productId,

                'OUT',

                $quantity,

                $remarks

            );

        }

        return $status;
    }

    /*
    |--------------------------------------------------------------------------
    | Stock Adjustment
    |--------------------------------------------------------------------------
    */

    public function adjust(
        int $productId,
        float $quantity,
        string $remarks=''
    ): bool
    {
        $stmt = $this->db->prepare("
            UPDATE inventory
            SET quantity=?
            WHERE product_id=?
        ");

        $status = $stmt->execute([

            $quantity,

            $productId

        ]);

        if($status){

            $this->history(

                $productId,

                'ADJUSTMENT',

                $quantity,

                $remarks

            );

        }

        return $status;
    }

    /*
    |--------------------------------------------------------------------------
    | Stock History
    |--------------------------------------------------------------------------
    */

    public function history(
        int $productId,
        string $type,
        float $quantity,
        string $remarks=''
    ): bool
    {
        $stmt = $this->db->prepare("
            INSERT INTO inventory_history(

                product_id,
                transaction_type,
                quantity,
                remarks,
                created_at

            )
            VALUES(

                ?,?,?,?,NOW()

            )
        ");

        return $stmt->execute([

            $productId,

            $type,

            $quantity,

            $remarks

        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | History List
    |--------------------------------------------------------------------------
    */

    public function historyList(
        int $productId
    ): array
    {
        $stmt = $this->db->prepare("
            SELECT *
            FROM inventory_history
            WHERE product_id=?
            ORDER BY id DESC
        ");

        $stmt->execute([$productId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /*
    |--------------------------------------------------------------------------
    | Low Stock
    |--------------------------------------------------------------------------
    */

    public function lowStock(): array
    {
        $stmt = $this->db->query("
            SELECT
                i.*,
                p.name,
                p.minimum_stock
            FROM inventory i
            INNER JOIN products p
                ON p.id=i.product_id
            WHERE
                i.quantity<=p.minimum_stock
            ORDER BY i.quantity ASC
        ");

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /*
    |--------------------------------------------------------------------------
    | Inventory Value
    |--------------------------------------------------------------------------
    */

    public function inventoryValue(): float
    {
        return (float)$this->db
            ->query("
                SELECT
                    SUM(quantity*cost_price)
                FROM inventory
            ")
            ->fetchColumn();
    }

    /*
    |--------------------------------------------------------------------------
    | Total Products
    |--------------------------------------------------------------------------
    */

    public function totalProducts(): int
    {
        return (int)$this->db
            ->query("
                SELECT COUNT(*)
                FROM inventory
            ")
            ->fetchColumn();
    }

    /*
    |--------------------------------------------------------------------------
    | Inventory Statistics
    |--------------------------------------------------------------------------
    */

    public function statistics(): array
    {
        return [

            'products'=>$this->totalProducts(),

            'low_stock'=>count(
                $this->lowStock()
            ),

            'inventory_value'=>$this->inventoryValue()

        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Delete Item
    |--------------------------------------------------------------------------
    */

    public function delete(
        int $id
    ): bool
    {
        $stmt = $this->db->prepare("
            DELETE
            FROM inventory
            WHERE id=?
        ");

        return $stmt->execute([$id]);
    }
}