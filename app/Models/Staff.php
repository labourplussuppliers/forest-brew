<?php

class Staff extends Model
{
    protected string $table = 'users';

    public function __construct(PDO $db)
    {
        parent::__construct($db);
    }

    /*
    |--------------------------------------------------------------------------
    | Find Staff
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
                role IN('admin','manager','cashier','staff')
            LIMIT 1
        ");

        $stmt->execute([$id]);

        $staff = $stmt->fetch(PDO::FETCH_ASSOC);

        return $staff ?: null;
    }

    /*
    |--------------------------------------------------------------------------
    | All Staff
    |--------------------------------------------------------------------------
    */

    public function all(): array
    {
        $stmt = $this->db->query("
            SELECT *
            FROM users
            WHERE role IN
            (
                'admin',
                'manager',
                'cashier',
                'staff'
            )
            ORDER BY first_name,last_name
        ");

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /*
    |--------------------------------------------------------------------------
    | Active Staff
    |--------------------------------------------------------------------------
    */

    public function active(): array
    {
        $stmt = $this->db->query("
            SELECT *
            FROM users
            WHERE
                status=1
            AND
                role IN
                (
                    'admin',
                    'manager',
                    'cashier',
                    'staff'
                )
            ORDER BY first_name
        ");

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /*
    |--------------------------------------------------------------------------
    | Search Staff
    |--------------------------------------------------------------------------
    */

    public function search(string $keyword): array
    {
        $stmt = $this->db->prepare("
            SELECT *
            FROM users
            WHERE
                role IN
                (
                    'admin',
                    'manager',
                    'cashier',
                    'staff'
                )
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
    | Attendance
    |--------------------------------------------------------------------------
    */

    public function attendance(
        int $staffId,
        string $date
    ): ?array
    {
        $stmt = $this->db->prepare("
            SELECT *
            FROM staff_attendance
            WHERE
                staff_id=?
            AND
                attendance_date=?
            LIMIT 1
        ");

        $stmt->execute([

            $staffId,

            $date

        ]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row ?: null;
    }

    /*
    |--------------------------------------------------------------------------
    | Clock In
    |--------------------------------------------------------------------------
    */

    public function clockIn(
        int $staffId
    ): bool
    {
        $stmt = $this->db->prepare("
            INSERT INTO staff_attendance(

                staff_id,
                attendance_date,
                clock_in

            )
            VALUES(

                ?,
                CURDATE(),
                NOW()

            )
        ");

        return $stmt->execute([

            $staffId

        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Clock Out
    |--------------------------------------------------------------------------
    */

    public function clockOut(
        int $staffId
    ): bool
    {
        $stmt = $this->db->prepare("
            UPDATE staff_attendance
            SET

                clock_out=NOW()

            WHERE

                staff_id=?

            AND

                attendance_date=CURDATE()
        ");

        return $stmt->execute([

            $staffId

        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Today's Attendance
    |--------------------------------------------------------------------------
    */

    public function todayAttendance(): array
    {
        $stmt = $this->db->query("
            SELECT
                a.*,
                u.first_name,
                u.last_name
            FROM staff_attendance a
            INNER JOIN users u
                ON u.id=a.staff_id
            WHERE attendance_date=CURDATE()
            ORDER BY clock_in
        ");

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /*
    |--------------------------------------------------------------------------
    | Monthly Salary
    |--------------------------------------------------------------------------
    */

    public function salary(
        int $staffId
    ): array
    {
        $stmt = $this->db->prepare("
            SELECT *
            FROM staff_salary
            WHERE staff_id=?
            ORDER BY salary_month DESC
        ");

        $stmt->execute([$staffId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /*
    |--------------------------------------------------------------------------
    | Staff Leaves
    |--------------------------------------------------------------------------
    */

    public function leaves(
        int $staffId
    ): array
    {
        $stmt = $this->db->prepare("
            SELECT *
            FROM staff_leaves
            WHERE staff_id=?
            ORDER BY created_at DESC
        ");

        $stmt->execute([$staffId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /*
    |--------------------------------------------------------------------------
    | Login Activity
    |--------------------------------------------------------------------------
    */

    public function loginHistory(
        int $staffId
    ): array
    {
        $stmt = $this->db->prepare("
            SELECT *
            FROM login_logs
            WHERE user_id=?
            ORDER BY login_at DESC
            LIMIT 100
        ");

        $stmt->execute([$staffId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /*
    |--------------------------------------------------------------------------
    | Top Cashiers
    |--------------------------------------------------------------------------
    */

    public function topCashiers(): array
    {
        $stmt = $this->db->query("
            SELECT
                u.id,
                u.first_name,
                u.last_name,
                COUNT(o.id) total_orders,
                SUM(o.grand_total) total_sales
            FROM users u
            LEFT JOIN orders o
                ON o.cashier_id=u.id
            WHERE u.role='cashier'
            GROUP BY u.id
            ORDER BY total_sales DESC
        ");

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /*
    |--------------------------------------------------------------------------
    | Staff Statistics
    |--------------------------------------------------------------------------
    */

    public function statistics(): array
    {
        return [

            'total'=>(int)$this->db
                ->query("
                    SELECT COUNT(*)
                    FROM users
                    WHERE role IN
                    (
                        'admin',
                        'manager',
                        'cashier',
                        'staff'
                    )
                ")
                ->fetchColumn(),

            'active'=>(int)$this->db
                ->query("
                    SELECT COUNT(*)
                    FROM users
                    WHERE
                        status=1
                    AND
                        role IN
                        (
                            'admin',
                            'manager',
                            'cashier',
                            'staff'
                        )
                ")
                ->fetchColumn(),

            'present'=>count(
                $this->todayAttendance()
            ),

            'cashiers'=>(int)$this->db
                ->query("
                    SELECT COUNT(*)
                    FROM users
                    WHERE role='cashier'
                ")
                ->fetchColumn()

        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Delete Staff
    |--------------------------------------------------------------------------
    */

    public function delete(
        int $id
    ): bool
    {
        $stmt = $this->db->prepare("
            DELETE
            FROM users
            WHERE id=?
        ");

        return $stmt->execute([$id]);
    }
}