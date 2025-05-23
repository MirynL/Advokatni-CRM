<?php

namespace App\Entities;

use Nette\Database\Row;
use Nette\Security\IIdentity;

class UserEntity implements IIdentity
{
    private ?int $id;
    private string $name;
    private string $surname;
    private ?string $fullname;
    private string $email;
    private array $roles = [];
    private \DateTime $created_at;
    private ?\DateTime $modified_at;
    private UserEntity $modified_by;
    private string $status;

    // Konstruktor pro nastavení hodnot uživatele
    public function __construct(?int $id, string $name, string $surname,?string $fullname, string $email, \DateTime $created_at, \DateTime $modified_at, ?UserEntity $modified_by, string $status, array $roles = [])
    {
        $this->id = $id;
        $this->name = $name;
        $this->surname = $surname;
        $this->fullname = $fullname;
        $this->email = $email;
        $this->created_at = $created_at;
        $this->modified_at = $modified_at;
        $this->modified_by = $modified_by;
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
    
    public function getSurname(): string
    {
        return $this->surname;
    }

    public function setSurname(string $surname): void
    {
        $this->surname = $surname;
    }

    public function getFullName(): string
    {
        return $this->fullname;
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
    public function getModifiedAt(): \DateTime
    {
        return $this->modified_at;
    }

    public function getModifiedBy(): ?UserEntity
    {
       return $this->modified_by;
    }

    public function setModifiedBy(UserEntity $modified_by): void
    {
       $this->modified_by = $modified_by;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): void
    {
        $this->status = $status;
    }

    public function getRoles(): array
    {
        return $this->roles;
    }

    public function setRoles(array $roles): void
    {
        $this->roles = $roles;
    }



    // Metoda pro převod dat do pole (např. pro odpověď na API nebo uložení do databáze)
    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'surname' => $this->getSurname(),
            'fullname' => $this->getFullName(),
            'email' => $this->getEmail(),
            'created_at' => $this->getCreatedAt()->format('Y-m-d H:i:s'),
            'status' => $this->getStatus(),
        ];
    }
}