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
        return $this->itemsCount() ? FALSE : TRUE;
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
            $cartItems = new ArrayList();
            if (!$sessionCartItems) {
                return $cartItems;
            } 
            
            $itemsArray = unserialize($sessionCartItems);
            foreach ($itemsArray as $item) {
                $cartItem = new TOSECartItem();
                $cartItem->update($item);
                $cartItems->push($cartItem);
            }

            return $cartItems;
        }
    }
    
    
    /**
     * Function is to check total products number in cart
     * @return type
     */
    public function productsCount() {
        $productCount = 0;
        if (TOSEMember::is_customer_login()) {

            $cartItems = $this->CartItems();
            foreach ($cartItems as $item) {
                $productCount += $item->Quantity;
            }
        } else {
            $itemsArray = unserialize(Session::get(TOSEPage::SessionCart));
            foreach ($itemsArray as $item) {
                $productCount += $item['Quantity'];
            }
        }

        return $productCount;
    }

    /**
     * Function is to check if there is already same item exist in cart
     * @param type $data
     * @return boolean
     */
    public function existItem($data) {
        if ($this->cartEmpty()) {
            return FALSE;
        }
        if(TOSEMember::is_customer_login()) {
            $item = $this->CartItems()->filter('SpecID', $data['SpecID'])->first();
            return $item;
        } else {
            $specID = $data['SpecID'];
            $itemsArray = unserialize(Session::get(TOSEPage::SessionCart));
            return array_key_exists($specID, $itemsArray) ? $itemsArray[$specID] : NULL;
        }

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
        if ($exitItem = $this->existItem($data)) {
            $Quantity = $exitItem->Quantity;
            $Quantity += $data['Quantity'];
        } else {
            $Quantity = $data['Quantity'];
        }

        if($Quantity > $productInventory) {
            die('Out of inventory');
        }
        $this->existItem($data) ? $this->updateItem($data) : $this->addItem($data);
    }

    /**
     * Function is to add new item in to cart
     * @param type $data
     */
    public function addItem($data) {
        if (TOSEMember::is_customer_login()) {
            $item = new TOSECartItem();
            $item->update($data);
            $item->CartID = $this->ID;
            $item->write();
        } else {
            $itemsArray = unserialize(Session::get(TOSEPage::SessionCart));
            $item['SpecID'] = $data['SpecID'];
            $item['Quantity'] = $data['Quantity'];
            $itemsArray['SpecID'] = $item;
            Session::set(TOSEPage::SessionCart, serialize($itemsArray));
        }

    }
    
    /**
     * Function is to update item which already exist in cart
     * @param type $data
     */
    public function updateItem($data) {
        if (TOSEMember::is_customer_login()) {
            $item = $this->CartItems()->filter('SpecID', $data['SpecID'])->first();
            if ($item) {
                $item->Quantity += $data['Quantity'];                
            } else {
                $item = new TOSECartItem();
                $item->update($data);
                $item->CartID = $this->ID;
            }
            
            $item->write();
            
        } else {
            $specID = $data['SpecID'];
            $itemsArray = unserialize(Session::get(TOSEPage::SessionCart));
            if(array_key_exists($specID, $itemsArray)) {
                $item = $itemsArray[$specID];
                $item['Quantity'] += $data['Quantity'];
            } else {
                $item['SpecID'] = $specID;
                $item['Quantity'] = $data['Quantity'];
                $itemsArray[$specID] = $item;
            }
            
            Session::set(TOSEPage::SessionCart, serialize($itemsArray));
        }
    }    

    /**
     * Function is to remove item in cart, can be optimized
     * @param type $data
     */
    public function removeItem($data) {
        if (TOSEMember::is_customer_login()){
            $cartItems = $this->CartItems();
            $item = $cartItems->filter('SpecID', $data['SpecID'])->first();
            $item->delete();
        } else {
            $itemsArray = unserialize(Session::get(TOSEPage::SessionCart));
            $specID = $data['SpecID'];
            unset($itemsArray[$specID]);
            Session::set(TOSEPage::SessionCart, serialize($itemsArray));
        }
    }

    /**
     * Function is to clear all items in cart
     */
    public function clearCart() {
        if (TOSEMember::is_customer_login()) {
            $cartItems = $this->CartItems();
            foreach ($cartItems as $item) {
                $item->delete();
            }
        } else {
            Session::clear(TOSEPage::SessionCart);
        }
        
    }
    
    /** 
     * Function is to get total price of items in cart
     * @return type
     */
    public function totalPrice() {
        $cartItems = $this->CartItems();
        $totalPrice = 0;
        foreach ($cartItems as $item) {
            $totalPrice += $item->subTotalPrice();
        }
        return $totalPrice;
    }
    
    /**
     * Function is to format total price to be more readable
     * @return type
     */
    public function totalPriceFormatted() {
        return number_format($this->totalPrice(), 2);
    }
    
}
