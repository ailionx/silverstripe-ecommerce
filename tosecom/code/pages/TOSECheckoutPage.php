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
        'orderForm'
    );
    
    private static $url_handlers = array(
        'empty' => 'cartEmpty'
    );


    public function index(SS_HTTPRequest $request) {
        $cart = TOSECart::get_current_cart();
        if ($cart->cartEmpty()) {
            return $this->redirect($this->Link()."cartEmpty");
        }
        
        return $this;
    }
    
    public function handleConfirmation(SS_HTTPRequest $request) {
        $cart = TOSECart::get_current_cart();
        if ($cart->cartEmpty()) {
            return $this->redirect($this->Link()."cartEmpty");
        }
        
        $data = $request->postVars();
    }
    
    public function orderForm(SS_HTTPRequest $request) {
//        var_dump($request); die();
    }
    
}