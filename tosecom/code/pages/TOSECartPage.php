<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class TOSECartPage extends TOSEPage {   
    
}


class TOSECartPage_Controller extends TOSEPage_Controller {
    
    private static $allowed_actions = array(
      'addToCart'  
    );


    public function addToCart(SS_HTTPRequest $request) {
        $data = $request->postVars();
        $cart = TOSECart::get_current_cart();
        $cart->addProduct($data);
        $this->redirectBack();
    }
    
    public function showCartItems() {
        $cartItems = TOSECart::get_cart_items();
    }
}