<?php

declare(strict_types=1);

class Request
{
    /**
     * ----------------------------------------------------------
     * Request Method
     * ----------------------------------------------------------
     */
    public function method(): string
    {
        return strtoupper(

            $_SERVER['REQUEST_METHOD'] ?? 'GET'

        );
    }

    /**
     * ----------------------------------------------------------
     * Request URI
     * ----------------------------------------------------------
     */
    public function uri(): string
    {
        return parse_url(

            $_SERVER['REQUEST_URI'] ?? '/',

            PHP_URL_PATH

        );
    }

    /**
     * ----------------------------------------------------------
     * Is GET Request
     * ----------------------------------------------------------
     */
    public function isGet(): bool
    {
        return $this->method() === 'GET';
    }

    /**
     * ----------------------------------------------------------
     * Is POST Request
     * ----------------------------------------------------------
     */
    public function isPost(): bool
    {
        return $this->method() === 'POST';
    }

    /**
     * ----------------------------------------------------------
     * Is PUT Request
     * ----------------------------------------------------------
     */
    public function isPut(): bool
    {
        return $this->method() === 'PUT';
    }

    /**
     * ----------------------------------------------------------
     * Is DELETE Request
     * ----------------------------------------------------------
     */
    public function isDelete(): bool
    {
        return $this->method() === 'DELETE';
    }

    /**
     * ----------------------------------------------------------
     * Get Input Value
     * ----------------------------------------------------------
     */
    public function input(
        string $key,
        mixed $default = null
    ): mixed {

        return $_POST[$key]
            ?? $_GET[$key]
            ?? $default;
    }

    /**
     * ----------------------------------------------------------
     * Get All Input
     * ----------------------------------------------------------
     */
    public function all(): array
    {
        return array_merge(

            $_GET,

            $_POST

        );
    }

    /**
     * ----------------------------------------------------------
     * Query Parameter
     * ----------------------------------------------------------
     */
    public function query(
        string $key,
        mixed $default = null
    ): mixed {

        return $_GET[$key]
            ?? $default;
    }

    /**
     * ----------------------------------------------------------
     * Post Parameter
     * ----------------------------------------------------------
     */
    public function post(
        string $key,
        mixed $default = null
    ): mixed {

        return $_POST[$key]
            ?? $default;
    }

    /**
     * ----------------------------------------------------------
     * Uploaded File
     * ----------------------------------------------------------
     */
    public function file(
        string $key
    ): ?array {

        return $_FILES[$key]
            ?? null;
    }

    /**
     * ----------------------------------------------------------
     * All Uploaded Files
     * ----------------------------------------------------------
     */
    public function files(): array
    {
        return $_FILES;
    }

    /**
     * ----------------------------------------------------------
     * Check Input Exists
     * ----------------------------------------------------------
     */
    public function has(
        string $key
    ): bool {

        return isset($_POST[$key])
            || isset($_GET[$key]);
    }

    /**
     * ----------------------------------------------------------
     * Server Variable
     * ----------------------------------------------------------
     */
    public function server(
        string $key,
        mixed $default = null
    ): mixed {

        return $_SERVER[$key]
            ?? $default;
    }

    /**
     * ----------------------------------------------------------
     * Client IP
     * ----------------------------------------------------------
     */
    public function ip(): string
    {
        return $_SERVER['REMOTE_ADDR']
            ?? '0.0.0.0';
    }

    /**
     * ----------------------------------------------------------
     * User Agent
     * ----------------------------------------------------------
     */
    public function userAgent(): string
    {
        return $_SERVER['HTTP_USER_AGENT']
            ?? '';
    }

    /**
     * ----------------------------------------------------------
     * AJAX Request
     * ----------------------------------------------------------
     */
    public function ajax(): bool
    {
        return (

            $_SERVER['HTTP_X_REQUESTED_WITH']
                ?? ''

        ) === 'XMLHttpRequest';
    }

    /**
     * ----------------------------------------------------------
     * Current URL
     * ----------------------------------------------------------
     */
    public function url(): string
    {
        $protocol = isset($_SERVER['HTTPS'])

            ? 'https://'

            : 'http://';

        return $protocol .

            ($_SERVER['HTTP_HOST'] ?? '') .

            ($_SERVER['REQUEST_URI'] ?? '');
    }
}