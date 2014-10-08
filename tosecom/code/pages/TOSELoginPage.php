<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class TOSELoginPage extends TOSEPage {
    
    
}

class TOSELoginPage_Controller extends TOSEPage_Controller {
    
    private static $allowed_actions = array(
        'LoginForm'
    );


    public function LoginForm() {
        return parent::LoginForm();
        
    }
    
}