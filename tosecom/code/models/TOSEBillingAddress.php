<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class TOSEBillingAddress extends TOSEAddress {
    
    private static $db = array(
    );

    private static $has_one = array(
        'Order' => 'TOSEOder'
    );
    
    private static $has_many = array();
    
    public static function save($data) {
        $billingAddress = new TOSEBillingAddress();
        $billingAddress->update($data);
        $billingAddress->write();
        
        return $billingAddress;
    }
}