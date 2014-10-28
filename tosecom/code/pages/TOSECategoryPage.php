<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class TOSECategoryPage extends TOSEPage {
    

}

class TOSECategoryPage_Controller extends TOSEPage_Controller {
    
//    private static $allowed_actions = array(
//        '' => 'index'
//    );

    private static $url_handlers = array(
        '$cateoryLink' => "index"
    );

    public function index(SS_HTTPRequest $request) {
        $params = $request->allParams();
        $cateoryLink = $params['cateoryLink'];
        
        if(!$cateoryLink) {

            return $this->customise(
                $data = array(
                    "ShowDefault" => TRUE
                ));
        }
        
        if(!$category = DataObject::get_one('TOSECategory', "Link = '$cateoryLink'")) {
            die('No such category');
        }

        return $this->customise(
            $data = array(
                "Category" => $category
            ));
        
    }
    
    public function getRootCategories() {

        $rootCategories = DataObject::get('TOSECategory', "ParentID='0'");

        return $rootCategories;
    }
    
    public function getDefaultProducts() {
        $products = DataObject::get('TOSEProduct');
        
        return TOSEProduct::get_enabled_products($products);
    }
    
}
