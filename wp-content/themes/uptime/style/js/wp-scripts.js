window.addEventListener("load", function () {
  document.querySelector('body').classList.add('loaded');
  jQuery( window ).trigger( 'resize' );
  setTimeout( function(){
  	jQuery( window ).trigger( 'resize' );
  }, 600);
});

jQuery(document).ready(function(){
	"use strict";
	
	// WooCommerce
	jQuery( '.woocommerce-page input, .woocommerce-page textarea' ).addClass( 'form-control' );
	
	// Add reading position to Elementor
	jQuery( 'body > .elementor' ).attr( 'data-reading-position', '' );

	//Login form
	jQuery('#loginform input').each(function(){
		
		var $this = jQuery(this);
		
		if( 'user_pass' == $this.attr('id') || 'user_login' == $this.attr('id') ){
			if( $this.prev().text() != '' ){
				$this.attr( 'placeholder', $this.prev().text() );
			}
		}
		
	});

	//Form inputs
	jQuery( '.wpcf7-checkbox input[type="checkbox"], .wpcf7-radio input[type="radio"], .wpcf7-acceptance input[type="checkbox"]' ).each(function(index){
		
	    var input = jQuery(this),
	        label = input.siblings('label'),
	        id    = "input-assigned-"+index;
			
	    if(typeof input.attr('id') === typeof undefined || input.attr('id') === ""){
	        input.attr('id', id);
	        label.attr('for', id);
	    }else{
	        id = input.attr('id');
	        label.attr('for', id);
	    }
		
	    input.after(label);
		
	});
	jQuery( '.wpcf7-acceptance input[type="checkbox"]' ).addClass('custom-control-input').next().addClass( 'custom-control-label' );
	jQuery('.custom-radio-cols input[type="radio"]').each(function(index){
	    var input = jQuery(this),	        
	        labelText = input.siblings('span');
	    input.siblings('.custom-control-label').html(labelText);
	});

	jQuery('.widget select, #job_type').each(function(){
		jQuery(this).addClass('custom-select');
	});

	jQuery('#submit-job-form [name="submit_job"]').each(function(){
		jQuery(this).addClass('btn btn-primary');
	});

	jQuery('.widget_nav_menu ul, .widget_pages ul, .widget_categories ul').each(function(){
		jQuery(this).addClass('nav flex-column');
	});

	jQuery('.widget_nav_menu ul li, .widget_pages ul li, .widget_categories li').each(function(){
		jQuery(this).addClass('nav-item');
	});

	jQuery('.widget_nav_menu ul li a, .widget_pages ul li a, .widget_categories ul li a').each(function(){
		jQuery(this).addClass('nav-link');
	});

	jQuery('section.elementor-element .decoration-wrapper-block:first-of-type').each(function(){
		jQuery(this).closest('section.elementor-element').find('.elementor-element:not(.elementor-widget-tommusrhodus-decorations-block)').addClass('layer-2');
		jQuery(this).closest('section.elementor-element').addClass('o-hidden').append(this);
		jQuery(this).appendTo(jQuery(this).closest('section.elementor-element') );
	});

	jQuery('.decoration-block').each(function(){
		jQuery(this).unwrap();
		jQuery(this).closest('.elementor-element').siblings().addClass('layer-2');		
		jQuery(this).appendTo(jQuery(this).closest('.elementor-column-wrap') );
	});

	jQuery('.job_filters .search_jobs input').each(function(){
		jQuery(this).addClass('form-control');
	});

	/* FitVids */
	jQuery(".entry__content, .wp-block-embed__wrapper").fitVids();

	/**
	 * Navigation items spacing fix
	*/
	if( jQuery('.navbar-nav > li').length > 6 && jQuery('.navbar-nav > li').length < 8 || jQuery('.navbar-nav > li').length > 6 && jQuery('.navbar-nav > li').length == 7 ) {
		jQuery('.navbar-nav > li').addClass('shrink-menu-item-size-m');
	} else if( jQuery('.navbar-nav > li').length > 8 ) {
		jQuery('.navbar-nav > li').addClass('shrink-menu-item-size-s');
	}

	/* AMMEND STICKY ITEMS IF NAV IS ALSO STICKY */
	if ( jQuery('.navbar-container nav[data-sticky]').length ) {
		jQuery('body').addClass('has-sticky-nav');
	}

	jQuery('.navbar-toggler').on('click', function() {
	    jQuery('.navbar-container > nav').toggleClass('navbar-toggled-show');
	});

	jQuery('.wizard').smartWizard({
      transitionEffect: 'fade',
      showStepURLhash: false,
      toolbarSettings: {
        toolbarPosition: 'none'
      }
    });

	/**
	 * Handle documentation upvoting.
	 */
	jQuery('body').on('click', '.btn-upvote.btn-outline-primary[data-id]', function(e){

		e.preventDefault();
		
		let $this = jQuery(this);
		
		if( $this.hasClass('disabled') ){
			return false;
		}
		
		jQuery.ajax({
			type: "POST",
			url: uptime_data.ajax_url,
			data: {
				action: 'tommusrhodus_update_docs_upvotes',
				docs_id: $this.attr('data-id'),
				nonce: uptime_data.ajax_nonce
			},
			error: function( response ) {
				 console.log( response );
			},
			success: function( response ) {
				var totalCount = parseInt(jQuery('[data-js-downvote-count]').text()) + 1;
				jQuery( '[data-js-upvote-count]' ).attr( 'data-js-upvote-count', response ).text( response );
				jQuery( '[data-js-downvote-count]' ).attr( 'data-js-downvote-count', totalCount ).text( totalCount );
				$this.addClass( 'disabled' );
			}
		});
		
	});
	
	/**
	 * Handle documentation downvoting.
	 */
	jQuery('body').on('click', '.btn-downvote.btn-outline-primary[data-id]', function(e){

		e.preventDefault();
		
		let $this = jQuery(this);
		
		if( $this.hasClass('disabled') ){
			return false;
		}
		
		jQuery.ajax({
			type: "POST",
			url: uptime_data.ajax_url,
			data: {
				action: 'tommusrhodus_update_docs_downvotes',
				docs_id: $this.attr('data-id'),
				nonce: uptime_data.ajax_nonce
			},
			error: function( response ) {
				 console.log( response );
			},
			success: function( response ) {
				var totalCount = parseInt(jQuery('[data-js-downvote-count]').text()) + 1;
				jQuery( '[data-js-downvote-count]' ).attr( 'data-js-downvote-count', totalCount ).text( totalCount );
				$this.addClass( 'disabled' );
			}
		});
		
	});

});

jQuery(window).bind("pageshow", function(event) {
    if (event.originalEvent.persisted) {
        jQuery( 'body' ).removeClass( 'fade-page' );
    }
});