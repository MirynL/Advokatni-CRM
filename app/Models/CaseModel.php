<?php

namespace App\Models;


use App\Entities\CaseEntity;

use Nette\Database\Table\ActiveRow;
use App\Models\UserModel;
use App\Models\CurrencyModel;
use App\Models\CaseStatusModel;


class CaseModel extends BaseModel
{
   
    private UserModel $userModel;
    private CurrencyModel $currencyModel;
    private CaseStatusModel $caseStatusModel;

    public function __construct(UserModel $userModel, CurrencyModel $currencyModel, CaseStatusModel $caseStatusModel)
    {
        
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
    protected function mapEntityToArray(object $case): array
    {
        assert($case instanceof CaseEntity);
        return [
            'case_number'   => $case->getCaseNumber(),
            'title'         => $case->getTitle(),
            'description'   => $case->getDescription(),
            'archived_at'   => $case->getArchivedAt(),
            'currency_code' => $case->getCurrency()->getCode(),
            'owner_id'      => $case->getOwner()->getId(),
            'created_by'    => $case->getCreatedBy()->getId(),
            'created_at'    => $case->getCreatedAt()->format('Y-m-d H:i:s'),
            'modified_by'   => $case->getModifiedBy() ? $case->getModifiedBy()->getId() : null,
            'modified_at'   => $case->getModifiedAt()->format('Y-m-d H:i:s'),
            'owner_id'      => $case->getOwner()->getId(),
            'created_by'    => $case->getCreatedBy()->getId(),
            'status_id'     => $case->getStatus()->getId(),
        ];
    }
    
}
