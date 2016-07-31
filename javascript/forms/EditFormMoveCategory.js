;(function($){

                $.entwine('ss', function($){
                
                    $('.mod-category-pop').entwine({
                        
                        onmatch: function(){
                            
                            var _$modOptionsFeild = $("#Form_ItemEditForm_modOptions");
                            var _$modCategoryMoveField = $("#moveSub");
                            var _$modCategoryElements = $(".mod-category-elements");
                            var _$modCategoryOrigin = $(".mod-category-origin");
                            var _$modCategoryPop = $(".mod-category-pop");
                            
                            
                            function checkOptionMove() {
                                if(_$modOptionsFeild.val()==='1'){
                                    _$modCategoryMoveField.show();
                                } else {
                                    _$modCategoryMoveField.hide();
                                }
                            }
                            
                            $('.mod-category-delete').entwine({

                                onclick: function(e){
                                    _$modCategoryElements.hide();
                                    _$modCategoryPop.show();

                                    checkOptionMove();
                                }
                            });


                            $(".mod-category-cancel").entwine({

                                onclick: function(e){

                                    _$modCategoryElements.hide();
                                    _$modCategoryOrigin.show();

                                    return false;
                                }

                            });

                            _$modOptionsFeild.change(function(){
                                checkOptionMove();
                            });
                        }
                    });
                    

                    
                });


}(jQuery));
