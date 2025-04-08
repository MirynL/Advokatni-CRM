<?php

namespace App\Models;

use App\Entities\TaskStatusEntity;
use Nette\Database\Explorer;

class TaskStatusModel
{
    private Explorer $db;

    public function __construct(Explorer $db)
    {
        $this->db = $db;
    }

    // Načtení všech rolí
    public function getAllTaskStatuses(): array
    {
        $statuses = [];
        foreach ($this->db->table('task_statuses') as $row) {
            $statuses[] = new TaskStatusEntity($row->id, $row->name);
        }
        return $statuses;
    }
    public function getTaskStatusById($id): TaskStatusEntity
    {
        $row = $this->db->table('task_statuses')->get($id);
        $task_status= new TaskStatusEntity($row->id, $row->name);
        
        return $task_status;
    }
}
