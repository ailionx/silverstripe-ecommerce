<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class TOSECheckoutPageExtension extends DataExtension {
    
    private static $db = array();
    
    private static $allowed_actions = array(
        'orderForm'
    );
    
    /*        'FirstName' => 'Varchar(100)',
        'SurName' => 'Varchar(100)',
        'Phone' => 'Varchar(100)',
        'Email' => 'Varchar(100)',
        'StreetNumber' => 'Int',
        'StreetName' => 'Varchar(100)',
        'Suburb' => 'Varchar(100)',
        'City' => 'Varchar(100)',
        'Region' => 'Varchar(100)',
        'Country' => 'Varchar(100)',
        'PostCode' => 'Int'*/
    
    public function orderForm() {
        $fields = new FieldList();
        $customerInfoFields = new CompositeField();
        $customerInfoFields->push(new LiteralField('CustomerInfo', '<h3>Customer Information</h3>'));
        $customerInfoFields->push(new TextField('CustomerName', 'Name'));
        $customerInfoFields->push(new TextField('CustomerEmail', 'Email'));
        $customerInfoFields->push(new TextField('CustomerPhone', 'Phone'));
        $fields->push($customerInfoFields);
        
        $shippingFields = new CompositeField();
        $shippingFields->push(new LiteralField('CustomerInfo', '<h3>Shipping Address</h3>'));
        $shippingFields->push(new TextField('ShippingFirstName', 'FirstName'));
        $shippingFields->push(new TextField('ShippingSurName', 'SurName'));
        $shippingFields->push(new TextField('ShippingPhone', 'Phone'));
        $shippingFields->push(new TextField('ShippingStreetNumber', 'StreetNumber'));
        $shippingFields->push(new TextField('ShippingStreetName', 'StreetName'));
        $shippingFields->push(new TextField('ShippingSuburb', 'Suburb'));
        $shippingFields->push(new TextField('ShippingCity', 'City'));
        $shippingFields->push(new TextField('ShippingRegion', 'Region'));
        $shippingFields->push(new TextField('ShippingCountry', 'Country'));
        $shippingFields->push(new TextField('ShippingPostCode', 'PostCode'));
        $fields->push($shippingFields);
        
        $invioceFields = new CompositeField();
        $invioceFields->push(new CheckboxField('needInvioce', 'Need Invioce?'));
        $fields->push($invioceFields);
        
        $billingFields = new CompositeField();
        $billingFields->push(new LiteralField('CustomerInfo', '<h3>Billing Address</h3>'));
        $billingFields->push(new TextField('BillingFirstName', 'FirstName'));
        $billingFields->push(new TextField('BillingSurName', 'SurName'));
        $billingFields->push(new TextField('BillingPhone', 'Phone'));
        $billingFields->push(new TextField('BillingStreetNumber', 'StreetNumber'));
        $billingFields->push(new TextField('BillingStreetName', 'StreetName'));
        $billingFields->push(new TextField('BillingSuburb', 'Suburb'));
        $billingFields->push(new TextField('BillingCity', 'City'));
        $billingFields->push(new TextField('BillingRegion', 'Region'));
        $billingFields->push(new TextField('BillingCountry', 'Country'));
        $billingFields->push(new TextField('BillingPostCode', 'PostCode'));
        $fields->push($billingFields);
                
        $actions = new FieldList(
                new FormAction('handleConfirmation', 'Next')
                );
        
        $form = new Form($this->owner, 'order-form', $fields, $actions);
        
        return $form;
    }
    
}