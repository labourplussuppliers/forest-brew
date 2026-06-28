<?php

class Auth
{
    private PDO $db;
    private Session $session;

    public function __construct(PDO $db)
    {
        $this->db = $db;
        $this->session = new Session();
    }

    /*
    |--------------------------------------------------------------------------
    | Login Attempt
    |--------------------------------------------------------------------------
    */

    public function attempt(
        string $email,
        string $password,
        bool $remember = false
    ): bool {

        $stmt = $this->db->prepare("
            SELECT
                u.*,
                r.name AS role_name
            FROM users u
            INNER JOIN roles r
                ON r.id = u.role_id
            WHERE u.email = ?
            LIMIT 1
        ");

        $stmt->execute([$email]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            return false;
        }

        if (!password_verify($password, $user['password'])) {
            return false;
        }

        if ($user['status'] !== 'Active') {
            return false;
        }

        unset($user['password']);

        $this->session->login($user);

        $this->updateLastLogin($user['id']);

        if ($remember) {
            $this->createRememberToken($user['id']);
        }

        return true;
    }

    /*
    |--------------------------------------------------------------------------
    | Logout
    |--------------------------------------------------------------------------
    */

    public function logout(): void
    {
        if ($this->check()) {
            $this->removeRememberToken($this->id());
        }

        $this->session->logout();
    }

    /*
    |--------------------------------------------------------------------------
    | Current User
    |--------------------------------------------------------------------------
    */

    public function user(): ?array
    {
        return $this->session->user();
    }

    public function id(): ?int
    {
        return $this->session->id();
    }

    public function check(): bool
    {
        return $this->session->check();
    }

    public function guest(): bool
    {
        return $this->session->guest();
    }

    /*
    |--------------------------------------------------------------------------
    | Roles
    |--------------------------------------------------------------------------
    */

    public function role(): ?string
    {
        return $this->user()['role_name'] ?? null;
    }

    public function hasRole(string|array $roles): bool
    {
        if (!$this->check()) {
            return false;
        }

        $roles = (array)$roles;

        return in_array(
            $this->role(),
            $roles,
            true
        );
    }

    public function isAdmin(): bool
    {
        return $this->hasRole([
            'Super Admin',
            'Admin',
            'Manager'
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Remember Me
    |--------------------------------------------------------------------------
    */

    private function createRememberToken(int $userId): void
    {
        $token = bin2hex(random_bytes(32));

        $hash = hash('sha256', $token);

        $stmt = $this->db->prepare("
            UPDATE users
            SET remember_token = ?
            WHERE id = ?
        ");

        $stmt->execute([
            $hash,
            $userId
        ]);

        $this->session->remember($token);
    }

    public function autoLogin(): bool
    {
        if ($this->check()) {
            return true;
        }

        $token = $this->session->rememberToken();

        if (!$token) {
            return false;
        }

        $hash = hash('sha256', $token);

        $stmt = $this->db->prepare("
            SELECT
                u.*,
                r.name AS role_name
            FROM users u
            INNER JOIN roles r
                ON r.id = u.role_id
            WHERE remember_token = ?
            LIMIT 1
        ");

        $stmt->execute([$hash]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            return false;
        }

        unset($user['password']);

        $this->session->login($user);

        return true;
    }

    private function removeRememberToken(int $userId): void
    {
        $stmt = $this->db->prepare("
            UPDATE users
            SET remember_token = NULL
            WHERE id = ?
        ");

        $stmt->execute([$userId]);

        setcookie(
            'remember_token',
            '',
            time() - 3600,
            '/'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Last Login
    |--------------------------------------------------------------------------
    */

    private function updateLastLogin(int $userId): void
    {
        $stmt = $this->db->prepare("
            UPDATE users
            SET last_login = NOW()
            WHERE id = ?
        ");

        $stmt->execute([$userId]);
    }
}