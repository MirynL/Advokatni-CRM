<?php

namespace App\Models;

use App\Entity\CaseEntity;
use Nette\Database\Explorer;
use Nette\Database\Table\ActiveRow;
use App\Models\UserModel;
use App\Models\CurrencyModel;
use App\Models\CaseStatusModel;

class CaseModel
{
    private Explorer $db;
    private UserModel $userModel;
    private CurrencyModel $currencyModel;
    private CaseStatusModel $caseStatusModel;

    public function __construct(Explorer $db, UserModel $userModel, CurrencyModel $currencyModel, CaseStatusModel $caseStatusModel)
    {
        $this->db = $db;
        $this->userModel = $userModel;
        $this->currencyModel = $currencyModel;
        $this->caseStatusModel = $caseStatusModel;
    }

    /** @return CaseEntity[] */
    public function getAllCases(): array
    {
        $rows = $this->db->table('cases')->fetchAll();
        return array_map([$this, 'mapRowToEntity'], $rows);
    }

    /** @return CaseEntity[] */
    public function getAllCasesOwnedById($owner_id): array
    {
        $rows = $this->db->table('cases')->where('owner_id = ?',$owner_id)->fetchAll();
        return array_map([$this, 'mapRowToEntity'], $rows);
    }

    public function getCaseById(int $id): ?CaseEntity
    {
        $row = $this->db->table('cases')->get($id);
        return $row ? $this->mapRowToEntity($row) : null;
    }

    public function insert(CaseEntity $case): ActiveRow
    {
        return $this->db->table('cases')->insert($this->mapEntityToArray($case));
    }

    public function update(CaseEntity $case): void
    {
        $this->db->table('cases')->get($case->getId())?->update($this->mapEntityToArray($case));
    }

    public function delete(int $id): void
    {
        $this->db->table('cases')->get($id)?->delete();
    }

    /** @internal */
    private function mapRowToEntity(ActiveRow $row): CaseEntity
    {
        $case = new CaseEntity();
        $case->setId($row->id);
        $case->setCaseNumber($row->case_number);
        $case->setTitle($row->title);
        $case->setDescription($row->description);
        $case->setArchivedAt($row->archived_at);
        $case->setCreatedAt(new \DateTimeImmutable($row->created_at));
        $case->setModifiedAt(new \DateTimeImmutable($row->modified_at));        

        $currency = $this->currencyModel->getCurrencyByCode($row->currency_code);
        $case->setCurrency($currency);

        $owner = $this->userModel->getUserById($row->owner_id);
        $case->setOwner($owner);

        $createdBy = $this->userModel->getUserById($row->created_by);
        $case->setCreatedBy($createdBy);

        $modifiedBy = null;
        if ($row->modified_by !== null) {
            $modifiedBy = $this->userModel->getUserById($row->modified_by);
        }
        $case->setModifiedBy($modifiedBy);

        $status = $this->caseStatusModel->getCaseStatusById($row->status_id);
        $case->setStatus($status);

        return $case;
    }

    /** @internal */
    private function mapEntityToArray(CaseEntity $case): array
    {
        return [
            'case_number'   => $case->getCaseNumber(),
            'title'         => $case->getTitle(),
            'description'   => $case->getDescription(),
            'archived_at'   => $case->getArchivedAt(),
            'currency_code' => $case->getCurrency()->getCode(),
            'owner_id'      => $case->getOwner()->getId(),
            'created_by'    => $case->getCreatedBy()->getId(),
            'created_at'    => $case->getCreatedAt()->format('Y-m-d H:i:s'),
            'modified_by'   => $case->getModifiedBy()->getId(),
            'modified_at'   => $case->getModifiedAt()->format('Y-m-d H:i:s'),
            'owner_id'      => $case->getOwner()->getId(),
            'created_by'    => $case->getCreatedBy()->getId(),
            'status_id'     => $case->getStatus()->getId(),
        ];
    }
    
}
