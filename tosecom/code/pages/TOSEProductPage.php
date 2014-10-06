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
 
        if(!$page = DataObject::get_one($this)) {
            $page = new TOSEProductPage();
            $page->Title = "Product";
            $page->URLSegment = 'product';
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

class TOSEProductPage_Controller extends Page_Controller {
    
}
