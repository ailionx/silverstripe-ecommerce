<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class TOSEProduct extends DataObject {
    
    private static $db = array(
        'Name' => 'Varchar(100)',
        'Description' => 'Text',
        'Link' => 'Varchar(100)',
        'NewFrom' => 'Date',
        'NewTo' => 'Date',
        'Enabled' => 'Boolean'
    );
    
    private static $has_one = array(
        'Category' => 'TOSECategory'
    );
    
    private static $has_many = array(
        'Images' => 'TOSEImage',
        'Specs' => 'TOSESpec'
    );
    
    private static $default_spec;
    
    private static $default_image;


    public function getDefaultSpec() {
        return $this->default_spec;
    }
    
    public function setDefualtSpec($id) {
        $this->default_spec = $id;
    }
    
    public function getDefaultImage() {
        return $this->default_image;
    }
    
    public function setDefualtImage($id) {
        $this->default_image = $id;
    }
    
    public function isEnabled() {
        return $this->Enabled;
    }
}