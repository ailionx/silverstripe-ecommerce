<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class TOSEPromotion extends DataObject{
    
    private static $db = array(
        'PromotionType' => "ENUM('Discount, WholeSale, Combination', 'Discount')",
        'CalculateMethod' => "ENUM('Percentage, Reduction, FixedPrice', 'Percentage')",
        'FromDate' => 'Date',
        'ToDate' => 'Date',
        'WholeSaleMinAmount' => 'Int',
        'CombinationWith' => 'Varchar(100)',
        'CombinationName' => 'Varchar(100)',
        'Percentage' => 'Percentage',
        'Reduction' => 'Currency',
        'FixedPrice' => 'Currency'
    );
    
    private static $has_one = array(
        'Spec' => 'TOSESpec'
    );
    
    private static $has_many = array(
        
    );

    public function calculatePrice($value) {
        
    }
    
    public function inPeriod(){
    }
    
}