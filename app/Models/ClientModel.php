<?php

namespace App\Models;

use App\Entity\ClientEntity;
use Nette\Database\Explorer;
use Nette\Database\Table\ActiveRow;
use Nette\Utils\DateTime;
use App\Models\UserModel;

class ClientModel
{
    private Explorer $db;
    private UserModel $userModel;

    public function __construct(Explorer $db, UserModel $userModel)
    {
        $this->db = $db;
        $this->userModel = $userModel;
    }

    /** @return ClientEntity[] */
    public function getAllClients(): array
    {
        $rows = $this->db->table('clients')->fetchAll();
        return array_map([$this, 'mapRowToEntity'], $rows);
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
        return $row ? $this->mapRowToEntity($row) : null;
    }

    public function insert(ClientEntity $client): ActiveRow
    {
        return $this->db->table('clients')->insert($this->mapEntityToArray($client));
    }

    public function update(ClientEntity $client): void
    {
        $this->db->table('clients')->get($client->getId())?->update($this->mapEntityToArray($client));
    }

    public function delete(int $id): void
    {
        $this->db->table('clients')->get($id)?->delete();
    }

    /** @internal */
    private function mapRowToEntity(ActiveRow $row): ClientEntity
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
    private function mapEntityToArray(ClientEntity $client): array
    {
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
            'owner_id'      => $client->getOwner()->id,
            'created_by'    => $client->getCreatedBy()->id,
            'created_at'    => $client->getCreatedAt()->format('Y-m-d H:i:s'),
            'modified_at'   => $client->getModifiedAt()->format('Y-m-d H:i:s'),
            'modified_by'   => $client->getModifiedBy()->id,
            'status'        => $client->getStatus(),
        ];
    }
    
}
