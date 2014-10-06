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
        'Order' => 'TOSEOrder'
    );
    
    private static $has_many = array();
}