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

    public function authenticate(string $email, string $entered_password) : IIdentity
    {
        

        // Získání uživatele z databáze
        $userRow = $this->userModel->getUserByEmail($email);
        $user_password = $this->userModel->getPasswordHash($email);

        if (!$userRow) {
            throw new Nette\Security\AuthenticationException('Uživatel nebyl nalezen.');
        }

        // Ověření hesla
        if (!password_verify($entered_password, $user_password)) {
            throw new Nette\Security\AuthenticationException('Špatné heslo.');
        }



        // Vytvoření Identity (IIdentity)
        return new class($userRow) implements IIdentity {
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
                
                return $this->userEntity->getRole();
            }

            public function getUserEntity(): UserEntity
            {
                return $this->userEntity;
            }
        };
    }
}