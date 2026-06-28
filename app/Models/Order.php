<?php

class Order extends Model
{
    protected string $table = 'orders';

    public function __construct(PDO $db)
    {
        parent::__construct($db);
    }

    /*
    |--------------------------------------------------------------------------
    | Find Order
    |--------------------------------------------------------------------------
    */

    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare("
            SELECT
                o.*,
                CONCAT(u.first_name,' ',u.last_name) customer_name,
                u.email,
                u.phone
            FROM orders o
            LEFT JOIN users u
                ON u.id=o.user_id
            WHERE o.id=?
            LIMIT 1
        ");

        $stmt->execute([$id]);

        $order = $stmt->fetch(PDO::FETCH_ASSOC);

        return $order ?: null;
    }

    /*
    |--------------------------------------------------------------------------
    | Find By Order Number
    |--------------------------------------------------------------------------
    */

    public function findByNumber(
        string $number
    ): ?array
    {
        $stmt = $this->db->prepare("
            SELECT *
            FROM orders
            WHERE order_number=?
            LIMIT 1
        ");

        $stmt->execute([$number]);

        $order = $stmt->fetch(PDO::FETCH_ASSOC);

        return $order ?: null;
    }

    /*
    |--------------------------------------------------------------------------
    | All Orders
    |--------------------------------------------------------------------------
    */

    public function all(): array
    {
        $stmt = $this->db->query("
            SELECT
                o.*,
                CONCAT(
                    u.first_name,
                    ' ',
                    u.last_name
                ) customer_name
            FROM orders o
            LEFT JOIN users u
                ON u.id=o.user_id
            ORDER BY o.id DESC
        ");

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /*
    |--------------------------------------------------------------------------
    | Latest Orders
    |--------------------------------------------------------------------------
    */

    public function latest(
        int $limit = 10
    ): array
    {
        $stmt = $this->db->prepare("
            SELECT
                o.*,
                CONCAT(
                    u.first_name,
                    ' ',
                    u.last_name
                ) customer_name
            FROM orders o
            LEFT JOIN users u
                ON u.id=o.user_id
            ORDER BY o.id DESC
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
    | Customer Orders
    |--------------------------------------------------------------------------
    */

    public function customerOrders(
        int $customerId
    ): array
    {
        $stmt = $this->db->prepare("
            SELECT *
            FROM orders
            WHERE user_id=?
            ORDER BY id DESC
        ");

        $stmt->execute([$customerId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /*
    |--------------------------------------------------------------------------
    | Order Items
    |--------------------------------------------------------------------------
    */

    public function items(
        int $orderId
    ): array
    {
        $stmt = $this->db->prepare("
            SELECT
                oi.*,
                p.name product_name,
                pv.name variant_name
            FROM order_items oi
            LEFT JOIN products p
                ON p.id=oi.product_id
            LEFT JOIN product_variants pv
                ON pv.id=oi.variant_id
            WHERE oi.order_id=?
            ORDER BY oi.id
        ");

        $stmt->execute([$orderId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /*
    |--------------------------------------------------------------------------
    | Add Order Item
    |--------------------------------------------------------------------------
    */

    public function addItem(
        array $data
    ): bool
    {
        $stmt = $this->db->prepare("
            INSERT INTO order_items(

                order_id,
                product_id,
                variant_id,
                quantity,
                unit_price,
                total_price

            )
            VALUES(

                ?,?,?,?,?,?

            )
        ");

        return $stmt->execute([

            $data['order_id'],

            $data['product_id'],

            $data['variant_id'],

            $data['quantity'],

            $data['unit_price'],

            $data['total_price']

        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Order Timeline
    |--------------------------------------------------------------------------
    */

    public function timeline(
        int $orderId
    ): array
    {
        $stmt = $this->db->prepare("
            SELECT *
            FROM order_timeline
            WHERE order_id=?
            ORDER BY id ASC
        ");

        $stmt->execute([$orderId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /*
    |--------------------------------------------------------------------------
    | Add Timeline Event
    |--------------------------------------------------------------------------
    */

    public function addTimeline(
        int $orderId,
        string $status,
        string $notes = ''
    ): bool
    {
        $stmt = $this->db->prepare("
            INSERT INTO order_timeline(

                order_id,
                status,
                notes,
                created_at

            )
            VALUES(

                ?,?,?,NOW()

            )
        ");

        return $stmt->execute([

            $orderId,

            $status,

            $notes

        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Generate Order Number
    |--------------------------------------------------------------------------
    */

    public function generateOrderNumber(): string
    {
        return 'FB-' .
            date('Ymd') .
            '-' .
            strtoupper(
                substr(
                    uniqid(),
                    -6
                )
            );
    }

/*
|--------------------------------------------------------------------------
| Create Online Order
|--------------------------------------------------------------------------
*/

public function createOnlineOrder(
    array $data
): int
{
    $stmt = $this->db->prepare("
        INSERT INTO orders(

            order_number,
            user_id,
            order_type,
            payment_method,
            payment_status,
            order_status,
            subtotal,
            discount,
            tax,
            delivery_charges,
            grand_total,
            delivery_address,
            notes,
            created_at

        )
        VALUES(

            ?,?,?,?,?,?,?,?,?,?,?,?,?,
            NOW()

        )
    ");

    $stmt->execute([

        $this->generateOrderNumber(),

        $data['user_id'],

        $data['order_type'],

        $data['payment_method'],

        $data['payment_status'],

        'Pending',

        $data['subtotal'],

        $data['discount'],

        $data['tax'],

        $data['delivery_charges'],

        $data['grand_total'],

        $data['delivery_address'],

        $data['notes'] ?? null

    ]);

    $orderId = (int)$this->db->lastInsertId();

    $this->addTimeline(

        $orderId,

        'Pending',

        'Order placed successfully.'

    );

    return $orderId;
}

/*
|--------------------------------------------------------------------------
| Create POS Order
|--------------------------------------------------------------------------
*/

public function createPosOrder(
    array $data
): int
{
    $stmt = $this->db->prepare("
        INSERT INTO orders(

            order_number,
            user_id,
            cashier_id,
            table_id,
            order_type,
            payment_method,
            payment_status,
            order_status,
            subtotal,
            discount,
            tax,
            grand_total,
            created_at

        )
        VALUES(

            ?,?,?,?,?,?,?,?,?,?,?,?,
            NOW()

        )
    ");

    $stmt->execute([

        $this->generateOrderNumber(),

        $data['customer_id'],

        $data['cashier_id'],

        $data['table_id'],

        $data['order_type'],

        $data['payment_method'],

        $data['payment_status'],

        $data['order_status'],

        $data['subtotal'],

        $data['discount'],

        $data['tax'],

        $data['grand_total']

    ]);

    $orderId = (int)$this->db->lastInsertId();

    $this->addTimeline(

        $orderId,

        'Completed',

        'POS order completed.'

    );

    return $orderId;
}

/*
|--------------------------------------------------------------------------
| Update Order Status
|--------------------------------------------------------------------------
*/

public function updateStatus(
    int $orderId,
    string $status,
    string $note = ''
): bool
{
    $stmt = $this->db->prepare("
        UPDATE orders
        SET

            order_status=?,
            updated_at=NOW()

        WHERE id=?
    ");

    $updated = $stmt->execute([

        $status,

        $orderId

    ]);

    if($updated){

        $this->addTimeline(

            $orderId,

            $status,

            $note

        );

    }

    return $updated;
}

/*
|--------------------------------------------------------------------------
| Update Payment Status
|--------------------------------------------------------------------------
*/

public function updatePaymentStatus(
    int $orderId,
    string $status
): bool
{
    $stmt = $this->db->prepare("
        UPDATE orders
        SET

            payment_status=?,
            updated_at=NOW()

        WHERE id=?
    ");

    return $stmt->execute([

        $status,

        $orderId

    ]);
}

/*
|--------------------------------------------------------------------------
| Cancel Order
|--------------------------------------------------------------------------
*/

public function cancelOrder(
    int $orderId,
    string $reason=''
): bool
{
    $stmt = $this->db->prepare("
        UPDATE orders
        SET

            order_status='Cancelled'

        WHERE id=?
    ");

    $status = $stmt->execute([

        $orderId

    ]);

    if($status){

        $this->addTimeline(

            $orderId,

            'Cancelled',

            $reason

        );

    }

    return $status;
}

/*
|--------------------------------------------------------------------------
| Refund Order
|--------------------------------------------------------------------------
*/

public function refundOrder(
    int $orderId
): bool
{
    $stmt = $this->db->prepare("
        UPDATE orders
        SET

            payment_status='Refunded'

        WHERE id=?
    ");

    $status = $stmt->execute([

        $orderId

    ]);

    if($status){

        $this->addTimeline(

            $orderId,

            'Refunded',

            'Payment refunded.'

        );

    }

    return $status;
}

/*
|--------------------------------------------------------------------------
| Deduct Inventory
|--------------------------------------------------------------------------
*/

public function deductInventory(
    int $orderId
): void
{
    $items = $this->items($orderId);

    foreach($items as $item){

        $stmt = $this->db->prepare("
            UPDATE products
            SET

                stock = stock - ?

            WHERE id = ?
        ");

        $stmt->execute([

            $item['quantity'],

            $item['product_id']

        ]);

    }
}

/*
|--------------------------------------------------------------------------
| Restore Inventory
|--------------------------------------------------------------------------
*/

public function restoreInventory(
    int $orderId
): void
{
    $items = $this->items($orderId);

    foreach($items as $item){

        $stmt = $this->db->prepare("
            UPDATE products
            SET

                stock = stock + ?

            WHERE id = ?
        ");

        $stmt->execute([

            $item['quantity'],

            $item['product_id']

        ]);

    }
}

/*
|--------------------------------------------------------------------------
| Delete Order
|--------------------------------------------------------------------------
*/

public function delete(
    int $id
): bool
{
    $stmt = $this->db->prepare("
        DELETE
        FROM orders
        WHERE id=?
    ");

    return $stmt->execute([

        $id

    ]);
}
/*
|--------------------------------------------------------------------------
| Today's Orders
|--------------------------------------------------------------------------
*/

public function todayOrders(): int
{
    return (int)$this->db
        ->query("
            SELECT COUNT(*)
            FROM orders
            WHERE DATE(created_at)=CURDATE()
        ")
        ->fetchColumn();
}

/*
|--------------------------------------------------------------------------
| Today's Sales
|--------------------------------------------------------------------------
*/

public function todaySales(): float
{
    return (float)$this->db
        ->query("
            SELECT
                IFNULL(SUM(grand_total),0)
            FROM orders
            WHERE
                DATE(created_at)=CURDATE()
            AND
                payment_status='Paid'
        ")
        ->fetchColumn();
}

/*
|--------------------------------------------------------------------------
| Weekly Sales
|--------------------------------------------------------------------------
*/

public function weeklySales(): float
{
    $stmt = $this->db->query("
        SELECT
            IFNULL(SUM(grand_total),0)
        FROM orders
        WHERE

            YEARWEEK(created_at,1)=YEARWEEK(CURDATE(),1)

        AND

            payment_status='Paid'
    ");

    return (float)$stmt->fetchColumn();
}

/*
|--------------------------------------------------------------------------
| Monthly Sales
|--------------------------------------------------------------------------
*/

public function monthlySales(): float
{
    $stmt = $this->db->query("
        SELECT

            IFNULL(SUM(grand_total),0)

        FROM orders

        WHERE

            MONTH(created_at)=MONTH(CURDATE())

        AND

            YEAR(created_at)=YEAR(CURDATE())

        AND

            payment_status='Paid'
    ");

    return (float)$stmt->fetchColumn();
}

/*
|--------------------------------------------------------------------------
| Yearly Sales
|--------------------------------------------------------------------------
*/

public function yearlySales(): float
{
    $stmt = $this->db->query("
        SELECT

            IFNULL(SUM(grand_total),0)

        FROM orders

        WHERE

            YEAR(created_at)=YEAR(CURDATE())

        AND

            payment_status='Paid'
    ");

    return (float)$stmt->fetchColumn();
}

/*
|--------------------------------------------------------------------------
| Pending Orders
|--------------------------------------------------------------------------
*/

public function pendingOrders(): int
{
    return (int)$this->db
        ->query("
            SELECT COUNT(*)
            FROM orders
            WHERE order_status='Pending'
        ")
        ->fetchColumn();
}

/*
|--------------------------------------------------------------------------
| Preparing Orders
|--------------------------------------------------------------------------
*/

public function preparingOrders(): int
{
    return (int)$this->db
        ->query("
            SELECT COUNT(*)
            FROM orders
            WHERE order_status='Preparing'
        ")
        ->fetchColumn();
}

/*
|--------------------------------------------------------------------------
| Ready Orders
|--------------------------------------------------------------------------
*/

public function readyOrders(): int
{
    return (int)$this->db
        ->query("
            SELECT COUNT(*)
            FROM orders
            WHERE order_status='Ready'
        ")
        ->fetchColumn();
}

/*
|--------------------------------------------------------------------------
| Completed Orders
|--------------------------------------------------------------------------
*/

public function completedOrders(): int
{
    return (int)$this->db
        ->query("
            SELECT COUNT(*)
            FROM orders
            WHERE order_status='Completed'
        ")
        ->fetchColumn();
}

/*
|--------------------------------------------------------------------------
| Cancelled Orders
|--------------------------------------------------------------------------
*/

public function cancelledOrders(): int
{
    return (int)$this->db
        ->query("
            SELECT COUNT(*)
            FROM orders
            WHERE order_status='Cancelled'
        ")
        ->fetchColumn();
}

/*
|--------------------------------------------------------------------------
| Average Order Value
|--------------------------------------------------------------------------
*/

public function todayAverage(): float
{
    $stmt = $this->db->query("
        SELECT

            IFNULL(AVG(grand_total),0)

        FROM orders

        WHERE

            DATE(created_at)=CURDATE()

        AND

            payment_status='Paid'
    ");

    return round(

        (float)$stmt->fetchColumn(),

        2

    );
}

/*
|--------------------------------------------------------------------------
| Best Selling Products
|--------------------------------------------------------------------------
*/

public function bestSellingProducts(
    int $limit = 10
): array
{
    $stmt = $this->db->prepare("
        SELECT

            p.id,

            p.name,

            SUM(oi.quantity) total_quantity,

            SUM(oi.total_price) total_sales

        FROM order_items oi

        INNER JOIN products p

            ON p.id=oi.product_id

        GROUP BY p.id

        ORDER BY total_quantity DESC

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
| Peak Sales Hours
|--------------------------------------------------------------------------
*/

public function peakHours(): array
{
    $stmt = $this->db->query("
        SELECT

            HOUR(created_at) hour,

            COUNT(*) total_orders,

            SUM(grand_total) total_sales

        FROM orders

        WHERE

            payment_status='Paid'

        GROUP BY HOUR(created_at)

        ORDER BY hour
    ");

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/*
|--------------------------------------------------------------------------
| Monthly Graph
|--------------------------------------------------------------------------
*/

public function monthlyGraph(): array
{
    $stmt = $this->db->query("
        SELECT

            DATE(created_at) sales_date,

            SUM(grand_total) total

        FROM orders

        WHERE

            MONTH(created_at)=MONTH(CURDATE())

        AND

            YEAR(created_at)=YEAR(CURDATE())

        AND

            payment_status='Paid'

        GROUP BY DATE(created_at)

        ORDER BY sales_date
    ");

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/*
|--------------------------------------------------------------------------
| Dashboard Statistics
|--------------------------------------------------------------------------
*/

public function dashboardStatistics(): array
{
    return [

        'today_orders' => $this->todayOrders(),

        'today_sales' => $this->todaySales(),

        'weekly_sales' => $this->weeklySales(),

        'monthly_sales' => $this->monthlySales(),

        'yearly_sales' => $this->yearlySales(),

        'pending_orders' => $this->pendingOrders(),

        'preparing_orders' => $this->preparingOrders(),

        'ready_orders' => $this->readyOrders(),

        'completed_orders' => $this->completedOrders(),

        'cancelled_orders' => $this->cancelledOrders(),

        'average_order' => $this->todayAverage()

    ];
}
/*
|--------------------------------------------------------------------------
| Sales Report
|--------------------------------------------------------------------------
*/

public function salesReport(
    string $from,
    string $to
): array
{
    $stmt = $this->db->prepare("
        SELECT
            o.*,
            CONCAT(
                u.first_name,
                ' ',
                u.last_name
            ) customer_name
        FROM orders o
        LEFT JOIN users u
            ON u.id=o.user_id
        WHERE
            DATE(o.created_at)
            BETWEEN ? AND ?
        ORDER BY o.created_at DESC
    ");

    $stmt->execute([

        $from,

        $to

    ]);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/*
|--------------------------------------------------------------------------
| Cashier Report
|--------------------------------------------------------------------------
*/

public function cashierReport(
    int $cashierId,
    string $from,
    string $to
): array
{
    $stmt = $this->db->prepare("
        SELECT *
        FROM orders
        WHERE

            cashier_id=?

        AND

            DATE(created_at)
            BETWEEN ? AND ?

        ORDER BY created_at DESC
    ");

    $stmt->execute([

        $cashierId,

        $from,

        $to

    ]);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/*
|--------------------------------------------------------------------------
| Customer Report
|--------------------------------------------------------------------------
*/

public function customerReport(
    int $customerId
): array
{
    $stmt = $this->db->prepare("
        SELECT *
        FROM orders
        WHERE user_id=?
        ORDER BY created_at DESC
    ");

    $stmt->execute([

        $customerId

    ]);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/*
|--------------------------------------------------------------------------
| Payment Method Report
|--------------------------------------------------------------------------
*/

public function paymentMethods(): array
{
    $stmt = $this->db->query("
        SELECT

            payment_method,

            COUNT(*) total_orders,

            SUM(grand_total) total_sales

        FROM orders

        WHERE payment_status='Paid'

        GROUP BY payment_method

        ORDER BY total_sales DESC
    ");

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/*
|--------------------------------------------------------------------------
| Tax Report
|--------------------------------------------------------------------------
*/

public function taxReport(
    string $from,
    string $to
): float
{
    $stmt = $this->db->prepare("
        SELECT

            IFNULL(
                SUM(tax),
                0
            )

        FROM orders

        WHERE

            DATE(created_at)
            BETWEEN ? AND ?

        AND

            payment_status='Paid'
    ");

    $stmt->execute([

        $from,

        $to

    ]);

    return (float)$stmt->fetchColumn();
}

/*
|--------------------------------------------------------------------------
| Kitchen Orders
|--------------------------------------------------------------------------
*/

public function kitchenOrders(): array
{
    $stmt = $this->db->query("
        SELECT *

        FROM orders

        WHERE

            order_status IN
            (

                'Pending',

                'Preparing',

                'Ready'

            )

        ORDER BY created_at ASC
    ");

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/*
|--------------------------------------------------------------------------
| Held Orders
|--------------------------------------------------------------------------
*/

public function heldOrders(): array
{
    $stmt = $this->db->query("
        SELECT *
        FROM held_orders
        ORDER BY id DESC
    ");

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/*
|--------------------------------------------------------------------------
| Invoice Data
|--------------------------------------------------------------------------
*/

public function invoiceData(
    int $orderId
): array
{
    return [

        'order'=>$this->find(

            $orderId

        ),

        'items'=>$this->items(

            $orderId

        ),

        'timeline'=>$this->timeline(

            $orderId

        )

    ];
}

/*
|--------------------------------------------------------------------------
| Thermal Receipt
|--------------------------------------------------------------------------
*/

public function receipt(
    int $orderId
): array
{
    return $this->invoiceData(

        $orderId

    );
}

/*
|--------------------------------------------------------------------------
| Search Orders
|--------------------------------------------------------------------------
*/

public function search(
    string $keyword
): array
{
    $stmt = $this->db->prepare("
        SELECT *

        FROM orders

        WHERE

        (

            order_number LIKE ?

            OR

            payment_method LIKE ?

            OR

            order_status LIKE ?

        )

        ORDER BY id DESC
    ");

    $stmt->execute([

        "%{$keyword}%",

        "%{$keyword}%",

        "%{$keyword}%"

    ]);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/*
|--------------------------------------------------------------------------
| Paginated Orders
|--------------------------------------------------------------------------
*/

public function paginate(
    int $limit,
    int $offset
): array
{
    $stmt = $this->db->prepare("
        SELECT *

        FROM orders

        ORDER BY id DESC

        LIMIT ?

        OFFSET ?
    ");

    $stmt->bindValue(

        1,

        $limit,

        PDO::PARAM_INT

    );

    $stmt->bindValue(

        2,

        $offset,

        PDO::PARAM_INT

    );

    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/*
|--------------------------------------------------------------------------
| Total Orders
|--------------------------------------------------------------------------
*/

public function count(): int
{
    return (int)$this->db
        ->query("
            SELECT COUNT(*)
            FROM orders
        ")
        ->fetchColumn();
}

/*
|--------------------------------------------------------------------------
| Export Data
|--------------------------------------------------------------------------
*/

public function export(
    string $from,
    string $to
): array
{
    return $this->salesReport(

        $from,

        $to

    );
}

/*
|--------------------------------------------------------------------------
| Delete Old Timeline
|--------------------------------------------------------------------------
*/

public function clearTimeline(
    int $orderId
): bool
{
    $stmt = $this->db->prepare("
        DELETE
        FROM order_timeline
        WHERE order_id=?
    ");

    return $stmt->execute([

        $orderId

    ]);
}
}