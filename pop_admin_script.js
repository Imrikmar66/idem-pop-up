var $ = jQuery;
$(document).ready(function(){
    
    var $select = $("#idem_pop_up_admin_select");
    var $check = $("#idem_pop_up_admin_check");

    if($select.length > 0 && $check.length > 0) {

        if($check.is(":checked")){
            $select.prop("disabled", false);
        }
        else {
            $select.prop("disabled", true);
        }

        $check.change(function(){

            if($(this).is(":checked")){
                $select.prop("disabled", false);
            }
            else {
                $select.prop("disabled", true);
            }

        })

    };

})