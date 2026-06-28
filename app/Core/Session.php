<?php

declare(strict_types=1);

class Session
{
    public function __construct()
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {

            session_start();

        }
    }

    /*
    |--------------------------------------------------------------------------
    | Set Session
    |--------------------------------------------------------------------------
    */

    public function set(
        string $key,
        mixed $value
    ): void {

        $_SESSION[$key] = $value;

    }

    /*
    |--------------------------------------------------------------------------
    | Get Session
    |--------------------------------------------------------------------------
    */

    public function get(
        string $key,
        mixed $default = null
    ): mixed {

        return $_SESSION[$key]

            ?? $default;

    }

    /*
    |--------------------------------------------------------------------------
    | Check Session
    |--------------------------------------------------------------------------
    */

    public function has(
        string $key
    ): bool {

        return isset(

            $_SESSION[$key]

        );

    }

    /*
    |--------------------------------------------------------------------------
    | Remove Session
    |--------------------------------------------------------------------------
    */

    public function forget(
        string $key
    ): void {

        unset(

            $_SESSION[$key]

        );

    }

    /*
    |--------------------------------------------------------------------------
    | Destroy Session
    |--------------------------------------------------------------------------
    */

    public function destroy(): void
    {
        $_SESSION = [];

        if (

            ini_get(
                'session.use_cookies'
            )

        ) {

            $params = session_get_cookie_params();

            setcookie(

                session_name(),

                '',

                time() - 42000,

                $params['path'],

                $params['domain'],

                $params['secure'],

                $params['httponly']

            );

        }

        session_destroy();
    }

    /*
    |--------------------------------------------------------------------------
    | Flash Message
    |--------------------------------------------------------------------------
    */

    public function flash(
        string $key,
        mixed $value
    ): void {

        $_SESSION['_flash'][$key] = $value;

    }

    /*
    |--------------------------------------------------------------------------
    | Get Flash
    |--------------------------------------------------------------------------
    */

    public function getFlash(
        string $key,
        mixed $default = null
    ): mixed {

        if (

            !isset($_SESSION['_flash'][$key])

        ) {

            return $default;

        }

        $value = $_SESSION['_flash'][$key];

        unset(

            $_SESSION['_flash'][$key]

        );

        return $value;
    }

    /*
    |--------------------------------------------------------------------------
    | Login User
    |--------------------------------------------------------------------------
    */

    public function login(
        array $user
    ): void {

        $_SESSION['user'] = $user;

    }

    /*
    |--------------------------------------------------------------------------
    | Logout User
    |--------------------------------------------------------------------------
    */

    public function logout(): void
    {
        unset(

            $_SESSION['user']

        );
    }

    /*
    |--------------------------------------------------------------------------
    | Current User
    |--------------------------------------------------------------------------
    */

    public function user(): ?array
    {
        return $_SESSION['user']

            ?? null;
    }

    /*
    |--------------------------------------------------------------------------
    | Authentication Check
    |--------------------------------------------------------------------------
    */

    public function check(): bool
    {
        return isset(

            $_SESSION['user']

        );
    }

    /*
    |--------------------------------------------------------------------------
    | User ID
    |--------------------------------------------------------------------------
    */

    public function id(): ?int
    {
        return $this->user()['id']

            ?? null;
    }

    /*
    |--------------------------------------------------------------------------
    | Regenerate Session ID
    |--------------------------------------------------------------------------
    */

    public function regenerate(): void
    {
        session_regenerate_id(

            true

        );
    }

    /*
    |--------------------------------------------------------------------------
    | All Session Data
    |--------------------------------------------------------------------------
    */

    public function all(): array
    {
        return $_SESSION;
    }
}