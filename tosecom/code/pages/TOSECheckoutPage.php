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
        'cartEmpty'
    );
    
    private static $url_handlers = array(
        'empty' => 'cartEmpty'
    );


    public function index(SS_HTTPRequest $request) {
        $cart = TOSECart::get_current_cart();
        if ($cart->cartEmpty()) {
            $this->redirect($this->Link()."cartEmpty");
        } else {
            return $this;
        }
    }
    
}