<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class TOSECurrencyExtension extends DataExtension {
    
    
    
    public function extraStatics() {
//        parent::extraStatics($class, $extension);
        $db = array();
        
        $config = $this->owner->config()->defaultConfig;
        $currencies = $config['currencies'];
        $currencyNames = array_keys($currencies);
        $currencyNamesString = implode(',', $currencyNames);
        
        
        return array('db'=>$db);
    }
    
    
}