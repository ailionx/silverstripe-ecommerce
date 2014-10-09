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
    
    public function save($data) {
        
        $cartItem = new TOSECartItem();
        $cartItem->update($data);
        $cartItem->write();
        
        return $cartItem;
    }
    
    public function subTotal() {
        
        $spec = $this->Spec();
        $currency = $spec->getCurrentCurrency();
        $price = $currency->Price;
        $subTotal = $this->Quantity * $price;
        return $subTotal;
    }
    
}