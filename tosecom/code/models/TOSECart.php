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
        $itemsCount = $this->getCartItems()->count();
        return $itemsCount ? FALSE : TRUE;
    }
    
    /**
     * Function is to get the count of items
     * @return type
     */
    public function itemsCount() {
        if(TOSEMember::is_customer_login()) {
            $cartItems = $this->CartItems();
        } else {
            $cartItems = Session::get(TOSEPage::SessionCart);
        }

        return $cartItems->count();
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
            if (!$sessionCartItems) {
                return new ArrayList();
            } 
            return unserialize($sessionCartItems);
        }
    }

    public function productsCount() {
        if(TOSEMember::is_customer_login()) {
            $cartItems = $this->CartItems();
        } else {
            $cartItems = Session::get(TOSEPage::SessionCart);
        }
        
        $productCount = 0;
        foreach ($cartItems as $item) {
            $productCount += $item->Quantity;
        }

        return $productCount;
    }

    public function existItem($data) {
        if ($this->cartEmpty()) {
            return FALSE;
        }
        
        if(TOSEMember::is_customer_login()) {
            $cartItems = $this->CartItems();
        } else {
            $cartItems = $this->getCartItems();
        }
        foreach ($cartItems as $item) {
            $itemProductID = $item->ProductID;
            $itemSpecID = $item->SpecID;
            if ($data['ProductID']===$itemProductID && $data['SpecID'] === $itemSpecID) {
                return $item; 
            }
        }
        return FALSE;
    }
    

    public function addProduct($data) {
        
        // Validate the input data
        $numberFields = array(
            'Quantity',
            'ProductID',
            'SpecID'
        );
        TOSEValidator::data_is_number($data, $numberFields, TRUE);
        
        $productInventory = DataObject::get_one('TOSESpec', "ProductID='".$data['ProductID']."' AND ID='".$data['SpecID']."'")->Inventory;
        if ($exitItem = $this->existItem($data)) {
            $Quantity = $exitItem->Quantity;
            $Quantity += $data['Quantity'];
        }

        if($Quantity > $productInventory) {
            die('Out of inventory');
        }
        $this->existItem($data) ? $this->updateItem($data) : $this->addItem($data);
    }

    public function addItem($data) {
        $item = new TOSECartItem();
        $item->update($data);
        if (TOSEMember::is_customer_login()) {
            $item->CartID = $this->ID;
            $item->write();
        } else {
            $cartItems = $this->getCartItems();
            $cartItems->add($item);
            Session::set(TOSEPage::SessionCart, serialize($cartItems));
        }

    }
    
    public function updateItem($data) {
        if (TOSEMember::is_customer_login()) {
            $item = $this->existItem($data);
            $oldQuantity = $item->Quantity;
            $newQuantity = $oldQuantity + $data['Quantity'];
            $item->Quantity = $newQuantity;
            $item->write();
        } else {
            
        }
    }    

    public function removeItem($data) {
        $item = DataObject::get_one('TOSECartItem', "CartID='$this->ID' AND ProductID='".$data['ProductID']."' AND SpecID='".$data['SpecID']."'");
        $item->delete();
    }

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
    
    public function totalPrice() {
        $cartItems = $this->CartItems();
        $totalPrice = 0;
        foreach ($cartItems as $item) {
            $totalPrice += $item->subTotalPrice();
        }
        return $totalPrice;
    }
    
    public function totalPriceFormatted() {
        return number_format($this->totalPrice(), 2);
    }
    
}
