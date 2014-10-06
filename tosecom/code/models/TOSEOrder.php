<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class TOSEOrder extends DataObject {
    
    const PENDING = "Pending";
    const DELIVERIED = "Deliveried";
    
    private static $db=array(
        'Reference'=>'Varchar(20)',
        'NeedInvoice' => "Boolean",
        'IsPickedup' => "Boolean",
        'Status'=>"Enum('Pending,Deliveried','Pending')",
        'ShippingFee'=>'Decimal',
        'CustomerName'=>'Varchar',
        'CustomerEmail'=>'Varchar',
        'CustomerPhone'=>'Varchar',
        'Comments' => "Text",
        'PickUpDate' => "Text"
    );
        
    private static $has_one=array(
        'Shipping'=>'TOSEShippingAddress',
        'Billing'=>'TOSEBillingAddress'
    );
    
    private static $has_many = array(
        'Items' => "TOSEOrderItem"
    );

}
