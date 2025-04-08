<?php

namespace App\Logger;

use Nette\Database\Explorer;
use Nette\Utils\DateTime;

class ActivityLogger
{
    public function __construct(
        private Explorer $db,
    ) {}

 /**
     * Loguje změny mezi starými a novými daty
     */
    public function logUpdate(string $entityType, int $recordId, array $old, array $new): void
    {
        $changes = $this->getChangedFields($old, $new);

        if (empty($changes)) {
            return; // není co logovat
        }

        $this->db->table('activity_log')->insert([
            'user_id' => $new['modified_by'] ?? null,
            'action' => 'update',
            'entity_type' => $entityType,
            'record_id' => $recordId,
            'changed_fields' => json_encode($changes),
            'created_at' => new DateTime(),
        ]);
    }

    /**
     * Loguje vytvoření záznamu
     */
    public function logCreate(string $entityType, int $recordId, array $new): void
    {
        $this->db->table('activity_log')->insert([
            'user_id' => $new['modified_by'] ?? null,
            'action' => 'insert',
            'entity_type' => $entityType,
            'record_id' => $recordId,
            'changed_fields' => json_encode(['created' => $new]),
            'created_at' => new DateTime(),
        ]);
    }

    /**
     * Loguje smazání záznamu
     */
    public function logDelete(string $entityType, int $recordId, array $old, ?string $description = null): void
    {
        $this->db->table('activity_log')->insert([
            'user_id' => $old['modified_by'] ?? null,
            'action' => 'delete',
            'entity_type' => $entityType,
            'record_id' => $recordId,
            'changed_fields' => json_encode(['deleted' => $old]),
            'created_at' => new DateTime(),
        ]);
    }

    /**
     * Vygeneruje pole se změněnými hodnotami
     */
    private function getChangedFields(array $old, array $new): array
    {
        $diff = [];

        foreach ($new as $key => $newValue) {
            $oldValue = $old[$key] ?? null;

            // konverze objektů (např. DateTime) na string pro porovnání
            if ($oldValue instanceof \DateTimeInterface) {
                $oldValue = $oldValue->format('Y-m-d H:i:s');
            }
            if ($newValue instanceof \DateTimeInterface) {
                $newValue = $newValue->format('Y-m-d H:i:s');
            }

            if ($oldValue != $newValue) {
                $diff[$key] = [$oldValue, $newValue];
            }
        }

        return $diff;
    }
}