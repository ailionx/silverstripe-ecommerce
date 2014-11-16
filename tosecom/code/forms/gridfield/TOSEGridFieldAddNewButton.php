<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of GridFieldAddNewButtonCategory
 *
 * @author Shawn
 */
class TOSEGridFieldAddNewButton extends GridFieldAddNewButton{
    //put your code here

        public function __construct($targetFragment = 'before', $name=null) {
		$this->targetFragment = $targetFragment;
                $this->buttonName = $name;
	}
        
    	public function getHTMLFragments($gridField) {
		$singleton = singleton($gridField->getModelClass());

		if(!$singleton->canCreate()) {
			return array();
		}

		if(!$this->buttonName) {
			// provide a default button name, can be changed by calling {@link setButtonName()} on this component
			$objectName = $singleton->i18n_singular_name();
			$this->buttonName = _t('GridField.Add', 'Add {name}', array('name' => $objectName));                        
		}

		$data = new ArrayData(array(
			'NewLink' => Controller::join_links($gridField->Link('item'), 'new'),
			'ButtonName' => $this->buttonName,
		));

		return array(
			$this->targetFragment => $data->renderWith('GridFieldAddNewbutton'),
		);
	}
}
