<?php

declare(strict_types=1);

abstract class Controller
{
    protected Request $request;

    protected Response $response;

    protected Database $db;

    protected Session $session;

    public function __construct()
    {
        $this->request = new Request();

        $this->response = new Response();

        $this->db = Database::getInstance();

        $this->session = new Session();
    }

    /*
    |--------------------------------------------------------------------------
    | Load View
    |--------------------------------------------------------------------------
    */

    protected function view(
        string $view,
        array $data = []
    ): void {

        View::render(

            $view,

            $data

        );
    }

    /*
    |--------------------------------------------------------------------------
    | Redirect
    |--------------------------------------------------------------------------
    */

    protected function redirect(
        string $url
    ): never {

        $this->response->redirect(

            $url

        );
    }

    /*
    |--------------------------------------------------------------------------
    | JSON Response
    |--------------------------------------------------------------------------
    */

    protected function json(
        array|object $data,
        int $status = 200
    ): never {

        $this->response->json(

            $data,

            $status

        );
    }

    /*
    |--------------------------------------------------------------------------
    | Abort
    |--------------------------------------------------------------------------
    */

    protected function abort(
        int $status = 404
    ): never {

        $this->response->abort(

            $status

        );
    }

    /*
    |--------------------------------------------------------------------------
    | Request
    |--------------------------------------------------------------------------
    */

    protected function input(
        string $key,
        mixed $default = null
    ): mixed {

        return $this->request->input(

            $key,

            $default

        );
    }

    /*
    |--------------------------------------------------------------------------
    | Flash Message
    |--------------------------------------------------------------------------
    */

    protected function flash(
        string $type,
        string $message
    ): void {

        $this->session->flash(

            $type,

            $message

        );
    }

    /*
    |--------------------------------------------------------------------------
    | Current User
    |--------------------------------------------------------------------------
    */

    protected function user(): ?array
    {
        return $this->session->user();
    }

    /*
    |--------------------------------------------------------------------------
    | Authentication Check
    |--------------------------------------------------------------------------
    */

    protected function auth(): bool
    {
        return $this->session->check();
    }

    /*
    |--------------------------------------------------------------------------
    | Require Login
    |--------------------------------------------------------------------------
    */

    protected function requireLogin(): void
    {
        if (!$this->auth()) {

            $this->redirect(

                BASE_URL . '/login'

            );

        }
    }

    /*
    |--------------------------------------------------------------------------
    | Require Admin
    |--------------------------------------------------------------------------
    */

    protected function requireAdmin(): void
    {
        $this->requireLogin();

        $user = $this->user();

        if (

            !$user ||

            empty($user['role_id']) ||

            $user['role_id'] > 2

        ) {

            $this->abort(403);

        }
    }
}