<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class TOSECheckoutPage extends TOSEPage {
    
}


class TOSECheckoutPage_Controller extends TOSEPage_Controller {
    
    private static $allowed_actions = array(
        'cartEmpty',
        'orderForm',
        'handleConfirmation'
    );
    
    private static $url_handlers = array(
        'empty' => 'cartEmpty',
        'handleConfirmation' => 'confirm'
    );


    public function index(SS_HTTPRequest $request) {
        $cart = TOSECart::get_current_cart();
        if ($cart->cartEmpty()) {
            return $this->redirect($this->Link()."cartEmpty");
        }
        
        return $this;
    }
    
//    public function doNext() {
//        
//    }

    public function handleConfirmation(SS_HTTPRequest $request) {
        $cart = TOSECart::get_current_cart();
        if ($cart->cartEmpty()) {
            return $this->redirect($this->Link()."cartEmpty");
        }
        
        $data = $request->postVars();
    }
    

    
}