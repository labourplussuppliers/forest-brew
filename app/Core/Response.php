<?php

declare(strict_types=1);

class Response
{
    /**
     * ----------------------------------------------------------
     * Set HTTP Status Code
     * ----------------------------------------------------------
     */
    public function status(
        int $code
    ): self {

        http_response_code($code);

        return $this;
    }

    /**
     * ----------------------------------------------------------
     * Redirect
     * ----------------------------------------------------------
     */
    public function redirect(
        string $url,
        int $status = 302
    ): never {

        header(
            'Location: ' . $url,
            true,
            $status
        );

        exit;
    }

    /**
     * ----------------------------------------------------------
     * JSON Response
     * ----------------------------------------------------------
     */
    public function json(
        array|object $data,
        int $status = 200
    ): never {

        http_response_code($status);

        header(
            'Content-Type: application/json; charset=utf-8'
        );

        echo json_encode(

            $data,

            JSON_PRETTY_PRINT
            |
            JSON_UNESCAPED_UNICODE

        );

        exit;
    }

    /**
     * ----------------------------------------------------------
     * HTML Response
     * ----------------------------------------------------------
     */
    public function html(
        string $content,
        int $status = 200
    ): never {

        http_response_code($status);

        header(
            'Content-Type: text/html; charset=utf-8'
        );

        echo $content;

        exit;
    }

    /**
     * ----------------------------------------------------------
     * Plain Text Response
     * ----------------------------------------------------------
     */
    public function text(
        string $content,
        int $status = 200
    ): never {

        http_response_code($status);

        header(
            'Content-Type: text/plain; charset=utf-8'
        );

        echo $content;

        exit;
    }

    /**
     * ----------------------------------------------------------
     * No Content
     * ----------------------------------------------------------
     */
    public function noContent(): never
    {
        http_response_code(204);

        exit;
    }

    /**
     * ----------------------------------------------------------
     * Download File
     * ----------------------------------------------------------
     */
    public function download(
        string $file,
        ?string $name = null
    ): never {

        if (!file_exists($file)) {

            $this->abort(404);

        }

        $name ??= basename($file);

        header(
            'Content-Description: File Transfer'
        );

        header(
            'Content-Type: application/octet-stream'
        );

        header(
            'Content-Disposition: attachment; filename="' . $name . '"'
        );

        header(
            'Content-Length: ' . filesize($file)
        );

        readfile($file);

        exit;
    }

    /**
     * ----------------------------------------------------------
     * Set Header
     * ----------------------------------------------------------
     */
    public function header(
        string $name,
        string $value
    ): self {

        header(
            "{$name}: {$value}"
        );

        return $this;
    }

    /**
     * ----------------------------------------------------------
     * Abort Request
     * ----------------------------------------------------------
     */
    public function abort(
        int $code = 404
    ): never {

        http_response_code($code);

        $errorView = BASE_PATH .
            '/app/Views/errors/' .
            $code .
            '.php';

        if (

            file_exists($errorView)

        ) {

            require $errorView;

        } else {

            echo "<h1>{$code}</h1>";

        }

        exit;
    }

    /**
     * ----------------------------------------------------------
     * Success Response
     * ----------------------------------------------------------
     */
    public function success(
        string $message = 'Success'
    ): never {

        $this->json([

            'success' => true,

            'message' => $message

        ]);

    }

    /**
     * ----------------------------------------------------------
     * Error Response
     * ----------------------------------------------------------
     */
    public function error(
        string $message,
        int $status = 400
    ): never {

        $this->json([

            'success' => false,

            'message' => $message

        ], $status);

    }
}