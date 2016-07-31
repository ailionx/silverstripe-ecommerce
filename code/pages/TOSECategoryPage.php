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

    /**
     * Function is to filter url data and direct to category page
     * @param SS_HTTPRequest $request
     * @return type
     */
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
    
    /**
     * Function is to get all root categories
     * @return type
     */
    public function getRootCategories() {

        $rootCategories = DataObject::get('TOSECategory', "ParentID='0'");

        return $rootCategories;
    }
    
    /**
     * Function is to set default products in category page when category name not given
     * @return type
     */
    public function getDefaultProducts() {
        $products = DataObject::get('TOSEProduct');
        
        return TOSEProduct::get_enabled_products($products);
    }
    
}
