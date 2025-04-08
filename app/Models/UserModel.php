<?php

namespace App\Models;

use App\Entities\UserEntity;
use App\Entities\RoleEntity;
use Nette\Database\Explorer;
use Nette\Security\Passwords;

class UserModel
{
    /** @var Explorer */
    private $db;

    public function __construct(Explorer $db)
    {
        $this->db = $db;
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
       

        $roles = [];
        foreach ($this->db->table('users_roles')->where('user_id', $row) as $roleRow) {
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
                $row->status,
                $roles
            );
        }
        return $users;
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