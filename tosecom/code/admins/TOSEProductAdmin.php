<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class TOSEProductAdmin extends ModelAdmin {
    
    private static $managed_models = array('TOSEProduct', 'TOSECategory'); 
    private static $url_segment = 'products';
    private static $menu_title = 'Products';
}