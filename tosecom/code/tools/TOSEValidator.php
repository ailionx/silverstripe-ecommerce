<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class TOSEValidator {
        
    /**
     * Function is to validate if data is number. Option: data is not zero
     * @param type $data
     * @param type $fields
     * @param type $nonZero
     */
    public static function data_is_number($data, $fields, $nonZero=FALSE) {
        if(is_array($fields)) {
            foreach ($fields as $field) {
                if(!is_numeric($data[$field])) {
                    die($field." must be number");
                }
                if($nonZero && ($data[$field]<=0)){
                    die($field." must bigger than zero");
                }
            }
        } elseif (is_string($fields)) {
            if(!is_numeric($data[$field])) {
                die($field." must be number");
            }
            if($nonZero && ($data[$field]==0)){
                die($field." cannot be zero");
            }
        } else {
            die("Wrong dataIsNumber input type");
        } 
    }
    
    
}