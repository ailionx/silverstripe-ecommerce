<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class TOSEOrderAdmin extends ModelAdmin {
    
    const PENDING = "Pending";
    const HISTORY = "History";
    
    private static $managed_models = array('TOSEOrder'); 
    private static $url_segment = 'orders';
    private static $menu_title = 'Orders';
    
    public $showImportForm = false;
}