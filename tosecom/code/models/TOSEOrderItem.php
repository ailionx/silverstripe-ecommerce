<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


class TOSEOrderItem extends DataObject {
    
    private static $db = array(
        'Quantity' => 'Int',
        'Name' => 'Varchar(100)',
        'Category' => 'Varchar(100)',
        'SKU' => 'Varchar(100)',
        'Weight' => 'Decimal',
        'Price' => 'Currency',
        'Currency' => 'Varchar(10)'
    );
    
    private static $has_one = array(
        'Product' => 'TOSEProduct',
        'Spec' => 'TOSESpec',
        'Order' => 'TOSEOrder',
        'HistoryOrder' => 'TOSEHistoryOrder'
    );
    
    private static $has_many = array();
    
    /**
     * Function is to save order item
     * @param type $data
     */
    public static function save($data) {
        $orderItem = new TOSEOrderItem();
        $orderItem->update($data);
        $orderItem->write();
        
        // update inventory after transaction
        $spec = $orderItem->Spec();
        $spec->Inventory = $spec->Inventory - $orderItem->Quantity;
        $spec->write();
    }
    

    
}
