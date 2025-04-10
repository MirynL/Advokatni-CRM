<?php

namespace App\Models;

use App\Entities\ClientEntity;
use Nette\Database\Table\ActiveRow;
use App\Models\UserModel;
use App\Models\CaseModel;

class ClientModel extends BaseModel
{
    private UserModel $userModel;
    private CaseModel $caseModel;

    public function __construct(UserModel $userModel, CaseModel $caseModel)
    {
        $this->userModel = $userModel;
        $this->caseModel = $caseModel;
    }

    /** @return ClientEntity[] */
    public function getAllClients(): array
    {
        $clients = [];
        $rows = $this->db->table('clients')->fetchAll();
        foreach ($rows as $row) {
            $cases = [];
                foreach ($this->db->table('clients_cases')->where('client_id', $row->getPrimary()) as $caseRow) {
                    $case = $this->caseModel->getCaseById($caseRow->case_id);
                    $cases[] = $case;
                }
            $client = $this->mapRowToEntity($row,$cases);
            $clients[] = $client; 
        }
        
        return $clients;
    }

    /** @return ClientEntity[] */
    public function getAllClientsOwnedById($owner_id): array
    {
        $rows = $this->db->table('clients')->where('owner_id = ?',$owner_id)->fetchAll();
        return array_map([$this, 'mapRowToEntity'], $rows);
    }

    public function getClientById(int $id): ?ClientEntity
    {
        $row = $this->db->table('clients')->get($id);
        $cases = [];
        foreach ($this->db->table('clients_cases')->where('client_id', $row->getPrimary()) as $caseRow) {
            $case = $this->caseModel->getCaseById($caseRow->case_id); 
            $cases[] = $case;
        }
        $client = $this->mapRowToEntity($row,$cases);
        

        return $client ? $client : null;
    }

    /** @internal */
    private function mapRowToEntity(ActiveRow $row,?array $cases): ClientEntity
    {
        $client = new ClientEntity();
        $client->setId($row->id);
        $client->setShortcode($row->shortcode);
        $client->setType($row->type);
        $client->setCompanyName($row->company_name);
        $client->setFirstName($row->first_name);
        $client->setLastName($row->last_name);
        $client->setBirthDate($row->birth_date ? new \DateTimeImmutable($row->birth_date) : null);
        $client->setPersonalId($row->personal_id);
        $client->setPhone($row->phone);
        $client->setEmail($row->email);
        $client->setAddress($row->address);
        $client->setCases($cases);
        $client->setOwner($this->userModel->getUserById($row->owner_id));
        $client->setCreatedBy($this->userModel->getUserById($row->created_by));
        $client->setCreatedAt(new \DateTimeImmutable($row->created_at));
        $client->setModifiedAt(new \DateTimeImmutable($row->modified_at));
        $user = null;
        if ($row->modified_by !== null) {
            $user = $this->userModel->getUserById($row->modified_by);
        }
        $client->setModifiedBy($user);
        $client->setStatus($row->status);
        return $client;
    }

    /** @internal */
    protected function mapEntityToArray(object $client): array
    {
        assert($client instanceof ClientEntity);
        return [
            'shortcode'     => $client->getShortcode(),
            'type'          => $client->getType(),
            'company_name'  => $client->getCompanyName(),
            'first_name'    => $client->getFirstName(),
            'last_name'     => $client->getLastName(),
            'full_name'     => $client->getFullName(),
            'birth_date'    => $client->getBirthDate()?->format('Y-m-d'),
            'personal_id'   => $client->getPersonalId(),
            'phone'         => $client->getPhone(),
            'email'         => $client->getEmail(),
            'address'       => $client->getAddress(),
            'owner_id'      => $client->getOwner()->getId(),
            'created_by'    => $client->getCreatedBy()->getId(),
            'created_at'    => $client->getCreatedAt()->format('Y-m-d H:i:s'),
            'modified_at'   => $client->getModifiedAt()->format('Y-m-d H:i:s'),
            'modified_by'   => $client->getModifiedBy()->getId(),
            'status'        => $client->getStatus(),
        ];
    }
    
}
