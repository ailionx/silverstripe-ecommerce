<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class TOSECategory extends DataObject {
    
    private static $db = array(
        'Name' => 'Varchar(100)',
        'Chain' => 'Varchar(100)',
        'Link' => 'Varchar(100)'
    );
    
    private static $has_one = array(
        'Parent' => 'TOSECategory'
    );
    
    private static $has_many = array(
        'ChildCategories' => 'TOSECategory',
        'Products' => 'TOSEProduct'
    );
    
    private static $summary_fields = array(
        'Name' => 'Name',
        'getParentName' => 'Parent Category',
        'Link' => 'Link'
    );
    
    
    /**
     * Function is to get the parent name of a category, return "Root" if doesn't have a parent category
     * @return string
     */
    public function getParentName() {
        
        if ($name = $this->Parent()->Name) {
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
    public function getDescendantCategories($categoryName, $categories=null, $level=1) {
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

            $this->getDescendantCategories($childCategory->Name, $categories, $level);
        }

        return $categories;
    }
    
    /**
     * Function is to get the all the ancerstor categories IDs of an given category
     * @return type
     */
    public function getAncerstorCategoriesID() {
        $id = $this->ID;
        $IDArray = array((string)$id);
        $sql = "select ID, @pv:=ParentID as 'ParentID', ClassName, Created, LastEdited, Name from (select * from tosecategory where 1 order by ID DESC)reverse join (select @pv:=$id)tmp where ID=@pv";
        
        $result = DB::query($sql);

        $IDArray = array_merge($IDArray, $result->column('ParentID'));

        return $IDArray;

    }

    /**
     * Function is to get ancoestor categories of a given category
     * @param type $categoryName
     * @param type $categories
     * @param type $level
     * @return type
     */
    public function getAncestorCategories($categoryName, $categories=null, $level=1) {
        $categories = $categories ? $categories : new ArrayList();
        if($level == 1) {
            $category = $this;
        } else {
            $category = DataObject::get_one('TOSECategory', "Name='$categoryName'");
        }

        $category->Level = $level;
        $categories->add($category);
        
        $parent = $category->Parent();
        if($parent->ID) {
            $parent->Level = ++$level;
            $this->getAncestorCategories($parent->Name, $categories, $level);
        }
        
        return $categories;
    }
    
    /**
     * Function is to get the chain information of this category
     * @return string
     */
    public function getCategoryChain() {
        $categories = $this->getAncestorCategories($this->Name)->sort('Level');
        $IDs = $categories->column('ID');
        $chain = '-'.implode('-', $IDs).'-';
        return $chain;
    }

    /**
     * Function is to get all products belongs to this category and all descendant categories
     * @return type
     */
    public function getAllProducts() {

        $products = DataObject::get('TOSEProduct')->innerJoin('TOSECategory', "TOSECategory.Chain like '%-{$this->ID}-%' AND TOSEProduct.CategoryID = TOSECategory.ID");
        
        return TOSEProduct::get_enabled_products($products);
        
    }

        /**
     * Function is to customize cms fields
     * @return type
     */
    public function getCMSFields() {
       $fields = parent::getCMSFields();
       $fields->removeByName('Chain');
       $fields->removeByName('Link');
//       $fields->replaceField('Chain', new HiddenField('Chain', '', $this->getCategoryChain()));
       
       return $fields;
       
    }
    
    /**
     * Function is to write in link for category
     */
    protected function onBeforeWrite() {
        parent::onBeforeWrite();
                //To setup link
        $link = $this->Name;
        //Lower case everything
        $link = strtolower($link);
        //Make alphanumeric (removes all other characters)
        $link = preg_replace("/[^a-z0-9_\s-]/", "", $link);
        //Clean up multiple dashes or whitespaces
        $link = preg_replace("/[\s-]+/", " ", $link);
        //Convert whitespaces and underscore to dash
        $link = preg_replace("/[\s_]/", "-", $link);
        
        $this->Link = $link;
        
        $chain = $this->getCategoryChain();
        $this->Chain = $chain;
    }
    
    /**
     * Function is to write in chain information for category
     */
//    public function onAfterWrite() {
//        parent::onAfterWrite();
//        if (!$this->Chain) {
//            $chain = $this->getCategoryChain();
//            var_dump($chain);die;
//            $this->Chain = $chain;
//            $this->write();
//        }
//    }
    
//    public function updateCategoryChain($id) {
//        $categories = DataObject::get('TOSECategory', "Chain like %-$id%");
//        
//    }
    
    
}
