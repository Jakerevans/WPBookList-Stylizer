<?php

// Function for displaying a book in the colorbox window
function wpbooklist_stylizer_colorbox_action_javascript() { 

	$trans1 = __('Loading, Please wait', 'wpbooklist');

	?>
  	<script type="text/javascript">
  	"use strict";
  	jQuery(document).ready(function($) {
  		$(document).on("click",".wpbooklist-stylizer-show-book-colorbox", function(event){
  			event.preventDefault ? event.preventDefault() : event.returnValue = false;
	  		var bookId = $(this).attr('data-bookid');
	  		var bookTable = $(this).attr('data-booktable');

		  	var data = {
				'action': 'wpbooklist_stylizer_colorbox_action',
				'security': '<?php echo wp_create_nonce( "wpbooklist_stylizer_colorbox_action_callback" ); ?>',
				'bookId':bookId,
				'bookTable':bookTable
			};

	     	var request = $.ajax({
			    url: ajaxurl,
			    type: "POST",
			    data:data,
			    timeout: 0,
			    success: function(response) {
			    	$.colorbox({
						open: true,
						preloading: true,
						scrolling: true,
						overlayClose:false,
						closeButton:false,
						escKey:false,
						width:'100%',
						height:'100%',
						html: response,
						onClosed:function(){
						  //Do something on close.
						},
						onComplete:function(){

							// Prevent some links from being followed
							$('#colorbox a').each(function(){
								$(this).click(function(event){
	    							event.preventDefault ? event.preventDefault() : event.returnValue = false;
	    						})
							})

							// Hide blank 'Similar Titles' images
							$('.wpbooklist-similar-image').load(function() {
								var image = new Image();
								image.src = $(this).attr("src");
								if(image.naturalHeight == '1'){
									$(this).parent().parent().css({'display':'none'})
								}
							});
				        	
							var color = $('#wpbooklist_display_table_2 #wpbooklist_bold').css("color");
							var container = $('#wpbooklist_display_table_2 #wpbooklist_bold').first().parent();
							var color2 = container.css("background-color");
							var test = $('#wpbooklist_display_table_2 #wpbooklist_bold').first().attr('data-modifycolor');
							if( test != 'false' ){
							  console.log('inside!')
							  //color = wpbooklist_get_background_color(color, color2, container);
							}

							/*
							$('#wpbooklist_display_table_2 #wpbooklist_bold').css({'color':color});
							$('#wpbooklist_display_table_2 td').css({'color':color});
							$('.wpbooklist_desc_p_class').css({'color':color});
							$('.wpbooklist-share-text').css({'color':color});
							$('.wpbooklist-purchase-title').css({'color':color});
*/
							addthis.toolbox(
				              $(".addthis_sharing_toolbox").get()
				            );
				            addthis.toolbox(
				              $(".addthis_sharing_toolbox").get()
				            );
				            addthis.counter(
				              $(".addthis_counter").get()
				            );
						}
					});
			    },
				error: function(jqXHR, textStatus, errorThrown) {
					console.log(errorThrown);
		            console.log(textStatus);
		            console.log(jqXHR);
				}
			});
	  	});
	});
	</script>
	<?php
}

// Callback function for showing books in the colorbox window
function wpbooklist_stylizer_colorbox_action_callback(){
	global $wpdb;
	check_ajax_referer( 'wpbooklist_stylizer_colorbox_action_callback', 'security' );
	$book_id = filter_var($_POST['bookId'],FILTER_SANITIZE_NUMBER_INT);
	$book_table = filter_var($_POST['bookTable'],FILTER_SANITIZE_STRING);

	// Double-check that Amazon review isn't expired
	require_once(CLASS_DIR.'class-book.php');
	$book = new WPBookList_Book($book_id, $book_table);
	$book->refresh_amazon_review($book_id, $book_table);

	// Instantiate the class that shows the book in colorbox
	require_once(CLASS_DIR.'class-show-book-in-colorbox.php');
	$colorbox = new WPBookList_Show_Book_In_Colorbox($book_id, $book_table);

	echo $colorbox->output;
	wp_die();
}

