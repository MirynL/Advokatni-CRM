<?php

namespace App\Models;

use App\Entities\RoleEntity;

class RoleModel extends BaseModel
{

    public function __construct()
    {
        
    }

    // Načtení všech rolí
    public function getAllRoles(): array
    {
        $roles = [];
        foreach ($this->db->table('roles') as $row) {
            $roles[] = new RoleEntity($row->id, $row->name);
        }
        return $roles;
    }
    public function getRoleById($id): RoleEntity
    {
        $row = $this->db->table('roles')->get($id);
        $role= new RoleEntity($row->id, $row->name);
        
        return $role;
    }
}
