<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class TOSEAddress extends DataObject {
    
    private static $db = array(
        'FirstName' => 'Varchar(100)',
        'SurName' => 'Varchar(100)',
        'Phone' => 'Varchar(100)',
        'Email' => 'Varchar(100)',
        'StreetNumber' => 'Int',
        'StreetName' => 'Varchar(100)',
        'Suburb' => 'Varchar(100)',
        'City' => 'Varchar(100)',
        'Region' => 'Varchar(100)',
        'Country' => 'Varchar(100)',
        'PostCode' => 'Int'
    );
    
    private static $has_one = array();
    
    private static $has_many = array();
}