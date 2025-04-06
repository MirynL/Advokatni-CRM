<?php

namespace App\Models;

use App\Entities\EntityEntity;
use Nette\Database\Explorer;

class EntityModel
{
    private Explorer $database;

    public function __construct(Explorer $database)
    {
        $this->database = $database;
    }

    // Načtení všech rolí
    public function getAllEntities(): array
    {
        $entities = [];
        foreach ($this->database->table('entities') as $row) {
            $entities[] = new EntityEntity($row->id, $row->name);
        }
        return $entities;
    }
    public function getEntityById($id): EntityEntity
    {
        $row = $this->database->table('Entities')->get($id);
        $entity= new EntityEntity($row->id, $row->name);
        
        return $entity;
    }
}
