/**
 * File customizer.js.
 *
 * Theme Customizer enhancements for a better user experience.
 *
 * Contains handlers to make Theme Customizer preview reload changes asynchronously.
 */

( function( $ ) {

//	if (wp && wp.customize){
//		// Site title and description.
//		wp.customize( 'blogname', function( value ) {
//			value.bind( function( to ) {
//				$( '.site-title a' ).text( to );
//			} );
//		} );
//		wp.customize( 'blogdescription', function( value ) {
//			value.bind( function( to ) {
//				$( '.site-description' ).text( to );
//			} );
//		} );
//	
//		// Header text color.
//		wp.customize( 'header_textcolor', function( value ) {
//			value.bind( function( to ) {
//				if ( 'blank' === to ) {
//					$( '.site-title, .site-description' ).css( {
//						'clip': 'rect(1px, 1px, 1px, 1px)',
//						'position': 'absolute'
//					} );
//				} else {
//					$( '.site-title, .site-description' ).css( {
//						'clip': 'auto',
//						'position': 'relative'
//					} );
//					$( '.site-title a, .site-description' ).css( {
//						'color': to
//					} );
//				}
//			} );
//		} );
//	}
	/**
	 * TinyMCE Custom Control
	 *
	 * @author Aurooba Ahmed
	 * @license http://www.gnu.org/licenses/gpl-2.0.html
	 *
	 */
	function tinyMCE_setup() {
		// Get the toolbar strings that were passed from the PHP Class
		var tinyMCEToolbar1 = _wpCustomizeSettings.controls[$( this ).attr( 'id' )].auroobamakes_tinymce_toolbar1;

		var tinyMCEToolbar2 = _wpCustomizeSettings.controls[$( this ).attr( 'id' )].auroobamakes_tinymce_toolbar2; // Get the media button condition passed from the PHP Class


		var tinyMCEMediaButtons = _wpCustomizeSettings.controls[$( this ).attr( 'id' )].auroobamakes_tinymce_mediabuttons; // Get the editor height passed from the PHP Class


		var tinyMCEheight = _wpCustomizeSettings.controls[$( this ).attr('id')].auroobamakes_tinymce_height; // Initialize editor with passed settings

		// initialize with unique ID (so you can have multiple) and with all the settings attributed correctly
		wp.editor.initialize( $( this ).attr( 'id' ), {
		tinymce: {
			wpautop: true,
			toolbar1: tinyMCEToolbar1,
			toolbar2: tinyMCEToolbar2,
			height: tinyMCEheight
		},
		quicktags: true,
		mediaButtons: tinyMCEMediaButtons
		});
	}
  
	function initialize_tinyMCE(event, editor) {
		editor.on( 'change', function () {
		tinyMCE.triggerSave();
		$( "#".concat( editor.id ) ).trigger( 'change' );
		});
	}
  
	function init() {
		$( document ).on( 'tinymce-editor-init', initialize_tinyMCE );
		// setTimeout(function(){
		// 	$( '.customize-control-tinymce-editor' ).each( tinyMCE_setup );
		// },3000);
		if ( wp && wp.hasOwnProperty("customize") ){
			wp.customize.bind('ready', function(){
				$( '.customize-control-tinymce-editor' ).each( tinyMCE_setup );
			})
		}

	}
  
	$( document) .on( 'ready', init );
  })( jQuery, wp.customize );
