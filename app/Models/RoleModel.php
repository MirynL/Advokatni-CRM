<?php

namespace App\Models;

use App\Entities\RoleEntity;
use Nette\Database\Explorer;

class RoleModel
{
    private Explorer $database;

    public function __construct(Explorer $database)
    {
        $this->database = $database;
    }

    // Načtení všech rolí
    public function getAllRoles(): array
    {
        $roles = [];
        foreach ($this->database->table('roles') as $row) {
            $roles[] = new RoleEntity($row->id, $row->name);
        }
        return $roles;
    }
}
