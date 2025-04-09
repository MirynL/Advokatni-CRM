<?php

namespace App\Models;

use App\Entities\TaskStatusEntity;

class TaskStatusModel extends BaseModel
{
  

    public function __construct()
    {
       
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

    protected function mapEntityToArray(object $taskStatus): array
    {
        assert($taskStatus instanceof TaskStatusEntity);
        return [
            'id'   => $taskStatus->getId(),
            'name' => $taskStatus->getName()
        ];
    }
}
