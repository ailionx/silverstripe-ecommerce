<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class TOSEPrice extends DataObject {
    
        
//    private static $db = array(
//        'Price' => 'Currency',
//        'Currency' => self::currency_options_for_field()
//        
//    );
    /**
     * Funtion is to get currency options string for db field
     * @return string
     */
//    public static function currency_options_for_field() {
//        $currencyNames = self::get_currency_names();
//        $currencyOptions = implode(',', $currencyNames);
//        $defaultCurrencyName = self::get_default_currency_name();
//        $enumString = "Enum(".$currencyOptions.", $defaultCurrencyName)";
//        
//        return $enumString;
//    }
    
    private static $db = array(
        'Currency' => "Varchar(10)",
        'Price' => 'Currency'
    );
    
    private static $has_one = array(
        'Spec' => 'TOSESpec'
    );
    
    private static $summary_fields = array(
        'Currency' => 'Currency',
        'Nice' => 'Price'
    );
    
    /**
     * Save the current currency name
     */
    const SessionCurrencyName = 'TOSEPriceName';
    
    /**
     * Function is to get default currency name
     * @return type
     */
    public static function get_primary_currency_name() {
        return Config::inst()->get('TOSEPrice', 'primaryCurrency');
    }
    
    /**
     * Function is to get default currency symbol
     * @return type
     */
    public static function get_primary_currency_symbol() {
        return Config::inst()->get('TOSEPrice', 'primaryCurrencySymbol');
    }
    
    /**
     * Function is to get all currencies
     * @return type
     */
    public static function get_all_currencies() {
        $primaryCurrencyName = self::get_primary_currency_name();
        $primaryOurrency[$primaryCurrencyName] = self::get_primary_currency_symbol();
        $optionalCurrencies = Config::inst()->get('TOSEPrice', 'optionalCurrencies');

        $currencies = array_merge($primaryOurrency, $optionalCurrencies);
        
        return $currencies;
    }

    
    /**
     * Function is to get all currency names
     * @return type
     */
    public static function get_currency_names() {
        $currencies = self::get_all_currencies();
        $currencyNames = array_keys($currencies);

        return $currencyNames;
    }
    

    
    /**
     * Function is to set current currency name
     * @param type $currencyName
     */
    public function set_active_currency_name($currencyName) {
        if (!array_key_exists($currencyName, self::get_all_currencies())) {
            die('No option for the currency name');
        }
        
        Session::set(self::SessionCurrencyName, $currencyName);            
    }

    /**
     * Function is to get current currency name
     * @return type
     */
    public static function get_active_currency_name() {
        $multiCurrency = Config::inst()->get('TOSEPrice', 'multiCurrency');
        $primaryCurrencyName = self::get_primary_currency_name();
        if ($multiCurrency === "TRUE") {
            $Name = Session::get(self::SessionCurrencyName);
            return $Name ? $Name : $primaryCurrencyName;
        } else {
            return $primaryCurrencyName;
        }
    }           
    
    /**
     * Function is get currency symbol for given currency name
     * @param type $currencyName
     * @return type
     */
    public static function get_currency_symbol($currencyName) {
        $currencies = self::get_all_currencies();
        return $currencies[$currencyName];
    }

    /**
     * Function is to get the symbol of current currency
     * @return type
     */
    public static function get_active_currency_symbol() {
        $Name = self::get_active_currency_name();
        return self::get_currency_symbol($Name);
    }
    
    /**
     * OVERRIDE
     * @return type
     */
    public function getCMSFields() {
        $fields = parent::getCMSFields();
        $fields->replaceField('Currency', new ReadonlyField('Currency', 'Currency'));
        $fields->removeByName('SpecID');
        
        return $fields;
    }

    /**
     * Returns the number as a currency, eg “$1,000.00”.
     */
    public function Nice() {
        $val = $this->Currency . " " . self::get_currency_symbol($this->Currency) . number_format(abs($this->Price), 2);
        if($this->Price < 0) return "($val)";
        else return $val;
    }
    
    
    /**
     * For template
     * @return type
     */
    public function forTemplate() {
        return $this->Price;
    }
    
    
}