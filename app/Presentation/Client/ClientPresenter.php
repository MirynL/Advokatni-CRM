<?php

declare(strict_types=1);
namespace App\Presentation\Client;

use App\Models\ClientModel;
use Nette;
use App\Presentation\Error\Error4xx\Error4xxPresenter;
class ClientPresenter extends Nette\Application\UI\Presenter
{
    private $clientModel;

    public function __construct(ClientModel $clientModel)
    {
        $this->clientModel = $clientModel;
    }

    // Akce pro zobrazení seznamu klientů
    public function renderDefault()
    {
        $clients = $this->clientModel->getAllClients();  // Metoda, která vrací seznam uživatelů
        $this->template->cliententities = $clients;  // Poslat data do šablony
    }

    // Akce pro zobrazení detailu klientů
    public function renderDetail(int $id)
    {
        $client = $this->clientModel->getClientById($id);  // Získat uživatele podle ID
        if (!$client) {
            $this->forward('error:error4xx:404');
        }
        $this->template->cliententity = $client;  // Poslat data do šablony
    }
}