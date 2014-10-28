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
        'confirm',
        'doPay',
        'result'
    );
    
    private static $url_handlers = array(
        'empty' => 'cartEmpty'
    );

    /**
     * Function is to do initial redirect
     * @param SS_HTTPRequest $request
     * @return \TOSECheckoutPage_Controller
     */
    public function index() {
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
        $invoiceFields->push(new CheckboxField('NeedInvoice', 'Need Invoice?'));
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
        
        $commentField = new TextareaField('Comments', '');
        $fields->push(new LiteralField('CommentsTitle', '<h3>Message</h3>'));
        $fields->push($commentField);
        
        $methods = Config::inst()->get('PaymentProcessor', 'supported_methods');
        if($this->multiPaymentMethod()) {
            $envMethods = array();
            foreach ($methods[SS_ENVIRONMENT_TYPE] as $method) {
                $envMethods[$method] = $method;
            }
            $paymentField = new DropdownField('PaymentMethod', 'Payment Method', $envMethods);
        } else {
            $envMethods = $methods[SS_ENVIRONMENT_TYPE][0];
            $paymentField = new HiddenField('PaymentMethod', 'Payment Method', $envMethods);
        }
        $fields->push($paymentField);
        
        $actions = new FieldList(
                new FormAction('doNext', 'Next')
                );
        
        $required = new RequiredFields(
                'Name',
                'Email',
                'Phone',
                'ShippingFirstName',
                'ShippingSurName',
                'ShippingPhone',
                'ShippingStreetNumber',
                'ShippingStreetName',
                'ShippingCity',
                'ShippingCountry',
                'ShippingPostCode'
            );
        
        $form = new Form($this, 'orderForm', $fields, $actions, $required);
        
        if($data = unserialize(Session::get(TOSEPage::SessionOrderInfo))) {

            $form->loadDataFrom($data);
        }
        
        return $form;
    }
    
    /**
     * Function is the action of order form
     * @param type $data
     * @return type
     */
    public function doNext($data) {
        $sessionData = serialize($data);
        Session::set(TOSEPage::SessionOrderInfo, $sessionData);

        return $this->redirect($this->Link('confirm'));
    }
    
    /**
     * Function is to show confirm page
     * @return type
     */
    public function confirm() {
        $cart = TOSECart::get_current_cart();
        if ($cart->cartEmpty()) {
            return $this->redirect($this->Link()."cartEmpty");
        }
        $data = unserialize(Session::get(TOSEPage::SessionOrderInfo));
        $data['NeedInvoice'] = key_exists('NeedInvoice', $data) ? TRUE : FALSE;

        return $this->customise($data)->renderWith(array('TOSECheckoutPage_confirm', 'Page'));
    }
    
    public function multiPaymentMethod() {
        $methods = Config::inst()->get('PaymentProcessor', 'supported_methods');
        $envMethods = $methods[SS_ENVIRONMENT_TYPE];
        if(count($envMethods)>1) {
            return TRUE;
        }
        
        return FALSE;
    }

    
    /**
     * Function is to get the shipping fee
     * @return int
     */
    public function getShippingFee() {
        return 10;
    }
    
    /**
     * Function si to calculate the total amount of the order
     * @return type
     */
    public function totalAmount() {
        
        $productAmount = TOSECart::get_current_cart()->totalPrice();
        $shippingFee = $this->getShippingFee();
        $totalAmount = $productAmount + $shippingFee;
        return $totalAmount;
    }
    

    /**
     * Function is to process payment.
     * @param SS_HTTPRequest $request
     */
    public function doPay() {
        $orderInfo = unserialize(Session::get(TOSEPage::SessionOrderInfo));
        $method = $orderInfo['PaymentMethod'];

        $processor = PaymentFactory::factory($method);
        $processor->setRedirectURL($this->Link('result'));
        
//        Amount = 'Amount'
//        Currency = 'Currency'
//        Reference = 'Reference' (optional)

        //To create data for payment gateway
        $data = array(
            'Amount' => $this->totalAmount(),
            'Currency' => TOSECurrency::get_current_currency_name(),
            'Status' => TOSEOrder::PENDING,
            'Reference' => null
        );
        
	// Process the payment 
	$processor->capture($data);        
    }
    
    /**
     * Function is to save shipping address with order id
     * @param type $orderID
     * @param type $data
     * @return type
     */
    public function saveShippingAddress($orderID, $data) {
        $shippingInfo = array();

        $shippingInfo['FirstName'] = $data['ShippingFirstName'];
        $shippingInfo['SurName'] = $data['ShippingSurName'];
        $shippingInfo['Phone'] = $data['ShippingPhone'];
        $shippingInfo['StreetNumber'] = $data['ShippingStreetNumber'];
        $shippingInfo['StreetName'] = $data['ShippingStreetName'];
        $shippingInfo['Suburb'] = $data['ShippingSuburb'];
        $shippingInfo['City'] = $data['ShippingCity'];
        $shippingInfo['Region'] = $data['ShippingRegion'];
        $shippingInfo['Country'] = $data['ShippingCountry'];
        $shippingInfo['PostCode'] = $data['ShippingPostCode'];
        $shippingInfo['OrderID'] = $orderID;

        return TOSEShippingAddress::save($shippingInfo);
    }

    /**
     * Function is to save billing address with order id
     * @param type $orderID
     * @param type $data
     * @return type
     */
    public function saveBillingAddress($orderID, $data) {
        $billingInfo = array();
        
        $billingInfo['FirstName'] = $data['BillingFirstName'];
        $billingInfo['SurName'] = $data['BillingSurName'];
        $billingInfo['Phone'] = $data['BillingPhone'];
        $billingInfo['StreetNumber'] = $data['BillingStreetNumber'];
        $billingInfo['StreetName'] = $data['BillingStreetName'];
        $billingInfo['Suburb'] = $data['BillingSuburb'];
        $billingInfo['City'] = $data['BillingCity'];
        $billingInfo['Region'] = $data['BillingRegion'];
        $billingInfo['Country'] = $data['BillingCountry'];
        $billingInfo['PostCode'] = $data['BillingPostCode'];
        $billingInfo['OrderID'] = $orderID;

        return TOSEBillingAddress::save($billingInfo);
    }
    
    /**
     * Function is to save the order
     * @return type
     */
    public function saveOrder() {
        $orderInfo = unserialize(Session::get(TOSEPage::SessionOrderInfo));
        $orderData['Reference'] = TOSEOrder::create_reference();
        $orderData['NeedInvoice'] = array_key_exists('NeedInvoice', $orderInfo);
        $orderData['Status'] = TOSEOrder::PENDING;
        $orderData['ShippingFee'] = $this->getShippingFee();
        $orderData['CustomerName'] = $orderInfo['CustomerName'];
        $orderData['CustomerEmail'] = $orderInfo['CustomerEmail'];
        $orderData['CustomerPhone'] = $orderInfo['CustomerPhone'];
        $orderData['Comments'] = $orderInfo['Comments'];

        $order = TOSEOrder::save($orderData);

        $shippingAddress = $this->saveShippingAddress($order->ID, $orderInfo);
        $order->ShippingAddressID = $shippingAddress->ID;
        
        if(key_exists('NeedInvoice', $orderInfo)) {
            $billingAddress = $this->saveBillingAddress($order->ID, $orderInfo);
            $order->BillingAddressID = $billingAddress->ID;
        }

        $order->write();
        
        return $order;
        
    }
    


    /**
     * Function is the action after payment
     * @return type
     */
    public function result(){
        $payment = DataObject::get_one("Payment", "ID = " . (int)Session::get('PaymentID'));
        $order = DataObject::get_one('TOSEOrder', "Reference='$payment->Reference'");

        //To create default data
        $data = array(
            'IsSuccess' => false,
            'Status' => Payment::FAILURE,
            'Reference' => $payment->Reference
        );

        //use const variable SUCCESS not directly use string
        if($payment && $payment->Status === Payment::SUCCESS){

            //To call save order function to create order

            $order = $this->saveOrder();
            $data['IsSuccess'] = true;
            $data['Status'] = Payment::SUCCESS;
            $data['Reference'] = $order->Reference;
        
            //Clear cart and order information
            
            TOSECart::get_current_cart()->clearCart();
            Session::clear(TOSEPage::SessionOrderInfo);
            //Clear Payment ID from Session
            Session::clear("PaymentID");
        }


        return $this->customise($data);
     }
     
}