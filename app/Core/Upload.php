<?php

class Upload
{
    private array $allowedTypes = [
        'image/jpeg',
        'image/png',
        'image/webp',
        'image/gif'
    ];

    private int $maxSize = 5 * 1024 * 1024;

    public function upload(
        array $file,
        string $directory = 'uploads'
    ): ?string {

        if (!$this->isValid($file)) {
            return null;
        }

        $this->createDirectory($directory);

        $extension = strtolower(
            pathinfo(
                $file['name'],
                PATHINFO_EXTENSION
            )
        );

        $fileName = uniqid() . '_' . time() . '.' . $extension;

        $destination = BASE_PATH .
            "/public/{$directory}/{$fileName}";

        if (!move_uploaded_file(
            $file['tmp_name'],
            $destination
        )) {
            return null;
        }

        return "{$directory}/{$fileName}";
    }

    public function uploadMultiple(
        array $files,
        string $directory = 'uploads'
    ): array {

        $uploaded = [];

        foreach ($files['name'] as $index => $name) {

            $file = [
                'name' => $files['name'][$index],
                'type' => $files['type'][$index],
                'tmp_name' => $files['tmp_name'][$index],
                'error' => $files['error'][$index],
                'size' => $files['size'][$index]
            ];

            $path = $this->upload(
                $file,
                $directory
            );

            if ($path) {
                $uploaded[] = $path;
            }
        }

        return $uploaded;
    }

    public function replace(
        array $file,
        ?string $oldFile,
        string $directory = 'uploads'
    ): ?string {

        $newFile = $this->upload(
            $file,
            $directory
        );

        if (!$newFile) {
            return null;
        }

        if ($oldFile) {
            $this->delete($oldFile);
        }

        return $newFile;
    }

    public function delete(
        string $file
    ): bool {

        $path = BASE_PATH .
            '/public/' .
            ltrim($file, '/');

        if (!file_exists($path)) {
            return false;
        }

        return unlink($path);
    }

    public function exists(
        string $file
    ): bool {

        return file_exists(
            BASE_PATH .
            '/public/' .
            ltrim($file, '/')
        );
    }

    public function size(
        string $file
    ): int {

        $path = BASE_PATH .
            '/public/' .
            ltrim($file, '/');

        if (!file_exists($path)) {
            return 0;
        }

        return filesize($path);
    }

    public function extension(
        string $file
    ): string {

        return strtolower(
            pathinfo(
                $file,
                PATHINFO_EXTENSION
            )
        );
    }

    public function filename(
        string $file
    ): string {

        return basename($file);
    }

    public function url(
        string $file
    ): string {

        return base_url(
            ltrim($file, '/')
        );
    }

    private function isValid(
        array $file
    ): bool {

        if (
            !isset($file['error']) ||
            $file['error'] !== UPLOAD_ERR_OK
        ) {
            return false;
        }

        if (
            !in_array(
                $file['type'],
                $this->allowedTypes
            )
        ) {
            return false;
        }

        if (
            $file['size'] > $this->maxSize
        ) {
            return false;
        }

        return true;
    }

    private function createDirectory(
        string $directory
    ): void {

        $path = BASE_PATH .
            "/public/{$directory}";

        if (!is_dir($path)) {

            mkdir(
                $path,
                0755,
                true
            );

        }
    }

    public function setAllowedTypes(
        array $types
    ): self {

        $this->allowedTypes = $types;

        return $this;
    }

    public function setMaxSize(
        int $size
    ): self {

        $this->maxSize = $size;

        return $this;
    }
}