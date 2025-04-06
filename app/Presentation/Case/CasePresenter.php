<?php

declare(strict_types=1);
namespace App\Presentation\Case;


use Nette;
use Nette\Application\UI\Form;
use App\Models\CaseModel;
use App\Models\CaseStatusModel;
use App\Models\CurrencyModel;
use App\Models\UserModel;
use App\Presentation\Error\Error4xx\Error4xxPresenter;
use App\Entity\CaseEntity;
use DateTime;

class CasePresenter extends Nette\Application\UI\Presenter
{
    private $caseModel;
    private $currencyModel;
    private $userModel;
    private $caseStatusModel;

    public function __construct(CaseModel $caseModel, CurrencyModel $currencyModel, UserModel $userModel, CaseStatusModel $caseStatusModel)
    {
        $this->caseModel = $caseModel;
        $this->currencyModel = $currencyModel;
        $this->userModel = $userModel;
        $this->caseStatusModel = $caseStatusModel;
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



    public function addNewCaseFormSucceeded(Form $form, $values): void
    {
       
        $case = new CaseEntity();
        $case->setCaseNumber($values->case_number);
        $case->setTitle($values->title);
        $case->setDescription($values->description);
        $case->setCurrency($this->currencyModel->getCurrencyByCode($values->currency_code));
        $case->setOwner($this->userModel->getUserById($values->owner));
        $case->setCreatedBy($this->userModel->getUserById($this->user->identity->getId()));
        $case->setCreatedAt(new DateTime());
        $case->setModifiedAt(new DateTime());
        $case->setStatus($this->caseStatusModel->getCaseStatusByName('Otevřený'));
        $this->caseModel->insert($case);
        $this->flashMessage('Případ byl úspěšně přidán');
        $this->redirect('this');

    }
    protected function createComponentAddNewCaseForm(): Form
    {
        $form = new Form;
        
        // Case Number
        $form->addText('case_number', 'Spisová značka:')
            ->setRequired('Spisová značka je povinná.')
            ->setMaxLength(50);

        // Title
        $form->addText('title', 'Název:')
            ->setRequired('Název je povinný.')
            ->setMaxLength(255);

        // Description
        $form->addTextArea('description', 'Popis:')
            ->setHtmlAttribute('rows', 5);

        // Currency
        $currencies = $this->currencyModel->getAllCurrencies();  // předpokládáme, že máme funkci pro všechny měny
        $currency_list = [];

        foreach ($currencies as $currency) {
            $currency_list[$currency->getCode()] = $currency->getName();
        }
        
        $form->addSelect('currency_code', 'Měna:', $currency_list)
            ->setDefaultValue('CZK');

        // Owner
        $users = $this->userModel->getAllUsers(); // předpokládáme, že máme funkci pro všechny uživatele
        $users_list = [];

        foreach ($users as $user) {
            $users_list[$user->getId()] = $user->getName();
        }
        $form->addSelect('owner', 'Přiřazeno k:', $users_list)
            ->setRequired('Vyberte uživatele')
            ->setDefaultValue($this->user->identity->getId());

        // Submit Button
        $form->addSubmit('submit', 'Uložit');
        $form->onSuccess[] = [$this, 'addNewCaseformSucceeded'];

        return $form;
    }




}