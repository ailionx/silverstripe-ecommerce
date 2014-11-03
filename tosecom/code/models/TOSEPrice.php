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
     * Function is to get default currency name
     * @return type
     */
    public static function get_default_currency_name() {
        return Config::inst()->get('TOSEPrice', 'defaultCurrency');
    }
    
    /**
     * Function is to get default currency symbol
     * @return type
     */
    public static function get_default_currency_symbol() {
        return Config::inst()->get('TOSEPrice', 'defaultCurrencySymbol');
    }
    
    /**
     * Function is to get all currencies
     * @return type
     */
    public static function get_all_currencies() {
        $currencies = Config::inst()->get('TOSEPrice', 'currencies');
        $defaultCurrencyName = self::get_default_currency_name();
        if(!array_key_exists($defaultCurrencyName, $currencies)) {
            $currencies[$defaultCurrencyName] = self::get_default_currency_symbol();
        }
        
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
        
        Session::set(TOSEPage::SessionCurrencyName, $currencyName);            
    }

    /**
     * Function is to get current currency name
     * @return type
     */
    public static function get_active_currency_name() {
        $multiCurrency = Config::inst()->get('TOSEPrice', 'multiCurrency');
        $defaultCurrencyName = self::get_default_currency_name();
        if ($multiCurrency === "TRUE") {
            $Name = Session::get(TOSEPage::SessionCurrencyName);
            return $Name ? $Name : $defaultCurrencyName;
        } else {
            return $defaultCurrencyName;
        }
    }           
    
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
    
    public function getCMSFields() {
        $fields = parent::getCMSFields();
        $fields->replaceField('Currency', new ReadonlyField('Currency', 'Currency'));
        
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
     * Function is to give message of price value in summary fields
     * @return type
     */
//    public function priceMessage4CMS() {
//        if($this->Price == 0) {
//            return _t("TOSE_ADMIN.MESSAGE.ADD_PRICE_VALUE");
//        } 
//        
//        return $this->Nice();
//    }
    
    /**
     * For template
     * @return type
     */
    public function forTemplate() {
        return $this->Price;
    }
    
    
}