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
        'Currencies' => 'TOSECurrency'
    );
    
    private static $summary_fields = array(
        'Weight' => "Weight",
        'SKU' => "SKU",
        'Inventory' => 'Inventory',
        'getDefaultPrice' => 'Price (NZD)',
        'ExtraInfo' => 'ExtraInfo'
    );
    
//    public function  getDefaultPrice() {
//        if($nzPrice = DataObject::get_one('TOSECurrency', "Currency='NZD' And SpecID='".$this->ID."'")) {
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
        $fields->removeByName('Currencies');
        $weightField = $fields->dataFieldByName('Weight');
//        var_dump($weightField); die();
        $weightField->setTitle('Weight (Unit: kg.)');
        
        if ($this->ID) {
            $gridFieldConfig = GridFieldConfig_RelationEditor::create();
            $gridField = new GridField('Currencies', 'Currencies', $this->Currencies(), $gridFieldConfig);
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
    public function getCurrentCurrency() {
        $currentCurrentyName = TOSECurrency::get_current_currency_name();
        $currentCurrency = DataObject::get_one('TOSECurrency', "SpecID='$this->ID' AND Currency='$currentCurrentyName'");
        return $currentCurrency;
    }
    
    public function getCurrentPrice() {
        return $this->getCurrentCurrency()->Price;
    }
    
}