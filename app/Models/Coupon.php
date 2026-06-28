<?php

class Coupon extends Model
{
    protected string $table = 'coupons';

    public function __construct(PDO $db)
    {
        parent::__construct($db);
    }

    /*
    |--------------------------------------------------------------------------
    | Find Coupon
    |--------------------------------------------------------------------------
    */

    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare("
            SELECT *
            FROM coupons
            WHERE id=?
            LIMIT 1
        ");

        $stmt->execute([$id]);

        $coupon = $stmt->fetch(PDO::FETCH_ASSOC);

        return $coupon ?: null;
    }

    /*
    |--------------------------------------------------------------------------
    | Find By Code
    |--------------------------------------------------------------------------
    */

    public function findByCode(string $code): ?array
    {
        $stmt = $this->db->prepare("
            SELECT *
            FROM coupons
            WHERE code=?
            LIMIT 1
        ");

        $stmt->execute([

            strtoupper($code)

        ]);

        $coupon = $stmt->fetch(PDO::FETCH_ASSOC);

        return $coupon ?: null;
    }

    /*
    |--------------------------------------------------------------------------
    | Active Coupons
    |--------------------------------------------------------------------------
    */

    public function active(): array
    {
        $stmt = $this->db->query("
            SELECT *
            FROM coupons
            WHERE status=1
            ORDER BY id DESC
        ");

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /*
    |--------------------------------------------------------------------------
    | Validate Coupon
    |--------------------------------------------------------------------------
    */

    public function validateCoupon(
        string $code,
        float $subtotal = 0
    ): array
    {
        $coupon = $this->findByCode($code);

        if (!$coupon) {

            return [

                'valid' => false,

                'message' => 'Coupon not found.'

            ];
        }

        if (!$coupon['status']) {

            return [

                'valid' => false,

                'message' => 'Coupon is inactive.'

            ];
        }

        if (
            !empty($coupon['start_date']) &&
            strtotime($coupon['start_date']) > time()
        ) {

            return [

                'valid' => false,

                'message' => 'Coupon is not active yet.'

            ];
        }

        if (
            !empty($coupon['expiry_date']) &&
            strtotime($coupon['expiry_date']) < time()
        ) {

            return [

                'valid' => false,

                'message' => 'Coupon has expired.'

            ];
        }

        if (
            $coupon['minimum_order'] > 0 &&
            $subtotal < $coupon['minimum_order']
        ) {

            return [

                'valid' => false,

                'message' => 'Minimum order amount is Rs. ' .
                    number_format($coupon['minimum_order'])

            ];
        }

        if (
            $coupon['usage_limit'] > 0 &&
            $coupon['used_count'] >= $coupon['usage_limit']
        ) {

            return [

                'valid' => false,

                'message' => 'Coupon usage limit reached.'

            ];
        }

        return [

            'valid' => true,

            'coupon' => $coupon

        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Calculate Discount
    |--------------------------------------------------------------------------
    */

    public function calculateDiscount(
        array $coupon,
        float $subtotal
    ): float
    {
        if ($coupon['discount_type'] === 'percentage') {

            $discount =
                ($subtotal * $coupon['discount_value']) / 100;

            if (
                !empty($coupon['maximum_discount']) &&
                $discount > $coupon['maximum_discount']
            ) {

                $discount = $coupon['maximum_discount'];

            }

            return round($discount,2);

        }

        return min(

            $coupon['discount_value'],

            $subtotal

        );
    }

    /*
    |--------------------------------------------------------------------------
    | Increase Usage
    |--------------------------------------------------------------------------
    */

    public function increaseUsage(
        int $couponId
    ): bool
    {
        $stmt = $this->db->prepare("
            UPDATE coupons
            SET used_count=used_count+1
            WHERE id=?
        ");

        return $stmt->execute([

            $couponId

        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Search
    |--------------------------------------------------------------------------
    */

    public function search(
        string $keyword
    ): array
    {
        $stmt = $this->db->prepare("
            SELECT *
            FROM coupons
            WHERE
            (
                code LIKE ?
                OR title LIKE ?
            )
            ORDER BY id DESC
        ");

        $stmt->execute([

            "%{$keyword}%",

            "%{$keyword}%"

        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /*
    |--------------------------------------------------------------------------
    | Coupon Statistics
    |--------------------------------------------------------------------------
    */

    public function statistics(): array
    {
        return [

            'total'=>(int)$this->db
                ->query("
                    SELECT COUNT(*)
                    FROM coupons
                ")
                ->fetchColumn(),

            'active'=>(int)$this->db
                ->query("
                    SELECT COUNT(*)
                    FROM coupons
                    WHERE status=1
                ")
                ->fetchColumn(),

            'expired'=>(int)$this->db
                ->query("
                    SELECT COUNT(*)
                    FROM coupons
                    WHERE expiry_date<CURDATE()
                ")
                ->fetchColumn(),

            'used'=>(int)$this->db
                ->query("
                    SELECT SUM(used_count)
                    FROM coupons
                ")
                ->fetchColumn()

        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Delete Coupon
    |--------------------------------------------------------------------------
    */

    public function delete(
        int $id
    ): bool
    {
        $stmt = $this->db->prepare("
            DELETE
            FROM coupons
            WHERE id=?
        ");

        return $stmt->execute([

            $id

        ]);
    }
}