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
    
    public function getProduct() {
        $spec = $this->Spec();
        return $spec->Product();
    }

    /**
     * Function is to get sub total price of current item
     * @return type
     */
    public function subTotalPrice($nice=FALSE) {
        $spec = $this->Spec();
        $price = $spec->getCurrentPriceValue();
        $subTotalPrice = $this->Quantity * $price;
        
        return $nice ? TOSEPrice::nice($subTotalPrice) : $subTotalPrice;
    }
    
    
    public function checkInventory() {
        return $this->Quantity > $this->Spec()->Inventory ? FALSE : TRUE;
    }
    
    public function write($showDebug = false, $forceInsert = false, $forceWrite = false, $writeComponents = false) {
        if(TOSEMember::is_customer_login()) {
            return parent::write($showDebug, $forceInsert, $forceWrite, $writeComponents);
        } else {
            $this->writeToSession();
        }
    }
    
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