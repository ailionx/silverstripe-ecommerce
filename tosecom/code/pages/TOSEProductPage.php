<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class TOSEProductPage extends TOSEPage {
    

}

class TOSEProductPage_Controller extends TOSEPage_Controller {
    
    private static $url_handlers = array(
        '$ID' => 'index'
    );
    
    private static $allowed_actions = array();
    
    public function index(SS_HTTPRequest $request) {
        $id = $request->param("ID");
        if(!$id || !is_numeric($id) || !($product = DataObject::get_by_id("TOSEProduct", $id)) || $product->isEnabled()==FALSE){
//            die("TOS Product NOT FOUND");
            return $this->redirect($this->Link()."1");
        }

        return $this->customise(array("Product" => $product));
    }
    
}
