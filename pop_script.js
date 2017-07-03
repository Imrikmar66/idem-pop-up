var $ = jQuery;
$(document).ready(function(){

    if($('#responsive-disabled').length > 0){
        
        if( $(window).width() < 960 ){

            return;

        }

    }

    if( $('#idem-action-mode').length < 1 )
        return;

    if( $('#idem-action-mode').val() == 'auto' ){
        setTimeout(function(){
            $('#idem_pop_up_overlay, #idem_pop_up').fadeIn(250);
        }, 1000);
    }
    else{

        if($('#idem-action-caller').length < 1)
            return;
        
        target = $('#idem-action-caller').val();
        if(!target || target == '')
            return;
            
        $("."+target).click(function(){

            $('#idem_pop_up_overlay, #idem_pop_up').fadeIn(250);

        });

    } 
    
});
$(document).on(
    'click', 
    '#idem_pop_up_overlay, #idem_pop_up_close',
    function(){
        $('#idem_pop_up_overlay, #idem_pop_up').fadeOut(250);
    }
);