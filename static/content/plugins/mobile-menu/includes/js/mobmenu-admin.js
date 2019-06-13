
  /*
    *
    *   Javascript Functions
    *   ------------------------------------------------
    *   WP Mobile Menu
    *   Copyright WP Mobile Menu 2017 - http://www.wpmobilemenu.com
    *
    */

    
 "use strict";

 (function ($) {
 jQuery( document ).ready( function() {

        //Hide deprecated field.
        jQuery( '#mobmenu_header_font_size' ).parent().parent().hide();
        jQuery( '#mobmenu_enabled_logo' ).parent().parent().hide();

        var icon_key;

        $( '.mobmenu-icon-holder' ).each( function() {

            if ( $( this ).parent().find('input').length) {
                icon_key = $( this ).parent().find('input').val();
                $( this ).html( '<span class="mobmenu-item mob-icon-' + icon_key + '" data-icon-key="' + icon_key + '"></span>');
            }
        });

        $( document ).on( "click", ".mobmenu-close-overlay" , function () {
        
            $( ".mobmenu-icons-overlay" ).fadeOut();
            $( ".mobmenu-icons-content" ).fadeOut();
            $( "#mobmenu_search_icons" ).attr( "value", "" );
            $( ".mobmenu-icons-content .mobmenu-item" ).removeClass( "mobmenu-hide-icons" );
            $( ".mobmenu-icons-remove-selected" ).hide();

            return false;
    
        });
          
        $( document ).on( "click", ".mobmenu-icons-remove-selected" , function () {


             $.ajax({
                type: "POST",
                url: ajaxurl,
                data: {
                    action: "save_menu_item_icon",
                    menu_item_id: $( ".mobmenu-icons-content" ).attr( "data-menu-item-id" ),
                    menu_item_icon: ""
                    },
               
                success: function( response ) {

                    $( ".mobmenu-item-selected").removeClass( "mobmenu-item-selected" );
                    $( ".mobmenu-icons-remove-selected" ).hide();                        
                                                                 
                }
            });
        
            return false;
    
        });
        
        $( document ).on( "click", ".toplevel_page_mobile-menu-options #mobmenu-modal-body .mobmenu-item" , function() {
            
            var icon_key = $( this ).attr( "data-icon-key" );
            $( ".mobmenu-icon-holder.selected-option" ).html( '<span class="mobmenu-item mob-icon-' + icon_key + '" data-icon-key="' + icon_key + '"></span>');
            $( ".mobmenu-close-overlay" ).trigger( "click" );
            $( ".mobmenu-icon-holder.selected-option" ).parent().find('input').val( icon_key );
            $( ".mobmenu-icon-holder.selected-option" ).removeClass( 'selected-option' );
            

        });

        $( document ).on( "click", ".nav-menus-php #mobmenu-modal-body .mobmenu-item" , function() {

            
            var icon_key = $( this ).attr( "data-icon-key" );
            
            $.ajax({
                type: "POST",
                url: ajaxurl,
                data: {
                    action: "save_menu_item_icon",
                    menu_item_id: $( ".mobmenu-icons-content" ).attr( "data-menu-item-id" ),
                    menu_item_icon: icon_key
                    },
               
                success: function( response ) {
                                               
                    $( "#mobmenu-modal-body" ).append( response );   
                                                                 
                }
            });

            $( "#mobmenu-modal-body .mobmenu-item" ).removeClass( "mobmenu-item-selected" );
            $( this ).addClass( "mobmenu-item-selected" );
            $( ".mobmenu-icons-remove-selected" ).show();
                        
        });

     
        $( document ).on( "input", "#mobmenu_search_icons",  function() {

            var foundResults = false;

            if ( $( this ).val().length > 1 ) {

                var str = $( this ).val();
                str = str.toLowerCase().replace(
                    /\b[a-z]/g, function( letter ) {
                        return letter.toLowerCase();
                    } 
                );

                var txAux = str; 
                
                $( "#mobmenu-modal-body .mobmenu-item" ).each(
                    function() {

                        if ( $( this ).attr( "data-icon-key" ).indexOf( txAux ) < 0 ) {
                            $( this ).addClass( "mobmenu-hide-icons" );
                        } else {
                            $( this ).removeClass( "mobmenu-hide-icons" );
                            foundResults = true;
                    
                        }

                    }
                );
            } else {
                $( "#mobmenu-modal-body .mobmenu-item" ).removeClass( "mobmenu-hide-icons" );
            }

            if ( $( this ).val() === '' || !foundResults ) {
                $( "#mobmenu-modal-body .mobmenu-item" ).removeClass( "mobmenu-hide-icons" );
                
            }

            if ( $( this ).val() !== '' &&  $( this ).val().length >= 3  && !foundResults ) {
                $( "#mobmenu-modal-body .mobmenu-item" ).addClass( "mobmenu-hide-icons" );    
            }

        });  

    $( document ).on( "click", ".mobmenu-icon-picker" , function( e ) {
          
          e.preventDefault();

          var full_content = '';
          var selected_icon = '';
          var menu_id = 0;
          var id = 0;

          jQuery( this ).prev().addClass( 'selected-option' );
               
          if (  $( ".mobmenu-icons-overlay" ).length ) {
                full_content = 'no';                                    
          } else {
                full_content = 'yes';
          }

          $.ajax({
                    type: 'POST',
                    url: ajaxurl,
                    data: {
                        action: "get_icons_html",
                        menu_item_id: 0,
                        menu_id: 0,
                        full_content: full_content
                        },
               
                    success: function( response ) {
                        if ( full_content == 'yes' ) {
                                                    
                            $( "body" ).append( response );   
                            selected_icon = $( ".mobmenu-icons-holder" ).attr( "data-selected-icon" );
                                                
                        } else {

                            $( ".mobmenu-icons-overlay" ).fadeIn();
                            $( ".mobmenu-icons-content" ).fadeIn();
                            $( "#mobmenu-modal-body .mobmenu-item" ).removeClass( "mobmenu-item-selected" );        
                            selected_icon = $( response ).attr( "data-selected-icon" );

                        }

                        if ( selected_icon != '' && selected_icon != undefined ) {
                            $( ".mob-icon-" + selected_icon ).addClass( "mobmenu-item-selected" );
                            $( ".mobmenu-icons-remove-selected" ).show();
                            //$( ".mobmenu-icon-picker" ).before( $( ".mob-icon-" + selected_icon ).html() );
                        }                       
                        
                    }

                });
    });

    $( document ).on( "click", ".mobmenu-item-settings" , function( e ) {
             
             e.preventDefault();

             var menu_item = $( this ).parent().parent().parent().parent();
             var menu_title = $(this).parent().parent().find('.menu-item-title').text();
             var menu_id = $( "#menu" ).val();
             var selected_icon = '';
             var full_content = '';
             var id = parseInt( menu_item.attr( 'id' ).match(/[0-9]+/)[0], 10);
             
             if (  $( ".mobmenu-icons-overlay" ).length ) {
                full_content = 'no';                                    
             } else {
                full_content = 'yes';
             }

             $.ajax({
                    type: 'POST',
                    url: ajaxurl,
                    data: {
                        action: "get_icons_html",
                        menu_item_id: id,
                        menu_id: menu_id,
                        menu_title: menu_title,
                        full_content: full_content
                        },
               
                    success: function( response ) {
                        if ( full_content == 'yes' ) {
                                                    
                            $( "body" ).append( response );   
                            selected_icon = $( ".mobmenu-icons-holder" ).attr( "data-selected-icon" );
                                                
                        } else {

                            $( ".mobmenu-icons-overlay" ).fadeIn();
                            $( ".mobmenu-icons-content" ).fadeIn();
                            $( "#mobmenu-modal-body .mobmenu-item" ).removeClass( "mobmenu-item-selected" );        
                            selected_icon = $( response ).attr( "data-selected-icon" );
                            $( "#mobmenu-modal-header h2").html( $( response ).attr( "data-title" ) );

                        }

                        if ( selected_icon != '' && selected_icon != undefined ) {
                            $( ".mob-icon-" + selected_icon ).addClass( "mobmenu-item-selected" );
                            $( ".mobmenu-icons-remove-selected" ).show();
                        }

                        $( "#mobmenu-modal-body" ).scrollTop( jQuery( ".mobmenu-item-selected" ).offset() - 250 );
                        $( ".mobmenu-icons-content" ).attr( "data-menu-id", menu_id );
                        $( ".mobmenu-icons-content" ).attr( "data-menu-item-id" , id );
                    }

                });

                                  
                $( "#mobmenu_search_icons" ).focus();         
    });

    $( document ).on( 'click', '.wp-mobile-menu-notice .notice-dismiss' , function( e ) {
        
        $.ajax({
                  type: 'POST',
                  url: ajaxurl,

                  data: {
                      action: 'dismiss_wp_mobile_notice',
                      security: jQuery( this ).parent().attr( 'data-ajax-nonce' )
                      }
              });
  });


}); 
    
}(jQuery)); 
   
