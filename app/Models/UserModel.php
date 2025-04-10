<?php

namespace App\Models;

use App\Entities\UserEntity;
use App\Entities\RoleEntity;

class UserModel extends BaseModel
{

    public function __construct()
    {
 
    }

    /**
     * Vrátí uživatele podle jeho ID.
     * 
     * @param int $id
     * @return UserEntity|null
     */
    public function getUserById(int $id): ?UserEntity
    {
        $row = $this->db->table('users')->get($id);
        $roles = [];
        $modified_by = $this->getUserById($row->modified_by);
        foreach ($this->db->table('users_roles')->where('user_id', $id) as $roleRow) {
            $role = new RoleEntity($roleRow->role_id, $roleRow->role->name); // předpokládám, že role mají id a name
            $roles[] = $role;
        }
        if ($row) {
            $user = new UserEntity(
                $row->id,
                $row->name,
                $row->surname,
                $row->fullname,
                $row->email,
                $row->created_at,
                $row->modified_at,
                $modified_by,
                $row->status,
                $roles // Předání rolí do konstruktoru
            );
            return $user;
        }
        return null;
    }

    public function getPasswordHash(string $email): string
    {
        $row = $this->db->table('users')->select('password_hash')->where('email',$email)->fetch();

        if ($row) {
            return $row['password_hash'];
        }
        return '';
    }
    /**
     * Vrátí uživatele podle jeho emailu.
     * 
     * @param string $email
     * @return UserEntity|null
     */
    public function getUserByEmail(string $email): ?UserEntity
    {
        $row = $this->db
        ->table('users')
        ->where('email', $email)
        ->fetch();
       
        $modified_by = $this->getUserById($row->modified_by);
        $roles = [];
        foreach ($this->db->table('users_roles')->where('user_id', $row->getPrimary()) as $roleRow) {
            $role = new RoleEntity($roleRow->role_id, $roleRow->role->name); 
            $roles[] = $role;
        }
        if ($row) {
            $user = new UserEntity(
                $row->id,
                $row->name,
                $row->surname,
                $row->fullname,
                $row->email,
                $row->created_at,
                $row->modified_at,
                $modified_by,
                $row->status,
                $roles // Předání rolí do konstruktoru
            );
            return $user;
        }
        return null;
    }
    /**
     * Vrátí všechny uživatele jako pole UserEntity.
     * 
     * @return UserEntity[]
     */
    public function getAllUsers(): array
    {
        $users = [];
        
        foreach ($this->db->table('users')->fetchAll() as $row) {
            $modified_by = $this->getUserById($row->modified_by);
            $roles = [];
            foreach ($this->db->table('users_roles')->where('user_id', $row) as $roleRow) {
                $role = new RoleEntity($roleRow->role_id, $roleRow->role->name); // předpokládám, že role mají id a name
                $roles[] = $role;
            }
            $users[] = new UserEntity(
                $row->id,
                $row->name,
                $row->surname,
                $row->fullname,
                $row->email,
                new \Nette\Utils\DateTime($row->created_at),
                new \Nette\Utils\DateTime($row->modified_at),
                $modified_by,
                $row->status,
                $roles
            );
        }
        return $users;
    }

    protected function mapEntityToArray(object $user): array
    {
        assert($user instanceof UserEntity);
        return [
            'id'   => $user->getId(),
            'name' => $user->getName(),
            'surname' => $user->getSurName(),
            'fullname' => $user->getFullName(),
            'email' => $user->getEmail(),
            'created_at' => $user->getCreatedAt(),
            'modified_at' => $user->getModifiedAt(),
            'modified_by' => $user->getModifiedBy()->getId(),
        ];
    }

    /**
     * Přidá nového uživatele.
     * 
     * @param string $name
     * @param string $email
     * @param string $status
     * @return void
     */
    public function addUser(UserEntity $user): void
    {

       
        $new_id = $this->db->table('users')->insert([
            'name' => $user->getName(),
            'surname' => $user->getSurname(),
            'email' => $user->getEmail(),
            'password_hash' => password_hash('1234', PASSWORD_DEFAULT),
            'status' => $user->getStatus()
        ]);

        foreach($user->getRoles() as $role){
        $this->db->table('users_roles')->insert([
            'user_id' => $new_id -> getPrimary(),
            'role_id' => $role->getId()
        ]);
        }
    }
}