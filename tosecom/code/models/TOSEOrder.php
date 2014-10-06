<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class TOSEOrder extends DataObject {
    
    const PENDING = "Pending";
    const CANCELLED = "Cancelled";
    const PAID = "Paid";
    
    private static $db=array(
        'Reference'=>'Varchar(20)',
        'NeedInvoice' => "Boolean",
        'Status'=>"Enum('Pending, Cancelled, Paid', 'Pending')",
        'ShippingFee'=>'Decimal',
        'CustomerName'=>'Varchar',
        'CustomerEmail'=>'Varchar',
        'CustomerPhone'=>'Varchar',
        'Comments' => "Text"
    );
        
    private static $has_one=array(
        'Shipping'=>'TOSEShippingAddress',
        'Billing'=>'TOSEBillingAddress'
    );
    
    private static $has_many = array(
        'Items' => "TOSEOrderItem"
    );

}