<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class DataGenerator {
    
    private static $has_initiated = FALSE;

    public static function hasInitiated() {
        return self::$has_initiated;
    }

    public static function startGen() {
        
        self::genTOSEPages();
        self::$has_initiated = TRUE;
    }

    public static function genTOSEPages() {
        
        // create TOSEPage
        if(!$page = DataObject::get_one('TOSEPage')) {
            $page = new TOSEPage();
            $page->Title = Config::inst()->get('TOSEPage', 'pageTitle');
            $page->URLSegment = Config::inst()->get('TOSEPage', 'pageURLSegment');
            $page->Status = 'Published';
            $page->ShowInMenus = 0;
            $page->ShowInSearch = 0;
            $page->ParentID = 0;
            $page->write();
            $page->publish('Stage', 'Live');
            $page->flushCache();
            DB::alteration_message('TOSEPage created', 'created'); 
        }
        
        $TOSEPageID = DataObject::get_one('SiteTree', "ClassName='TOSEPage'")->ID;
        
        // create TOSECategoryPage
        if(!$page = DataObject::get_one('TOSECategoryPage')) {
            $page = new TOSECategoryPage();
            $page->Title = Config::inst()->get('TOSECategoryPage', 'pageTitle');
            $page->URLSegment = Config::inst()->get('TOSECategoryPage', 'pageURLSegment');
            $page->Status = 'Published';
            $page->ShowInMenus = 0;
            $page->ShowInSearch = 0;
            $page->ParentID = $TOSEPageID;
            $page->write();
            $page->publish('Stage', 'Live');
            $page->flushCache();
            DB::alteration_message('TOSECategoryPage created', 'created'); 
        }
        
        // create TOSEProductPage
        if(!$page = DataObject::get_one('TOSEProductPage')) {
            $page = new TOSEProductPage();
            $page->Title = Config::inst()->get('TOSEProductPage', 'pageTitle');
            $page->URLSegment = Config::inst()->get('TOSEProductPage', 'pageURLSegment');
            $page->Status = 'Published';
            $page->ShowInMenus = 0;
            $page->ShowInSearch = 0;
            $page->ParentID = $TOSEPageID;
            $page->write();
            $page->publish('Stage', 'Live');
            $page->flushCache();
            DB::alteration_message('TOSEProductPage created', 'created'); 
        }
        
        // create TOSECartPage
        if(!$page = DataObject::get_one('TOSECartPage')) {
            $page = new TOSECartPage();
            $page->Title = Config::inst()->get('TOSECartPage', 'pageTitle');
            $page->URLSegment = Config::inst()->get('TOSECartPage', 'pageURLSegment');
            $page->Status = 'Published';
            $page->ShowInMenus = 0;
            $page->ShowInSearch = 0;
            $page->ParentID = $TOSEPageID;
            $page->write();
            $page->publish('Stage', 'Live');
            $page->flushCache();
            DB::alteration_message('TOSECartPage created', 'created'); 
        }
        
        // create TOSECheckoutPage
        if(!$page = DataObject::get_one('TOSECheckoutPage')) {
            $page = new TOSECheckoutPage();
            $page->Title = Config::inst()->get('TOSECheckoutPage', 'pageTitle');
            $page->URLSegment = Config::inst()->get('TOSECheckoutPage', 'pageURLSegment');
            $page->Status = 'Published';
            $page->ShowInMenus = 0;
            $page->ShowInSearch = 0;
            $page->ParentID = $TOSEPageID;
            $page->write();
            $page->publish('Stage', 'Live');
            $page->flushCache();
            DB::alteration_message('TOSECheckoutPage created', 'created'); 
        }
        
        // create TOSELoginPage
        $config = Config::inst()->get('TOSEPage', 'needLogin');
        if(!$page = DataObject::get_one('TOSELoginPage') && ($config != "No")) {
            $page = new TOSELoginPage();
            $page->Title = Config::inst()->get('TOSELoginPage', 'pageTitle');
            $page->URLSegment = Config::inst()->get('TOSELoginPage', 'pageURLSegment');
            $page->Status = 'Published';
            $page->ShowInMenus = 0;
            $page->ShowInSearch = 0;
            $page->ParentID = $TOSEPageID;
            $page->write();
            $page->publish('Stage', 'Live');
            $page->flushCache();
            DB::alteration_message('TOSELoginPage created', 'created'); 
        }
    }
}