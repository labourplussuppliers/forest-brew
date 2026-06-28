<?php

class Cart
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function items(int $userId): array
    {
        $stmt = $this->db->prepare("
            SELECT
                c.*,
                p.name,
                p.slug,
                p.image,
                pv.price,
                ps.name AS size_name,
                sl.name AS sugar_level,
                il.name AS ice_level
            FROM cart c
            INNER JOIN products p
                ON p.id = c.product_id
            LEFT JOIN product_variants pv
                ON pv.id = c.variant_id
            LEFT JOIN product_sizes ps
                ON ps.id = pv.size_id
            LEFT JOIN sugar_levels sl
                ON sl.id = c.sugar_level_id
            LEFT JOIN ice_levels il
                ON il.id = c.ice_level_id
            WHERE c.user_id = ?
            ORDER BY c.id DESC
        ");

        $stmt->execute([$userId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function add(array $data): bool
    {
        $check = $this->db->prepare("
            SELECT id, quantity
            FROM cart
            WHERE user_id = ?
            AND product_id = ?
            AND variant_id <=> ?
            LIMIT 1
        ");

        $check->execute([
            $data['user_id'],
            $data['product_id'],
            $data['variant_id']
        ]);

        $item = $check->fetch(PDO::FETCH_ASSOC);

        if ($item) {

            $quantity = $item['quantity'] + $data['quantity'];

            $update = $this->db->prepare("
                UPDATE cart
                SET quantity = ?,
                    total_price = quantity * unit_price
                WHERE id = ?
            ");

            return $update->execute([
                $quantity,
                $item['id']
            ]);
        }

        $stmt = $this->db->prepare("
            INSERT INTO cart (
                user_id,
                product_id,
                variant_id,
                sugar_level_id,
                ice_level_id,
                quantity,
                unit_price,
                total_price
            )
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ");

        return $stmt->execute([
            $data['user_id'],
            $data['product_id'],
            $data['variant_id'],
            $data['sugar_level_id'],
            $data['ice_level_id'],
            $data['quantity'],
            $data['unit_price'],
            $data['quantity'] * $data['unit_price']
        ]);
    }

    public function update(int $cartId, int $quantity): bool
    {
        $stmt = $this->db->prepare("
            UPDATE cart
            SET quantity = ?,
                total_price = quantity * unit_price
            WHERE id = ?
        ");

        return $stmt->execute([
            $quantity,
            $cartId
        ]);
    }

    public function remove(int $cartId): bool
    {
        $stmt = $this->db->prepare("
            DELETE FROM cart
            WHERE id = ?
        ");

        return $stmt->execute([$cartId]);
    }

    public function clear(int $userId): bool
    {
        $stmt = $this->db->prepare("
            DELETE FROM cart
            WHERE user_id = ?
        ");

        return $stmt->execute([$userId]);
    }

    public function count(int $userId): int
    {
        $stmt = $this->db->prepare("
            SELECT SUM(quantity)
            FROM cart
            WHERE user_id = ?
        ");

        $stmt->execute([$userId]);

        return (int) $stmt->fetchColumn();
    }

    public function subtotal(int $userId): float
    {
        $stmt = $this->db->prepare("
            SELECT SUM(total_price)
            FROM cart
            WHERE user_id = ?
        ");

        $stmt->execute([$userId]);

        return (float) $stmt->fetchColumn();
    }

    public function tax(int $userId, float $rate = 0.05): float
    {
        return $this->subtotal($userId) * $rate;
    }

    public function delivery(float $subtotal): float
    {
        return $subtotal >= 3000 ? 0 : 250;
    }

    public function grandTotal(int $userId): float
    {
        $subtotal = $this->subtotal($userId);

        return $subtotal
            + $this->tax($userId)
            + $this->delivery($subtotal);
    }

    public function exists(int $userId, int $productId): bool
    {
        $stmt = $this->db->prepare("
            SELECT id
            FROM cart
            WHERE user_id = ?
            AND product_id = ?
            LIMIT 1
        ");

        $stmt->execute([
            $userId,
            $productId
        ]);

        return (bool) $stmt->fetch();
    }
}