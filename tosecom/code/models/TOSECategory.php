<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class TOSECategory extends DataObject {
    
    private static $db = array(
        'Name' => 'Varchar(100)',
        'Chain' => 'Varchar(100)'
//        'Link' => 'Varchar(100)'
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
    public static function get_descendant_categories($categoryName, $categories=null, $level=1) {
        $categories = $categories ? $categories : new ArrayList();
        $category = DataObject::get_one('TOSECategory', "Name='$categoryName'");
        $childCategories = $category->ChildCategories();

        $category->Level = $level;
        $categories->add($category);
        
        if (!$childCategories->count()) {
            return $categories;
        }
        
        $level++;
        foreach ($childCategories as $childCategory) {

            self::get_descendant_categories($childCategory->Name, $categories, $level);
        }

        return $categories;
    }
    
    public function getAncerstorCategoriesID() {
        $id = $this->ID;
        $IDArray = array((string)$id);
//        $query = new SQLQuery();
//        $select = "select ID, Name, @pv:=ParentCategoryID as 'ParentCategoryID'";
//        $from = "(select * from TOSECategory order by ID DESC)reverse";
//        $join = "(select @pv:=$id)tmp";
//        $where = "ID=@pv";
//        $query->setSelect($select)->setFrom($from);
        $sql = "select ID, @pv:=ParentCategoryID as 'ParentCategoryID', ClassName, Created, LastEdited, Name from (select * from tosecategory where 1 order by ID DESC)reverse join (select @pv:=$id)tmp where ID=@pv";
        
        $result = DB::query($sql);
        var_dump($result); die;
        $IDArray = array_merge($IDArray, $result->column('ParentCategoryID'));

        return $IDArray;

    }


    public static function get_ancestor_categories($categoryName, $categories=null, $level=1) {
        $categories = $categories ? $categories : new ArrayList();
        $category = DataObject::get_one('TOSECategory', "Name='$categoryName'");
        $category->Level = $level;
        $categories->add($category);
        
        $parentCategory = $category->ParentCategory();
        if($parentCategory->ID) {
            $parentCategory->Level = ++$level;
            self::get_ancestor_categories($parentCategory->Name, $categories, $level);
        }
        
        return $categories;
    }
    
    public function getDescendant() {
        self::get_descendant_categories($this->Name);
    }

    public function getAncestors() {
        self::get_ancestor_categories($this->Name);
    }


    public function getDescendantProducts() {
        
        
        
//        $products = $this->Products();
//        $productsMap = $products->map('ID', 'Name');
//
//        $categories = self::get_descendant_categories($this->Name);
//        foreach ($categories as $category) {
//            $Maps = $category->Products()->map('ID', 'Name');
//            $productsMap = array_merge($productsMap, $Maps);
//        }
//        var_dump($productsMap); die;
//        return TOSEProduct::get_enabled_products($products);
    }
    

    
}
