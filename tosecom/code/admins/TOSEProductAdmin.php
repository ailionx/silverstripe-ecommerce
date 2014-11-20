<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class TOSEProductAdmin extends ModelAdmin {
    
    private static $managed_models = array('TOSEProduct', 'TOSECategory'); 
    private static $url_segment = 'products';
    private static $menu_title = 'Products';
    
    
    
    /**
     * OVERRIDE
     * @param type $id
     * @param type $fields
     * @return type
     */
    	public function getEditForm($id = null, $fields = null) {
		$list = $this->getList();
		$exportButton = new GridFieldExportButton('before');
		$exportButton->setExportColumns($this->getExportFields());
                
		$listField = GridField::create(
			$this->sanitiseClassName($this->modelClass),
			false,
			$list,
			$fieldConfig = GridFieldConfig_RecordEditor::create($this->stat('page_length'))
				->addComponent($exportButton)
				->removeComponentsByType('GridFieldFilterHeader')
				->addComponents(new GridFieldPrintButton('before'))
                                ->removeComponentsByType('GridFieldDeleteAction')

		);
                if($this->modelClass === 'TOSECategory') {
                    $fieldConfig->getComponentByType('GridFieldDetailForm')->setItemRequestClass('TOSECategoryGridFieldDetailForm_ItemRequest');
                    $fieldConfig->getComponentByType('GridFieldAddNewButton')->setButtonName('Add New Category');

                } else {
                    $fieldConfig->getComponentByType('GridFieldAddNewButton')->setButtonName('Add New Product');
                }
                
		// Validation
		if(singleton($this->modelClass)->hasMethod('getCMSValidator')) {
			$detailValidator = singleton($this->modelClass)->getCMSValidator();
			$listField->getConfig()->getComponentByType('GridFieldDetailForm')->setValidator($detailValidator);
		}

		$form = CMSForm::create( 
			$this,
			'EditForm',
			new FieldList($listField),
			new FieldList()
		)->setHTMLID('Form_EditForm');
		$form->setResponseNegotiator($this->getResponseNegotiator());
		$form->addExtraClass('cms-edit-form cms-panel-padded center');
		$form->setTemplate($this->getTemplatesWithSuffix('_EditForm'));
		$editFormAction = Controller::join_links($this->Link($this->sanitiseClassName($this->modelClass)), 'EditForm');
		$form->setFormAction($editFormAction);
		$form->setAttribute('data-pjax-fragment', 'CurrentForm');

		$this->extend('updateEditForm', $form);
		
		return $form;
	}
}