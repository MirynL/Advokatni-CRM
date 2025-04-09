<?php

namespace App\Models;

use Nette\Database\Explorer;
use App\Logger\ActivityLogger;

abstract class BaseModel
{
    public function __construct(
        protected Explorer $db,
        protected ActivityLogger $logger
    ) {}

protected function create(string $table,object $entity): void
    {
        $data = $this->mapEntityToArray($entity);
        $row = $this->db->table($table)->insert($data);

        $this->logger->logCreate(
            $table,
            (int) $row->getPrimary(),
            $data
        );
    }

protected function update(string $table,object $entity): void 
    {
        $row = $this->db->table($table)->get($entity->getId()); 

        if (!$row) {
             throw new \Exception("Záznam nenalezen ($table #$entity->getId()");
        }

        $new = $this->mapEntityToArray($entity); 
        $old = $row->toArray();

        $row->update($new);

        $this->logger->logUpdate(
            $table, 
            $entity->getId(), 
            $old,
            $new
        );
    }
    protected function delete(int $id,string $table): void
    {
        $row = $this->db->table($table)->get($id);

        if (!$row) {
            throw new \Exception("Záznam nenalezen (".$table." #".$id.")");
        }

        $old = $row->toArray();
        $row->delete();

        $this->logger->logDelete(
            $table,
            $id,
            $old
        );
    }




abstract protected function mapEntityToArray(object $entity): array;





}