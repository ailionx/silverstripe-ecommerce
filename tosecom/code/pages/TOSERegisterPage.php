<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
/*
		'FirstName' => 'Varchar',
		'Surname' => 'Varchar',
		'Email' => 'Varchar(256)', // See RFC 5321, Section 4.5.3.1.3.
        'Phone' => 'Varchar(100)',
        'StreetNumber' => 'Int',
        'StreetName' => 'Varchar(100)',
        'Suburb' => 'Varchar(100)',
        'City' => 'Varchar(100)',
        'Region' => 'Varchar(100)',
        'Country' => 'Varchar(100)',
        'PostCode' => 'Int'
 */
class TOSERegisterPage extends TOSEPage {
    


}

class TOSERegisterPage_Controller extends TOSEPage_Controller {
    
    private static $allowed_actions = array(
        'registerForm',
        'doRegister'
    );


    public function registerForm() {
        $fields = new FieldList();
        $userInfo = new CompositeField();
        $userInfo->push(new LiteralField('userInfo', '<h3>User Information</h3>'));
        $userInfo->push(new TextField('FirstName', 'FirstName'));
        $userInfo->push(new TextField('Surname', 'Surname'));
        $userInfo->push(new TextField('Email', 'Email'));
        $userInfo->push(new TextField('Phone', 'Phone'));
        $fields->push($userInfo);
        
        $userAddress = new CompositeField();
        $userAddress->push(new LiteralField('userInfo', '<h3>Address Information</h3>'));
        $userAddress->push(new TextField('StreetNumber', 'StreetNumber'));
        $userAddress->push(new TextField('StreetName', 'StreetName'));
        $userAddress->push(new TextField('Suburb', 'Suburb'));
        $userAddress->push(new TextField('City', 'City'));
        $userAddress->push(new TextField('Region', 'Region'));
        $userAddress->push(new TextField('Country', 'Country'));
        $userAddress->push(new TextField('PostCode', 'PostCode'));
        $fields->push($userAddress);
        
        $actions = new FieldList(
                new FormAction('doRegister', 'Register')
                );
        
        $form = new Form($this, 'registerForm', $fields, $actions);
        
        if(TOSEMember::is_customer_login()) {
            return $this->redirect($this->getEcommerceRootPageLink()."/".Config::inst()->get('TOSEAccountPage', 'pageURLSegment'));
        }
        return $form;
    }

            
    public function checkMailExist($email){
        $sqlField = Convert::raw2sql($email);
        $existMemberInfo = DataObject::get_one('Member',"Email = '$sqlField'");
        return $result = $existMemberInfo ? TRUE : FALSE;
    }
    
    public function doRegister($data, $form) {
        
        var_dump($data); die();
        
        if ($this->checkMailExist($data['Email'])) {
            $fieldName = 'Email';
            $message = 'This Email Address has been registerd, please use another one or go to login';
            $messageType = 'validation-error';
            $form->addErrorMessage($fieldName, $message, $messageType);
            return $this->redirectBack();
        }
        
        $member = TOSEMember::save($data);
        $data['MemberID'] = $member->ID;
        TOSEAddress::save($data);
        
        return $this->redirect($this->getEcommerceRootPageLink()."/".Config::inst()->get('TOSELoginPage', 'pageURLSegment'));
    }
    
}