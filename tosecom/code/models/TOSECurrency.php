<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class TOSECurrency extends DataObject {
    
    private static $db = array(
        'Price' => 'Currency',
        'Currency' => 'Varchar(10)'
    );
}