<?php

namespace App\Entities;

class CurrencyEntity
{
    private string $code;
    private ?string $name;

    // Konstruktor
    public function __construct(string $code, ?string $name)
    {
        $this->code = $code;
        $this->name = $name;
    }

    // Gettery a settery
    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): void
    {
        $this->code = $code;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }
}
