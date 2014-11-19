<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class TOSEShippingAddress extends TOSEAddress {
    
    private static $db = array(
    );
    
    private static $has_one = array(
        'Order' => 'TOSEOrder',
        'HistoryOrder' => 'TOSEHistoryOrder'
    );
    
    private static $has_many = array();
    
    /**
     * OVERRIDE
     * @param type $data
     * @return \TOSEShippingAddress
     */
    public static function save($data) {
        $shippingAddress = new TOSEShippingAddress();

        $shippingAddress->update($data);
        $shippingAddress->write();
        
        return $shippingAddress;
    }
}