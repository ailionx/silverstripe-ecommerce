<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class TOSECategory extends DataObject {
    
    private static $db = array(
        'Name' => 'Varchar(100)',
        'Link' => 'Varchar(100)'
    );
    
    private static $has_one = array(
        'ParentCategory' => 'TOSECategory'
    );
    
    private static $has_many = array(
        'ChildCategories' => 'TOSECategory',
        'Products' => 'TOSEProduct'
    );
    
    private static $summary_fields = array(
        'Name' => 'Name',
        'getParentCategoryName' => 'Parent Category'
    );
    
    public function getParentCategoryName() {
        
        if ($name = $this->ParentCategory()->Name) {
            return $name;
        } else {
            return 'Root';
        }
//        return $parent=$this->ParentCategory() ? $parent->Name : 'Root';
    }   
    
}
