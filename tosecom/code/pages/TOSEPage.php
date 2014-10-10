<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class TOSEPage extends Page {
    
    const SessionCart = 'TOSECart';
    
    const SessionCurrencyName = 'TOSECurrencyName';

    private static $allowed_children = array('TOSEProductPage', 'TOSECategoryPage', 'TOSECartPage', 'TOSECheckoutPage', 'TOSELoginPage');
    
    public function requireDefaultRecords() {
        parent::requireDefaultRecords();
        
        //To create default data
        if(!DataGenerator::hasInitiated()){
            DataGenerator::startGen();
        }
        
    }
    
    public function logout() {
        TOSEMember::logout();
        return $this->redirect('ecommerce/login');
    }
            


    public function showLogout () {
        
        if (Member::currentUserID()) {
            $htmlText = new LiteralField('logoutButton', '<a href="ecommerce/logout"><button>logout</button></a>');
            
        } else {
            $htmlText = new LiteralField('loginButton', '<a href="ecommerce/login"><button>login</button></a>');
        }
        
        return $htmlText;
    }
    
    public function getMemberName() {
        if (Member::currentUserID()) {
           return "User:".Member::currentUser()->FirstName; 
        }
        
        return FALSE;
    }
    
    public function getCurrentCurrencySymbol() {
        return TOSECurrency::get_current_currency_symbol();
    }
     
    
}

class TOSEPage_Controller extends Page_Controller {
    
    private static $allowed_actions = array(
        'logout'
    );
    
}