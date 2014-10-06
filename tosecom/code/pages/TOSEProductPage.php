<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class TOSEProductPage extends Page {
    
    private static $page_title;

    private static $page_URL_segment;

    public function requireDefaultRecords() {
        parent::requireDefaultRecords();
 
        if(!$page = DataObject::get_one('TOSEProductPage')) {
            $page = new TOSEProductPage();
            $page->Title = Config::inst()->get('TOSEProductPage', 'page_title');
            $page->URLSegment = Config::inst()->get('TOSEProductPage', 'page_URL_segment');
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
    
    public function test() {
        return var_dump(Config::inst()->get('TOSEProductPage', 'page_URL_segment'));
    }
    
}

class TOSEProductPage_Controller extends Page_Controller {
    
    private static $url_handlers = array();
    
    private static $allowed_actions = array();
    

}
