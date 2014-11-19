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
        'getActivePriceValue' => 'Price',
        'ExtraInfo' => 'ExtraInfo'
    );
    
    

    /**
     * Setup CMS fields
     * @return type
     */
    public function getCMSFields() {
        $fields = parent::getCMSFields();
        $fields->removeByName('Prices');
        if(!TOSEProduct::has_inventory()){
            $fields->removeByName('Inventory');
        }
        $weightField = $fields->dataFieldByName('Weight');
//        var_dump($weightField); die();
        $weightField->setTitle('Weight (Unit: kg.)');
        
        if ($this->ID) {
            $gridFieldConfig = GridFieldConfig_RelationEditor::create();
            $gridFieldConfig
                    ->removeComponentsByType('GridFieldAddNewButton')
                    ->removeComponentsByType('GridFieldAddExistingAutocompleter')
                    ->removeComponentsByType('GridFieldDeleteAction');
            //var_dump($gridFieldConfig); die;
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
    public function getActivePrice() {
        $Name = TOSEPrice::get_active_currency_name();
        $activePrice = $this->Prices()->find('Currency', $Name);

        return $activePrice ? $activePrice : NULL;
    }
    
    /**
     * Function is to get current currency
     * @return type
     */
    public function getPrimaryPrice() {
        $Name = TOSEPrice::get_primary_currency_name();
        $primaryPrice = $this->Prices()->find('Currency', $Name);

        return $primaryPrice ? $primaryPrice : NULL;
    }
    
    
    /**
     * Function is to get the value of current price
     * @return type
     */
    public function getActivePriceValue() {
        return $this->getActivePrice()->Nice();
    }
    
    /**
     * OVERRIDE
     * @return type
     */
    public function getTitle() {
        return "SKU".$this->SKU;
    }

    /**
     * Write in default price after this spec written
     */
    public function onAfterWrite() {
        parent::onAfterWrite();
        $currencyNames = TOSEPrice::get_currency_names();
        $childPrices = $this->Prices()->column('Currency');
        foreach ($currencyNames as $name) {
            if(!in_array($name, $childPrices)) {
                $price = new TOSEPrice();
                $price->Currency = $name;
                $price->Price = 0.00;
                $price->SpecID = $this->ID;
                $price->write();
            }
        }
    }
    
}