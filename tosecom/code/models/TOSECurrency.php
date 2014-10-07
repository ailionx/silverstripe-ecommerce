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

    
    
    public static function get_all_currencies() {
        $config = Config::inst()->get('TOSECurrency', 'defaultConfig');
        $currencies = array_keys($config['currencies']);
        return $currencies;
    }
    
    public function getCMSFields() {
        $fields = parent::getCMSFields();
//        $currencies = self::getAllCurrencies();
        
        return $fields;
    }
    
}