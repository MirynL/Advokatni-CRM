<?php

declare(strict_types=1);
namespace App\Presentation\User;

use Nette;
use App\Models\UserModel; 
use App\Models\ClientModel; 
use Nette\Forms\Form;
use App\Presentation\Error\Error4xx\Error4xxPresenter;
class UserPresenter extends Nette\Application\UI\Presenter
{
    private $userModel;
    private $clientModel;

    public function __construct(UserModel $userModel, ClientModel $clientModel)
    {
        $this->userModel = $userModel;
        $this->clientModel = $clientModel;
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
     $this->template->form = $this->createUserForm();
 }

 // Tvorba formuláře pro přidání uživatele
 private function createUserForm(): Form
 {
     $form = new Form;

     // Přidání polí pro uživatele
     $form->addText('name', 'Jméno:')
         ->setRequired('Jméno je povinné.')
         ->setMaxLength(255);

     $form->addText('email', 'E-mail:')
         ->setRequired('E-mail je povinný.')
         ->addRule(Form::EMAIL, 'Zadejte platný e-mail.');

         $form->addSelect('role', 'Role:', [
            '1' => 'Administrátor',
            '2' => 'Advokát',
            '3' => 'Právní koncipient',
            '4' => 'Asistent',
            '5' => 'Účetní',
            '6' => 'Externista',
            '7' => 'Recepční',
        ])
     ->setRequired('Role je povinná.');

     $form->addSelect('status', 'Status:', [
         'active' => 'Aktivní',
         'inactive' => 'Neaktivní',
     ])
     ->setRequired('Status je povinný.');

     // Odesílací tlačítko
     $form->addSubmit('send', 'Přidat uživatele');

     // Akce pro odeslání formuláře
     $form->onSuccess[] = [$this, 'userFormSucceeded'];

     return $form;
 }

 // Funkce, která se zavolá po úspěšném odeslání formuláře
 public function userFormSucceeded(Form $form, \stdClass $values): void
 {
     // Přidání uživatele do databáze
     $this->userModel->addUser($values->name, $values->email, $values->role, $values->status);

     // Přesměrování po přidání uživatele
     $this->flashMessage('Uživatel byl úspěšně přidán.', 'success');
     $this->redirect('User:default');
 }
}


