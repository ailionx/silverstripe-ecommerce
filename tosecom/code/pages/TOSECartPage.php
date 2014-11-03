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
        'updateQuantity',
        'removeItem',
        'cartEmpty',
        'quantityMinus',
        'quantityPlus'
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
    public function updateQuantity(SS_HTTPRequest $request) {
        $data = $request->postVars();
        // Validate if inputs type is number 
        $numberFields = array(
            'Quantity',
            'SpecID'
        );
        TOSEValidator::data_is_number($data, $numberFields, TRUE);
        $cart = TOSECart::get_current_cart();
        $cart->itemAssignQuantity($data['SpecID'], $data['Quantity']);
        return $this->redirectBack();
    }
    
    /**
     * Function is to make item quantity minus 1
     * @param SS_HTTPRequest $request
     * @return type
     */
    public function quantityMinus(SS_HTTPRequest $request) {
        $data = $request->getVars();
        // Validate if inputs type is number 
        $numberFields = array(
            'SpecID'
        );
        TOSEValidator::data_is_number($data, $numberFields, TRUE);
        
        $cart = TOSECart::get_current_cart();
        $specID = $data['SpecID'];
        $item = DataObject::get_one('TOSECartItem', "SpecID = '$specID' AND CartID = '{$cart->ID}'");
        $quantity = $item->Quantity - 1;
        $cart->itemAssignQuantity($specID, $quantity);
        
        return $this->redirectBack();
        
    }
    
    /**
     * Function is to make item quantity plus 1
     * @param SS_HTTPRequest $request
     * @return type
     */
    public function quantityPlus(SS_HTTPRequest $request) {
        $data = $request->getVars();
        // Validate if inputs type is number 
        $numberFields = array(
            'SpecID'
        );
        TOSEValidator::data_is_number($data, $numberFields, TRUE);
        
        $cart = TOSECart::get_current_cart();
        $specID = $data['SpecID'];
        $item = DataObject::get_one('TOSECartItem', "SpecID = '$specID' AND CartID = '{$cart->ID}'");
        $quantity = $item->Quantity + 1;
        $cart->itemAssignQuantity($specID, $quantity);
        
        return $this->redirectBack();
        
    }

    /**
     * Function is to delete item in cart page
     * @param SS_HTTPRequest $request
     * @return type
     */
    public function removeItem(SS_HTTPRequest $request) {
        $data = $request->getVars();
        $cart = TOSECart::get_current_cart();
        $cart->removeItem($data);
        return $this->redirectBack();
    }
    
    
}