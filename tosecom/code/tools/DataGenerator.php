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
            $config = Config::inst()->get('TOSEPage', 'defaultConfig');
            $page = new TOSEPage();
            $page->Title = $config['pageTitle'];
            $page->URLSegment = $config['pageURLSegment'];
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
            $config = Config::inst()->get('TOSECategoryPage', 'defaultConfig');
            $page = new TOSECategoryPage();
            $page->Title = $config['pageTitle'];
            $page->URLSegment = $config['pageURLSegment'];
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
            $config = Config::inst()->get('TOSEProductPage', 'defaultConfig');
            $page = new TOSEProductPage();
            $page->Title = $config['pageTitle'];
            $page->URLSegment = $config['pageURLSegment'];
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
            $config = Config::inst()->get('TOSECartPage', 'defaultConfig');
            $page = new TOSECartPage();
            $page->Title = $config['pageTitle'];
            $page->URLSegment = $config['pageURLSegment'];
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
            $config = Config::inst()->get('TOSECheckoutPage', 'defaultConfig');
            $page = new TOSECheckoutPage();
            $page->Title = $config['pageTitle'];
            $page->URLSegment = $config['pageURLSegment'];
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