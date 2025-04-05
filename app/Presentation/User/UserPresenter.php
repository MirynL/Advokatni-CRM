<?php

declare(strict_types=1);
namespace App\Presentation\User;

use Nette;
use App\Models\UserModel; 
use App\Entities\UserEntity;
use App\Models\ClientModel; 
use App\Models\RoleModel;
use Nette\Application\UI\Form;
use App\Presentation\Error\Error4xx\Error4xxPresenter;
class UserPresenter extends Nette\Application\UI\Presenter
{
    private $userModel;
    private $clientModel;
    private $roleModel;

    public function __construct(UserModel $userModel, ClientModel $clientModel, RoleModel $roleModel)
    {
        $this->userModel = $userModel;
        $this->clientModel = $clientModel;
        $this->roleModel = $roleModel;
    }

    // Akce pro zobrazení seznamu uživatelů
    public function renderDefault()
    {
        $users = $this->userModel->getAllUsers();  // Metoda, která vrací seznam uživatelů
        
        $this->template->useridentities = $users;  // Poslat data do šablony

    }

    // Akce pro zobrazení detailu uživatele
    public function renderDetail(int $id)
    {
        $user = $this->userModel->getUserById($id);  // Získat uživatele podle ID
        if (!$user) {
            $this->forward('error:error4xx:404');
        }
        $this->template->userentity = $user;  // Poslat data do šablony
        $owned_clients = $this->clientModel->getAllClientsOwnedById($id);  // Metoda, která vrací seznam klientů vlastněných uživatelem
        $this->template->ownedclients = $owned_clients;
    }

 // Akce pro zobrazení formuláře
 public function renderNew()
 {
     // Formulář pro přidání uživatele
     $this->template->form = $this->createComponentCreateUserForm();
 }

 // Tvorba formuláře pro přidání uživatele
 public function createComponentCreateUserForm(): Form
 {
     $form = new Form;

     // Jméno uživatele
     $form->addText('name', 'Jméno:')
         ->setRequired('Zadejte jméno uživatele.');

     // E-mail uživatele
     $form->addEmail('email', 'E-mail:')
         ->setRequired('Zadejte e-mail uživatele.');

     // Status uživatele
     $form->addSelect('status', 'Status:', [
         'Aktivní' => 'Aktivní',
         'Neaktivní' => 'Neaktivní',
     ])
         ->setRequired('Vyberte status uživatele.');

     // Role uživatele (MultiSelect)
     $roles = $this->roleModel->getAllRoles(); // Metoda vrátí asociativní pole ID => název role
     
     $rolenames = [];
     foreach ($roles as $role) {
         $rolenames[$role->getId()] = $role->getName();
     }
  
     $form->addMultiSelect('roles', 'Role:', $rolenames)
         ->setRequired('Vyberte alespoň jednu roli.');

     // Tlačítko pro odeslání formuláře
     $form->addSubmit('submit', 'Vytvořit uživatele');

     // Zpracování formuláře
     $form->onSuccess[] = [$this, 'createUserFormSucceeded'];

     return $form;
 }

 // Funkce, která se zavolá po úspěšném odeslání formuláře
 public function createUserFormSucceeded(Form $form, \stdClass $values): void
 {
     // Vytvoření UserEntity s hodnotami z formuláře
     $newuser = new UserEntity(
         null,                // ID bude auto-increment, takže ho necháme na null
         $values->name,       // Jméno
         $values->email,      // E-mail
         new \DateTime(),   // Vytvořeno (aktuální čas)    
         $values->status     // Status
          
     );
 
     // Přiřazení rolí k entitě (pokud jsou nějaké vybrány)
     $roles = [];
     foreach ($values->roles as $roleId) {
         $roleEntity = $this->roleModel->getRoleById($roleId);  // Předpokládáme metodu getRoleById v RoleModel
         $roles[] = $roleEntity;
     }
     $newuser->setRoles($roles); // Přiřazení rolí k uživatelskému objektu
 
     // Uložení uživatele do databáze přes UserModel
     $this->userModel->addUser($newuser);
 
     // Zobrazení úspěšné zprávy
     $this->flashMessage('Uživatel byl úspěšně vytvořen.');
     
     // Přesměrování na seznam uživatelů
     $this->redirect('User:default');
 }
}


