<?php

declare(strict_types=1);

namespace App\Presentation\Sign;

use Nette;
use Nette\Security\User;

final class SignPresenter extends Nette\Application\UI\Presenter
{
    private $authenticator;
    private $user;

    public function __construct(User $user)
    {
      
        $this->user = $user;
    }

    public function renderIn(){
        if($this->user->isLoggedIn()){
            $this->redirect('Home:default');
        }
        else echo('nepřihlášen');
    
    }


        
    

    public function actionOut()
    {
        $this->user->logout();
    }



}
