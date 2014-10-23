<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class TOSECategoryPage extends TOSEPage {
    

}

class TOSECategoryPage_Controller extends TOSEPage_Controller {
    
//    public function index() {
//        
//    }
    
    public function getRootCategories() {

        $rootCategories = DataObject::get('TOSECategory', "ParentCategoryID='0'");

        return $rootCategories;
    }
    
    public function test() {
        TOSECategory::get_ancestor_categories('round fru');
    }
    
}
