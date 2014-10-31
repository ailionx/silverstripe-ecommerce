<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class TOSESpec extends DataObject {
    
    private static $db = array(
        'Weight' => 'Decimal',
        'SKU' => 'Varchar(50)',
        'Inventory' => 'Int',
        'ExtraInfo' => 'Text'
    );

    private static $has_one = array(
        'Product' => 'TOSEProduct'       
    );
    
    private static $has_many = array(
        'Prices' => 'TOSEPrice'
    );
    
    private static $summary_fields = array(
        'Weight' => "Weight",
        'SKU' => "SKU",
        'Inventory' => 'Inventory',
        'getDefaultPrice' => 'Price (NZD)',
        'ExtraInfo' => 'ExtraInfo'
    );
    
    
//    public function  getDefaultPrice() {
//        if($nzPrice = DataObject::get_one('TOSEPrice', "Currency='NZD' And SpecID='".$this->ID."'")) {
//            return $nzPrice->Price;
//        } else {
//            return '0';
//        }
//    }

    /**
     * Setup CMS fields
     * @return type
     */
    public function getCMSFields() {
        $fields = parent::getCMSFields();
        $fields->removeByName('Prices');
        $weightField = $fields->dataFieldByName('Weight');
//        var_dump($weightField); die();
        $weightField->setTitle('Weight (Unit: kg.)');
        
        if ($this->ID) {
            $gridFieldConfig = GridFieldConfig_RelationEditor::create();
            $gridField = new GridField('Prices', 'Prices', $this->Prices(), $gridFieldConfig);
            $fields->addFieldToTab('Root.Main', $gridField);
        } else {
            
            $fields->addFieldToTab(
                    "Root.Main", 
                    new LiteralField(
                            "NewProductPriceMsg", 
                            "<div class='message warning'>" . _t("TOSE_ADMIN.MESSAGE.NEW_PRODUCT_PRICE") . "</div>"
                    )
            );
        }

        
        return $fields;
    }
    
    /**
     * Function is to get current currency
     * @return type
     */
    public function getCurrentPrice() {
        $currentCurrencyName = TOSEPrice::get_current_currency_name();
        $currentPrice = $this->Prices()->find('Currency', $currentCurrencyName);

        return $currentPrice ? $currentPrice : NULL;
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
    
}