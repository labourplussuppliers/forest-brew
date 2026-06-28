<?php

class Validator
{
    private PDO $db;
    private array $errors = [];

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function required(string $field, mixed $value, string $message = null): self
    {
        if (trim((string)$value) === '') {
            $this->errors[$field] = $message ?? ucfirst($field) . ' is required.';
        }

        return $this;
    }

    public function email(string $field, mixed $value, string $message = null): self
    {
        if (!empty($value) && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
            $this->errors[$field] = $message ?? 'Please enter a valid email address.';
        }

        return $this;
    }

    public function min(string $field, mixed $value, int $length, string $message = null): self
    {
        if (strlen((string)$value) < $length) {
            $this->errors[$field] = $message ?? ucfirst($field) . " must be at least {$length} characters.";
        }

        return $this;
    }

    public function max(string $field, mixed $value, int $length, string $message = null): self
    {
        if (strlen((string)$value) > $length) {
            $this->errors[$field] = $message ?? ucfirst($field) . " may not exceed {$length} characters.";
        }

        return $this;
    }

    public function numeric(string $field, mixed $value, string $message = null): self
    {
        if (!is_numeric($value)) {
            $this->errors[$field] = $message ?? ucfirst($field) . ' must be numeric.';
        }

        return $this;
    }

    public function integer(string $field, mixed $value, string $message = null): self
    {
        if (filter_var($value, FILTER_VALIDATE_INT) === false) {
            $this->errors[$field] = $message ?? ucfirst($field) . ' must be an integer.';
        }

        return $this;
    }

    public function same(
        string $field,
        mixed $value,
        mixed $compare,
        string $message = null
    ): self {

        if ($value !== $compare) {
            $this->errors[$field] = $message ?? ucfirst($field) . ' does not match.';
        }

        return $this;
    }

    public function unique(
        string $field,
        string $table,
        string $column,
        mixed $value,
        string $message = null
    ): self {

        $stmt = $this->db->prepare("
            SELECT id
            FROM {$table}
            WHERE {$column} = ?
            LIMIT 1
        ");

        $stmt->execute([$value]);

        if ($stmt->fetch()) {
            $this->errors[$field] = $message ?? ucfirst($field) . ' already exists.';
        }

        return $this;
    }

    public function exists(
        string $field,
        string $table,
        string $column,
        mixed $value,
        string $message = null
    ): self {

        $stmt = $this->db->prepare("
            SELECT id
            FROM {$table}
            WHERE {$column} = ?
            LIMIT 1
        ");

        $stmt->execute([$value]);

        if (!$stmt->fetch()) {
            $this->errors[$field] = $message ?? ucfirst($field) . ' does not exist.';
        }

        return $this;
    }

    public function image(
        string $field,
        array $file,
        string $message = null
    ): self {

        if (empty($file['name'])) {
            return $this;
        }

        $allowed = [
            'image/jpeg',
            'image/png',
            'image/webp'
        ];

        if (!in_array($file['type'], $allowed)) {
            $this->errors[$field] = $message ?? 'Invalid image format.';
        }

        return $this;
    }

    public function maxFileSize(
        string $field,
        array $file,
        int $sizeMB,
        string $message = null
    ): self {

        if (empty($file['name'])) {
            return $this;
        }

        if ($file['size'] > ($sizeMB * 1024 * 1024)) {
            $this->errors[$field] = $message ?? "Maximum file size is {$sizeMB} MB.";
        }

        return $this;
    }

    public function url(
        string $field,
        mixed $value,
        string $message = null
    ): self {

        if (!empty($value) && !filter_var($value, FILTER_VALIDATE_URL)) {
            $this->errors[$field] = $message ?? 'Please enter a valid URL.';
        }

        return $this;
    }

    public function custom(
        bool $condition,
        string $field,
        string $message
    ): self {

        if (!$condition) {
            $this->errors[$field] = $message;
        }

        return $this;
    }

    public function passed(): bool
    {
        return empty($this->errors);
    }

    public function failed(): bool
    {
        return !$this->passed();
    }

    public function errors(): array
    {
        return $this->errors;
    }

    public function first(string $field): ?string
    {
        return $this->errors[$field] ?? null;
    }

    public function reset(): self
    {
        $this->errors = [];

        return $this;
    }
}