<?php

namespace App\Providers;

use DateTimeImmutable;

class Validator
{
    private array $errors = [];

    private string $key = "";
    private string $value = "";
    private string $name = "";

    public function field(
        string $key,
        mixed $value,
        string $name
    ): self {
        $this->key = $key;
        $this->name = $name;
        $this->value = "";

        if ($value === null) {
            return $this;
        }

        if (!is_string($value)) {
            $this->addError("$name doit être une valeur texte.");

            return $this;
        }

        $this->value = $value;

        return $this;
    }

    public function required(): self
    {
        if (!$this->hasError() && trim($this->value) === "") {
            $this->addError("$this->name est obligatoire.");
        }

        return $this;
    }

    public function min(int $length): self
    {
        if ($this->skipOptional()) {
            return $this;
        }

        if (mb_strlen($this->value, "UTF-8") < $length) {
            $this->addError(
                "$this->name doit contenir au moins $length caractères."
            );
        }

        return $this;
    }

    public function max(int $length): self
    {
        if ($this->skipOptional()) {
            return $this;
        }

        if (mb_strlen($this->value, "UTF-8") > $length) {
            $this->addError(
                "$this->name doit contenir au maximum $length caractères."
            );
        }

        return $this;
    }

    public function int(): self
    {
        if ($this->skipOptional()) {
            return $this;
        }

        $result = filter_var(
            $this->value,
            FILTER_VALIDATE_INT,
            ["options" => ["min_range" => 1]]
        );

        if ($result === false) {
            $this->addError(
                "$this->name doit être un entier positif."
            );
        }

        return $this;
    }

    public function date(): self
    {
        if ($this->skipOptional()) {
            return $this;
        }

        if (
            preg_match(
                '/\A[0-9]{4}-[0-9]{2}-[0-9]{2}\z/',
                $this->value
            ) !== 1
        ) {
            $this->addError(
                "$this->name doit respecter le format AAAA-MM-JJ."
            );

            return $this;
        }

        $date = DateTimeImmutable::createFromFormat(
            "!Y-m-d",
            $this->value
        );

        if (
            $date === false
            || $date->format("Y-m-d") !== $this->value
            || substr($this->value, 0, 4) < "1000"
        ) {
            $this->addError("$this->name est invalide.");
        }

        return $this;
    }

    private function addError(string $message): self
    {
        $this->errors[$this->key] ??= $message;

        return $this;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    private function hasError(): bool
    {
        return isset($this->errors[$this->key]);
    }

    private function skipOptional(): bool
    {
        return $this->hasError() || $this->value === "";
    }
}
