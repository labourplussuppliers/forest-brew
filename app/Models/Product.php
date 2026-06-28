<?php

class Product extends Model
{
    protected string $table = 'products';

    public function __construct(PDO $db)
    {
        parent::__construct($db);
    }

    /*
    |--------------------------------------------------------------------------
    | Find Product
    |--------------------------------------------------------------------------
    */

    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare("
            SELECT
                p.*,
                c.name AS category_name
            FROM products p
            LEFT JOIN categories c
                ON c.id = p.category_id
            WHERE p.id = ?
            LIMIT 1
        ");

        $stmt->execute([$id]);

        $product = $stmt->fetch(PDO::FETCH_ASSOC);

        return $product ?: null;
    }

    /*
    |--------------------------------------------------------------------------
    | Find By Slug
    |--------------------------------------------------------------------------
    */

    public function findBySlug(string $slug): ?array
    {
        $stmt = $this->db->prepare("
            SELECT
                p.*,
                c.name AS category_name
            FROM products p
            LEFT JOIN categories c
                ON c.id=p.category_id
            WHERE
                p.slug=?
            LIMIT 1
        ");

        $stmt->execute([$slug]);

        $product = $stmt->fetch(PDO::FETCH_ASSOC);

        return $product ?: null;
    }

    /*
    |--------------------------------------------------------------------------
    | Active Products
    |--------------------------------------------------------------------------
    */

    public function active(): array
    {
        $stmt = $this->db->query("
            SELECT
                p.*,
                c.name category_name
            FROM products p
            LEFT JOIN categories c
                ON c.id=p.category_id
            WHERE p.status=1
            ORDER BY p.name
        ");

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /*
    |--------------------------------------------------------------------------
    | Available Products
    |--------------------------------------------------------------------------
    */

    public function available(): array
    {
        $stmt = $this->db->query("
            SELECT
                p.*,
                c.name category_name,
                (
                    SELECT MIN(price)
                    FROM product_variants
                    WHERE product_id=p.id
                ) AS starting_price
            FROM products p
            LEFT JOIN categories c
                ON c.id=p.category_id
            WHERE
                p.status=1
            AND
                p.stock>0
            ORDER BY p.name
        ");

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /*
    |--------------------------------------------------------------------------
    | Featured Products
    |--------------------------------------------------------------------------
    */

    public function featured(): array
    {
        $stmt = $this->db->query("
            SELECT *
            FROM products
            WHERE
                featured=1
            AND
                status=1
            ORDER BY id DESC
        ");

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /*
    |--------------------------------------------------------------------------
    | Popular Products
    |--------------------------------------------------------------------------
    */

    public function popular(): array
    {
        $stmt = $this->db->query("
            SELECT *
            FROM products
            WHERE
                popular=1
            AND
                status=1
            ORDER BY id DESC
        ");

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /*
    |--------------------------------------------------------------------------
    | Category Products
    |--------------------------------------------------------------------------
    */

    public function category(int $categoryId): array
    {
        $stmt = $this->db->prepare("
            SELECT *
            FROM products
            WHERE
                category_id=?
            AND
                status=1
            ORDER BY name
        ");

        $stmt->execute([$categoryId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /*
    |--------------------------------------------------------------------------
    | Product Variants
    |--------------------------------------------------------------------------
    */

    public function variants(int $productId): array
    {
        $stmt = $this->db->prepare("
            SELECT *
            FROM product_variants
            WHERE product_id=?
            ORDER BY sort_order
        ");

        $stmt->execute([$productId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /*
    |--------------------------------------------------------------------------
    | Product Gallery
    |--------------------------------------------------------------------------
    */

    public function gallery(int $productId): array
    {
        $stmt = $this->db->prepare("
            SELECT *
            FROM product_gallery
            WHERE product_id=?
            ORDER BY id
        ");

        $stmt->execute([$productId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /*
    |--------------------------------------------------------------------------
    | Product Extras
    |--------------------------------------------------------------------------
    */

    public function extras(int $productId): array
    {
        $stmt = $this->db->prepare("
            SELECT
                e.*
            FROM extras e
            INNER JOIN product_extras pe
                ON pe.extra_id=e.id
            WHERE
                pe.product_id=?
            ORDER BY e.name
        ");

        $stmt->execute([$productId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /*
    |--------------------------------------------------------------------------
    | Search Products
    |--------------------------------------------------------------------------
    */

    public function search(string $keyword): array
    {
        $stmt = $this->db->prepare("
            SELECT *
            FROM products
            WHERE
            (
                name LIKE ?
                OR sku LIKE ?
            )
            AND status=1
            ORDER BY name
        ");

        $stmt->execute([

            "%{$keyword}%",

            "%{$keyword}%"

        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /*
    |--------------------------------------------------------------------------
    | Latest Products
    |--------------------------------------------------------------------------
    */

    public function latest(int $limit=8): array
    {
        $stmt=$this->db->prepare("
            SELECT *
            FROM products
            WHERE status=1
            ORDER BY id DESC
            LIMIT ?
        ");

        $stmt->bindValue(

            1,

            $limit,

            PDO::PARAM_INT

        );

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /*
    |--------------------------------------------------------------------------
    | Update Stock
    |--------------------------------------------------------------------------
    */

    public function updateStock(
        int $productId,
        int $qty
    ): bool
    {
        $stmt=$this->db->prepare("
            UPDATE products
            SET stock=stock-?
            WHERE id=?
        ");

        return $stmt->execute([

            $qty,

            $productId

        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Increase Stock
    |--------------------------------------------------------------------------
    */

    public function increaseStock(
        int $productId,
        int $qty
    ): bool
    {
        $stmt=$this->db->prepare("
            UPDATE products
            SET stock=stock+?
            WHERE id=?
        ");

        return $stmt->execute([

            $qty,

            $productId

        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Low Stock
    |--------------------------------------------------------------------------
    */

    public function lowStock(): array
    {
        $stmt=$this->db->query("
            SELECT *
            FROM products
            WHERE
                stock<=minimum_stock
            ORDER BY stock ASC
        ");

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /*
    |--------------------------------------------------------------------------
    | Product Count
    |--------------------------------------------------------------------------
    */

    public function count(): int
    {
        return (int)$this->db
            ->query("
                SELECT COUNT(*)
                FROM products
            ")
            ->fetchColumn();
    }

    /*
    |--------------------------------------------------------------------------
    | Delete Product
    |--------------------------------------------------------------------------
    */

    public function delete(int $id): bool
    {
        $stmt=$this->db->prepare("
            DELETE
            FROM products
            WHERE id=?
        ");

        return $stmt->execute([$id]);
    }
}