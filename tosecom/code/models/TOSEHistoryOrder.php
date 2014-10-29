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
    
    /**
     * OVERRIDE
     * @param type $member
     * @return boolean
     */
    public function canDelete($member = null) {
        return FALSE;
    }
    
    
    /**
     * OVERRIDE
     * @param type $member
     * @return boolean
     */
    public function canEdit($member = null) {
        return FALSE;
    }
    
    /**
     * Function is to get total products price
     * @return type
     */
    public function getProductsPrice() {
        $items = $this->Items();
        $productPrice = 0;
        foreach ($items as $item) {
            $productPrice += $item->Price;
        }
        
        return $productPrice;        
    }

    /**
     * Function is to get total price including shipping fee
     * @return type
     */        
    public function getTotalPrice() {

        $totalPrice = $this->getProductsPrice() + $this->ShippingFee;
        
        return $totalPrice;
    }

    /**
     * Function is to update order status
     * @param type $reference
     * @param type $status
     */
//    public function updateOrderStatus($status) {
//        $this->Status = $status;
//        $this->write();
//    }
    
    /**
     * OVERRIDE
     * @return \FieldList
     */
    public function getCMSFields() {
        $fields = new FieldList();
        
        $fields->push(new ReadonlyField('Reference', 'Reference No.'));
        $statusField = new ReadonlyField('Status', 'Status');
        $fields->push($statusField);
        
        $customerInfoField = new CompositeField();
        $customerInfoField->push(new HeaderField('customerInfoHeader', 'Customer Information'));
        $customerInfoField->push(new ReadonlyField('CustomerName', 'Cutomer Name'));
        $customerInfoField->push(new ReadonlyField('CustomerEmail', 'Customer Email'));
        $customerInfoField->push(new ReadonlyField('CustomerPhone', 'Customer Phone'));
        $customerInfoField->push(new ReadonlyField('Comments', 'Comments'));
        $fields->push($customerInfoField);
        
        $orderInfoField = new CompositeField();
        $orderInfoField->push(new HeaderField('orderInfoHeader', 'Order Information'));
        $orderInfoField->push(new ReadonlyField('Created', 'Created'));
        $orderInfoField->push(new ReadonlyField('NeedInvoiceString', 'Need Invoice?', $this->NeedInvoice ? 'Yes' : 'No'));
        $orderInfoField->push(new ReadonlyField(FALSE, 'Product Price', "NZD $".$this->getProductsPrice()));
        $orderInfoField->push(new ReadonlyField(FALSE, 'Shipping fee', "NZD $".$this->ShippingFee));
        $orderInfoField->push(new ReadonlyField(FALSE, 'Total Price', "NZD $".$this->getTotalPrice()));
        $fields->push($orderInfoField);
        
        $itemsInfoFields = new CompositeField();
        $itemsInfoFields->push(new HeaderField('itemsInfoHeader', 'Order Items List'));
        $itemsInfoFields->push(new LiteralField('ItemsInfo', $this->getItemsInfo4CMS()));
        $fields->push($itemsInfoFields);
        
        $shippingFields = new CompositeField();
        $shippingFields->push(new HeaderField('shippingHeader', 'Shipping Information'));
        $shippingFields->push(new LiteralField('ShippingInfo', $this->getShippingInfo4CMS()));
        $fields->push($shippingFields);
        
        if($this->NeedInvoice) {
            $billingFields = new CompositeField();
            $billingFields->push(new HeaderField('BillingHeader', 'Billing Information'));
            $billingFields->push(new LiteralField('BillingInfo', $this->getbillingInfo4CMS()));
            $fields->push($billingFields);
        }
        
        return $fields;
    }

    /**
     * Function is to get shipping info for CMS to show order
     * @return string
     */
    public function getShippingInfo4CMS() {
        $shippingAddress = $this->ShippingAddress();
        $info = $shippingAddress->FirstName . " " . $shippingAddress->SurName;
        $info .= "<br>" . $shippingAddress->Phone;
        $info .= "<br>" . $shippingAddress->StreetNumber . " " .$shippingAddress->StreetName . ", " . $shippingAddress->Suburb;
        $info .= "<br>" . $shippingAddress->City . ", " . $shippingAddress->Region . ", " . $shippingAddress->Country . ", " . $shippingAddress->PostCode;
        return $info;
    }
    
    /**
     * Function is to get billing info for CMS to show order
     * @return string
     */
    public function getBillingInfo4CMS() {
        $billingAddress = $this->ShippingAddress();
        $info = $billingAddress->FirstName . " " . $billingAddress->SurName;
        $info .= "<br>" . $billingAddress->Phone;
        $info .= "<br>" . $billingAddress->StreetNumber . " " .$billingAddress->StreetName . ", " . $billingAddress->Suburb;
        $info .= "<br>" . $billingAddress->City . ", " . $billingAddress->Region . ", " . $billingAddress->Country . ", " . $billingAddress->PostCode;
        return $info;
    }
    
    public function getItemsInfo4CMS() {
        $items = $this->items();
        $info = "<table style='border-spacing: 50px 5px; border-collapse:separate'>"
                . "<tr><th></th><th>Name</th><th>Category</th><th>Price</th><th>QTY</th><th>Sub Total</th></tr>";
        foreach ($items as $item) {
            $info .= "<tr><td><img src='".$item->Product()->getDefaultImage()->Filename."' style='width:60px;' ></td>"
                    . "<td>$item->Name</td>"
                    . "<td>$item->Category</td>"
                    . "<td>NZD $$item->Price</td>"
                    . "<td>$item->Quantity</td>"
                    . "<td>$item->Name</td>";
        }
        
        $info .= "</table>";
        
        return $info;
        
    }
}