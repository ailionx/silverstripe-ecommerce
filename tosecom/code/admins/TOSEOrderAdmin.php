<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class TOSEOrderAdmin extends ModelAdmin {
    
    const PENDING = "Pending";
    const HISTORY = "History";
    
//    private $order_type;
    
    private $className = 'TOSEOrder';
    
    
    private static $managed_models = array(
        'TOSEOrder',
        'TOSEHistoryOrder'
    ); 
    private static $url_segment = 'orders';
    private static $menu_title = 'Orders';
    
    /**
     * We shouldn't import any data into orders
     * @var type 
     */
    public $showImportForm = false;
    

    /**
     * OVERRIDE
     * @return type
     */
    public function getManagedModels() {            
            $models = array();
        
            $models['TOSEOrder'] = array('title' => 'Pending Orders');
            $models['TOSEHistoryOrder'] = array('title' => 'History Orders');

            return $models;
    }

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
        $fieldConfig = GridFieldConfig_RecordEditor::create($this->stat('page_length'))
            ->addComponent($exportButton)
            ->removeComponentsByType('GridFieldAddNewButton')
            ->removeComponentsByType('GridFieldFilterHeader')
            ->addComponents(new GridFieldPrintButton('before'));
        
        $listField = GridField::create(
                $this->sanitiseClassName($this->modelClass),
                false,
                $list,
                $fieldConfig 
        );
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