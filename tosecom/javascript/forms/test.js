;(function($){

	$.entwine('ss', function($) {
            
                var _$modCategoryFields = $(".cms-edit-form .mod-category-fields");
                var _$modCategoryMoveSub = $(".cms-edit-form .mod-category-fields #moveSub");
                var _$modOptionMoveReal = $(".cms-edit-form .mod-category-fields #Form_ItemEditForm_modOptions");
                var _$modCategoryConfirm = $(".cms-edit-form .Actions .mod-category-confirm");
                var _$modCategorySave = $(".cms-edit-form .Actions #Form_ItemEditForm_action_doSave");
                var _$modCategoryDelete = $(".cms-edit-form .Actions .action-delete-mod, .cms-edit-form .Actions .action-delete");
                var _$modCategoryCancel = $(".cms-edit-form .mod-category-cancel");
                
//		$(".cms-edit-form .Actions .action-delete-mod").entwine({
		$('.ss-gridfield .col-buttons .action.gridfield-button-delete, .cms-edit-form .Actions button.action.action-delete-mod').entwine({
			onclick: function(e){
                            
                                if(_$modCategoryFields.get(0)){
//                    alert(_$modCategoryFields); return false;
                                        _$modCategoryFields.show();
                                        _$modCategoryConfirm.show();
                                        _$modCategoryCancel.show();
                                        _$modCategorySave.hide();
                                        _$modCategoryDelete.hide();

                                        checkOptionMove();

                                        return false;
                                }
			}
		});
                
                $(".cms-edit-form .Actions .action-test").entwine({
			onclick: function(e){
				if(!confirm('11')) {
					e.preventDefault();
					return false;
				} else {
					this._super(e);
				}
			}
		});

        //        $(".cms-edit-form .Actions .action-delete-mod").click(function(){
                $(".cms-edit-form .Actions .action-test").entwine({
                    
                    onclick: function(e){
                        alert(_$modCategoryFields); return false;
                        if(_$modCategoryFields.get(0)){

                            _$modCategoryFields.show();
                            _$modCategoryConfirm.show();
                            _$modCategoryCancel.show();
                            _$modCategorySave.hide();
                            _$modCategoryDelete.hide();

                            checkOptionMove();

                            return false;
                        }
                    }

                });

                function checkOptionMove() {
        //            console.log(_$modOptionMove.hasClass("result-selected"));
        //            console.log(typeof(_$modOptionMoveReal.val()));
                    if(_$modOptionMoveReal.val()==='1'){
                        _$modCategoryMoveSub.show();
                    } else {
                        _$modCategoryMoveSub.hide();
                    }
                }

                _$modOptionMoveReal.change(function(){
                    checkOptionMove();
                });		

               _$modCategoryCancel.click(function(){
                        _$modCategoryFields.hide();
                        _$modCategoryConfirm.hide();
                        _$modCategoryCancel.hide();
                        _$modCategorySave.show();
                        _$modCategoryDelete.show();
                });
	});
}(jQuery));
