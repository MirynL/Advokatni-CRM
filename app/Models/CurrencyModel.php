<?php

namespace App\Models;

use App\Entities\CurrencyEntity;
use Nette\Database\Explorer;

class CurrencyModel
{
    private Explorer $db;

    public function __construct(Explorer $db)
    {
        $this->db = $db;
    }

    // Načtení všech rolí
    public function getAllCurrencies(): array
    {
        $currencies = [];
        foreach ($this->db->table('currencies') as $row) {
            $currencies[] = new CurrencyEntity($row->code, $row->name);
        }
        return $currencies;
    }
    public function getCurrencyByCode($code): CurrencyEntity
    {
        $row = $this->db->table('currencies')->get($code);
        $currency= new CurrencyEntity($row->code, $row->name);
        
        return $currency;
    }
}
