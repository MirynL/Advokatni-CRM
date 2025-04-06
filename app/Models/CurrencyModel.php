<?php

namespace App\Models;

use App\Entities\CurrencyEntity;
use Nette\Database\Explorer;

class CurrencyModel
{
    private Explorer $database;

    public function __construct(Explorer $database)
    {
        $this->database = $database;
    }

    // Načtení všech rolí
    public function getAllCurrencies(): array
    {
        $currencies = [];
        foreach ($this->database->table('currencies') as $row) {
            $currencies[] = new CurrencyEntity($row->code, $row->name);
        }
        return $currencies;
    }
    public function getCurrencyByCode($code): CurrencyEntity
    {
        $row = $this->database->table('currencies')->get($code);
        $currency= new CurrencyEntity($row->code, $row->name);
        
        return $currency;
    }
}
