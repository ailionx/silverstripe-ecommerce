<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class TOSEPage extends Page {
    
    private static $allowed_children = array('TOSEProductPage', 'TOSECategoryPage', 'TOSECartPage', 'TOSECheckoutPage');
    
    public function requireDefaultRecords() {
        parent::requireDefaultRecords();
        
        //To create default data
        if(!DataGenerator::hasInitiated()){
            DataGenerator::startGen();
        }
        
    }
    
}

class TOSEPage_Controller extends Page_Controller {
    
}