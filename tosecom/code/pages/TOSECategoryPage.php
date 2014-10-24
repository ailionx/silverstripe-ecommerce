<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class TOSECategoryPage extends TOSEPage {
    

}

class TOSECategoryPage_Controller extends TOSEPage_Controller {
    
    private static $url_handlers = array(
        '$ID' => "index"
    );

    public function index(SS_HTTPRequest $request) {
        $params = $request->allParams();
        $id = $params['ID'];
        if(!is_numeric($id)) {
            die('Invalid category');
        }
        if(!$category = DataObject::get_one('TOSECategory', "ID = '$id'")) {
            die('No such category');
        }

        return $this->customise(
            $data = array(
                "Category" => $category
            ));
        
    }
    
    public function getRootCategories() {

        $rootCategories = DataObject::get('TOSECategory', "ParentCategoryID='0'");

        return $rootCategories;
    }
    
    
}
