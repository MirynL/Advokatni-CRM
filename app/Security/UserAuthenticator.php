<?php

namespace App\Security;

use Nette;
use App\Models\UserModel;
use App\Entities\UserEntity;
use Nette\Security\Authenticator;
use Nette\Security\IIdentity;

class UserAuthenticator implements Authenticator
{
    private UserModel $userModel;

    public function __construct(UserModel $userModel)
    {
        $this->userModel = $userModel;
    }

    public function authenticate(array $credentials): IIdentity
    {
        list($email, $password) = $credentials;

        // Získání uživatele z databáze
        $userRow = $this->userModel->getUserByEmail($email);

        if (!$userRow) {
            throw new Nette\Security\AuthenticationException('Uživatel nebyl nalezen.', self::IDENTITY_NOT_FOUND);
        }

        // Ověření hesla
        if (!password_verify($password, $userRow->password)) {
            throw new Nette\Security\AuthenticationException('Špatné heslo.', self::INVALID_CREDENTIAL);
        }

        // Vytvoření UserEntity
        $userEntity = new UserEntity(
            $userRow->id,
            $userRow->name,
            $userRow->email,
            $userRow->created_at,
            $userRow->status
        );

        // Vytvoření Identity (IIdentity)
        return new class($userEntity) implements IIdentity {
            private $userEntity;

            public function __construct(UserEntity $userEntity)
            {
                $this->userEntity = $userEntity;
            }

            public function getId(): int
            {
                return $this->userEntity->getId();
            }

            public function getRoles(): array
            {
                // Předpokládejme, že uživatel má roli 'user'
                return ['user'];  // Tuto roli si můžete upravit podle databáze
            }

            public function getUserEntity(): UserEntity
            {
                return $this->userEntity;
            }
        };
    }
}