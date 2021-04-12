window.addEventListener("load", function () {
  document.querySelector('body').classList.add('loaded');
});

jQuery(document).ready(function(){
	"use strict";
	
	jQuery( 'footer select' ).addClass( 'custom-select' ).wrap( '<div class="form-group" />' );
	
	jQuery( '.wp-block-embed__wrapper' ).fitVids();
	
	// Quick Form Styling
	jQuery( 'input, textarea' ).not( 'input[type="submit"]' ).not( 'input[type="checkbox"]' ).addClass( 'form-control' );
	jQuery( 'input[type="submit"]:not([class])' ).addClass( 'btn btn-primary' );

	//Login form
	jQuery('#loginform input').each(function(){
		
		var $this = jQuery(this);
		
		if( 'user_pass' == $this.attr('id') || 'user_login' == $this.attr('id') ){
			$this.attr('placeholder', $this.prev().text());
		}
		
	});
	
	jQuery( 'blockquote' ).addClass( 'blockquote' );

	jQuery('section.elementor-element .decoration-wrapper-block:first-of-type').each(function(){
		jQuery(this).closest('section.elementor-section:not(.elementor-inner-section)').find('.elementor-element:not(.elementor-widget-tommusrhodus-decorations-block)').addClass('layer-2');
		jQuery(this).closest('section.elementor-section').addClass('o-hidden').append(this);
		jQuery(this).appendTo(jQuery(this).closest('section.elementor-section:not(.elementor-inner-section)') );
	});

	jQuery('.decoration-block').each(function(){
		jQuery(this).unwrap();
		jQuery(this).closest('.elementor-element').siblings().addClass('layer-2');		
		jQuery(this).appendTo(jQuery(this).closest('.elementor-column-wrap') );
	});

	jQuery('.job_filters .search_jobs input').each(function(){
		jQuery(this).addClass('form-control');
	});

	jQuery('.custom-select').after('<svg class="icon icon-sm" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12.416 14.376C12.2181 14.6728 11.7819 14.6728 11.584 14.376L8.51823 9.77735C8.29672 9.44507 8.53491 9 8.93426 9L15.0657 9C15.4651 9 15.7033 9.44507 15.4818 9.77735L12.416 14.376Z" fill="#2C3038"/></svg>');
 
  	jQuery('body').on('click', 'a[href*="#"]', function() {
	    var modalID = jQuery(this).attr('href');
	    if ( jQuery(modalID + '.modal').length > 0 ) {
		    jQuery(modalID).modal({show: true});
		}
	});
	
	/**
	 * Handle documentation upvoting.
	 */
	jQuery('body').on('click', '.btn-upvote', function(e){

		e.preventDefault();
		
		let $this = jQuery(this);
		
		if( $this.hasClass('disabled') ){
			return false;
		}
		
		jQuery.ajax({
			type: "POST",
			url: jumpstart_data.ajax_url,
			data: {
				action: 'tommusrhodus_update_docs_upvotes',
				docs_id: $this.attr('data-id'),
				nonce: jumpstart_data.ajax_nonce
			},
			error: function( response ) {
				 console.log( response );
			},
			success: function( response ) {
				jQuery( '[data-js-upvote-count]' ).attr( 'data-js-upvote-count', response ).text( response );
				$this.addClass( 'disabled' );
			}
		});
		
	});
	
	/**
	 * Handle documentation downvoting.
	 */
	jQuery('body').on('click', '.btn-downvote', function(e){

		e.preventDefault();
		
		let $this = jQuery(this);
		
		if( $this.hasClass('disabled') ){
			return false;
		}
		
		jQuery.ajax({
			type: "POST",
			url: jumpstart_data.ajax_url,
			data: {
				action: 'tommusrhodus_update_docs_downvotes',
				docs_id: $this.attr('data-id'),
				nonce: jumpstart_data.ajax_nonce
			},
			error: function( response ) {
				 console.log( response );
			},
			success: function( response ) {
				jQuery( '[data-js-downvote-count]' ).attr( 'data-js-downvote-count', response ).text( response );
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