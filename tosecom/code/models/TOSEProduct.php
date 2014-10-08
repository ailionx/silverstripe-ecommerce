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
        'Category' => 'TOSECategory'
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
     * Function is to get the default spec of product, for cms
     * @return type
     */
    public function getDefaultSpec() {
        $Spec = DataObject::get('TOSESpec', "ProductID = '".$this->ID."'")->first();
        return $Spec;
    }
    
    /**
     * Function is to check if the Product is enabled, if not, this product should not be shown in front end
     * @return type
     */
    public function isEnabled() {
        return $this->Enabled;
    }
    
    public function isNew() {
        
    }

    public function getCartLink() {
        $cartPage = DataObject::get_one('TOSECartPage');
        $link = $cartPage->Link();
        return $link;
    }

//    public function setDefualtSpec($id) {
//        $this->default_spec = $id;
//    }
//    
    public function getDefaultImage() {
        if($this->Images()->Count() > 0){
            return $this->Images()->First();
        }
    }
//    
//    public function setDefualtImage($id) {
//        $this->default_image = $id;
//    }
    

    
    public function getCMSFields() {

        $fields = parent::getCMSFields();
        $fields->removeByName('Specs');
        $fields->removeByName('Images');
        $fields->removeByName('Enabled');
        $enabledField = new CheckboxField('Enabled', 'Enabled this product', TRUE);
        $fields->addFieldToTab('Root.Main', $enabledField, 'NewFrom');
        $newFromField = $fields->dataFieldByName('NewFrom');
        $newToField = $fields->dataFieldByName('NewTo');
        $newFromField->setConfig('showcalendar', true);
        $newToField->setConfig('showcalendar', true);
        
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
}