<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class TOSERegisterPage extends TOSEPage {
    


}

class TOSERegisterPage_Controller extends TOSEPage_Controller {
    
    private static $allowed_actions = array(
        'registerForm',
        'success'
    );
    
    /**
     * Save the register information
     */
    const SessionRegisterInfo = 'TOSERegisterInfo';

    public function registerForm() {
        $fields = new FieldList();
        $userInfo = new CompositeField();
        $userInfo->push(new LiteralField('userInfo', '<h3>User Information</h3>'));
        $userInfo->push(new TextField('FirstName', 'FirstName'));
        $userInfo->push(new TextField('Surname', 'Surname'));
        $userInfo->push(new EmailField('Email', 'Email'));
        $userInfo->push(new PasswordField('Password', 'Password'));
        $userInfo->push(new PasswordField('ConfirmPassword', 'Confirm Password'));
        $userInfo->push(new TextField('Phone', 'Phone'));
        $fields->push($userInfo);
        
        $userAddress = new CompositeField();
        $userAddress->push(new LiteralField('userInfo', '<h3>Address Information</h3>'));
        $userAddress->push(new NumericField('StreetNumber', 'StreetNumber'));
        $userAddress->push(new TextField('StreetName', 'StreetName'));
        $userAddress->push(new TextField('Suburb', 'Suburb'));
        $userAddress->push(new TextField('City', 'City'));
        $userAddress->push(new TextField('Region', 'Region'));
        $userAddress->push(new TextField('Country', 'Country'));
        $userAddress->push(new NumericField('PostCode', 'PostCode'));
        $fields->push($userAddress);
        
        $actions = new FieldList(
                new FormAction('doRegister', 'Register')
                );
        
        $required = new RequiredFields(
                'FirstName',
                'Surname',
                'Email',
                'Phone',
                'StreetNumber',
                'StreetName',
                'City',
                'Country'
                );
        
        $form = new Form($this, 'registerForm', $fields, $actions, $required);
        
        if(TOSEMember::is_customer_login()) {
            return $this->redirect(TOSEPage::get_page_link('TOSEAccountPage'));
        }
        
        if($data = Session::get(self::SessionRegisterInfo)) {
            $form->loadDataFrom($data);
        }
        
        return $form;
    }

            
    public function checkMailExist($email){
        $sqlField = Convert::raw2sql($email);
        $existMemberInfo = DataObject::get_one('Member',"Email = '$sqlField'");
        return $result = $existMemberInfo ? TRUE : FALSE;
    }
    
    
    public function doRegister($data, $form) {
        
        Session::set(self::SessionRegisterInfo, $data);
        if ($this->checkMailExist($data['Email'])) {
            $fieldName = 'Email';
            $message = 'This Email Address has been registerd, please use another one or go to login';
            $messageType = 'validation-error';
            $form->addErrorMessage($fieldName, $message, $messageType);
            return $this->redirectBack();
        }
        
        if ($data['Password'] != $data['ConfirmPassword']) {
            $fieldName = 'ConfirmPassword';
            $message = 'The two passwords you typed do not match';
            $messageType = 'validation-error';
            $form->addErrorMessage($fieldName, $message, $messageType);
            return $this->redirectBack();
        }
        
        $member = TOSEMember::save($data);
        $groupCode = Config::inst()->get('Member', 'customerGroup');

        $data['MemberID'] = $member->ID;
        $address = TOSEMemberAddress::save($data);
        $member->AddressID = $address->ID;
        $member->addToGroupByCode($groupCode);
        $member->write();
        Session::clear(self::SessionRegisterInfo);
        
        return $this->redirect($this->Link()."success");
    }
    
}