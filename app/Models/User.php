<?php

class User
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function create(array $data): bool
    {
        $stmt = $this->db->prepare("
            INSERT INTO users (
                role_id,
                first_name,
                last_name,
                username,
                email,
                phone,
                password
            )
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");

        return $stmt->execute([
            $data['role_id'],
            $data['first_name'],
            $data['last_name'],
            $data['username'],
            $data['email'],
            $data['phone'],
            password_hash($data['password'], PASSWORD_DEFAULT)
        ]);
    }

    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare("
            SELECT *
            FROM users
            WHERE id = ?
            LIMIT 1
        ");

        $stmt->execute([$id]);

        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function findByEmail(string $email): ?array
    {
        $stmt = $this->db->prepare("
            SELECT *
            FROM users
            WHERE email = ?
            LIMIT 1
        ");

        $stmt->execute([$email]);

        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function findByUsername(string $username): ?array
    {
        $stmt = $this->db->prepare("
            SELECT *
            FROM users
            WHERE username = ?
            LIMIT 1
        ");

        $stmt->execute([$username]);

        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function update(int $id, array $data): bool
    {
        $stmt = $this->db->prepare("
            UPDATE users
            SET
                first_name = ?,
                last_name = ?,
                username = ?,
                email = ?,
                phone = ?,
                gender = ?,
                date_of_birth = ?
            WHERE id = ?
        ");

        return $stmt->execute([
            $data['first_name'],
            $data['last_name'],
            $data['username'],
            $data['email'],
            $data['phone'],
            $data['gender'],
            $data['date_of_birth'],
            $id
        ]);
    }

    public function updatePassword(int $id, string $password): bool
    {
        $stmt = $this->db->prepare("
            UPDATE users
            SET password = ?
            WHERE id = ?
        ");

        return $stmt->execute([
            password_hash($password, PASSWORD_DEFAULT),
            $id
        ]);
    }

    public function updatePhoto(int $id, string $photo): bool
    {
        $stmt = $this->db->prepare("
            UPDATE users
            SET profile_photo = ?
            WHERE id = ?
        ");

        return $stmt->execute([
            $photo,
            $id
        ]);
    }

    public function updateLastLogin(int $id): bool
    {
        $stmt = $this->db->prepare("
            UPDATE users
            SET last_login = NOW()
            WHERE id = ?
        ");

        return $stmt->execute([$id]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("
            DELETE
            FROM users
            WHERE id = ?
        ");

        return $stmt->execute([$id]);
    }

    public function all(): array
    {
        return $this->db
            ->query("
                SELECT
                    u.*,
                    r.name AS role_name
                FROM users u
                INNER JOIN roles r
                    ON r.id = u.role_id
                ORDER BY u.id DESC
            ")
            ->fetchAll(PDO::FETCH_ASSOC);
    }

    public function customers(): array
    {
        $stmt = $this->db->prepare("
            SELECT *
            FROM users
            WHERE role_id = 5
            ORDER BY id DESC
        ");

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function admins(): array
    {
        $stmt = $this->db->prepare("
            SELECT *
            FROM users
            WHERE role_id < 5
            ORDER BY id ASC
        ");

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function total(): int
    {
        return (int)$this->db
            ->query("
                SELECT COUNT(*)
                FROM users
            ")
            ->fetchColumn();
    }

    public function customerCount(): int
    {
        return (int)$this->db
            ->query("
                SELECT COUNT(*)
                FROM users
                WHERE role_id = 5
            ")
            ->fetchColumn();
    }

    public function activeCount(): int
    {
        return (int)$this->db
            ->query("
                SELECT COUNT(*)
                FROM users
                WHERE status = 'Active'
            ")
            ->fetchColumn();
    }

    public function blockedCount(): int
    {
        return (int)$this->db
            ->query("
                SELECT COUNT(*)
                FROM users
                WHERE status = 'Blocked'
            ")
            ->fetchColumn();
    }

    public function emailExists(string $email): bool
    {
        return $this->findByEmail($email) !== null;
    }

    public function usernameExists(string $username): bool
    {
        return $this->findByUsername($username) !== null;
    }
}