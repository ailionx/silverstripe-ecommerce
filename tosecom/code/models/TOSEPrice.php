<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class TOSEPrice extends DataObject {
    
        
//    private static $db = array(
//        'Price' => 'Currency',
//        'Currency' => self::currency_options()
//        
//    );
    
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

     
    public static function currency_options() {
        $config = Config::inst()->get('TOSEPrice', 'currencies');
        $currencies = array_keys($config);
        if(!in_array('NZD', $currencies)){
            $currencies[] = 'NZD';
        }
        $currencyOptions = implode(',', $currencies);
        $enumString = "Enum(".$currencyOptions.",'NZD')";
        
        return $enumString;
    }

//    public static function get_all_currencies() {
//        $config = Config::inst()->get('TOSEPrice', 'currencies');
//        $currencies = array_keys($config);
//        return $currencies;
//    }
    
    /**
     * Function is to set CMS fields
     * @return type
     */
    public function getCMSFields() {
        $fields = parent::getCMSFields();
//        $currencies = self::getAllCurrencies();
        
        return $fields;
    }
    
    /**
     * Function is to get current currency name
     * @return type
     */
    public static function get_current_currency_name() {
        $multiCurrency = Config::inst()->get('TOSEPrice', 'multiCurrency');
        $defaultCurrencyName = Config::inst()->get('TOSEPrice', 'defaultCurrency');
        if ($multiCurrency === "TRUE") {
            $currentCurrencyName = Session::get(TOSEPage::SessionCurrencyName);
            return $currentCurrencyName ? $currentCurrencyName : $defaultCurrencyName;
        } else {
            return $defaultCurrencyName;
        }
    }           
    
    /**
     * Function is to get the symbol of current currency
     * @return type
     */
    public static function get_current_currency_symbol() {
        $multiCurrency = Config::inst()->get('TOSEPrice', 'multiCurrency');
        $defaultCurrencySymbol = Config::inst()->get('TOSEPrice', 'defaultCurrencySymbol');
        $currencies = Config::inst()->get('TOSEPrice', 'currencies');
        if ($multiCurrency === "TRUE") {
            $currentCurrencyName = Session::get(TOSEPage::SessionCurrencyName);
            return $currencies[$currentCurrencyName];
        } else {
            return $defaultCurrencySymbol;
        }
    }
    
    /**
     * Function is to format price to be more readable
     * @return type
     */
    public function priceFormatted() {
        return number_format($this->Price, 2);
    }
    
}