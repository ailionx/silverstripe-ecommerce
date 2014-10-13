<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class TOSEMemberAddress extends TOSEAddress {
    
    private static $db = array();
    
    private static $has_one = array(
        'Member' => 'Member'
    );
    
    public static function save($data) {
        
        $memberAddress = new TOSEMemberAddress();
        $memberAddress->update($data);
        $memberAddress->write();
        
        return $memberAddress;
    }
}
