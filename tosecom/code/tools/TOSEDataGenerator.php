<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class TOSEDataGenerator {
    
    private static $has_initiated = FALSE;
    
    /**
     * Function is to check if the generator has been initiated
     * @return type
     */
    public static function has_initiated() {
        return self::$has_initiated;
    }
    
    /**
     * Function is to start gen TOSEData
     */
    public static function start_gen() {
        
        self::gen_TOSEPages();
        self::gen_TOSEModules();
        self::$has_initiated = TRUE;
    }

    /**
     * Function is to generate TOSEPages
     */
    public static function gen_TOSEPages() {
        
        if(!$page = DataObject::get_one('SiteTree', "ClassName='TOSEPage'")) {
            self::gen_TOSEPage();
        }
        
        $TOSEPageID = DataObject::get_one('SiteTree', "ClassName='TOSEPage'")->ID; 
        
        if(!$page = DataObject::get_one('SiteTree', "ClassName='TOSECategoryPage'")) {
            self::gen_TOSECategoryPage($TOSEPageID);
        }
        
        if(!$page = DataObject::get_one('SiteTree', "ClassName='TOSEProductPage'")) {
            self::gen_TOSEProductPage($TOSEPageID);
        }
        
        if(!$page = DataObject::get_one('SiteTree', "ClassName='TOSECartPage'")) {
            self::gen_TOSECartPage($TOSEPageID);
        }
        
        if(!$page = DataObject::get_one('SiteTree', "ClassName='TOSECheckoutPage'")) {
            self::gen_TOSECheckoutPage($TOSEPageID);
        }
        
        // determin if the purchase system need login
        $config = TOSEMember::need_login();
        if ($config != TOSEMember::NeedLoginNo) {
            if(!$page = DataObject::get_one('SiteTree', "ClassName='TOSERegisterPage'")) {
                self::gen_TOSERegisterPage($TOSEPageID);
            }

            if(!$page = DataObject::get_one('SiteTree', "ClassName='TOSELoginPage'")) {
                self::gen_TOSELoginPage($TOSEPageID);
            }

            if(!$page = DataObject::get_one('SiteTree', "ClassName='TOSEAccountPage'")) {
                self::gen_TOSEAccountPage($TOSEPageID);
            }
        }
            
    }
    
    public static function gen_TOSEModules() {        
        $groupCode = TOSEMember::get_customer_group_code();
        if(!$group = DataObject::get_one('Group', "Code='$groupCode'")) {
            $group = self::gen_customer_group($groupCode);
        }
    }

    // create TOSEPage
    public static function gen_TOSEPage() {
        $page = new TOSEPage();
        $page->Title = TOSEPage::get_page_title('TOSEPage');
        $page->URLSegment = TOSEPage::get_page_URLSegment('TOSEPage');
        $page->Status = 'Published';
        $page->ShowInMenus = 0;
        $page->ShowInSearch = 0;
        $page->ParentID = 0;
        $page->write();
        $page->publish('Stage', 'Live');
        $page->flushCache();
        DB::alteration_message('TOSEPage created', 'created'); 
    }
    
    
    // create TOSECategoryPage
    public static function gen_TOSECategoryPage($parentID) {
        $page = new TOSECategoryPage();
        $page->Title = TOSEPage::get_page_title('TOSECategoryPage');
        $page->URLSegment = TOSEPage::get_page_URLSegment('TOSECategoryPage');
        $page->Status = 'Published';
        $page->ShowInMenus = 0;
        $page->ShowInSearch = 0;
        $page->ParentID = $parentID;
        $page->write();
        $page->publish('Stage', 'Live');
        $page->flushCache();
        DB::alteration_message('TOSECategoryPage created', 'created'); 
    }
        
    // create TOSEProductPage
    public static function gen_TOSEProductPage($parentID) {
        $page = new TOSEProductPage();
        $page->Title = TOSEPage::get_page_title('TOSEProductPage');
        $page->URLSegment = TOSEPage::get_page_URLSegment('TOSEProductPage');
        $page->Status = 'Published';
        $page->ShowInMenus = 0;
        $page->ShowInSearch = 0;
        $page->ParentID = $parentID;
        $page->write();
        $page->publish('Stage', 'Live');
        $page->flushCache();
        DB::alteration_message('TOSEProductPage created', 'created'); 
    }
        
    // create TOSECartPage
    public static function gen_TOSECartPage($parentID) {
        $page = new TOSECartPage();
        $page->Title = TOSEPage::get_page_title('TOSECartPage');
        $page->URLSegment = TOSEPage::get_page_URLSegment('TOSECartPage');
        $page->Status = 'Published';
        $page->ShowInMenus = 0;
        $page->ShowInSearch = 0;
        $page->ParentID = $parentID;
        $page->write();
        $page->publish('Stage', 'Live');
        $page->flushCache();
        DB::alteration_message('TOSECartPage created', 'created'); 
    }
        
    // create TOSECheckoutPage
    public static function gen_TOSECheckoutPage($parentID) {
        $page = new TOSECheckoutPage();
        $page->Title = TOSEPage::get_page_title('TOSECheckoutPage');
        $page->URLSegment = TOSEPage::get_page_URLSegment('TOSECheckoutPage');
        $page->Status = 'Published';
        $page->ShowInMenus = 0;
        $page->ShowInSearch = 0;
        $page->ParentID = $parentID;
        $page->write();
        $page->publish('Stage', 'Live');
        $page->flushCache();
        DB::alteration_message('TOSECheckoutPage created', 'created'); 
    }
        
    // generate register page
    public static function gen_TOSERegisterPage($parentID) {
        $page = new TOSERegisterPage();
        $page->Title = TOSEPage::get_page_title('TOSERegisterPage');
        $page->URLSegment = TOSEPage::get_page_URLSegment('TOSERegisterPage');
        $page->Status = 'Published';
        $page->ShowInMenus = 0;
        $page->ShowInSearch = 0;
        $page->ParentID = $parentID;
        $page->write();
        $page->publish('Stage', 'Live');
        $page->flushCache();
        DB::alteration_message('TOSERegisterPage created', 'created'); 
    }
            
    // generate login page
    public static function gen_TOSELoginPage($parentID) {
        $page = new TOSELoginPage();
        $page->Title = TOSEPage::get_page_title('TOSELoginPage');
        $page->URLSegment = TOSEPage::get_page_URLSegment('TOSELoginPage');
        $page->Status = 'Published';
        $page->ShowInMenus = 0;
        $page->ShowInSearch = 0;
        $page->ParentID = $parentID;
        $page->write();
        $page->publish('Stage', 'Live');
        $page->flushCache();
        DB::alteration_message('TOSELoginPage created', 'created'); 
    }
            
    // generate account page
    public static function gen_TOSEAccountPage($parentID) {
        $page = new TOSEAccountPage();
        $page->Title = TOSEPage::get_page_title('TOSEAccountPage');
        $page->URLSegment = TOSEPage::get_page_URLSegment('TOSEAccountPage');
        $page->Status = 'Published';
        $page->ShowInMenus = 0;
        $page->ShowInSearch = 0;
        $page->ParentID = $parentID;
        $page->write();
        $page->publish('Stage', 'Live');
        $page->flushCache();
        DB::alteration_message('TOSEAccountPage created', 'created'); 
    }
    
    public static function gen_customer_group($code) {
        $group = new Group();
        $group->Code = $code;
        $group->Title = ucfirst($code);
        $group->ParentID = 0;
        $group->write();
        $groupID = $group->ID;
        $permission = new Permission();
        $permission->Code = TOSEMember::PermissionCode;
        $permission->GroupID = $groupID;
        $permission->write();
        DB::alteration_message("Group ".$group->Title." created", 'created'); 
    }
}