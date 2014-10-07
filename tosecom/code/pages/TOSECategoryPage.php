<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class TOSECategoryPage extends TOSEPage {
    
    public function requireDefaultRecords() {
        parent::requireDefaultRecords();
        
        if(!($page = DataObject::get_one("TOSECategoryPage"))){
            $config = $this->config()->defaultConfig;
            $page = new TOSECategoryPage();
            $page->Title = $config['pageTitle'];
            $page->URLSegment = $config['pageURLSegment'];
            $page->Status = 'Published';
            $page->ShowInMenus = 0;
            $page->ShowInSearch = 0;
            $page->ParentID = 0;
            $page->write();
            $page->publish('Stage', 'Live');
            $page->flushCache();
            DB::alteration_message('TOSECategoryPage created', 'created'); 
        }
    }
}

class TOSECategoryPage_Controller extends TOSEPage_Controller {
    
}
