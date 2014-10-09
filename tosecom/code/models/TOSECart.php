<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class TOSECart extends DataObject {
    
    const CartSession = 'TOSECart';

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
        return $member->inGroup('customer');
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
        $items = $this->getCartItems();
        return empty($items);
    }
    
    public function itemsCount() {
        $cart = self::get_current_cart();
        if(self::is_customer_login()) {
            $cartItems = $cart->CartItems();
        } else {
            $cartItems = Session::get(self::CartSession);
        }

        return $cartItems->count();
    }
    
    public function getCartItems() {
        if(self::is_customer_login()) {
            $cart = self::get_current_cart();
            return $cart->CartItems();
        } else {
            $sessionCartItems = Session::get(self::CartSession);
            if (!$sessionCartItems) {
                return new ArrayList();
            } 
            return unserialize($sessionCartItems);
        }
    }

    public function productsCount() {
        $cart = self::get_current_cart();
        if(self::is_customer_login()) {
            $cartItems = $cart->CartItems();
        } else {
            $cartItems = Session::get(self::CartSession);
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
            $cartItems = $cart->CartItems();
        } else {
            $cartItems = $this->getCartItems();
        }
        foreach ($cartItems as $item) {
            $itemProductID = $item->ProductID;
            $itemSpecID = $item->SpecID;
            if ($data['ProductID']===$itemProductID && $data['SpecID'] === $itemSpecID) {
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
            $cartItems = $this->getCartItems();
            $cartItems->add($item);
            Session::set(self::CartSession, serialize($cartItems));
        }

    }
    
    public function updateItem($data) {
        $cart = self::get_current_cart();
        if (self::is_customer_login()) {
            $item = $this->existItem($data);
            $oldQuantity = $item->Quantity;
            $newQuantity = $oldQuantity + $data['Quantity'];
            $item->Quantity = $newQuantity;
            $item->write();
        } else {
            
        }
    }
    
    public function itemPlus($data) {
        
    }
    
    public function itemMinus($data) {
        
    }
    
    public function itemRemove($data) {
        
    }

    public function clearCart() {
        if (self::is_customer_login()) {
            $cartItems = $this->CartItems();
            foreach ($cartItems as $item) {
                $item->delete();
            }
        } else {
            Session::clear(self::CartSession);
        }
        
    }


    
    public function saveSessionCart($cart) {
        
        Session::set(self::CartSession, serialize($cart));
    }
    
}