// For saving new styles
function wpbooklist_stylizer_write_styles_action_javascript() { 
	?>
  	<script type="text/javascript" >
  	"use strict";
  	jQuery(document).ready(function($) {
	  	$("#wpbooklist-stylizer-save-styling-button").click(function(event){

	  		$('#wpbooklist-spinner-stylizer-2').animate({'opacity':'1'})
	  		$('#wpbooklist-stylizer-success-div').html('');

	  		var selected = $('#wpbooklist-stylizer-select').val();

	  		// Colorbox Styles
	  		var element1 = '#wpbooklist_title_div';
	  		var element2 = '.wpbooklist-share-text';
	  		var element3 = '.wpbooklist-purchase-title';
	  		var element4 = '#wpbooklist-similar-titles-id';
	  		var element5 = '#wpbooklist-desc-title-id';
	  		var element6 = '#wpbooklist-amazon-review-title-id';
	  		var element7 = '#wpbooklist-notes-title-id';
	  		var element8 = '.wpbooklist-purchase-img img';
	  		var element9 = '#wpbooklist-kindle-title-id';
	  		var element10 = '#wpbooklist_cover_image_popup';
	  		var element11 = '.wpbooklist-similar-image';
	  		var element12 = '#colorbox #wpbooklist_display_image_container .addthis_sharing_toolbox .at-icon, #colorbox #wpbooklist_display_image_container .addthis_sharing_toolbox .at-icon-wrapper';
	  		var element13 = '.wpbooklist-bold-stats-class';
	  		var element14 = '.wpbooklist-bold-stats-value';
	  		var element15 = '.wpbooklist-bold-stats-page';

	  		// Frontend styles
	  		var element16 = '.wpbooklist-purchase-book-text-link';
	  		var element17 = '#wpbooklist-sort-select-box';
	  		var element18 = '#wpbooklist-search-checkboxes p';
	  		var element19 = '#wpbooklist-search-text';
	  		var element20 = '#wpbooklist-search-sub-button';
	  		var element21 = '.wpbooklist_stats_tdiv';
	  		var element22 = '.wpbooklist_stats_tdiv p';
	  		var element23 = '#wpbooklist-quote-actual';
	  		var element24 = '#wpbooklist-attribution-actual';
	  		var element25 = '.wpbooklist_entry_div';
	  		var element26 = '.wpbooklist_inner_main_display_div';
	  		var element27 = '.wpbooklist_cover_image_class';
	  		var element28 = '.wpbooklist_saved_title_link';
	  		var element29 = '.wpbooklist-frontend-library-price a';
	  		var element30 = '.wpbooklist-frontend-library-buy-img img';
	  		var element31 = '.wpbooklist-frontend-library-buy-img';

	  		// Colorbox Styles
	  		var style1 = $("#wpbooklist-stylizer-iframe").contents().find(element1).attr('style');
	  		var style2 = $("#wpbooklist-stylizer-iframe").contents().find(element2).attr('style');
			var style3 = $("#wpbooklist-stylizer-iframe").contents().find(element3).attr('style');
			var style4 = $("#wpbooklist-stylizer-iframe").contents().find(element4).attr('style');
	  		var style5 = $("#wpbooklist-stylizer-iframe").contents().find(element5).attr('style');
			var style6 = $("#wpbooklist-stylizer-iframe").contents().find(element6).attr('style');
			var style7 = $("#wpbooklist-stylizer-iframe").contents().find(element7).attr('style');
			var style8 = $("#wpbooklist-stylizer-iframe").contents().find(element8).attr('style');
			var style9 = $("#wpbooklist-stylizer-iframe").contents().find(element9).attr('style');
			var style10 = $("#wpbooklist-stylizer-iframe").contents().find(element10).attr('style');
			var style11 = $("#wpbooklist-stylizer-iframe").contents().find(element11).attr('style');
			var style12 = $("#wpbooklist-stylizer-iframe").contents().find(element12).attr('style');
			var style13 = $("#wpbooklist-stylizer-iframe").contents().find(element13).attr('style');
			var style14 = $("#wpbooklist-stylizer-iframe").contents().find(element14).attr('style');
			var style15 = $("#wpbooklist-stylizer-iframe").contents().find(element15).attr('style');

			// Frontend Styles
			var style16 = $("#wpbooklist-stylizer-iframe").contents().find(element16).attr('style');
			var style17 = $("#wpbooklist-stylizer-iframe").contents().find(element17).attr('style');
			var style18 = $("#wpbooklist-stylizer-iframe").contents().find(element18).attr('style');
			var style19 = $("#wpbooklist-stylizer-iframe").contents().find(element19).attr('style');
			var style20 = $("#wpbooklist-stylizer-iframe").contents().find(element20).attr('style');
			var style21 = $("#wpbooklist-stylizer-iframe").contents().find(element21).attr('style');
			var style22 = $("#wpbooklist-stylizer-iframe").contents().find(element22).attr('style');
			var style23 = $("#wpbooklist-stylizer-iframe").contents().find(element23).attr('style');
			var style24 = $("#wpbooklist-stylizer-iframe").contents().find(element24).attr('style');
			var style25 = $("#wpbooklist-stylizer-iframe").contents().find(element25).attr('style');
			var style26 = $("#wpbooklist-stylizer-iframe").contents().find(element26).attr('style');
			var style27 = $("#wpbooklist-stylizer-iframe").contents().find(element27).attr('style');
			var style28 = $("#wpbooklist-stylizer-iframe").contents().find(element28).attr('style');
			var style29 = $("#wpbooklist-stylizer-iframe").contents().find(element29).attr('style');
			var style30 = $("#wpbooklist-stylizer-iframe").contents().find(element30).attr('style');
			var style31 = $("#wpbooklist-stylizer-iframe").contents().find(element31).attr('style');

			if(selected == 'Book View'){
				var finalStyleString = element1+'{'+style1+'}'+
									   element2+'{'+style2+'}'+
									   element3+'{'+style3+'}'+
									   element4+'{'+style4+'}'+
									   element5+'{'+style5+'}'+
									   element6+'{'+style6+'}'+
									   element7+'{'+style7+'}'+
									   element8+'{'+style8+'}'+
									   element9+'{'+style9+'}'+
									   element10+'{'+style10+'}'+
									   element11+'{'+style11+'}'+
									   element12+'{'+style12+'}'+
									   element13+'{'+style13+'}'+
									   element14+'{'+style14+'}'+
									   element15+'{'+style15+'}';

			} else {
				var finalStyleString = element16+'{'+style16+'}'+
									   element17+'{'+style17+'}'+
									   element18+'{'+style18+'}'+
									   element19+'{'+style19+'}'+
									   element20+'{'+style20+'}'+
									   element21+'{'+style21+'}'+
									   element22+'{'+style22+'}'+
									   element23+'{'+style23+'}'+
									   element24+'{'+style24+'}'+
									   element25+'{'+style25+'}'+
									   element26+'{'+style26+'}'+
									   element27+'{'+style27+'}'+
									   element28+'{'+style28+'}'+
									   element29+'{'+style29+'}'+
									   element30+'{'+style30+'}'+
									   element31+'{'+style31+'}'+
									   '#wpbooklist-stylizer-frontend-p{display:none;}';
			}

			//console.log(finalStyleString);

		  	var data = {
				'action': 'wpbooklist_stylizer_write_styles_action',
				'security': '<?php echo wp_create_nonce( "wpbooklist_stylizer_write_styles_action_callback" ); ?>',
				'finalStyleString':finalStyleString,
				'selected':selected
			};
			console.log(data);

	     	var request = $.ajax({
			    url: ajaxurl,
			    type: "POST",
			    data:data,
			    timeout: 0,
			    success: function(response) {
			    	if(response != false && response != 'false'){

			    		var html = '<p><span id="wpbooklist-add-book-success-span">Success!</span><br/>You\'ve just saved your Custom Styles! Keep in mind that you may need to clear your browser\'s cache to see your new styles.</p><div id="wpbooklist-addbook-success-thanks">Thanks for using WPBookList, and <a href="http://wpbooklist.com/index.php/extensions/">&nbsp;be sure to check out the WPBookList Extensions!</a><br><br>If you happen to be thrilled with WPBookList, then by all means, <a id="wpbooklist-addbook-success-review-link" href="https://wordpress.org/support/plugin/wpbooklist/reviews/?filter=5">Feel Free to Leave a 5-Star Review Here!</a><img id="wpbooklist-smile-icon-1" src="http://vgstuff.jakerevans.com/wp-content/plugins/wpbooklist/assets/img/icons/smile.png"></div>';

			    		$('#wpbooklist-stylizer-success-div').html(html);
			    		//$('#wpbooklist-spinner-stylizer-2').animate({'opacity':'0'})

			    		setTimeout(function(){
			    			document.location.reload(true);
			    		}, 5000)
			    	} 
			    	console.log(response);
			    },
				error: function(jqXHR, textStatus, errorThrown) {
					console.log(errorThrown);
		            console.log(textStatus);
		            console.log(jqXHR);
				}
			});

			event.preventDefault ? event.preventDefault() : event.returnValue = false;
	  	});
	});
	</script>
	<?php
}

