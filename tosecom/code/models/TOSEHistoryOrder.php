<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class TOSEHistoryOrder extends DataObject {

    private static $db=array(
        'Reference'=>'Varchar(20)',
        'NeedInvoice' => "Boolean",
        'Status'=>"Enum('Pending, Delivered', 'Pending')",
        'ShippingFee'=>'Decimal',
        'CustomerName'=>'Varchar',
        'CustomerEmail'=>'Varchar',
        'CustomerPhone'=>'Varchar',
        'Comments' => "Text"
    );
        
    private static $has_one=array(
        'ShippingAddress'=>'TOSEShippingAddress',
        'BillingAddress'=>'TOSEBillingAddress',
        'Member' => 'Member'
    );
    
    private static $has_many = array(
        'Items' => "TOSEOrderItem"
    );
    
    private static $summary_fields = array(
        'Reference' => 'Reference',
        'getTotalPrice' => 'Total Price',
        'Created' => 'Created',
        'CustomerName' => 'Customer Name',
        'Status' => 'Status'
    );
}