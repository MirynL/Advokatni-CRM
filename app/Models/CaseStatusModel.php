<?php

namespace App\Models;

use App\Entities\CaseStatusEntity;
use Nette\Database\Explorer;

class CaseStatusModel
{
    private Explorer $database;

    public function __construct(Explorer $database)
    {
        $this->database = $database;
    }

    // Načtení všech rolí
    public function getAllCaseStatuses(): array
    {
        $statuses = [];
        foreach ($this->database->table('case_statuses') as $row) {
            $statuses[] = new CaseStatusEntity($row->id, $row->name);
        }
        return $statuses;
    }
    public function getCaseStatusById($id): CaseStatusEntity
    {
        $row = $this->database->table('case_statuses')->get($id);
        $case_status= new CaseStatusEntity($row->id, $row->name);
        
        return $case_status;
    }
    public function getCaseStatusByName($name): CaseStatusEntity
    {
        $row = $this->database->table('case_statuses')->where('name', $name)->fetch();
        $case_status= new CaseStatusEntity($row->id, $row->name);
        
        return $case_status;
    }
}
