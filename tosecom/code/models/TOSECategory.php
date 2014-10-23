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
        }
        
        return 'Root';
    }   
    
    /**
     * Function is to get all descendant categories in whole hierachy picture
     * @param type $categoryName
     * @param type $categories
     * @param type $level
     * @return type
     */
    public static function get_descendant_categories($categoryName, $categories=null, $level=0) {
        $categories = $categories ? $categories : new ArrayList();
        $category = DataObject::get_one('TOSECategory', "Name='$categoryName'");
        $childCategories = $category->ChildCategories();

        if (!$childCategories->count()) {
            return $categories;
        }
        $level++;
        foreach ($childCategories as $childCategory) {
            
            $childCategory->Level = $level;
            $categories->add($childCategory);
            self::get_descendant_categories($childCategory->Name, $categories, $level);
        }

        return $categories;
    }
    
    public static function get_ancestor_categories($categoryName, $categories=null, $level=0) {

        $categories = $categories ? $categories : new ArrayList();
        $category = DataObject::get_one('TOSECategory', "Name='$categoryName'");
        $parentCategory = $category->ParentCategory();
        $category->Level = $level;
        $categories->add($category);
        
        if($parentCategory->ID) {
            $parentCategory->Level = ++$level;
            self::get_ancestor_categories($parentCategory->Name, $categories, $level);            
//            return $categories;
        } 

        foreach ($categories as $item) {
            $item->Level = $level - $item->Level;
        }

        return $categories;
    }


    public function getProducts() {
        
    }
        

    
}
