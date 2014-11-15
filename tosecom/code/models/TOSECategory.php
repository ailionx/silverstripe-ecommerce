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
     * Function is to get the all the ancerstor categories IDs of an given category with sql
     * @return type
     */
//    public function getAncerstorCategoriesID() {
//        $id = $this->ID;
//        $IDArray = array((string)$id);
//        $sql = "select ID, @pv:=ParentID as 'ParentID', ClassName, Created, LastEdited, Name from (select * from tosecategory where 1 order by ID DESC)reverse join (select @pv:=$id)tmp where ID=@pv";        
//        $result = DB::query($sql);
//
//        $IDArray = array_merge($IDArray, $result->column('ParentID'));
//
//        return $IDArray;
//
//    }

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
        $categories = $this->getAncestorCategories($this->Name)->sort('Level DESC');
        $IDs = $categories->column('ID');
        $chain = implode('-', $IDs);
        return $chain;
    }

    /**
     * Function is to get all products belongs to this category and all descendant categories
     * @return type
     */
    public function getAllProducts() {

        $products = DataObject::get('TOSEProduct')
                ->innerJoin(
                        'TOSECategory', 
                        "(TOSECategory.Chain LIKE '{$this->Chain}-%' OR TOSECategory.Chain='{$this->Chain}') AND TOSEProduct.CategoryID = TOSECategory.ID"
                );
        
        return TOSEProduct::get_enabled_products($products);
        
    }

        /**
     * Function is to customize cms fields
     * @return type
     */
    public function getCMSFields() {
       $fields = parent::getCMSFields();
       $fields->removeByName(array('Chain', 'Link', 'ChildCategories', 'Products'));
       $fields->replaceField('ParentID', $categoryField = new TreeDropdownField('ParentID', 'Parent', "TOSECategory", 'ID', 'Name', FALSE));
//       $fields->replaceField('Chain', new HiddenField('Chain', '', $this->getCategoryChain()));
// add child category gridfield
        if ($this->ID) {
            $gridFieldConfig = GridFieldConfig_RelationEditor::create();
            $gridField = new GridField("ChildCategories", "Child Categories", $this->ChildCategories(), $gridFieldConfig);
            $fields->addFieldToTab('Root.Main', $gridField);   
            
            $gridFieldConfig = GridFieldConfig_RelationEditor::create();
            $gridField = new GridField("Products", "Products", $this->Products(), $gridFieldConfig);
            $fields->addFieldToTab('Root.Main', $gridField);  
        }

         
         
       return $fields;
       
    }
    
    /**
     * Function is to update category chain and product category ID when one category is deleted
     * @param type $deleteID
     * @param type $newID
     */
    private static function update_category_chain($deleteID, $newID) {
        $products = DataObject::get('TOSEProduct', "CategoryID='$deleteID'");
        $categories = DataObject::get('TOSECategory', "Chain like '%-{$deleteID}-%'");
        if ($products->count()) {
            foreach ($products as $product) {
                $product->CategoryID = $newID;
                $product->write();
            }
        }
        
        if ($categories->count()) {
            foreach ($categories as $category) {
                $category->ParentID = $newID;
                $category->Chain = $category->getCategoryChain();
                $category->write();
            }
        }
        
    }
    
    /**
     * Function is to write in link and chain information for category
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
    
    protected function onBeforeDelete() {
        parent::onBeforeDelete();
        self::update_category_chain($this->ID, $this->ParentID);
    }

    
    
}
