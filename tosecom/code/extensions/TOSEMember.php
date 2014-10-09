<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class TOSEMember extends DataExtension {
    
    private static $db = array();

    private static $has_one = array(
        'Address' => 'TOSEAddress'
    );
    
    private static $has_many = array(
        'Orders' => 'TOSEOrder'
    );
    
    public static function save($data) {
        
        $member = new TOSEMember();
        $member->update($data);
        $member->write();
        
        $data['MemberID'] = $member->ID;
        TOSEAddress::save($data);
    } 
    
    /**
     * Function is to check if customer account logged in
     * @return boolean
     */
    public static function is_customer_login() {
        $customerGroup = Config::inst()->get('Member', 'customerGroup');
        if(!$member = Member::currentUser()) {
            return FALSE;
        }
        return $member->inGroup($customerGroup);
    }
    
    public static function logout() {
        $member = Member::currentUser();
        $member->logOut();
    }
}
