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
        $needLogin = Config::inst()->get('TOSECart', 'needLogin');
        
        if(Member::currentUserID()) {
            if ($cart = DataObject::get_one('TOSECart', "MemberID='".Member::currentUserID()."'")) {
                return $cart;
            }
            $cart = new TOSECart();
            $cart->Member() = Member::currentUser();
            $cart->write();
            return $cart;
        } else {
            
        }
        
    }
    
    public function existItem($data) {
        
        $items = $this->CartItems();
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
        $this->existItem($data) ? TOSECartItem::updateItem($data) : TOSECartItem::addItem($data);
    }

    public function addItem($data) {
        $item = new TOSECartItem();
        $item->update($data);
        $item->write();
    }
    
    public function updateItem($data) {
        
    }
    
}
