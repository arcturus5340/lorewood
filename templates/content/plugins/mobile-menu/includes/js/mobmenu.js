
  /*
    *
    *   Javascript Functions
    *   ------------------------------------------------
    *   WP Mobile Menu
    *   Copyright WP Mobile Menu 2017 - http://www.wpmobilemenu.com
    *
    *
    */

    
   "use strict";
    
   (function ($) {
      jQuery( document ).ready( function() {

        jQuery( document ).on( 'click', '.show-nav-right .mobmenu-push-wrap,  .show-nav-right .mobmenu-overlay', function ( e ) { 
  
          e.preventDefault();
          jQuery( '.mobmenu-right-bt' ).first().trigger( 'click' );
          e.stopPropagation();
  
        });
          
        jQuery( document ).on( 'click', '.show-nav-left .mobmenu-push-wrap,  .show-nav-left .mobmenu-overlay', function ( e ) { 
  
          e.preventDefault();
          jQuery( '.mobmenu-left-bt' ).first().trigger( 'click' );
          e.stopPropagation();
  
        });
    
        
        if ( jQuery( 'body' ).find( '.mobmenu-push-wrap' ).length <= 0 &&  jQuery( 'body' ).hasClass( 'mob-menu-slideout' ) ) {
          jQuery( 'body' ).wrapInner( '<div class="mobmenu-push-wrap"></div>' );
          jQuery( '.mobmenu-push-wrap' ).after( jQuery( '.mob-menu-left-panel' ).detach() );
          jQuery( '.mobmenu-push-wrap' ).after( jQuery( '.mob-menu-right-panel' ).detach() );
          jQuery( '.mobmenu-push-wrap' ).after( jQuery( '.mob-menu-header-holder' ).detach() ); 
          
          if ( jQuery('.mob-menu-header-holder' ).attr( 'data-detach-el' ) != '' ) {
            jQuery( '.mobmenu-push-wrap' ).after( jQuery(   jQuery('.mob-menu-header-holder' ).attr( 'data-detach-el' ) ).detach() ); 
          }
  
          // Double Check the the menu display classes where added to the body.
          var menu_display_type = jQuery( '.mob-menu-header-holder' ).attr( 'data-menu-display' );
  
          if ( menu_display_type != '' && !jQuery( 'body' ).hasClass( menu_display_type ) ) {
            jQuery( 'body' ).addClass( menu_display_type );
          }
  
          jQuery( '#wpadminbar' ).appendTo( 'body' );

          jQuery( 'video' ).each( function(){
            if( 'autoplay' === jQuery( this ).attr('autoplay') ) {
              jQuery( this )[0].play();
            }
          });
  
  
        }

        jQuery( document ).on( 'click',  '.mobmenu-left-bt, .mob-menu-left-panel .mobmenu_content a, .show-nav-left .mob-cancel-button' , function ( e ) {  

          // Parent Link open submenu(1st Level).
          if ( jQuery(this).parent().parent().parent().parent().hasClass( 'mobmenu-parent-link' ) || jQuery(this).parent().parent().parent().parent().parent().hasClass( 'mobmenu-parent-link' ) ) {
            if( 'mobmenuleft' ===  jQuery(this).parent().parent().attr('id') && jQuery(this).parent().find( '.mob-expand-submenu' ).length > 0 )  { 
              jQuery(this).parent().find( '.mob-expand-submenu' ).first().trigger( 'click' );
              return false;
            }
          }
          // Parent Link open submenu(2nd Level).
          if ( $(this).parent().parent().parent().parent().parent().parent().parent().hasClass( 'mobmenu-parent-link-2nd-level' )  ) {
            if( 'mobmenuleft' ===  $(this).parent().parent().parent().parent().attr('id') && $(this).parent().find( '.mob-expand-submenu' ).length > 0 )  { 
              $(this).parent().find( '.mob-expand-submenu' ).first().trigger( 'click' );
              return false;
            }
          }

          jQuery('body').toggleClass('show-nav-left'); 

          if ( !jQuery( 'body' ).hasClass( 'show-nav-left') ){  
            jQuery( 'html' ).removeClass( 'hidden-overflow' );
            if ( jQuery( this ).hasClass( 'mob-cancel-button') || jQuery( this ).hasClass( 'mobmenu-left-bt' ) ) {
              return false;
            }
  
          } else {
            jQuery( 'html' ).addClass( 'hidden-overflow' );
            e.preventDefault();
           
          }
  
        });
  
        jQuery( document ).on( 'click', '.mobmenu-right-bt, .mob-menu-right-panel .mobmenu_content a, .show-nav-right .mob-cancel-button' , function ( e ) {
  
          if ( jQuery(this).parent().parent().parent().parent().hasClass( 'mobmenu-parent-link' ) || jQuery(this).parent().parent().parent().parent().parent().hasClass( 'mobmenu-parent-link' ) ) {
            if( 'mobmenuright' ===  jQuery(this).parent().parent().attr('id') && jQuery(this).parent().find( '.mob-expand-submenu' ).length > 0 )  { 
              jQuery(this).parent().find( '.mob-expand-submenu' ).first().trigger( 'click' );
              return false;
            }
          }

          // Parent Link open submenu(2nd Level).
          if ( $(this).parent().parent().parent().parent().parent().parent().parent().hasClass( 'mobmenu-parent-link-2nd-level' )  ) {
            if( 'mobmenuright' ===  $(this).parent().parent().parent().parent().attr('id') && $(this).parent().find( '.mob-expand-submenu' ).length > 0 )  { 
              $(this).parent().find( '.mob-expand-submenu' ).first().trigger( 'click' );
              return false;
            }
          }
  
          jQuery('body').toggleClass('show-nav-right'); 
          
          if ( !jQuery( 'body' ).hasClass( 'show-nav-right') ){
            jQuery( 'html' ).removeClass( 'hidden-overflow' );
            if ( jQuery( this ).hasClass( 'mob-cancel-button') || jQuery( this ).hasClass( 'mobmenu-right-bt' ) ) {
                return false;
            }

          } else {
            jQuery( 'html' ).addClass( 'hidden-overflow' );
            e.preventDefault();
          }

        });
        var submenu_open_icon  = jQuery( '.mob-menu-header-holder' ).attr( 'data-open-icon' );
        var submenu_close_icon = jQuery( '.mob-menu-header-holder' ).attr( 'data-close-icon' );
        jQuery( '.mobmenu_content .sub-menu' ).each( function(){

          jQuery( this ).before('<div class="mob-expand-submenu"><i class="mob-icon-' + submenu_open_icon + ' open-icon"></i><i class="mob-icon-' + submenu_close_icon + ' close-icon hide"></i></div>');
  
        });

        jQuery( document ).on( 'click', '.mob-expand-submenu' , function ( e ) {
  
          e.stopPropagation();

          if ( jQuery( this ).next().hasClass( 'show-sub-menu' )  ) {
            jQuery(this).find('.show-sub-menu' ).hide();
          }
          if ( ! jQuery( this ).parents('.show-sub-menu').prev().hasClass('mob-expand-submenu') && jQuery( this ).next()[0] !== jQuery('.show-sub-menu')[0] && jQuery( this ).parent('.sub-menu').length <= 0 ) {
            jQuery(this).find('.open-icon').removeClass('hide');
            jQuery(this).find('.close-icon').addClass('hide');
            jQuery(this).find( '.show-submenu' ).hide().toggleClass( 'show-sub-menu' );
            
          }

          jQuery( this ).find('.open-icon').toggleClass('hide');
          jQuery( this ).find('.close-icon').toggleClass('hide');
          
          if ( !jQuery( this ).next().hasClass( 'show-sub-menu' ) ) {  
            jQuery(this).next().fadeIn( 'slow' );
          } else {
            jQuery(this).next().hide();
          }
  
          jQuery(this).next().toggleClass( 'show-sub-menu');

        });

        $('.mobmenu a[href*="#"]')
    // Remove links that don't actually link to anything
    .not('[href="#"]')
    .not('[href="#0"]')
    .on( 'click', function(event) {
      // On-page links
      if (
        location.pathname.replace(/^\//, '') == this.pathname.replace(/^\//, '') 
        && 
        location.hostname == this.hostname
        &&
        $(this).parents('.mobmenu_content').length > 0
      ) {
        // Figure out element to scroll to
        var target = $(this.hash);
        target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');
        // Does a scroll target exist?
        if (target.length) {
          // Only prevent default if animation is actually gonna happen
          event.preventDefault();
          event.stopPropagation();
          $( '.show-nav-left .mobmenu-left-bt').first().click();
          $( '.show-nav-right .mobmenu-right-bt').first().trigger( 'click' );
          $( 'html' ).css( 'overflow', '' );

          $('body').animate({
            scrollTop: target.offset().top
          }, 1000, function() {
            // Callback after animation
            // Must change focus!
            var $target = $(target);
            $target.focus();
            if ($target.is(":focus")) { // Checking if the target was focused
              return false;
            } else {
              $target.attr('tabindex','-1'); // Adding tabindex for elements not focusable
              $target.focus(); // Set focus again
            };
          });
        }
      }
    });
  
  });
  }(jQuery));  