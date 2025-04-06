<?php

declare(strict_types=1);
namespace App\Presentation\Case;

use App\Models\CaseModel;
use Nette;
use App\Presentation\Error\Error4xx\Error4xxPresenter;
class CasePresenter extends Nette\Application\UI\Presenter
{
    private $caseModel;

    public function __construct(CaseModel $caseModel)
    {
        $this->caseModel = $caseModel;
    }

    // Akce pro zobrazení seznamu případů
    public function renderDefault()
    {
        $cases = $this->caseModel->getAllCases();  // Metoda, která vrací seznam uživatelů
        $this->template->cases = $cases;  // Poslat data do šablony
    }

    // Akce pro zobrazení detailu klientů
    public function renderDetail(int $id)
    {
        $case = $this->casetModel->getCaseById($id);  // Získat uživatele podle ID
        if (!$case) {
            $this->forward('error:error4xx:404');
        }
        $this->template->case = $case;  // Poslat data do šablony
    }
}