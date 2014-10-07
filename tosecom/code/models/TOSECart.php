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
    
    
    public static function get_current_cart() {
        
        if(Member::currentUserID()) {
            if ($cart = DataObject::get_one('TOSECart', "MemberID='".Member::currentUserID()."'")) {
                return $cart;
            }
            $cart = new TOSECart();
            $cart->MemberID = Member::currentUserID();
            $cart->write();
            return $cart;
        } else {
            return $cart = new TOSECart();
        }
        
    }
    
    public function isEmpty() {
        
        
    }
    
    public function itemsCount() {
        $cart = self::get_current_cart();
        $cartItems = $cart->CartItems();
        return $cartItems->count();
    }
    
    public function productsCount() {
        $cart = self::get_current_cart();
        $cartItems = $cart->CartItems();
        $productCount = 0;
        foreach ($cartItems as $item) {
            $productCount += $item->Quantity;
        }
        return $productCount;
    }

    public function existItem($data) {
        $cart = self::get_current_cart();
        $items = $cart->CartItems();
        foreach ($items as $item) {
            $itemProductID = $item->Product()->ID;
            $itemSpecID = $item->Spec()->ID;
            if ($data['ProductID']===$itemProductID && $data['specID'] === $itemSpecID) {
                return $item;
            } else {
                return FALSE;
            }
        }
        
    }
    
    public function addProduct($data) {
        $this->existItem($data) ? self::updateItem($data) : self::addItem($data);
    }

    public static function add_item($data) {
        $cart = self::get_current_cart();
        $item = new TOSECartItem();
        if (Member::currentUserID()) {
            $item->update($data);
            $item->CartID = $cart->ID;
            $item->write();
        } else {

        }

    }
    
    public static function update_item($data) {
        
    }
    
}