// Callback function for creating backups
function wpbooklist_stylizer_write_styles_action_callback(){
	global $wpdb;
	check_ajax_referer( 'wpbooklist_stylizer_write_styles_action_callback', 'security' );
	$finalStyleString = filter_var($_POST['finalStyleString'],FILTER_SANITIZE_STRING);
	$selected = filter_var($_POST['selected'],FILTER_SANITIZE_STRING);


	if($selected == 'Library View'){
		echo file_put_contents(STYLIZER_ROOT_CSS_DIR."stylizer-frontend-ui.css",$finalStyleString);;
		wp_die();
	} else {
		echo file_put_contents(STYLIZER_ROOT_CSS_DIR."stylizer-bookview-ui.css",$finalStyleString);
		wp_die();
	}

	wp_die();
}

// For resetting all styles
function wpbooklist_stylizer_reset_action_javascript() { 
	?>
  	<script type="text/javascript" >
  	"use strict";
  	jQuery(document).ready(function($) {
	  	$("#wpbooklist-stylizer-clear-styling-button").click(function(event){

	  		$('#wpbooklist-stylizer-success-div').html('');
	  		$('#wpbooklist-spinner-stylizer-2').animate({'opacity':'1'})

		  	var data = {
				'action': 'wpbooklist_stylizer_reset_action',
				'security': '<?php echo wp_create_nonce( "wpbooklist_stylizer_reset_action_callback" ); ?>',
			};
			console.log(data);

	     	var request = $.ajax({
			    url: ajaxurl,
			    type: "POST",
			    data:data,
			    timeout: 0,
			    success: function(response) {
			    	document.location.reload(true);
			    	console.log(response);
			    },
				error: function(jqXHR, textStatus, errorThrown) {
					console.log(errorThrown);
		            console.log(textStatus);
		            console.log(jqXHR);
				}
			});

			event.preventDefault ? event.preventDefault() : event.returnValue = false;
	  	});
	});
	</script>
	<?php
}

