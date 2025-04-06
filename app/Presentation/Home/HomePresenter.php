<?php

declare(strict_types=1);

namespace App\Presentation\Home;

use Nette;
use App\Models\EntityModel;
use Nette\Application\UI\Form;
use App\Security\UserAuthenticator;
use Nette\Security\AuthenticationException;

final class HomePresenter extends Nette\Application\UI\Presenter
{
    
    private $entityModel;
    private $authenticator;

    public function __construct(EntityModel $entityModel, UserAuthenticator $authenticator)
    {
        $this->entityModel = $entityModel;
        $this->authenticator = $authenticator;
    }
    public function renderDefault(){
        $entities = $this->entityModel->getAllEntities();  // Metoda, která vrací výčet Entit
        $this->template->entities = $entities;  // Poslat data do šablony
        $user = $this->user->getIdentity();
        
    }
    public function renderPasswordReset()
    {
    
    }
    public function createComponentLoginForm(): Form
    {
        $form = new Form;

        $form->addText('username', 'E-mail:')
            ->setRequired('Zadejte svůj e-mail.')
            ->addRule($form::Email, 'Zadejte platnou e-mailovou adresu.');

        $form->addPassword('password', 'Heslo:')
            ->setRequired('Zadejte heslo.');

        $form->addSubmit('send', 'Přihlásit');

        $form->onSuccess[] = [$this, 'loginFormSucceeded'];

        return $form;
    }

    public function loginFormSucceeded(Nette\Application\UI\Form $form, $values): void
    {
        try {
            $identity = $this->authenticator->authenticate([$values->username, $values->password]);
            $this->user->login($identity);
            $this->flashMessage('Úspěšně přihlášeno!');
            $this->redirect('this');
        } catch (AuthenticationException $e) {
            $this->flashMessage('Chyba při přihlášení: ' . $e->getMessage(), 'error');
            $this->redirect('this');
        }
    }
}
