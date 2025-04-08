<?php

namespace App\Models;

use App\Entities\CaseStatusEntity;


class CaseStatusModel extends BaseModel
{
    

    public function __construct()
    {
       
    }

    // Načtení všech rolí
    public function getAllCaseStatuses(): array
    {
        $statuses = [];
        foreach ($this->db->table('case_statuses') as $row) {
            $statuses[] = new CaseStatusEntity($row->id, $row->name);
        }
        return $statuses;
    }
    public function getCaseStatusById($id): CaseStatusEntity
    {
        $row = $this->db->table('case_statuses')->get($id);
        $case_status= new CaseStatusEntity($row->id, $row->name);
        
        return $case_status;
    }
    public function getCaseStatusByName($name): CaseStatusEntity
    {
        $row = $this->db->table('case_statuses')->where('name', $name)->fetch();
        $case_status= new CaseStatusEntity($row->id, $row->name);
        
        return $case_status;
    }
}
