<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class TOSEMember extends DataExtension {
    
    private static $db = array(
        'Phone' => 'Varchar(50)'
    );

    private static $has_one = array(
        'Address' => 'TOSEMemberAddress'
    );
    
    private static $has_many = array(
        'Orders' => 'TOSEOrder'
    );
    
    const NeedLoginYes = "Yes";
    
    const NeedLoginNo = "No";
    
    const NeedLoginBoth = "Both";
    
    const PermissionCode = "SITETREE_VIEW_ALL";

    public static function save($data) {
        
        $member = new Member();
        $member->update($data);
        $member->write();
        return $member;
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
    
    public static function need_login() {
        $options = array(
            self::NeedLoginYes,
            self::NeedLoginNo,
            self::NeedLoginBoth
        );
        $config = Config::inst()->get('Member', 'needLogin');
        foreach ($options as $option) {
            if($config === $option) {
                return $option;
            }
        }
        
        die("needLogin is not defined correctly, it only can be".self::NeedLoginYes.", ".self::NeedLoginNo." or ".self::NeedLoginBoth."please check config file");
    }
    
    public static function get_customer_group_code() {
        $code = Config::inst()->get('Member', 'customerGroup');
        $lcCode = strtolower($code);
        return strtolower($lcCode);
    }
    
    
    public static function check_purchase_permission() {
        if((self::need_login()===self::NeedLoginYes) && !self::is_customer_login()) {
            return FALSE;
        } else {
            return TRUE;
        }
    }
    
}
