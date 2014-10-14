<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class TOSECheckoutPage extends TOSEPage {
    
}


class TOSECheckoutPage_Controller extends TOSEPage_Controller {
    
    private static $allowed_actions = array(
        'cartEmpty',
        'orderForm',
        'confirm'
    );
    
    private static $url_handlers = array(
        'empty' => 'cartEmpty'
    );


    public function index(SS_HTTPRequest $request) {
        $cart = TOSECart::get_current_cart();
        if ($cart->cartEmpty()) {
            return $this->redirect($this->Link()."cartEmpty");
        }
        
        return $this;
    }
    
//    public function doNext() {
//        
//    }
    /**
     * Function is generate form for order page
     * @return \Form
     */
    public function orderForm() {
        $memberAddress = TOSEAddress::getCurrentMemberAddress();
        $member = Member::currentUser();
        $fields = new FieldList();
        $customerInfoFields = new CompositeField();
        $customerInfoFields->addExtraClass('customer-info');
        $customerInfoFields->push(new LiteralField('CustomerInfo', '<h3>Customer Information</h3>'));
        $customerInfoFields->push(new TextField('CustomerName', 'Name', $member->FirstName." ".$member->Surname));
        $customerInfoFields->push(new EmailField('CustomerEmail', 'Email', $member->Email));
        $customerInfoFields->push(new TextField('CustomerPhone', 'Phone', $member->Phone));
        $fields->push($customerInfoFields);
        
        $shippingFields = new CompositeField();
        $shippingFields->addExtraClass('shipping-info');
        $shippingFields->push(new LiteralField('ShippingInfo', '<h3>Shipping Address</h3>'));
        $shippingFields->push(new TextField('ShippingFirstName', 'First Name', $member->FirstName));
        $shippingFields->push(new TextField('ShippingSurName', 'SurName', $member->Surname));
        $shippingFields->push(new TextField('ShippingPhone', 'Phone', $member->Phone));
        $shippingFields->push(new NumericField('ShippingStreetNumber', 'Street Number', $memberAddress->StreetNumber));
        $shippingFields->push(new TextField('ShippingStreetName', 'Street Name', $memberAddress->StreetName));
        $shippingFields->push(new TextField('ShippingSuburb', 'Suburb', $memberAddress->Suburb));
        $shippingFields->push(new TextField('ShippingCity', 'City', $memberAddress->City));
        $shippingFields->push(new TextField('ShippingRegion', 'Region', $memberAddress->Region));
        $shippingFields->push(new TextField('ShippingCountry', 'Country', $memberAddress->Country));
        $shippingFields->push(new NumericField('ShippingPostCode', 'PostCode', $memberAddress->PostCode));
        $fields->push($shippingFields);
        
        $invoiceFields = new CompositeField();
        $invoiceFields->addExtraClass('need-invoice');
        $invoiceFields->push(new CheckboxField('needInvioce', 'Need Invioce?'));
        $fields->push($invoiceFields);
        
        $billingFields = new CompositeField();
        $billingFields->addExtraClass('billing-info');
        $billingFields->push(new LiteralField('BillingInfo', '<h3>Billing Address</h3>'));
        $billingFields->push(new TextField('BillingFirstName', 'First Name', $member->FirstName));
        $billingFields->push(new TextField('BillingSurName', 'SurName', $member->Surname));
        $billingFields->push(new TextField('BillingPhone', 'Phone', $member->Phone));
        $billingFields->push(new NumericField('BillingStreetNumber', 'Street Number', $memberAddress->StreetNumber));
        $billingFields->push(new TextField('BillingStreetName', 'Street Name', $memberAddress->StreetName));
        $billingFields->push(new TextField('BillingSuburb', 'Suburb', $memberAddress->Suburb));
        $billingFields->push(new TextField('BillingCity', 'City', $memberAddress->City));
        $billingFields->push(new TextField('BillingRegion', 'Region', $memberAddress->Region));
        $billingFields->push(new TextField('BillingCountry', 'Country', $memberAddress->Country));
        $billingFields->push(new NumericField('BillingPostCode', 'PostCode', $memberAddress->PostCode));
        $fields->push($billingFields);
                
        $actions = new FieldList(
                new FormAction('doNext', 'Next')
                );
        
        $required = new RequiredFields(
                'Name',
                'Email',
                'Phone'
            );
        
        $form = new Form($this, 'orderForm', $fields, $actions, $required);
        
        if($data = unserialize(Session::get(TOSEPage::SessionOrderInfo))) {

            $form->loadDataFrom($data);
        }
        
        return $form;
    }
    
    public function doNext($data) {
        $sessionData = serialize($data);
        Session::set(TOSEPage::SessionOrderInfo, $sessionData);

        return $this->redirect($this->Link('confirm'));
    }

    public function confirm() {
        $cart = TOSECart::get_current_cart();
        if ($cart->cartEmpty()) {
            return $this->redirect($this->Link()."cartEmpty");
        }
        $data = unserialize(Session::get(TOSEPage::SessionOrderInfo));
        return $this->customise($data)->renderWith(array('TOSECheckoutPage_confirm', 'Page'));
    }
    

}