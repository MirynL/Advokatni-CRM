<?php

declare(strict_types=1);

namespace App\Presentation\Home;

use Nette;
use App\Models\EntityModel;

final class HomePresenter extends Nette\Application\UI\Presenter
{
    
    private $entityModel;

    public function __construct(EntityModel $entityModel)
    {
        $this->entityModel = $entityModel;
    }
    public function renderDefault(){
        $entities = $this->entityModel->getAllEntities();  // Metoda, která vrací výčet Entit
        $this->template->entities = $entities;  // Poslat data do šablony
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
