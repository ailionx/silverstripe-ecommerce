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
        'addToCart',
        'clearCart',
        'updateItem',
        'getCart',
        'deleteItem'
    );


    public function addToCart(SS_HTTPRequest $request) {
        $data = $request->postVars();
        $cart = TOSECart::get_current_cart();
        $cart->addProduct($data);
        return $this->redirectBack();
    }
    
//    public function getCartItems() {
//        $cart = TOSECart::get_current_cart();
//        $cartItems = $cart->getCartItems();
//        if ($cartItems->count() > 0) {
//            foreach ($cartItems as $item) {
//                $tester = $item->Product(); 
//            }
//        }
////        var_dump($tester); die();
//        return $cartItems;
//    }
    
    public function getCart() {
        return TOSECart::get_current_cart();
    }

    public function clearCart() {
        $cart = TOSECart::get_current_cart();
        $cart->clearCart();
        return $this->redirectBack();
    }
    
        
    public function updateItem(SS_HTTPRequest $request) {
        $data = $request->postVars();
        $cart = TOSECart::get_current_cart();
        $item = DataObject::get_one('TOSECartItem',"CartID='$cart->ID' AND ProductID='".$data['ProductID']."' AND SpecID='".$data['SpecID']."'");
        $item->update($data);
        $item->write();
        return $this->redirectBack();
    }
    
    public function deleteItem(SS_HTTPRequest $request) {
        $data = $request->getVars();
        $cart = TOSECart::get_current_cart();
        $cart->removeItem($data);
        return $this->redirectBack();
    }
    
}