// Callback function for creating backups
function wpbooklist_stylizer_reset_action_callback(){
	global $wpdb;
	check_ajax_referer( 'wpbooklist_stylizer_reset_action_callback', 'security' );
	file_put_contents(STYLIZER_ROOT_CSS_DIR."stylizer-bookview-ui.css",'');
	echo file_put_contents(STYLIZER_ROOT_CSS_DIR."stylizer-frontend-ui.css", '#wpbooklist-stylizer-frontend-p{display:none;}');
	wp_die();
}



/*
 * Below is a stylizer ajax function and callback, 
 * complete with console.logs and echos to verify functionality
 */

/*
// For adding a book from the admin dashboard
add_action( 'admin_footer', 'wpbooklist_stylizer_action_javascript' );
add_action( 'wp_ajax_wpbooklist_stylizer_action', 'wpbooklist_stylizer_action_callback' );
add_action( 'wp_ajax_nopriv_wpbooklist_stylizer_action', 'wpbooklist_stylizer_action_callback' );


function wpbooklist_stylizer_action_javascript() { 
	?>
  	<script type="text/javascript" >
  	"use strict";
  	jQuery(document).ready(function($) {
	  	$("#wpbooklist-admin-addbook-button").click(function(event){

		  	var data = {
				'action': 'wpbooklist_stylizer_action',
				'security': '<?php echo wp_create_nonce( "wpbooklist_stylizer_action_callback" ); ?>',
			};
			console.log(data);

	     	var request = $.ajax({
			    url: ajaxurl,
			    type: "POST",
			    data:data,
			    timeout: 0,
			    success: function(response) {
			    	console.log(response);
			    },
				error: function(jqXHR, textStatus, errorThrown) {
					console.log(errorThrown);
		            console.log(textStatus);
		            console.log(jqXHR);
				}
			});

			event.preventDefault ? event.preventDefault() : event.returnValue = false;
	  	});
	});
	</script>
	<?php
}

// Callback function for creating backups
function wpbooklist_stylizer_action_callback(){
	global $wpdb;
	check_ajax_referer( 'wpbooklist_stylizer_action_callback', 'security' );
	//$var1 = filter_var($_POST['var'],FILTER_SANITIZE_STRING);
	//$var2 = filter_var($_POST['var'],FILTER_SANITIZE_NUMBER_INT);
	echo 'hi';
	wp_die();
}*/




?>