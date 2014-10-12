<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class TOSEDataGenerator {
    
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
        
        // determin if the purchase system need login
        
        $config = Config::inst()->get('Member', 'needLogin');
        if ($config != "No") {
            // generate register page
            if(!$page = DataObject::get_one('TOSERegisterPage')) {
                $page = new TOSERegisterPage();
                $page->Title = Config::inst()->get('TOSERegisterPage', 'pageTitle');
                $page->URLSegment = Config::inst()->get('TOSERegisterPage', 'pageURLSegment');
                $page->Status = 'Published';
                $page->ShowInMenus = 0;
                $page->ShowInSearch = 0;
                $page->ParentID = $TOSEPageID;
                $page->write();
                $page->publish('Stage', 'Live');
                $page->flushCache();
                DB::alteration_message('TOSERegisterPage created', 'created'); 
            }
            
            // generate login page
            if(!$page = DataObject::get_one('TOSELoginPage')) {
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
            
            // generate account page
            if(!$page = DataObject::get_one('TOSEAccountPage')) {
                $page = new TOSEAccountPage();
                $page->Title = Config::inst()->get('TOSEAccountPage', 'pageTitle');
                $page->URLSegment = Config::inst()->get('TOSEAccountPage', 'pageURLSegment');
                $page->Status = 'Published';
                $page->ShowInMenus = 0;
                $page->ShowInSearch = 0;
                $page->ParentID = $TOSEPageID;
                $page->write();
                $page->publish('Stage', 'Live');
                $page->flushCache();
                DB::alteration_message('TOSEAccountPage created', 'created'); 
            }
        }

    }
}