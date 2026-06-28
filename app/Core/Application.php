<?php

declare(strict_types=1);

class Application
{
    private Router $router;

    private Request $request;

    private Response $response;

    public function __construct()
    {
        $this->request = new Request();

        $this->response = new Response();

        $this->router = new Router();
    }

    /**
     * ----------------------------------------------------------
     * Run Application
     * ----------------------------------------------------------
     */
    public function run(): void
    {
        $this->loadRoutes();

        $this->router->dispatch();
    }

    /**
     * ----------------------------------------------------------
     * Load Routes
     * ----------------------------------------------------------
     */
    private function loadRoutes(): void
    {
        $router = $this->router;

        $routeFile = ROUTES_PATH . '/web.php';

        if (!file_exists($routeFile)) {

            throw new Exception(

                'Route file not found : ' . $routeFile

            );

        }

        require $routeFile;
    }

    /**
     * ----------------------------------------------------------
     * Get Router
     * ----------------------------------------------------------
     */
    public function router(): Router
    {
        return $this->router;
    }

    /**
     * ----------------------------------------------------------
     * Get Request
     * ----------------------------------------------------------
     */
    public function request(): Request
    {
        return $this->request;
    }

    /**
     * ----------------------------------------------------------
     * Get Response
     * ----------------------------------------------------------
     */
    public function response(): Response
    {
        return $this->response;
    }
}