<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class TOSECurrency extends DataObject {
    
    private static $db = array(
        'Currency' => "Enum('NZD, USD, GBP', 'NZD')",
        'Price' => 'Currency'
    );
    
    private static $has_one = array(
        'Spec' => 'TOSESpec'
    );
    
    
    private static $summary_fields = array(
        'Currency' => 'Currency',
        'Price' => 'Price'
    );

    
    
//    public static function get_all_currencies() {
//        $config = Config::inst()->get('TOSECurrency', 'currencies');
//        $currencies = array_keys($config);
//        return $currencies;
//    }
    
    public function getCMSFields() {
        $fields = parent::getCMSFields();
//        $currencies = self::getAllCurrencies();
        
        return $fields;
    }
    
    public static function getCurrencySymbol($currencyName) {
        $currencies = Config::inst()->get('TOSECurrency', 'currencies');
        var_dump($currencyName); die();
        return $currencies[$currencyName];
    }
    
    public static function get_current_currency_name() {
        $multiCurrency = Config::inst()->get('TOSEPage', 'multiCurrency');
        $defaultCurrencyName = Config::inst()->get('TOSEPage', 'defaultCurrency');
        if ($multiCurrency) {
            $currentCurrencyName = Session::get(self::SessionCurrencyName);
            return $currentCurrencyName ? $currentCurrencyName : $defaultCurrencyName;
        } else {
            return $defaultCurrencyName;
        }
    }           
    
    public static function get_current_currency_symbol() {
        $multiCurrency = Config::inst()->get('TOSEPage', 'multiCurrency');
        $defaultCurrencySymbol = Config::inst()->get('TOSEPage', 'defaultSymbol');
        $currencies = Config::inst()->get('TOSEPage', 'currencies');
        if ($multiCurrency) {
            $currentCurrencyName = Session::get(self::SessionCurrencyName);
            return $currencies[$currentCurrencyName];
        } else {
            return $defaultCurrencySymbol;
        }
    }
    
}