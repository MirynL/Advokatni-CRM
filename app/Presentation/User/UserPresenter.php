<?php

declare(strict_types=1);
namespace App\Presentation\User;

use Nette;
use App\Models\UserModel; // Pokud používáte model pro získání uživatelů
use App\Presentation\Error\Error4xx\Error4xxPresenter;
class UserPresenter extends Nette\Application\UI\Presenter
{
    private $userModel;

    public function __construct(UserModel $userModel)
    {
        $this->userModel = $userModel;
    }

    // Akce pro zobrazení seznamu uživatelů
    public function renderDefault()
    {
        $users = $this->userModel->getAllUsers();  // Metoda, která vrací seznam uživatelů
        $this->template->useridentities = $users;  // Poslat data do šablony
    }

    // Akce pro zobrazení detailu uživatele
    public function renderDetail($id)
    {
        $user = $this->userModel->getUserById($id);  // Získat uživatele podle ID
        if (!$user) {
            $this->forward('error:error4xx:404');
        }
        $this->template->userentity = $user;  // Poslat data do šablony
    }
}