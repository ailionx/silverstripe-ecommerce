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
    
    
    private static $managed_models = array('TOSEOrder'); 
    private static $url_segment = 'orders';
    private static $menu_title = 'Orders';
    
    public $showImportForm = false;
    
    
//    public function init() {
//        parent::init();
//        $status = $this->request->getVar('type');
//        $this->order_type = $status;
//    }


    public function canDelete() {
        return false;
    }
    
    protected function getManagedModelTabs() {
        $tabs = new ArrayList();

        $tabs->push(new ArrayData(array(
            'Title'     => 'Pending Orders',
            'ClassName' => $this->className,
            'Link' => $this->Link($this->sanitiseClassName($this->className))."?type=" . self::PENDING,
            'LinkOrCurrent' => ($this->request->getVar('type') == self::PENDING) ? 'current' : 'link'
        )));
        
        $tabs->push(new ArrayData(array(
            'Title'     => 'History Orders',
            'ClassName' => $this->className,
            'Link' => $this->Link($this->sanitiseClassName($this->className))."?type=" . self::HISTORY,
            'LinkOrCurrent' => ($this->request->getVar('type') == self::HISTORY) ? 'current' : 'link'
        )));
//        var_dump($tabs); die;
        
        return $tabs;
    }
    
    public function getList() {
        $list = parent::getList();
        $status = $this->request->getVar('type');
        switch ($status) {
            case self::PENDING: 
                $thisList = $list->where("Status='".TOSEOrder::PENDING."'");
                break;
            case self::HISTORY: 
                $thisList = $list->where("Status='".TOSEOrder::DELIVERED."'");
                break;
            default :
                $thisList = $list;
        }

        return $thisList;
                
    }
    
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