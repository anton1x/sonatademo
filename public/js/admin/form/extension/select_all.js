(
    function ($) {

        $(document).ready(
            function () {
                $('.js-select-all').click(
                    function (e) {
                        var link = this;
                        var container = $(e.currentTarget).data('id');
                        $('#' + container).find("input[type=checkbox]").each(
                            function(){
                                var newValue = 'checked';
                                if($(link).data('unselect') == '1'){
                                    newValue = false;
                                }
                                $(this).prop('checked', newValue);
                                $(this).iCheck('update');
                            }
                        );
                    }
                )
            }
        );


    }
)(jQuery);