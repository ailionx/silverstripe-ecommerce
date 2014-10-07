<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class TOSEProductPage extends TOSEPage {
    

    public function requireDefaultRecords() {
        parent::requireDefaultRecords();
        
        if(!$page = DataObject::get_one('TOSEProductPage')) {
            $config = $this->config()->defaultConfig;
            $page = new TOSEProductPage();
            $page->Title = $config['pageTitle'];
            $page->URLSegment = $config['pageURLSegment'];
            $page->Status = 'Published';
            $page->ShowInMenus = 0;
            $page->ShowInSearch = 0;
            $page->ParentID = 0;
            $page->write();
            $page->publish('Stage', 'Live');
            $page->flushCache();
            DB::alteration_message('TOSEProductPage created', 'created'); 
        }
    }
    
    
    
}

class TOSEProductPage_Controller extends TOSEPage_Controller {
    
    private static $url_handlers = array(
        '$ID' => 'index'
    );
    
    private static $allowed_actions = array();
    
    public function index(SS_HTTPRequest $request) {
        $id = $request->param("ID");
        if(!$id || !is_numeric($id) || !($product = DataObject::get_by_id("TOSEProduct", $id))){
            die("TOS Product NOT FOUND");
        }
        
        return $this->customise(array("Product" => $product));
    }
    
}
