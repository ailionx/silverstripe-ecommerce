<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class TOSEAccountPage extends TOSEPage {
    /**
     * Function is to get orders for current user
     * @return type
     */
    public function getMyOrders() {
        $memberID = Member::currentUserID();
        $myOrders = DataObject::get('TOSEOrder', "MemberID='$memberID'");

        return $myOrders;
    }
}

class TOSEAccountPage_Controller extends TOSEPage_Controller {
    

    
}
