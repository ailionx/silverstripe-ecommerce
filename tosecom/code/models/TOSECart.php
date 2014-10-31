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
     * Function is to get current cart
     * @return \TOSECart
     */
    public static function get_current_cart() {
        
        if(TOSEMember::is_customer_login()) {
            $cart = DataObject::get_one('TOSECart', "MemberID='".Member::currentUserID()."'");
            if ($cart) {
                return $cart;
            } else {
                $cart = new TOSECart();
                $cart->MemberID = Member::currentUserID();
                $cart->write();
                return $cart;
            }
        } else {
            return new TOSECart();
        }
        
    }
    
    /**
     * Function is to check if cart is empty
     * @return type
     */
    public function cartEmpty() {
        return !$this->getCartItems()->exists();
    }
    
    /**
     * Function is to get the count of items
     * @return type
     */
    public function itemsCount() {
        return $this->getCartItems()->count();
    }
    
    /**
     * Function is to get the items of cart
     * @return \ArrayList
     */
    public function getCartItems() {
        if(TOSEMember::is_customer_login()) {
            return $this->CartItems();
        } else {
            $sessionCartItems = Session::get(TOSEPage::SessionCart);
            $items = new ArrayList();
            if (!$sessionCartItems) {
                return $items;
            } 
            
            $itemsArray = unserialize($sessionCartItems);
            foreach ($itemsArray as $itemArray) {
                $item = new TOSECartItem();
                $item->update($itemArray);
                $items->push($item);
            }

            return $items;
        }
    }
    
    
    /**
     * Function is to check total products number in cart
     * @return type
     */
    public function productsCount() {
        $productCount = 0;
        
        $items = $this->getCartItems();
        foreach ($items as $item) {
            $productCount += $item->Quantity;
        }

        return $productCount;
    }

    /**
     * Function is to check if there is already same item exist in cart
     * @param type $data
     * @return boolean
     */
    public function existItem($data) {
        $item = $this->getCartItems()->find('SpecID', $data['SpecID']);
        return $item;
    }
    
    /**
     * Function is to add new products in to cart
     * @param type $data
     */
    public function addProduct($data) {

        // Validate the input data
        $numberFields = array(
            'Quantity',
            'SpecID'
        );
        TOSEValidator::data_is_number($data, $numberFields, TRUE);
        
        $productInventory = DataObject::get_one('TOSESpec', "ID='".$data['SpecID']."'")->Inventory;

        $this->existItem($data) ? $this->updateItem($data) : $this->addItem($data);
    }

    /**
     * Function is to add new item in to cart
     * @param type $data
     */
    public function addItem($data) {
        $item = new TOSECartItem();
        $item->update($data);
        $item->CartID = $this->ID;
        $item->checkInventory();
        $item->write();
    }
    
    /**
     * Function is to update item which already exist in cart
     * @param type $data
     */
    public function updateItem($data) {
        $item = $this->getCartItems()->find('SpecID', $data['SpecID']);
        $quantity = $item->Quantity + $data['Quantity'];
        $this->itemAssignQuantity($data['SpecID'], $quantity);
    }
    
    public function itemAssignQuantity($specID, $quantity) {
        $item = $this->getCartItems()->find('SpecID', $specID);
        $item->Quantity = $quantity;
        $item->checkInventory();
        $item->write();
    }
    

    /**
     * Function is to remove item in cart, can be optimized
     * @param type $data
     */
    public function removeItem($data) {
        $item = $this->getCartItems()->find('SpecID', $data['SpecID']);
        $item->delete();
    }

    /**
     * Function is to clear all items in cart
     */
    public function clearCart() {
        $cartItems = $this->getCartItems();
        foreach ($cartItems as $item) {
            $item->delete();
        }
    }
    
    /** 
     * Function is to get total price of items in cart
     * @return type
     */
    public function totalPrice($nice=FALSE) {
        $items = $this->getCartItems();
        $totalPriceVal = 0;
        foreach ($items as $item) {
            $totalPriceVal += $item->subTotalPrice()->Price;
        }
        $totalPrice = new TOSEPrice();
        $totalPrice->Currency = TOSEPrice::get_current_currency_name();
        $totalPrice->Price = $totalPriceVal;
        
        return $totalPrice;
    }

}
