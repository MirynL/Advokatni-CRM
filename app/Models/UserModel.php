<?php

namespace App\Models;

use App\Entities\UserEntity;
use Nette\Database\Explorer;

class UserModel
{
    /** @var Explorer */
    private $database;

    public function __construct(Explorer $database)
    {
        $this->database = $database;
    }

    /**
     * Vrátí uživatele podle jeho ID.
     * 
     * @param int $id
     * @return UserEntity|null
     */
    public function getUserById(int $id): ?UserEntity
    {
        $row = $this->database->table('users')->get($id);
        $roles= $this->database->query('
            SELECT roles.name
            FROM roles
            JOIN users_roles ON roles.id = users_roles.role_id
            JOIN users ON users_roles.user_id = users.id
            WHERE users.id = ?
        ',$id);

        if ($row) {
            return new UserEntity(
                $row->id,
                $row->name,
                $row->email,
                $roles,
                new \Nette\Utils\DateTime($row->created_at),
                $row->status
            );
        }
        return null;
    }

    public function getPasswordHash(string $email): string
    {
        $row = $this->database->table('users')->select('password_hash')->where('email',$email)->fetch();

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
        $row = $this->database->table('users')->where('email', $email)->fetch();
        $roles= $this->database->query('
        SELECT roles.name
        FROM roles
        JOIN users_roles ON roles.id = users_roles.role_id
        JOIN users ON users_roles.user_id = users.id
        WHERE users.email = ?
    ',$email);
        if ($row) {
            return new UserEntity(
                $row->id,
                $row->name,
                $row->email,
                $roles,
                new \Nette\Utils\DateTime($row->created_at),
                $row->status
            );
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
        foreach ($this->database->table('users')->fetchAll() as $row) {
            $roles= $this->database->query('
            SELECT roles.name
            FROM roles
            JOIN users_roles ON roles.id = users_roles.role_id
            JOIN users ON users_roles.user_id = users.id
            WHERE users.id = ?
        ',$row->id);
            $users[] = new UserEntity(
                $row->id,
                $row->name,
                $row->email,
                $roles,
                new \Nette\Utils\DateTime($row->created_at),
                $row->status
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
    public function addUser(string $name, string $email, string $status): void
    {

        // tady jsem skončil
        $this->database->table('users')->insert([
            'name' => $name,
            'email' => $email,
            'status' => $status,
            'created_at' => new \Nette\Utils\DateTime(), // aktuální datum a čas
        ]);
    }
}