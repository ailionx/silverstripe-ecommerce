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
        if(!TOSEProduct::has_inventory()) {
            return;
        }
        
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
        if(!TOSEProduct::has_inventory()) {
            return FALSE;
        }
        $inventory = $this->Spec()->Inventory;
        return $this->Quantity >= $inventory;
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
            $this->writeToNonDB();
        }
    }
    
    /**
     * Function is to save cart item to session
     * @return type
     */
    public function writeToNonDB() {
        $allItemsArray = TOSECart::get_cart_data_from_nondb();
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
        TOSECart::set_cart_data_to_nondb($allItemsArray);
        return $id;
    }

    /**
     * OVERRIDE
     */
    public function delete() {
        if(TOSEMember::is_customer_login()) {
            parent::delete();
        } else {
            $itemsArray = TOSECart::get_cart_data_from_nondb();
            unset($itemsArray[$this->ID]);
            TOSECart::set_cart_data_to_nondb($itemsArray);
        }
    }
    
    
}