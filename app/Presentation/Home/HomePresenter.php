<?php

declare(strict_types=1);

namespace App\Presentation\Home;

use Nette;


final class HomePresenter extends Nette\Application\UI\Presenter
{

    public function renderDefault(){

    }
    public function renderLogin()
    {
    
    }
    public function actionLogout()
    {
    $this->user->logout();
    }
    public function renderPasswordReset()
    {
    
    }
}
