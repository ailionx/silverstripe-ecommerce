<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of TOSEGridFieldDetailForm
 *
 * @author Shawn
 */


class TOSECategoryGridFieldDetailForm_ItemRequest extends GridFieldDetailForm_ItemRequest{
    
        private static $allowed_actions = array(
		'edit',
		'view',
		'ItemEditForm'
	);
                
        public function ItemEditForm() {

		$list = $this->gridField->getList();

		if (empty($this->record)) {
			$controller = $this->getToplevelController();
			$noActionURL = $controller->removeAction($_REQUEST['url']);
			$controller->getResponse()->removeHeader('Location');   //clear the existing redirect
			return $controller->redirect($noActionURL, 302);
		}

		$canView = $this->record->canView();
		$canEdit = $this->record->canEdit();
		$canDelete = $this->record->canDelete();
		$canCreate = $this->record->canCreate();

		if(!$canView) {
			$controller = $this->getToplevelController();
			// TODO More friendly error
			return $controller->httpError(403);
		}

		$actions = new FieldList();
		if($this->record->ID !== 0) {
			if($canEdit) {
				$actions->push(FormAction::create('doSave', _t('GridFieldDetailForm.Save', 'Save'))
					->setUseButtonTag(true)
					->addExtraClass('ss-ui-action-constructive')
					->setAttribute('data-icon', 'accept'));
			}
                        
                                

                        
			if($canDelete) {
//                                    $actions->push(FormAction::create('doDelete', _t('GridFieldDetailForm.Delete', 'Delete'))
//					->setUseButtonTag(true)
//					->addExtraClass('ss-ui-action-destructive action-delete'));
                                
                                if(!$this->record->categoryEmpty()) {
                                    
                                    $actions->push(FormAction::create('doDelete', _t('GridFieldDetailForm.Delete', 'Delete'))
					->setUseButtonTag(true)
					->addExtraClass('ss-ui-action-destructive action-delete-mod'));
                                    
                                    $actions->push(FormAction::create('doModCategory', _t('TOSE_Admin.Gridfield.GridFieldDetailForm.ModCategory', 'Confirm'))
                                        ->setUseButtonTag(true)
					->addExtraClass('ss-ui-action-constructive mod-category-confirm')
                                        ->setAttribute('data-icon', 'accept')); 
                                    
                                    $actions->push(new LiteralField(
                                            'CancelModCategory',
                                            '<button class="ss-ui-button ss-ui-action-destructive ui-corner-all mod-category-cancel">Cancel</button>'
                                        ));
                                    
                                } else {
                                    $actions->push(FormAction::create('doDelete', _t('GridFieldDetailForm.Delete', 'Delete'))
					->setUseButtonTag(true)
					->addExtraClass('ss-ui-action-destructive action-delete'));
                                }
                                
                                $actions->push(new LiteralField(
                                            'testAction',
                                            '<button class="ss-ui-button ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only ss-ui-action-destructive action-test">Test</button>'
                                        ));
			}                        


		}else{ // adding new record
			//Change the Save label to 'Create'
			$actions->push(FormAction::create('doSave', _t('GridFieldDetailForm.Create', 'Create'))
				->setUseButtonTag(true)
				->addExtraClass('ss-ui-action-constructive')
				->setAttribute('data-icon', 'add'));
				
			// Add a Cancel link which is a button-like link and link back to one level up.
			$curmbs = $this->Breadcrumbs();
			if($curmbs && $curmbs->count()>=2){
				$one_level_up = $curmbs->offsetGet($curmbs->count()-2);
				$text = sprintf(
					"<a class=\"%s\" href=\"%s\">%s</a>",
					"crumb ss-ui-button ss-ui-action-destructive cms-panel-link ui-corner-all", // CSS classes
					$one_level_up->Link, // url
					_t('GridFieldDetailForm.CancelBtn', 'Cancel') // label
				);
				$actions->push(new LiteralField('cancelbutton', $text));
			}
		}

		$fields = $this->component->getFields();
                
		if(!$fields) $fields = $this->record->getCMSFields();

		// If we are creating a new record in a has-many list, then
		// pre-populate the record's foreign key. Also disable the form field as
		// it has no effect.
		if($list instanceof HasManyList) {
			$key = $list->getForeignKey();
			$id = $list->getForeignID();

			if(!$this->record->isInDB()) {
				$this->record->$key = $id;
			}

			if($field = $fields->dataFieldByName($key)) {
				$fields->makeFieldReadonly($field);
			}
		}

		// Caution: API violation. Form expects a Controller, but we are giving it a RequestHandler instead.
		// Thanks to this however, we are able to nest GridFields, and also access the initial Controller by
		// dereferencing GridFieldDetailForm_ItemRequest->getController() multiple times. See getToplevelController
		// below.
		$form = new Form(
			$this,
			'ItemEditForm',
			$fields,
			$actions,
			$this->component->getValidator()
		);
		
		$form->loadDataFrom($this->record, $this->record->ID == 0 ? Form::MERGE_IGNORE_FALSEISH : Form::MERGE_DEFAULT);

		if($this->record->ID && !$canEdit) {
			// Restrict editing of existing records
			$form->makeReadonly();
			// Hack to re-enable delete button if user can delete
			if ($canDelete) {
				$form->Actions()->fieldByName('action_doDelete')->setReadonly(false);
			}
		} elseif(!$this->record->ID && !$canCreate) {
			// Restrict creation of new records
			$form->makeReadonly();
		}

		// Load many_many extraData for record.
		// Fields with the correct 'ManyMany' namespace need to be added manually through getCMSFields().
		if($list instanceof ManyManyList) {
			$extraData = $list->getExtraData('', $this->record->ID);
			$form->loadDataFrom(array('ManyMany' => $extraData));
		}
		
		// TODO Coupling with CMS
		$toplevelController = $this->getToplevelController();
		if($toplevelController && $toplevelController instanceof LeftAndMain) {
			// Always show with base template (full width, no other panels), 
			// regardless of overloaded CMS controller templates.
			// TODO Allow customization, e.g. to display an edit form alongside a search form from the CMS controller
			$form->setTemplate('LeftAndMain_EditForm');
			$form->addExtraClass('cms-content cms-edit-form center');
			$form->setAttribute('data-pjax-fragment', 'CurrentForm Content');
			if($form->Fields()->hasTabset()) {
				$form->Fields()->findOrMakeTab('Root')->setTemplate('CMSTabSet');
				$form->addExtraClass('cms-tabset');
			}

			$form->Backlink = $this->getBackLink();
		}

		$cb = $this->component->getItemEditFormCallback();
		if($cb) $cb($form, $this);
		$this->extend("updateItemEditForm", $form);
		return $form;
	}
        
        
        public function doDelete($data, $form) {
		$title = $this->record->Title;
		try {
			if (!$this->record->canDelete()) {
				throw new ValidationException(
					_t('GridFieldDetailForm.DeletePermissionsFailure',"No delete permissions"),0);
			}

//			$this->record->delete();
		} catch(ValidationException $e) {
			$form->sessionMessage($e->getResult()->message(), 'bad');
			return $this->getToplevelController()->redirectBack();
		}

		$message = sprintf(
			_t('GridFieldDetailForm.Deleted', 'Deleted %s %s'),
			$this->record->i18n_singular_name(),
			htmlspecialchars($title, ENT_QUOTES)
		);
		
		$toplevelController = $this->getToplevelController();
		if($toplevelController && $toplevelController instanceof LeftAndMain) {
			$backForm = $toplevelController->getEditForm();
			$backForm->sessionMessage($message, 'good');
		} else {
			$form->sessionMessage($message, 'good');
		}

		//when an item is deleted, redirect to the parent controller
		$controller = $this->getToplevelController();
		$controller->getRequest()->addHeader('X-Pjax', 'Content'); // Force a content refresh

		return $controller->redirect($this->getBacklink(), 302); //redirect back to admin section
	}
        
        public function doModCategory($data, $form) {

            $descendantCategories = $this->record->getDescendantCategories();
            $allProducts = $this->record->getAllProducts();
            
            if($data['modOptions'] == '0') {
                
                foreach ($descendantCategories as $category) {
                    $category->ParentID = 0;
                    $category->write();
//                    $category->delete();
                }
                foreach ($allProducts as $product) {
                    $product->CategoryID = 0;
                    $product->write();
//                    $product->delete();
                }
                
            } elseif (($data['modOptions'] == '1') && $data['moveSub']) {
                $parentID = $data['moveSub'];
                $this->record->moveDescendants($parentID);
            }
            
            $controller = $this->getToplevelController();
            return $this->edit($controller->getRequest());
        }
}
