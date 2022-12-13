<?php

namespace App\Http\Controllers;

use App\Models\Currency;

class CurrencyController
{
    public function currency()
    {
        $dailyXML = file_get_contents('http://www.cbr.ru/scripts/XML_daily.asp?date_req=02/03/2002');
        $objectXML = simplexml_load_string($dailyXML);

        foreach ($objectXML as $obj) {
            $currency = Currency::withTrashed()->where('name', $obj->Name)->first();
            if (!$currency) $currency = new Currency();

            $currency->name = $obj->Name;
            $currency->valuteID = $obj[0]['ID'];
            $currency->numCode = intval($obj->NumCode);
            $currency->charCode = $obj->CharCode;
            $currency->value = floatval($obj->Value);
            $currency->date = $objectXML[0]['Date'];
            $currency->save();
        }
    }
}
