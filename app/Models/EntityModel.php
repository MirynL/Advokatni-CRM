<?php

namespace App\Models;

use App\Entities\EntityEntity;

class EntityModel extends BaseModel
{

    public function __construct()
    {
      
    }

    // Načtení všech rolí
    public function getAllEntities(): array
    {
        $entities = [];
        foreach ($this->db->table('entities') as $row) {
            $entities[] = new EntityEntity($row->id, $row->name);
        }
        return $entities;
    }
    public function getEntityById($id): EntityEntity
    {
        $row = $this->db->table('Entities')->get($id);
        $entity= new EntityEntity($row->id, $row->name);
        
        return $entity;
    }
}
