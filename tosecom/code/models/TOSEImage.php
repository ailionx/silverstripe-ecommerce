<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class TOSEImage extends Image {
    
    private static $db = array();

    private static $has_one = array(
        'Product' => 'TOSEProduct'
    );
    
    private static $has_many = array();
}
