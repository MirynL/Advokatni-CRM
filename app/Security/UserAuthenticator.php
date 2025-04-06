<?php

namespace App\Model;

use Nette\Security\AuthenticationException;
use Nette\Database\Explorer;
use Nette\Security\IIdentity;
use App\Models\UserModel;
use Nette\Security\IAuthenticator;

class UserAuthenticator implements IAuthenticator
{
    private Explorer $database;
    private UserModel $userModel;

    public function __construct(Explorer $database, UserModel $userModel)
    {
        $this->database = $database;
        $this->userModel = $userModel;
    }

    public function authenticate(array $credentials): IIdentity
    {
        [$email, $password] = $credentials;

        // Získání uživatele z databáze na základě e-mailu
        $userRow = $this->database->table('users')->where('email', $email)->fetch();
        $user = $this->userModel->getUserByEmail($email);
        if (!$userRow) {
            throw new AuthenticationException('Uživatel nenalezen.');
        }

        // Zde bys měl přidat logiku pro kontrolu hesla (např. pomocí password_verify())
        if (!password_verify($password, $userRow->password)) {
            throw new AuthenticationException('Nesprávné heslo.');
        }

        // Vrácení identity
        return new IIdentity($user->getId(),$user->getRoles());
    }
}