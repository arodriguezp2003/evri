(function($) {
    "use strict";
    
    jQuery(document).ready(function() {
    	var markup = nbbfem_localize.markup;
    	var position = nbbfem_localize.position;
        var cookie_status = Cookies.get('nbbfem_status');
        var nbbfem_coockie_age_old = Cookies.get('nbbfem_coockie_age_old');
        var dont_show_after_close_status = $.trim($(markup).attr('data-nbbfem_dont_show_after_close_status'));
        var coockie_age = $(markup).attr('data-nbbfem_cookie_age') ? parseInt($(markup).attr('data-nbbfem_cookie_age')) : 7;
        
        // // update coockie
        if( dont_show_after_close_status == 'yes' ){
            if( (nbbfem_coockie_age_old != coockie_age) ){
                Cookies.set( 'nbbfem_status', '1', { expires: coockie_age } );
                cookie_status = 1;
            } else {
                Cookies.set( 'nbbfem_status', '0', { expires: coockie_age } );
                cookie_status = 0;
            }
        }

        // remove coockie
        if( dont_show_after_close_status != 'yes' ){
            Cookies.remove('nbbfem_status', { path: '/' } );
            cookie_status = 1;
        }

        if( cookie_status != '0'){
            if( position === 'bottom' ){
                $( "body" ).append( markup );
                $( "body" ).addClass( 'nbbfem_notice_open' );
            } else {
                $( "body" ).prepend( markup );
                $( "body" ).addClass( 'nbbfem_notice_open' );
            }
        }

        // close notice
        $( '.nbbfem_close_btn' ).on( 'click', function(){
            // hide notice
            $(this).parent().hide();

            $('body').removeClass('nbbfem_notice_open');
            $('body').addClass('nbbfem_notice_closed');

            // dont show later
            if( dont_show_after_close_status == 'yes' ){
                Cookies.set( 'nbbfem_status', '0', { expires: coockie_age } );
                Cookies.set('nbbfem_coockie_age_old', coockie_age, { expires: coockie_age });
            }

        });
    });
})(jQuery);