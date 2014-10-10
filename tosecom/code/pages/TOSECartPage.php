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
        'deleteItem',
        'cartEmpty'
    );
    
    private static $url_handlers = array(
        'empty' => 'cartEmpty'
    );
    
//    public function index() {
//        $cart = TOSECart::get_current_cart();
//        if ($cart->cartEmpty()) {
//            return $this->redirect($this->Link()."empty");
//        } else {
//            return $this;
//        }
//    }

    /**
     * Function is to handle add action for a product in page
     * @param SS_HTTPRequest $request
     * @return type
     */
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
    
    /**
     * Function is to get current cart object
     * @return type
     */
    public function getCart() {
        return TOSECart::get_current_cart();
    }
    
    /**
     * Function is to clear current cart
     * @return type
     */
    public function clearCart() {
        $cart = TOSECart::get_current_cart();
        $cart->clearCart();
        return $this->redirectBack();
    }
    
    /**
     * Function is to update cart item in cart page
     * @param SS_HTTPRequest $request
     * @return type
     */
    public function updateItem(SS_HTTPRequest $request) {
        $data = $request->postVars();
        $cart = TOSECart::get_current_cart();
        $item = DataObject::get_one('TOSECartItem',"CartID='$cart->ID' AND ProductID='".$data['ProductID']."' AND SpecID='".$data['SpecID']."'");
        
        // Validate if inputs type is number 
        $numberFields = array(
            'Quantity',
            'ProductID',
            'SpecID'
        );
        TOSEValidator::data_is_number($data, $numberFields, TRUE);
        
        $inventory = $item->spec()->Inventory;
        if ($data['Quantity']>$inventory) {
            die('Out of inventory');
        }
        $item->update($data);
        $item->write();
        return $this->redirectBack();
    }
    
    /**
     * Function is to delete item in cart page
     * @param SS_HTTPRequest $request
     * @return type
     */
    public function deleteItem(SS_HTTPRequest $request) {
        $data = $request->getVars();
        $cart = TOSECart::get_current_cart();
        $cart->removeItem($data);
        return $this->redirectBack();
    }
    
//    public function cartEmpty() {
//        die('test');
//    }
    
}