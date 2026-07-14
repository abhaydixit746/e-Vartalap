<?php
namespace App\Util;

class Validator
{
    private array $errors = [];

    public function required(string $field, mixed $value, string $label): static
    {
        if ($value === null || $value === '') {
            $this->errors[$field] = "{$label} is required.";
        }
        return $this;
    }

    public function minLen(string $field, string $value, int $min, string $label): static
    {
        if (!isset($this->errors[$field]) && mb_strlen($value) < $min) {
            $this->errors[$field] = "{$label} must be at least {$min} characters.";
        }
        return $this;
    }

    public function maxLen(string $field, string $value, int $max, string $label): static
    {
        if (!isset($this->errors[$field]) && mb_strlen($value) > $max) {
            $this->errors[$field] = "{$label} must be at most {$max} characters.";
        }
        return $this;
    }

    public function email(string $field, string $value): static
    {
        if (!isset($this->errors[$field]) && $value !== '' && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
            $this->errors[$field] = "Invalid email address.";
        }
        return $this;
    }

    public function regex(string $field, string $value, string $pattern, string $msg): static
    {
        if (!isset($this->errors[$field]) && $value !== '' && !preg_match($pattern, $value)) {
            $this->errors[$field] = $msg;
        }
        return $this;
    }

    public function passes(): bool
    {
        return empty($this->errors);
    }

    public function errors(): array
    {
        return $this->errors;
    }

    public function firstError(): string
    {
        return reset($this->errors) ?: '';
    }
}
