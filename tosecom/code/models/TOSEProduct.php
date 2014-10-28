<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class TOSEProduct extends DataObject {
    
    private static $db = array(
        'Name' => 'Varchar(100)',
        'Description' => 'Text',
        'NewFrom' => 'Date',
        'NewTo' => 'Date',
        'Enabled' => 'Boolean'
    );
    
    private static $has_one = array(
        'Category' => 'TOSECategory',
    );
    
    private static $has_many = array(
        'Images' => 'TOSEImage',
        'Specs' => 'TOSESpec'
    );
    
    private static $summary_fields = array(
        'Name' => 'Name',
        'getCategoryName' => 'Category',
        'Description' => 'Description'
    );

    /**
     * Function is to get Category name of product, for cms
     * @return type
     */
    public function getCategoryName() {
        return $this->Category()->Name;
    }

    /**
     * Function is to get the default spec of product
     * @return type
     */
    public function getDefaultSpec() {
        $spec = $this->Specs()->first();

        return $spec ? $spec : FALSE;
    }
    
    public function getDefaultPrice() {
        if ($this->getDefaultSpec()) {
            $price = $this->getDefaultSpec()->getCurrentPrice();
            return $price ? $price : FALSE;
        }
        
        return FALSE;
    }


    /**
     * Function is to get default image of product
     * @return type
     */
    public function getDefaultImage() {
        $image = $this->Images()->first();
        return $image;
    }
    
    public function getLink() {
        $pageURL = DataObject::get_one('TOSEProductPage')->Link();
        $link = $pageURL . $this->ID;
        
        return $link;
    }

        /**
     * Function is to check if the Product is enabled, if not, this product should not be shown in front end
     * @return type
     */
    public function isEnabled() {
        return $this->Enabled;
    }
    
    /**
     * Function is to check if the product is new product
     * @return boolean
     */
    public function isNew() {
        $today = time();
        if(($today>strtotime($this->NewFrom)) && ($today<strtotime($this->NewTo))) {
            return TRUE;
        }
        
        return FALSE;
    }
    
    /**
     * Function is to get the link of cart page
     * @return type
     */
    public function getCartLink() {
        $cartPage = DataObject::get_one('TOSECartPage');
        $link = $cartPage->Link();
        return $link;
    }

    /**
     * Function is to filter enabled products from a products list
     * @param type $products
     * @return type
     */
    public static function get_enabled_products($products) {
        
        if(is_object($products)) {
            $className = get_class($products);
            if($className=="ArrayList" || $className=="DataList") {
                return $products->filter('Enabled','1');
            }
        }
        
        if (is_array($products)) {
            return array_filter($products, function($obj){
                if ((get_class($obj))!="TOSEProduct") {
                    die('Only product object can be checked enable');
                }
                return $obj->isEnabled() ? TRUE : FALSE;
            });
        }
        
        die('Not supported type, must be ArrayList, DataList or Array');
        
    }


    /**
     * Function is to customize cms fields
     * @return type
     */
    public function getCMSFields() {

        $fields = parent::getCMSFields();
        $fields->removeByName(array('Specs', 'Images', 'Enabled', 'CategoryChain'));
        $enabledField = new CheckboxField('Enabled', 'Enabled this product', TRUE);
        $fields->addFieldToTab('Root.Main', $enabledField, 'NewFrom');
        $newFromField = $fields->dataFieldByName('NewFrom');
        $newToField = $fields->dataFieldByName('NewTo');
        $newFromField->setConfig('showcalendar', true);
        $newToField->setConfig('showcalendar', true);
        $fields->replaceField('CategoryID', $categoryField = new TreeDropdownField('CategoryID', 'Category', "TOSECategory", 'ID', 'Name', FALSE));
//        var_dump($categoryField->getSourceObject()); die;
//        $categoryField = $fields->fieldByName('CategoryID'); 
//        var_dump($categoryField); 
//        var_dump($fields);
//        die;
        
        if ($this->ID) {
            
            // add specifications gridfield
            $gridFieldConfig = GridFieldConfig_RelationEditor::create();
            $gridField = new GridField("Specs", "Product Specification", $this->Specs(), $gridFieldConfig);
            
            $fields->addFieldToTab('Root.Main', $gridField);
            
            // add images upload
            $imagesField = new UploadField('Images', "Product Images", $this->Images());
            $imagesField->setAllowedExtensions(array('jpg', 'jpeg', 'png'));
            $imagesField->setFolderName("Uploads/TOSEProduct");
            $imagesField->setAllowedMaxFileNumber(10);
            $fields->addFieldToTab("Root.Main", $imagesField);
            
        } else {
            
            $fields->addFieldToTab(
                    "Root.Main", 
                    new LiteralField(
                            "NewProductPriceMsg", 
                            "<div class='message warning'>" . _t("TOSE_ADMIN.MESSAGE.NEW_PRODUCT_PRICE") . "</div>"
                    )
            );
            
            $fields->addFieldToTab(
                    "Root.Main", 
                    new LiteralField(
                            "NewProductImageMsg", 
                            "<div class='message warning'>" . _t("TOSE_ADMIN.MESSAGE.NEW_PRODUCT_IMAGE") . "</div>"
                    )
            );
        }

        return $fields;
    }
    
    
//    protected function onAfterWrite() {
//        parent::onAfterWrite();
//        if (!$this->Specs()) {
//            
//        }        
//        
//    }
    
}