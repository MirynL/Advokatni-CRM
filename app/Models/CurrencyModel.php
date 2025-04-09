<?php

namespace App\Models;

use App\Entities\CurrencyEntity;

class CurrencyModel extends BaseModel
{


    public function __construct()
    {
       
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
    protected function mapEntityToArray(object $currency): array
    {
        assert($currency instanceof CurrencyEntity);
        return [
            'code'   => $currency->getCode(),
            'name' => $currency->getName()
        ];
    }

}
