<?php

class Review extends Model
{
    protected string $table = 'reviews';

    public function __construct(PDO $db)
    {
        parent::__construct($db);
    }

    /*
    |--------------------------------------------------------------------------
    | Find Review
    |--------------------------------------------------------------------------
    */

    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare("
            SELECT
                r.*,
                u.first_name,
                u.last_name,
                p.name AS product_name
            FROM reviews r
            INNER JOIN users u
                ON u.id = r.user_id
            INNER JOIN products p
                ON p.id = r.product_id
            WHERE r.id = ?
            LIMIT 1
        ");

        $stmt->execute([$id]);

        $review = $stmt->fetch(PDO::FETCH_ASSOC);

        return $review ?: null;
    }

    /*
    |--------------------------------------------------------------------------
    | Product Reviews
    |--------------------------------------------------------------------------
    */

    public function product(int $productId): array
    {
        $stmt = $this->db->prepare("
            SELECT
                r.*,
                u.first_name,
                u.last_name
            FROM reviews r
            INNER JOIN users u
                ON u.id = r.user_id
            WHERE
                r.product_id = ?
            AND
                r.status = 1
            ORDER BY r.created_at DESC
        ");

        $stmt->execute([$productId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /*
    |--------------------------------------------------------------------------
    | Customer Reviews
    |--------------------------------------------------------------------------
    */

    public function customer(int $customerId): array
    {
        $stmt = $this->db->prepare("
            SELECT
                r.*,
                p.name AS product_name
            FROM reviews r
            INNER JOIN products p
                ON p.id = r.product_id
            WHERE
                r.user_id = ?
            ORDER BY r.created_at DESC
        ");

        $stmt->execute([$customerId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /*
    |--------------------------------------------------------------------------
    | Pending Reviews
    |--------------------------------------------------------------------------
    */

    public function pending(): array
    {
        $stmt = $this->db->query("
            SELECT
                r.*,
                u.first_name,
                u.last_name,
                p.name AS product_name
            FROM reviews r
            INNER JOIN users u
                ON u.id = r.user_id
            INNER JOIN products p
                ON p.id = r.product_id
            WHERE r.status = 0
            ORDER BY r.created_at DESC
        ");

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /*
    |--------------------------------------------------------------------------
    | Approve Review
    |--------------------------------------------------------------------------
    */

    public function approve(int $id): bool
    {
        $stmt = $this->db->prepare("
            UPDATE reviews
            SET status = 1
            WHERE id = ?
        ");

        return $stmt->execute([$id]);
    }

    /*
    |--------------------------------------------------------------------------
    | Reject Review
    |--------------------------------------------------------------------------
    */

    public function reject(int $id): bool
    {
        $stmt = $this->db->prepare("
            UPDATE reviews
            SET status = 2
            WHERE id = ?
        ");

        return $stmt->execute([$id]);
    }

    /*
    |--------------------------------------------------------------------------
    | Admin Reply
    |--------------------------------------------------------------------------
    */

    public function reply(
        int $id,
        string $reply
    ): bool
    {
        $stmt = $this->db->prepare("
            UPDATE reviews
            SET

                admin_reply = ?,
                replied_at = NOW()

            WHERE id = ?
        ");

        return $stmt->execute([

            $reply,

            $id

        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Average Rating
    |--------------------------------------------------------------------------
    */

    public function averageRating(
        int $productId
    ): float
    {
        $stmt = $this->db->prepare("
            SELECT
                IFNULL(AVG(rating),0)
            FROM reviews
            WHERE
                product_id = ?
            AND
                status = 1
        ");

        $stmt->execute([$productId]);

        return round(

            (float)$stmt->fetchColumn(),

            1

        );
    }

    /*
    |--------------------------------------------------------------------------
    | Rating Breakdown
    |--------------------------------------------------------------------------
    */

    public function breakdown(
        int $productId
    ): array
    {
        $stmt = $this->db->prepare("
            SELECT

                rating,

                COUNT(*) total

            FROM reviews

            WHERE

                product_id = ?

            AND

                status = 1

            GROUP BY rating

            ORDER BY rating DESC
        ");

        $stmt->execute([$productId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /*
    |--------------------------------------------------------------------------
    | Featured Reviews
    |--------------------------------------------------------------------------
    */

    public function featured(
        int $limit = 6
    ): array
    {
        $stmt = $this->db->prepare("
            SELECT
                r.*,
                u.first_name,
                p.name product_name
            FROM reviews r
            INNER JOIN users u
                ON u.id = r.user_id
            INNER JOIN products p
                ON p.id = r.product_id
            WHERE
                r.status = 1
            AND
                r.featured = 1
            ORDER BY r.created_at DESC
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
    | Review Statistics
    |--------------------------------------------------------------------------
    */

    public function statistics(): array
    {
        return [

            'total'=>(int)$this->db
                ->query("
                    SELECT COUNT(*)
                    FROM reviews
                ")
                ->fetchColumn(),

            'approved'=>(int)$this->db
                ->query("
                    SELECT COUNT(*)
                    FROM reviews
                    WHERE status=1
                ")
                ->fetchColumn(),

            'pending'=>(int)$this->db
                ->query("
                    SELECT COUNT(*)
                    FROM reviews
                    WHERE status=0
                ")
                ->fetchColumn(),

            'rejected'=>(int)$this->db
                ->query("
                    SELECT COUNT(*)
                    FROM reviews
                    WHERE status=2
                ")
                ->fetchColumn()

        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Delete Review
    |--------------------------------------------------------------------------
    */

    public function delete(
        int $id
    ): bool
    {
        $stmt = $this->db->prepare("
            DELETE
            FROM reviews
            WHERE id = ?
        ");

        return $stmt->execute([$id]);
    }
}