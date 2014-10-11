<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class TOSEPage extends Page {
    
    const SessionCart = 'TOSECart';
    
    const SessionCurrencyName = 'TOSECurrencyName';

    private static $allowed_children = array(
            'TOSEProductPage',
            'TOSECategoryPage',
            'TOSECartPage',
            'TOSECheckoutPage',
            'TOSELoginPage'
        );
    
    public function test() {
 
    }
    /**
     * Function is to generate tosecom pagess
     */
    public function requireDefaultRecords() {
        parent::requireDefaultRecords();
        
        //To create default data
        if(!TOSEDataGenerator::hasInitiated()){
            TOSEDataGenerator::startGen();
        }
        
    }
    
    /**
     * Function is to check if customer member logged in
     * @return type
     */
    public function isCustomerLogin() {
        return TOSEMember::is_customer_login();
    }
    
    /**
     * Function is to get login or logout string based on member login status
     * @return type
     */
    public function getLogInOut() {
        return Member::currentUserID() ? "logout" : "login";
    }
    
    /**
     * Function is to get the name of login member
     * @return boolean
     */
    public function getMemberName() {
        if (TOSEMember::is_customer_login()) {
           return Member::currentUser()->FirstName; 
        }
        
        return FALSE;
    }
    
    /**
     * Function is to get symbol current currency  
     * @return type
     */
    public function getCurrentCurrencySymbol() {
        return TOSECurrency::get_current_currency_symbol();
    }
    
    /**
     * Function is to get the link of ecommerce page
     * @return type
     */
    public function getEcommerceRootPageLink() {
        $page = DataObject::get_one('SiteTree', "ClassName='TOSEPage'");
        return $page->URLSegment;
    }
    
    /**
     * Function is to get current cart object
     * @return type
     */
    public function getCart() {
        return TOSECart::get_current_cart();
    }
}


class TOSEPage_Controller extends Page_Controller {
    
    private static $allowed_actions = array(
        'logout'
    );
    
    /**
     * Function is to perform logout action
     * @return type
     */
    public function logout() {
        TOSEMember::logout();
        return $this->redirect('ecommerce/login');
    }
    
    
}