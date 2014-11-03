<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class TOSECartItem extends DataObject {
    
    private static $db = array(
        'Quantity' => 'Int'
    );

    private static $has_one = array(
        'Spec' => 'TOSESpec',
        'Cart' => 'TOSECart'
    );
    
    /**
     * Function is to save a new cart item
     * @param type $data
     * @return \TOSECartItem
     */
    public static function save($data) {
        
        $cartItem = new TOSECartItem();
        $cartItem->update($data);
        $cartItem->write();
        
        return $cartItem;
    }
    
    /**
     * Function is to get product belongs to this cart item based on spec
     * @return type
     */
    public function getProduct() {
        $spec = $this->Spec();
        return $spec->Product();
    }

    /**
     * Function is to get sub total price of current item
     * @return type
     */
    public function subTotalPrice() {
        $spec = $this->Spec();
        $price = $spec->getActivePrice();
        $totalPrice = new TOSEPrice();
        $totalPrice->Currency = $price->Currency;
        $totalPrice->Price = $price->Price * $this->Quantity;
        return $totalPrice;
    }
    
    
    /**
     * Function is to get sub total price of current item
     * @return type
     */
    public function subTotalPriceToPay() {
        $spec = $this->Spec();
        $price = $spec->getActivePrice();
        $totalPrice = new TOSEPrice();
        $totalPrice->Currency = $price->Currency;
        $totalPrice->Price = $price->Price * $this->Quantity;
        return $totalPrice;
    }
    
    
    /**
     * Function is to check if the quantity beyonds inventory
     * @return type
     */
    public function checkQuantity() {
        if ($this->Quantity > $this->Spec()->Inventory) {
            die('Out of inventory');
        }
        
        if ($this->Quantity<1) {
            $this->delete();
        }
        
    }
    
    public function QuantityReachMin($num=1) {
        return $this->Quantity <= $num;
    }
    
    public function QuantityReachMax() {
        return $this->Quantity >= $this->Inventory;
    }

    /**
     * OVERRIDE
     * @param type $showDebug
     * @param type $forceInsert
     * @param type $forceWrite
     * @param type $writeComponents
     * @return type
     */
    public function write($showDebug = false, $forceInsert = false, $forceWrite = false, $writeComponents = false) {
        if(TOSEMember::is_customer_login()) {
            return parent::write($showDebug, $forceInsert, $forceWrite, $writeComponents);
        } else {
            $this->writeToSession();
        }
    }
    
    /**
     * Function is to save cart item to session
     * @return type
     */
    public function writeToSession() {
        $allItemsArray = unserialize(Session::get(TOSEPage::SessionCart));
        if (!$allItemsArray) {
            $id = 1;
        } else {
            foreach ($allItemsArray as $item) {
                if ($item['SpecID'] == $this->SpecID) {
                    $id = $item['ID'];
                }
            }
            $id = $id ? $id : (max(array_keys($allItemsArray))) + 1;
        }
        $itemArray = array();
        $itemArray['ID'] = $id;
        $itemArray['SpecID'] = $this->SpecID;
        $itemArray['Quantity'] = $this->Quantity;
        $allItemsArray[$id] = $itemArray;
        Session::set(TOSEPage::SessionCart, serialize($allItemsArray));
        return $id;
    }

    /**
     * OVERRIDE
     */
    public function delete() {
        if(TOSEMember::is_customer_login()) {
            parent::delete();
        } else {
            $itemsArray = unserialize(Session::get(TOSEPage::SessionCart));
            unset($itemsArray[$this->ID]);
            Session::set(TOSEPage::SessionCart, serialize($itemsArray));
        }
    }
    
    
}