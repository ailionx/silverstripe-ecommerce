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
        'Product' => 'TOSEProduct',
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
     * Function is to get sub total price of current item
     * @return type
     */
    public function subTotalPrice() {
        
        $spec = $this->Spec();
        $price = $spec->getCurrentPrice();
        $subTotalPrice = $this->Quantity * $price;
        return $subTotalPrice;
    }
    
    /**
     * Function is to format sub total price to be more readable
     * @return type
     */
    public function subTotalPriceFormatted() {
        return number_format($this->subTotalPrice(), 2);
    }
    
}