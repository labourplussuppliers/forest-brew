<?php

declare(strict_types=1);

class View
{
    /**
     * ----------------------------------------------------------
     * Render View
     * ----------------------------------------------------------
     */
    public static function render(
        string $view,
        array $data = []
    ): void {

        $view = str_replace(

            '.',

            '/',

            $view

        );

        $file = APP_PATH .

            '/Views/' .

            $view .

            '.php';

        if (!file_exists($file)) {

            throw new Exception(

                "View not found : {$view}"

            );

        }

        extract(

            $data,

            EXTR_SKIP

        );

        require $file;
    }

    /**
     * ----------------------------------------------------------
     * Check View Exists
     * ----------------------------------------------------------
     */
    public static function exists(
        string $view
    ): bool {

        $view = str_replace(

            '.',

            '/',

            $view

        );

        return file_exists(

            APP_PATH .

            '/Views/' .

            $view .

            '.php'

        );

    }

    /**
     * ----------------------------------------------------------
     * Render Partial
     * ----------------------------------------------------------
     */
    public static function partial(
        string $view,
        array $data = []
    ): void {

        self::render(

            $view,

            $data

        );

    }

    /**
     * ----------------------------------------------------------
     * Escape Output
     * ----------------------------------------------------------
     */
    public static function escape(
        mixed $value
    ): string {

        return htmlspecialchars(

            (string) $value,

            ENT_QUOTES,

            'UTF-8'

        );

    }

    /**
     * ----------------------------------------------------------
     * Include File
     * ----------------------------------------------------------
     */
    public static function include(
        string $view,
        array $data = []
    ): void {

        self::render(

            $view,

            $data

        );

    }

    /**
     * ----------------------------------------------------------
     * Share Global Variable
     * ----------------------------------------------------------
     */
    public static function share(
        string $key,
        mixed $value
    ): void {

        $GLOBALS[$key] = $value;

    }

    /**
     * ----------------------------------------------------------
     * Get Shared Variable
     * ----------------------------------------------------------
     */
    public static function get(
        string $key,
        mixed $default = null
    ): mixed {

        return $GLOBALS[$key]

            ?? $default;

    }
}