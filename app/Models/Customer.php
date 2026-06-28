<?php

class Customer extends Model
{
    protected string $table = 'users';

    public function __construct(PDO $db)
    {
        parent::__construct($db);
    }

    /*
    |--------------------------------------------------------------------------
    | Find Customer
    |--------------------------------------------------------------------------
    */

    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare("
            SELECT *
            FROM users
            WHERE
                id=?
            AND
                role='customer'
            LIMIT 1
        ");

        $stmt->execute([$id]);

        $customer = $stmt->fetch(PDO::FETCH_ASSOC);

        return $customer ?: null;
    }

    /*
    |--------------------------------------------------------------------------
    | Active Customers
    |--------------------------------------------------------------------------
    */

    public function active(): array
    {
        $stmt = $this->db->query("
            SELECT *
            FROM users
            WHERE
                role='customer'
            AND
                status=1
            ORDER BY first_name
        ");

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /*
    |--------------------------------------------------------------------------
    | Search Customer
    |--------------------------------------------------------------------------
    */

    public function search(string $keyword): array
    {
        $stmt = $this->db->prepare("
            SELECT *
            FROM users
            WHERE
                role='customer'
            AND
            (
                first_name LIKE ?
                OR last_name LIKE ?
                OR email LIKE ?
                OR phone LIKE ?
            )
            ORDER BY first_name
        ");

        $stmt->execute([

            "%{$keyword}%",

            "%{$keyword}%",

            "%{$keyword}%",

            "%{$keyword}%"

        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /*
    |--------------------------------------------------------------------------
    | Customer Orders
    |--------------------------------------------------------------------------
    */

    public function orders(int $customerId): array
    {
        $stmt = $this->db->prepare("
            SELECT *
            FROM orders
            WHERE user_id=?
            ORDER BY created_at DESC
        ");

        $stmt->execute([$customerId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /*
    |--------------------------------------------------------------------------
    | Customer Addresses
    |--------------------------------------------------------------------------
    */

    public function addresses(int $customerId): array
    {
        $stmt = $this->db->prepare("
            SELECT *
            FROM customer_addresses
            WHERE customer_id=?
            ORDER BY is_default DESC,id DESC
        ");

        $stmt->execute([$customerId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /*
    |--------------------------------------------------------------------------
    | Reward Points
    |--------------------------------------------------------------------------
    */

    public function rewardPoints(int $customerId): int
    {
        $stmt = $this->db->prepare("
            SELECT reward_points
            FROM users
            WHERE id=?
        ");

        $stmt->execute([$customerId]);

        return (int)$stmt->fetchColumn();
    }

    /*
    |--------------------------------------------------------------------------
    | Wallet Balance
    |--------------------------------------------------------------------------
    */

    public function walletBalance(int $customerId): float
    {
        $stmt = $this->db->prepare("
            SELECT wallet_balance
            FROM users
            WHERE id=?
        ");

        $stmt->execute([$customerId]);

        return (float)$stmt->fetchColumn();
    }

    /*
    |--------------------------------------------------------------------------
    | Total Spending
    |--------------------------------------------------------------------------
    */

    public function totalSpent(int $customerId): float
    {
        $stmt = $this->db->prepare("
            SELECT
                IFNULL(SUM(grand_total),0)
            FROM orders
            WHERE
                user_id=?
            AND
                payment_status='Paid'
        ");

        $stmt->execute([$customerId]);

        return (float)$stmt->fetchColumn();
    }

    /*
    |--------------------------------------------------------------------------
    | Total Orders
    |--------------------------------------------------------------------------
    */

    public function totalOrders(int $customerId): int
    {
        $stmt = $this->db->prepare("
            SELECT COUNT(*)
            FROM orders
            WHERE user_id=?
        ");

        $stmt->execute([$customerId]);

        return (int)$stmt->fetchColumn();
    }

    /*
    |--------------------------------------------------------------------------
    | Last Order
    |--------------------------------------------------------------------------
    */

    public function lastOrder(int $customerId): ?array
    {
        $stmt = $this->db->prepare("
            SELECT *
            FROM orders
            WHERE user_id=?
            ORDER BY created_at DESC
            LIMIT 1
        ");

        $stmt->execute([$customerId]);

        $order = $stmt->fetch(PDO::FETCH_ASSOC);

        return $order ?: null;
    }

    /*
    |--------------------------------------------------------------------------
    | Update Reward Points
    |--------------------------------------------------------------------------
    */

    public function updateRewardPoints(
        int $customerId,
        int $points
    ): bool
    {
        $stmt = $this->db->prepare("
            UPDATE users
            SET reward_points=?
            WHERE id=?
        ");

        return $stmt->execute([

            $points,

            $customerId

        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Update Wallet
    |--------------------------------------------------------------------------
    */

    public function updateWallet(
        int $customerId,
        float $amount
    ): bool
    {
        $stmt = $this->db->prepare("
            UPDATE users
            SET wallet_balance=?
            WHERE id=?
        ");

        return $stmt->execute([

            $amount,

            $customerId

        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Block Customer
    |--------------------------------------------------------------------------
    */

    public function block(int $customerId): bool
    {
        $stmt = $this->db->prepare("
            UPDATE users
            SET status=0
            WHERE id=?
        ");

        return $stmt->execute([$customerId]);
    }

    /*
    |--------------------------------------------------------------------------
    | Activate Customer
    |--------------------------------------------------------------------------
    */

    public function activate(int $customerId): bool
    {
        $stmt = $this->db->prepare("
            UPDATE users
            SET status=1
            WHERE id=?
        ");

        return $stmt->execute([$customerId]);
    }

    /*
    |--------------------------------------------------------------------------
    | Total Customers
    |--------------------------------------------------------------------------
    */

    public function count(): int
    {
        return (int)$this->db
            ->query("
                SELECT COUNT(*)
                FROM users
                WHERE role='customer'
            ")
            ->fetchColumn();
    }

    /*
    |--------------------------------------------------------------------------
    | Today's Customers
    |--------------------------------------------------------------------------
    */

    public function todayCustomers(): int
    {
        return (int)$this->db
            ->query("
                SELECT COUNT(*)
                FROM users
                WHERE
                    role='customer'
                AND
                    DATE(created_at)=CURDATE()
            ")
            ->fetchColumn();
    }

    /*
    |--------------------------------------------------------------------------
    | Customer Statistics
    |--------------------------------------------------------------------------
    */

    public function statistics(): array
    {
        return [

            'total'=>$this->count(),

            'today'=>$this->todayCustomers(),

            'active'=>(int)$this->db
                ->query("
                    SELECT COUNT(*)
                    FROM users
                    WHERE
                        role='customer'
                    AND
                        status=1
                ")
                ->fetchColumn(),

            'blocked'=>(int)$this->db
                ->query("
                    SELECT COUNT(*)
                    FROM users
                    WHERE
                        role='customer'
                    AND
                        status=0
                ")
                ->fetchColumn()

        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Top Customers
    |--------------------------------------------------------------------------
    */

    public function topCustomers(int $limit = 10): array
    {
        $stmt = $this->db->prepare("
            SELECT
                u.id,
                u.first_name,
                u.last_name,
                u.email,
                COUNT(o.id) total_orders,
                SUM(o.grand_total) total_spent
            FROM users u
            LEFT JOIN orders o
                ON o.user_id=u.id
            WHERE u.role='customer'
            GROUP BY u.id
            ORDER BY total_spent DESC
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