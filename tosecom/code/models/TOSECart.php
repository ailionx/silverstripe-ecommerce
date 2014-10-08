<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class TOSECart extends DataObject {
    
    private static $db = array(
        
    );
    
    private static $has_one = array(
        'Member' => 'Member'
    );
    
    private static $has_many = array(
        'CartItems' => 'TOSECartItem'
    );
    
    /**
     * Function is to check if customer account logged in
     * @return boolean
     */
    public static function is_customer_login() {
        if(!$member = Member::currentUser()) {
            return FALSE;
        }
        
        return $member->inGroup('Customer');
    }

    public static function get_current_cart() {
        
        if(self::is_customer_login()) {
            if ($cart = DataObject::get_one('TOSECart', "MemberID='".Member::currentUserID()."'")) {
                return $cart;
            }
            $cart = new TOSECart();
            $cart->MemberID = Member::currentUserID();
            $cart->write();
            return $cart;
        } else {
            return new TOSECart();
        }
        
    }
    
    public function isEmpty() {
        $items = self::get_cart_items();
        return empty($items);
    }
    
    public function itemsCount() {
        $cart = self::get_current_cart();
        if(self::is_customer_login()) {
            $cartItems = $cart->CartItems();
        } else {
            $cartItems = Session::get('TOSECart');
        }

        return $cartItems->count();
    }
    
    public static function get_cart_items() {
        if(self::is_customer_login()) {
            $cart = self::get_current_cart();
            $cartItems = $cart->CartItems()->toArray();
        } else {
            $sessionCartItems = Session::get('TOSECart');
            $cartItems = array();
            foreach ($sessionCartItems as $item) {
                $cartItems[] = unserialize($item);
            }
        }
        return $cartItems;
    }

    public function productsCount() {
        $cart = self::get_current_cart();
        if(self::is_customer_login()) {
            $cartItems = $cart->CartItems();
        } else {
            $cartItems = Session::get('TOSECart');
        }
        
        $productCount = 0;
        foreach ($cartItems as $item) {
            $productCount += $item->Quantity;
        }

        return $productCount;
    }

    public function existItem($data) {
        if ($this->isEmpty()) {
            return FALSE;
        }
        
        if(self::is_customer_login()) {
            $cart = self::get_current_cart();
            $items = $cart->CartItems();
        } else {
            $items = self::get_cart_items();
        }
        foreach ($items as $item) {
            $itemProductID = $item->Product()->ID;
            $itemSpecID = $item->Spec()->ID;
            if ($data['ProductID']===$itemProductID && $data['specID'] === $itemSpecID) {
                return $item;
            }
            return FALSE;
        }
    }
    
    public function addProduct($data) {
        $this->existItem($data) ? $this->updateItem($data) : $this->addItem($data);
    }

    public function addItem($data) {
        $cart = self::get_current_cart();
        $item = new TOSECartItem();
        $item->update($data);
        if (self::is_customer_login()) {
            $item->CartID = $cart->ID;
            $item->write();
        } else {
            $cartItems = self::get_cart_items();
            $cartItems[] = serialize($item);
            Session::set('TOSECart', $cartItems);
        }

    }
    
    public function updateItem($data) {
        
        
        
    }
    
//    public function getSessionCart() {
//        
//        if ($sessionCartItems = Session::get('TOSECart')) {
//            return $this->;
//            
//        }
//         ? Session::get('TOSECart') : new TOSECart();
//        return $cart;
//    }
    
    public function saveSessionCart($cart) {
        
        Session::set('TOSECart', serialize($cart));
    }
    
}
