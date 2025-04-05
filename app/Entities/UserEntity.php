<?php

namespace App\Entities;

use Nette\Database\Row;

class UserEntity
{
    private ?int $id;
    private string $name;
    private string $email;
    private array $roles = [];
    private \DateTime $created_at;
    private string $status;

    // Konstruktor pro nastavení hodnot uživatele
    public function __construct(?int $id, string $name, string $email, \DateTime $created_at, string $status, array $roles = [])
    {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->created_at = $created_at;
        $this->status = $status;
        $this->roles = $roles;
    }

    // Gettery a settery pro jednotlivé vlastnosti

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getCreatedAt(): \DateTime
    {
        return $this->created_at;
    }

    public function getStatus(): string
    {
        return $this->status;
    }
    public function getRoles(): array
    {
        return $this->roles;
    }

    public function setRoles(array $roles): void
    {
        $this->roles = $roles;
    }

    public function setStatus(string $status): void
    {
        $this->status = $status;
    }

    // Metoda pro převod dat do pole (např. pro odpověď na API nebo uložení do databáze)
    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'email' => $this->getEmail(),
            'created_at' => $this->getCreatedAt()->format('Y-m-d H:i:s'),
            'status' => $this->getStatus(),
        ];
    }
}