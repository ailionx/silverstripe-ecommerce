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
    
    public static function save($data) {
        
        $cartItem = new TOSECartItem();
        $cartItem->update($data);
        $cartItem->write();
    }
}