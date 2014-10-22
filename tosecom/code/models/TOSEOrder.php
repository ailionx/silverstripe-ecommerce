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
        'ShippingAddress'=>'TOSEShippingAddress',
        'BillingAddress'=>'TOSEBillingAddress'
    );
    
    private static $has_many = array(
        'Items' => "TOSEOrderItem"
    );
    
////        private static $db = array(
//        'Quantity' => 'Int',
//        'Name' => 'Varchar(100)',
//        'Category' => 'Varchar(100)',
//        'SKU' => 'Varchar(100)',
//        'Weight' => 'Decimal',
//        'Price' => 'Currency',
//        'Currency' => 'Varchar(10)'
//    );
//    
//    private static $has_one = array(
//        'Product' => 'TOSEProduct',
//        'Spec' => 'TOSESpec',
//        'Order' => 'TOSEOrder'
    public static function save($data) {
        
        $order = new TOSEOrder();
        $order->update($data);
        $order->write();
        $cartItems = TOSECart::get_current_cart()->getCartItems();
        foreach ($cartItems as $cartItem) {
            $orderItem = array();
            
            $orderItem['OrderID'] = $order->ID;
            $orderItem['Quantity'] = $cartItem->Quantity;
            $orderItem['Name'] = $cartItem->Product()->Name;
            $orderItem['Category'] = $cartItem->Product()->Category()->ID;
            $orderItem['SKU'] = $cartItem->Spec()->SKU;
            $orderItem['Weight'] = $cartItem->Spec()->Weight;
            $orderItem['Price'] = $cartItem->Spec()->getCurrentPrice();
            $orderItem['Currency'] = TOSECurrency::get_current_currency_name();
            $orderItem['ProductID'] = $cartItem->ProductID;
            $orderItem['SpecID'] = $cartItem->SpecID;

            TOSEOrderItem::save($orderItem);
        }
        
//        TOSECart::get_current_cart()->clearCart();
        
        return $order;
    }
    
    public static function create_reference(){
        $date = date("Y-m-d");  
        $time = (int)date("Ymd")*10000;
        
        $amount = DataObject::get("TOSEOrder", "Created Like'{$date}%'")->count();
        $ref = $time + $amount+1;
        
        return $ref;
    }
    
    /**
     * Function is to update order status
     * @param type $reference
     * @param type $status
     */
    public function updateOrderStatus($status) {
        $this->Status = $status;
        $this->write();
    }
    
}
