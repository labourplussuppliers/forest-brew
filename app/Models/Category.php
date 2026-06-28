<?php

class Category extends Model
{
    protected string $table = 'categories';

    public function __construct(PDO $db)
    {
        parent::__construct($db);
    }

    /*
    |--------------------------------------------------------------------------
    | Find Category
    |--------------------------------------------------------------------------
    */

    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare("
            SELECT *
            FROM categories
            WHERE id=?
            LIMIT 1
        ");

        $stmt->execute([$id]);

        $category = $stmt->fetch(PDO::FETCH_ASSOC);

        return $category ?: null;
    }

    /*
    |--------------------------------------------------------------------------
    | Find By Slug
    |--------------------------------------------------------------------------
    */

    public function findBySlug(string $slug): ?array
    {
        $stmt = $this->db->prepare("
            SELECT *
            FROM categories
            WHERE slug=?
            LIMIT 1
        ");

        $stmt->execute([$slug]);

        $category = $stmt->fetch(PDO::FETCH_ASSOC);

        return $category ?: null;
    }

    /*
    |--------------------------------------------------------------------------
    | Active Categories
    |--------------------------------------------------------------------------
    */

    public function active(): array
    {
        $stmt = $this->db->query("
            SELECT *
            FROM categories
            WHERE status=1
            ORDER BY sort_order ASC,name ASC
        ");

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /*
    |--------------------------------------------------------------------------
    | Menu Categories
    |--------------------------------------------------------------------------
    */

    public function menu(): array
    {
        $stmt = $this->db->query("
            SELECT
                c.*,
                COUNT(p.id) products
            FROM categories c
            LEFT JOIN products p
                ON p.category_id=c.id
            WHERE c.status=1
            GROUP BY c.id
            ORDER BY c.sort_order
        ");

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /*
    |--------------------------------------------------------------------------
    | Categories With Products
    |--------------------------------------------------------------------------
    */

    public function withProducts(): array
    {
        $stmt = $this->db->query("
            SELECT
                c.*,
                COUNT(p.id) products_count
            FROM categories c
            LEFT JOIN products p
                ON p.category_id=c.id
            GROUP BY c.id
            ORDER BY c.sort_order
        ");

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /*
    |--------------------------------------------------------------------------
    | Products
    |--------------------------------------------------------------------------
    */

    public function products(int $categoryId): array
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
    | Search
    |--------------------------------------------------------------------------
    */

    public function search(string $keyword): array
    {
        $stmt = $this->db->prepare("
            SELECT *
            FROM categories
            WHERE
                name LIKE ?
            ORDER BY sort_order
        ");

        $stmt->execute([

            "%{$keyword}%"

        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /*
    |--------------------------------------------------------------------------
    | Create
    |--------------------------------------------------------------------------
    */

    public function create(array $data): int
    {
        $stmt = $this->db->prepare("
            INSERT INTO categories(

                name,
                slug,
                description,
                image,
                icon,
                sort_order,
                status,
                meta_title,
                meta_description

            )
            VALUES(

                ?,?,?,?,?,?,?,?,?

            )
        ");

        $stmt->execute([

            $data['name'],

            $data['slug'],

            $data['description'],

            $data['image'],

            $data['icon'],

            $data['sort_order'],

            $data['status'],

            $data['meta_title'],

            $data['meta_description']

        ]);

        return (int)$this->db->lastInsertId();
    }

    /*
    |--------------------------------------------------------------------------
    | Update
    |--------------------------------------------------------------------------
    */

    public function update(
        int $id,
        array $data
    ): bool
    {
        $stmt = $this->db->prepare("
            UPDATE categories
            SET

                name=?,
                slug=?,
                description=?,
                image=?,
                icon=?,
                sort_order=?,
                status=?,
                meta_title=?,
                meta_description=?

            WHERE id=?
        ");

        return $stmt->execute([

            $data['name'],

            $data['slug'],

            $data['description'],

            $data['image'],

            $data['icon'],

            $data['sort_order'],

            $data['status'],

            $data['meta_title'],

            $data['meta_description'],

            $id

        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Delete
    |--------------------------------------------------------------------------
    */

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("
            DELETE
            FROM categories
            WHERE id=?
        ");

        return $stmt->execute([$id]);
    }

    /*
    |--------------------------------------------------------------------------
    | Slug Exists
    |--------------------------------------------------------------------------
    */

    public function slugExists(
        string $slug,
        int $ignore=0
    ): bool
    {
        $stmt = $this->db->prepare("
            SELECT COUNT(*)
            FROM categories
            WHERE
                slug=?
            AND
                id!=?
        ");

        $stmt->execute([

            $slug,

            $ignore

        ]);

        return (bool)$stmt->fetchColumn();
    }

    /*
    |--------------------------------------------------------------------------
    | Next Sort Order
    |--------------------------------------------------------------------------
    */

    public function nextSortOrder(): int
    {
        return (int)$this->db
            ->query("
                SELECT
                IFNULL(MAX(sort_order),0)+1
                FROM categories
            ")
            ->fetchColumn();
    }

    /*
    |--------------------------------------------------------------------------
    | Category Count
    |--------------------------------------------------------------------------
    */

    public function count(): int
    {
        return (int)$this->db
            ->query("
                SELECT COUNT(*)
                FROM categories
            ")
            ->fetchColumn();
    }

    /*
    |--------------------------------------------------------------------------
    | Active Count
    |--------------------------------------------------------------------------
    */

    public function activeCount(): int
    {
        return (int)$this->db
            ->query("
                SELECT COUNT(*)
                FROM categories
                WHERE status=1
            ")
            ->fetchColumn();
    }

    /*
    |--------------------------------------------------------------------------
    | Dashboard Statistics
    |--------------------------------------------------------------------------
    */

    public function statistics(): array
    {
        return [

            'total'=>$this->count(),

            'active'=>$this->activeCount(),

            'inactive'=>$this->count()-$this->activeCount()

        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Home Categories
    |--------------------------------------------------------------------------
    */

    public function home(int $limit=8): array
    {
        $stmt = $this->db->prepare("
            SELECT *
            FROM categories
            WHERE status=1
            ORDER BY sort_order
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
}