<?php

/**
 * Verifies that the core WPBookList plugin is installed and activated - otherwise, the Extension doesn't load and a message is displayed to the user.
 */
function wpbooklist_stylizer_core_plugin_required() {

	// Require core WPBookList Plugin.
	if ( ! is_plugin_active( 'wpbooklist/wpbooklist.php' ) && current_user_can( 'activate_plugins' ) ) {

		// Stop activation redirect and show error.
		wp_die( 'Whoops! This WPBookList Extension requires the Core WPBookList Plugin to be installed and activated! <br><a target="_blank" href="https://wordpress.org/plugins/wpbooklist/">Download WPBookList Here!</a><br><br><a href="' . admin_url( 'plugins.php' ) . '">&laquo; Return to Plugins</a>');
	}
}

// Function to add table names to the global $wpdb
function wpbooklist_stylizer_register_table_name() {
    global $wpdb;
    $wpdb->wpbooklist_stylizer_settings = "{$wpdb->prefix}wpbooklist_stylizer_settings";
}

// Runs once upon plugin activation and creates tables
function wpbooklist_stylizer_create_settings_table() {
  require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
  global $wpdb;
  global $charset_collate; 

  // Call this manually as we may have missed the init hook
  wpbooklist_stylizer_register_table_name();
  //Creating the table

  $settings_table = $wpdb->prefix."wpbooklist_stylizer_settings";

  $sql_create_table = "CREATE TABLE {$wpdb->wpbooklist_stylizer_settings} 
  (
        ID bigint(190) auto_increment,
        iframepage varchar(190),
        PRIMARY KEY  (ID),
          KEY iframepage (iframepage)
  ) $charset_collate; ";
  dbDelta( $sql_create_table );

  $wpdb->insert( $settings_table, array('ID' => 1)); 


}

// Adding the front-end ui css file for this extension
function wpbooklist_jre_stylizer_frontend_ui_style() {
    wp_register_style( 'wpbooklist-stylizer-frontend-ui', STYLIZER_ROOT_CSS_URL.'stylizer-frontend-ui.css' );
    wp_enqueue_style('wpbooklist-stylizer-frontend-ui');
}

// Adding the front-end ui css file for this extension
function wpbooklist_jre_stylizer_bookview_ui_style() {
    wp_register_style( 'wpbooklist-stylizer-bookview-ui', STYLIZER_ROOT_CSS_URL.'stylizer-bookview-ui.css' );
    wp_enqueue_style('wpbooklist-stylizer-bookview-ui');
}

// Code for adding the general admin CSS file
function wpbooklist_jre_stylizer_admin_style() {
  if(current_user_can( 'administrator' )){
      wp_register_style( 'wpbooklist-stylizer-admin-ui', STYLIZER_ROOT_CSS_URL.'stylizer-admin-ui.css');
      wp_enqueue_style('wpbooklist-stylizer-admin-ui');
  }
}

// Code for adding the colorpicker js file
function wpbooklist_colorpicker_script() {
    wp_register_script( 'wpbooklist-colorpicker-script', STYLIZER_ROOT_JS_URL.'jscolor.js', array('jquery') );
    wp_enqueue_script('wpbooklist-colorpicker-script');
}

// Code for adding font detector js
function wpbooklist_fontdetect_script() {
    wp_register_script( 'wpbooklist-fontdetect-script', STYLIZER_ROOT_JS_URL.'available-fonts/src/available-fonts.js', array('jquery') );
    wp_enqueue_script('wpbooklist-fontdetect-script');
}

function wpbooklist_stylizer_create_iframe_page(){
	global $wpdb;
	$permalink = null;
	$status = null;

    // Try to get the page by the incoming title
    $post = get_page_by_title( strtolower( STYLIZER_REQUIRED_PAGE_TITLE ), OBJECT, 'page' );

    // If the post exists, then let's get its permalink
    if( null != $post ) {
        $permalink = get_permalink( $post->ID );
        $status = get_post_status($post->ID);
    }

    // If the Post is in the trash, restore it
    if($status == 'trash'){
    	wp_update_post( 
    	 	array(
				'ID'	=>	$post->ID,
				'post_status' =>	'private',
			) 
    	);
    }

    // If the post content doesn't contain [wpbooklist_shortcode]
    $content = get_post_field('post_content', $post->ID);
    if(strpos($content, '[wpbooklist_shortcode]') === false ){
    	wp_update_post( 
    	 	array(
				'ID'	=>	$post->ID,
				'post_content' => '[wpbooklist_shortcode]'
			) 
    	);
    }

    if($permalink == null){
		$page_id = wp_insert_post(
			array(
				'comment_status'	=>	'closed',
				'ping_status'		=>	'closed',
				'post_author'		=>	1,			// Administrator is creating the page
				'post_title'		=>	STYLIZER_REQUIRED_PAGE_TITLE,
				'post_name'		=>	STYLIZER_REQUIRED_PAGE_TITLE,
				'post_status'		=>	'private',
				'post_type'		=>	'page',
				'post_content' => '[wpbooklist_shortcode]'
			)
		);

		$post = get_page_by_title( strtolower( STYLIZER_REQUIRED_PAGE_TITLE ), OBJECT, 'page' );

	    // If the post exists, then let's get its permalink
	    if( null != $post ) {
	        $permalink = get_permalink( $post->ID );
	    }

	    $settings_table = $wpdb->prefix."wpbooklist_stylizer_settings";
	   	// Update admin message
	    $data = array(
	      'iframepage' => $permalink,
	    );
	    $format = array( '%s');   
	    $where = array( 'ID' => 1 );
	    $where_format = array( '%d' );
	    $wpdb->update( $settings_table, $data, $where, $format, $where_format );
	}
}





function wpbooklist_stylizer_available_fonts(){
	global $wpdb;
	$settings_table = $wpdb->prefix."wpbooklist_stylizer_settings";
	$iframeurl = $wpdb->get_row("SELECT * FROM $settings_table");
	$actual_link = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

	if($iframeurl->iframepage == $actual_link){
		?>
	    <script type="text/javascript" >
	    "use strict";
	    jQuery(document).ready(function($) {
	    	(function() {
			  var xhr = new XMLHttpRequest();
			  xhr.onreadystatechange = function() {
			    if (xhr.readyState === 4 && xhr.status === 200) {
			      var testFonts = JSON.parse(xhr.responseText).testFonts;
			      var availableFontsList = availableFonts(testFonts);

			      var txt = $("<p id='wpbooklist-stylizer-frontend-p'></p>").text(availableFontsList.join(', '));
			      $("body").append(txt);
			    }
			  };
			  xhr.open('GET', '<?php echo STYLIZER_ROOT_JS_URL; ?>available-fonts/test-fonts.json');
			  xhr.send();
			}())
	    });
	  	</script>
	  	<?php
	}

}

function wpbooklist_stylizer_iframeload_javascript() { 

	global $wpdb;
	$settings_table = $wpdb->prefix."wpbooklist_stylizer_settings";
	$iframeurl = $wpdb->get_row("SELECT * FROM $settings_table");
	$url = $iframeurl->iframepage
  	?>
    <script type="text/javascript" >
    "use strict";
    jQuery(document).ready(function($) {

    	$(document).on("change","#wpbooklist-stylizer-select", function(event){

    		if($(this).val() == 'Book View'){


	    		$('#wpbooklist-spinner-stylizer-1').animate({'opacity':'1'})

	    		// Once the iframe finishes loading...
	    		$('#wpbooklist-stylizer-iframe').on('load', function(){

	    			// Hide the rest of the iframe
	    			$("#wpbooklist-stylizer-iframe").contents().find("#main").css({'display':'none'})
	    		
	    			// Add our stylizer class
					$("#wpbooklist-stylizer-iframe").contents().find(".wpbooklist_cover_image_class").first().removeClass().addClass('wpbooklist-stylizer-show-book-colorbox');

	    			// Trigger the onclick
		    		$("#wpbooklist-stylizer-iframe").contents().find(".wpbooklist-stylizer-show-book-colorbox").first().trigger( "click" );

		    		// Give some space to the top of Colorbox to make sure user can see book title
		    		var colorbox = $("#wpbooklist-stylizer-iframe").contents().find("#colorbox");
		    		if(colorbox.length > 0){
		    			colorbox.css({'margin-top':'45px'})
		    		}

		   
		    		(function fonts_exist(){
		    			if($("#wpbooklist-stylizer-iframe").contents().find("#wpbooklist-stylizer-frontend-p") == undefined){
		    				setTimeout(function(){
		    					fonts_exist();
		    				}, 1000)
		    			} else {
		    				var fonts = $("#wpbooklist-stylizer-iframe").contents().find("#wpbooklist-stylizer-frontend-p").text();

				    		fonts = fonts.replace(/, /g,',');
				    		fonts = fonts.split(',');
				    		var option = '';
				    		$('.wpbooklist-stylizer-interact-family-select').each(function(){
				    			for (var i = 0; i < fonts.length; i++) {
				    				console.log(fonts[i]);
				    				option = option+'<option>'+fonts[i]+'</option>';

				    			};

				    			$(this).append(option);
				    		})
		    			}
		    		})();
		    		


		    		// Reveal the iframe
		    		$('#wpbooklist-stylizer-bookview-styles').css({'height':'auto'})
		    		$('#wpbooklist-stylizer-iframe').css({'height':'500px'})
		    		$('#wpbooklist-stylizer-iframe').animate({'opacity':'1', 'height':'500px'}, 3000)
		    		$('#wpbooklist-stylizer-styles-div').css({'height':'auto'});
		    		$('#wpbooklist-stylizer-styles-div').animate({'opacity':'1', 'margin-top':'50px'}, 3000)
		    		$('#wpbooklist-spinner-stylizer-1').animate({'opacity':'0'},3000)

		  		});
		    		
		    	// Add iframe src
		    	$('#wpbooklist-stylizer-iframe').attr('src','<?php echo $url; ?>');

		    } else {

		    	$('#wpbooklist-spinner-stylizer-1').animate({'opacity':'1'})

	    		// Once the iframe finishes loading...
	    		$('#wpbooklist-stylizer-iframe').on('load', function(){

	    			// Hide the rest of the iframe
	    			$("#wpbooklist-stylizer-iframe").contents().find("#page").css({'margin':'0px'});
	    			$("#wpbooklist-stylizer-iframe").contents().find("body").css({'background':'transparent'});

		   
		    		(function fonts_exist(){
		    			if($("#wpbooklist-stylizer-iframe").contents().find("#wpbooklist-stylizer-frontend-p") == undefined){
		    				setTimeout(function(){
		    					fonts_exist();
		    				}, 1000)
		    			} else {
		    				var fonts = $("#wpbooklist-stylizer-iframe").contents().find("#wpbooklist-stylizer-frontend-p").text();

				    		fonts = fonts.replace(/, /g,',');
				    		fonts = fonts.split(',');
				    		var option = '';
				    		$('.wpbooklist-stylizer-interact-family-select').each(function(){
				    			for (var i = 0; i < fonts.length; i++) {
				    				console.log(fonts[i]);
				    				option = option+'<option>'+fonts[i]+'</option>';

				    			};

				    			$(this).append(option);
				    		})
		    			}
		    		})();

		    		$('#wpbooklist-stylizer-iframe').contents().find("html").animate({
				        scrollTop:  $("#wpbooklist-stylizer-iframe").contents().find('.wpbooklist-top-container').offset().top
				      }, 500);
		    		


		    		// Reveal the iframe
		    		$('#wpbooklist-stylizer-libraryview-styles').css({'height':'auto'})
		    		$('#wpbooklist-stylizer-iframe').css({'height':'500px', 'border':'solid 1px black'})
		    		$('#wpbooklist-stylizer-iframe').animate({'opacity':'1', 'height':'500px'}, 3000)
		    		$('#wpbooklist-stylizer-styles-div').css({'height':'auto'});
		    		$('#wpbooklist-stylizer-styles-div').animate({'opacity':'1', 'margin-top':'50px'}, 3000)
		    		$('#wpbooklist-spinner-stylizer-1').animate({'opacity':'0'},3000)

		  		});
		    		
		    	// Add iframe src
		    	$('#wpbooklist-stylizer-iframe').attr('src','<?php echo $url; ?>');
		    }
		});
  	});
  </script>
  <?php
}

// For listening to changes in the style inputs and applying to preview
function wpbooklist_stylizer_style_listeners_javascript() { 
  ?>
    <script type="text/javascript" >
    "use strict";
    jQuery(document).ready(function($) {

    	// Controls the height/expansion of the different elements
    	$(document).on("click",".wpbooklist-stylizer-title", function(event){
    		var height = $(this).parent().css('height')
    		$('.wpbooklist-stylizer-section-container').each(function(){
    			$(this).animate({'height':'45px'})
    		})

    		if(height != '45px'){
    			$(this).parent().animate({'height':'45px'})
    		} else {

    			var styleblocks = 17;
    			$(this).next().children().each(function(){
    				if($(this).attr('style') == 'display: none;'){
    					styleblocks--;
    				}
    			});

    			var height = (styleblocks*37)+140;
    			$(this).parent().animate({'height':height+'px'})
    		}
    	})

    	// Hiding style blocks that don't apply
		$('#wpbooklist-stylizer-section-1 #wpbooklist-stylizer-style-block-1, #wpbooklist-stylizer-section-1 #wpbooklist-stylizer-style-block-2, #wpbooklist-stylizer-section-2 #wpbooklist-stylizer-style-block-1, #wpbooklist-stylizer-section-2 #wpbooklist-stylizer-style-block-2, #wpbooklist-stylizer-section-3 #wpbooklist-stylizer-style-block-1, #wpbooklist-stylizer-section-3 #wpbooklist-stylizer-style-block-2, #wpbooklist-stylizer-section-4 #wpbooklist-stylizer-style-block-1, #wpbooklist-stylizer-section-4 #wpbooklist-stylizer-style-block-2, #wpbooklist-stylizer-section-5 #wpbooklist-stylizer-style-block-1, #wpbooklist-stylizer-section-5 #wpbooklist-stylizer-style-block-2, #wpbooklist-stylizer-section-6 #wpbooklist-stylizer-style-block-1, #wpbooklist-stylizer-section-6 #wpbooklist-stylizer-style-block-2, #wpbooklist-stylizer-section-7 #wpbooklist-stylizer-style-block-1, #wpbooklist-stylizer-section-7 #wpbooklist-stylizer-style-block-2, #wpbooklist-stylizer-section-8 #wpbooklist-stylizer-style-block-1, #wpbooklist-stylizer-section-8 #wpbooklist-stylizer-style-block-2, #wpbooklist-stylizer-section-9 #wpbooklist-stylizer-style-block-1, #wpbooklist-stylizer-section-9 #wpbooklist-stylizer-style-block-3, #wpbooklist-stylizer-section-9 #wpbooklist-stylizer-style-block-4, #wpbooklist-stylizer-section-9 #wpbooklist-stylizer-style-block-5, #wpbooklist-stylizer-section-9 #wpbooklist-stylizer-style-block-6, #wpbooklist-stylizer-section-9 #wpbooklist-stylizer-style-block-7, #wpbooklist-stylizer-section-9 #wpbooklist-stylizer-style-block-8, #wpbooklist-stylizer-section-9 #wpbooklist-stylizer-style-block-9, #wpbooklist-stylizer-section-9 #wpbooklist-stylizer-style-block-10, #wpbooklist-stylizer-section-9 #wpbooklist-stylizer-style-block-11, #wpbooklist-stylizer-section-9 #wpbooklist-stylizer-style-block-12, #wpbooklist-stylizer-section-10 #wpbooklist-stylizer-style-block-1, #wpbooklist-stylizer-section-10 #wpbooklist-stylizer-style-block-3, #wpbooklist-stylizer-section-10 #wpbooklist-stylizer-style-block-4, #wpbooklist-stylizer-section-10 #wpbooklist-stylizer-style-block-5, #wpbooklist-stylizer-section-10 #wpbooklist-stylizer-style-block-6, #wpbooklist-stylizer-section-10 #wpbooklist-stylizer-style-block-7, #wpbooklist-stylizer-section-10 #wpbooklist-stylizer-style-block-8, #wpbooklist-stylizer-section-10 #wpbooklist-stylizer-style-block-9, #wpbooklist-stylizer-section-10 #wpbooklist-stylizer-style-block-10, #wpbooklist-stylizer-section-10 #wpbooklist-stylizer-style-block-11, #wpbooklist-stylizer-section-10 #wpbooklist-stylizer-style-block-12, #wpbooklist-stylizer-section-11 #wpbooklist-stylizer-style-block-1, #wpbooklist-stylizer-section-11 #wpbooklist-stylizer-style-block-3, #wpbooklist-stylizer-section-11 #wpbooklist-stylizer-style-block-4, #wpbooklist-stylizer-section-11 #wpbooklist-stylizer-style-block-5, #wpbooklist-stylizer-section-11 #wpbooklist-stylizer-style-block-6, #wpbooklist-stylizer-section-11 #wpbooklist-stylizer-style-block-7, #wpbooklist-stylizer-section-11 #wpbooklist-stylizer-style-block-8, #wpbooklist-stylizer-section-11 #wpbooklist-stylizer-style-block-9, #wpbooklist-stylizer-section-11 #wpbooklist-stylizer-style-block-10, #wpbooklist-stylizer-section-11 #wpbooklist-stylizer-style-block-11, #wpbooklist-stylizer-section-11 #wpbooklist-stylizer-style-block-12, #wpbooklist-stylizer-section-13 #wpbooklist-stylizer-style-block-1, #wpbooklist-stylizer-section-13 #wpbooklist-stylizer-style-block-2, #wpbooklist-stylizer-section-14 #wpbooklist-stylizer-style-block-1, #wpbooklist-stylizer-section-14 #wpbooklist-stylizer-style-block-2, #wpbooklist-stylizer-section-15 #wpbooklist-stylizer-style-block-1, #wpbooklist-stylizer-section-15 #wpbooklist-stylizer-style-block-2, #wpbooklist-stylizer-section-16 #wpbooklist-stylizer-style-block-1, #wpbooklist-stylizer-section-16 #wpbooklist-stylizer-style-block-2, #wpbooklist-stylizer-section-23 #wpbooklist-stylizer-style-block-1, #wpbooklist-stylizer-section-23 #wpbooklist-stylizer-style-block-2, #wpbooklist-stylizer-section-22 #wpbooklist-stylizer-style-block-1, #wpbooklist-stylizer-section-22 #wpbooklist-stylizer-style-block-2, #wpbooklist-stylizer-section-24 #wpbooklist-stylizer-style-block-3, #wpbooklist-stylizer-section-24 #wpbooklist-stylizer-style-block-4, #wpbooklist-stylizer-section-24 #wpbooklist-stylizer-style-block-5, #wpbooklist-stylizer-section-24 #wpbooklist-stylizer-style-block-6, #wpbooklist-stylizer-section-24 #wpbooklist-stylizer-style-block-7, #wpbooklist-stylizer-section-24 #wpbooklist-stylizer-style-block-8, #wpbooklist-stylizer-section-24 #wpbooklist-stylizer-style-block-9, #wpbooklist-stylizer-section-24 #wpbooklist-stylizer-style-block-10, #wpbooklist-stylizer-section-24 #wpbooklist-stylizer-style-block-11, #wpbooklist-stylizer-section-24 #wpbooklist-stylizer-style-block-12, #wpbooklist-stylizer-section-25 #wpbooklist-stylizer-style-block-3, #wpbooklist-stylizer-section-25 #wpbooklist-stylizer-style-block-4, #wpbooklist-stylizer-section-25 #wpbooklist-stylizer-style-block-5, #wpbooklist-stylizer-section-25 #wpbooklist-stylizer-style-block-6, #wpbooklist-stylizer-section-25 #wpbooklist-stylizer-style-block-7, #wpbooklist-stylizer-section-25 #wpbooklist-stylizer-style-block-8, #wpbooklist-stylizer-section-25 #wpbooklist-stylizer-style-block-9, #wpbooklist-stylizer-section-25 #wpbooklist-stylizer-style-block-10, #wpbooklist-stylizer-section-25 #wpbooklist-stylizer-style-block-11, #wpbooklist-stylizer-section-25 #wpbooklist-stylizer-style-block-12, #wpbooklist-stylizer-section-26 #wpbooklist-stylizer-style-block-3, #wpbooklist-stylizer-section-26 #wpbooklist-stylizer-style-block-4, #wpbooklist-stylizer-section-26 #wpbooklist-stylizer-style-block-5, #wpbooklist-stylizer-section-26 #wpbooklist-stylizer-style-block-6, #wpbooklist-stylizer-section-26 #wpbooklist-stylizer-style-block-7, #wpbooklist-stylizer-section-26 #wpbooklist-stylizer-style-block-8, #wpbooklist-stylizer-section-26 #wpbooklist-stylizer-style-block-9, #wpbooklist-stylizer-section-26 #wpbooklist-stylizer-style-block-10, #wpbooklist-stylizer-section-26 #wpbooklist-stylizer-style-block-11, #wpbooklist-stylizer-section-26 #wpbooklist-stylizer-style-block-12, #wpbooklist-stylizer-section-26 #wpbooklist-stylizer-style-block-13, #wpbooklist-stylizer-style-block-12, #wpbooklist-stylizer-section-30 #wpbooklist-stylizer-style-block-3, #wpbooklist-stylizer-section-30 #wpbooklist-stylizer-style-block-4, #wpbooklist-stylizer-section-30 #wpbooklist-stylizer-style-block-5, #wpbooklist-stylizer-section-30 #wpbooklist-stylizer-style-block-6, #wpbooklist-stylizer-section-30 #wpbooklist-stylizer-style-block-7, #wpbooklist-stylizer-section-30 #wpbooklist-stylizer-style-block-8, #wpbooklist-stylizer-section-30 #wpbooklist-stylizer-style-block-9, #wpbooklist-stylizer-section-30 #wpbooklist-stylizer-style-block-10, #wpbooklist-stylizer-section-30 #wpbooklist-stylizer-style-block-11, #wpbooklist-stylizer-section-30 #wpbooklist-stylizer-style-block-12, #wpbooklist-stylizer-section-30 #wpbooklist-stylizer-style-block-13, #wpbooklist-stylizer-section-28 #wpbooklist-stylizer-style-block-1, #wpbooklist-stylizer-section-28 #wpbooklist-stylizer-style-block-2,  #wpbooklist-stylizer-section-29 #wpbooklist-stylizer-style-block-1, #wpbooklist-stylizer-section-29 #wpbooklist-stylizer-style-block-2').css({'display':'none'})

		$(document).on("change",".wpbooklist-stylizer-interact", function(event){
			var forwhat = $(this).parent().parent().attr('data-element');
			var id = $(this).attr('id');
			var value = $(this).val();
			var newstyle1 = '';
			var newstyle2 = '';
			var element = '';
			var existbefore = '';
			var existafter = '';
			var val1 = 0;
			var val2 = 0;
			var val3 = 0;
			var val4 = 0;
			var val5 = 0;
			var val6 = 0;

			// Get browser prefix
			var styles = window.getComputedStyle(document.documentElement, '');
			var pre = (Array.prototype.slice
		      .call(styles)
		      .join('') 
		      .match(/-(moz|webkit|ms)-/) || (styles.OLink === '' && ['', 'o'])
		    )[1]

			switch(id) {
				case 'wpbooklist-stylizer-d1':
			        newstyle1 = 'height';
			        newstyle2 = value+'px';
			        break;
			    case 'wpbooklist-stylizer-d2':
			    	newstyle1 = 'width';
			        newstyle2 = value+'px';
			        break;
			    case 'wpbooklist-stylizer-f1':
			        newstyle1 = 'font-size';
			        newstyle2 = value+'px';
			        break;
			    case 'wpbooklist-stylizer-f2':
			    	newstyle1 = 'color';
			        newstyle2 = '#'+value;
			        break;
			    case 'wpbooklist-stylizer-f3':
			    	newstyle1 = 'font-family';
			        newstyle2 = value;
			        break;
			    case 'wpbooklist-stylizer-f4':
			    	newstyle1 = 'font-style';
			        newstyle2 = value;
			        break;
			    case 'wpbooklist-stylizer-f5':
			    	newstyle1 = 'font-weight';
			        newstyle2 = value;
			        break;
			    case 'wpbooklist-stylizer-f6':
			    	newstyle1 = 'font-variant';
			        newstyle2 = value;
			        break;
			    case 'wpbooklist-stylizer-f7':
			    	newstyle1 = 'line-height';
			        newstyle2 = value;
			        break;
			    case 'wpbooklist-stylizer-f8':
			    	newstyle1 = 'letter-spacing';
			        newstyle2 = value+'px';
			        break;
			    case 'wpbooklist-stylizer-f9':

				    if(pre == 'webkit'){
				    	newstyle1 = '-webkit-text-stroke-width';
				    }

				    if(pre == 'moz'){
				    	newstyle1 = '-moz-text-stroke-width';
				    }

				    if(pre == 'ms'){
				    	newstyle1 = '-ms-text-stroke-width';
				    }

			        newstyle2 = value+'px';
			        break;
			    case 'wpbooklist-stylizer-f10':

			    	if(pre == 'webkit'){
				    	newstyle1 = '-webkit-text-stroke-color';
				    }

				    if(pre == 'moz'){
				    	newstyle1 = '-moz-text-stroke-color';
				    }

				    if(pre == 'ms'){
				    	newstyle1 = '-ms-text-stroke-color';
				    }

			        newstyle2 = '#'+value;
			        break;
			    case 'wpbooklist-stylizer-f11':
			    	newstyle1 = 'text-align';
			        newstyle2 = value;
			        break;
			    case 'wpbooklist-stylizer-f12':
			    	newstyle1 = 'background-color';
			        newstyle2 = '#'+value;
			        break;
			    case 'wpbooklist-stylizer-p1-1':
			    	newstyle1 = 'margin';
			        newstyle2 = value+'px';
			        break;
			    case 'wpbooklist-stylizer-p1-2':
			    	newstyle1 = 'margin';
			        newstyle2 = value+'px';
			        break;
			    case 'wpbooklist-stylizer-p1-3':
			    	newstyle1 = 'margin';
			        newstyle2 = value+'px';
			        break;
			    case 'wpbooklist-stylizer-p1-4':
			    	newstyle1 = 'margin';
			        newstyle2 = value+'px';
			        break;
			    case 'wpbooklist-stylizer-p2-1':
			    	newstyle1 = 'padding';
			        newstyle2 = value+'px';
			        break;
			    case 'wpbooklist-stylizer-p2-2':
			    	newstyle1 = 'padding';
			        newstyle2 = value+'px';
			        break;
			    case 'wpbooklist-stylizer-p2-3':
			    	newstyle1 = 'padding';
			        newstyle2 = value+'px';
			        break;
			    case 'wpbooklist-stylizer-p2-4':
			    	newstyle1 = 'padding';
			        newstyle2 = value+'px';
			        break;
			    case 'wpbooklist-stylizer-b1-1':
			    	newstyle1 = 'box-shadow';
			        newstyle2 = value+'px';
			        break;
			    case 'wpbooklist-stylizer-b1-2':
			    	newstyle1 = 'box-shadow';
			        newstyle2 = value+'px';
			        break;
			    case 'wpbooklist-stylizer-b1-3':
			    	newstyle1 = 'box-shadow';
			        newstyle2 = value+'px';
			        break;
			    case 'wpbooklist-stylizer-b1-4':
			    	newstyle1 = 'box-shadow';
			        newstyle2 = value+'px';
			        break;
			    case 'wpbooklist-stylizer-shadow-inset':
			    	newstyle1 = 'box-shadow';
			        newstyle2 = value;
			        break;
			    case 'wpbooklist-stylizer-b1':
			    	newstyle1 = 'box-shadow';
			        newstyle2 = value;
			        break;
			    default:

			}

			// If the Style is a margin
			if(newstyle1 == 'margin'){

				var parent = $(this).parent().children();

				val1 = parent.eq(1).val();
				val2 = parent.eq(2).val();
				val3 = parent.eq(3).val();
				val4 = parent.eq(4).val();

				if(val1 == '' || val1 == undefined){
					val1 = 0;
				}

				if(val2 == '' || val2 == undefined){
					val2 = 0;
				}

				if(val3 == '' || val3 == undefined){
					val3 = 0;
				}

				if(val4 == '' || val4 == undefined){
					val4 = 0;
				}

				newstyle2 = val1+'px '+val2+'px '+val3+'px '+val4+'px';
			}

			// If the Style is padding
			if(newstyle1 == 'padding'){

				var parent = $(this).parent().children();

				val1 = parent.eq(1).val();
				val2 = parent.eq(2).val();
				val3 = parent.eq(3).val();
				val4 = parent.eq(4).val();

				if(val1 == '' || val1 == undefined){
					val1 = 0;
				}

				if(val2 == '' || val2 == undefined){
					val2 = 0;
				}

				if(val3 == '' || val3 == undefined){
					val3 = 0;
				}

				if(val4 == '' || val4 == undefined){
					val4 = 0;
				}

				newstyle2 = val1+'px '+val2+'px '+val3+'px '+val4+'px';
			}
console.log(newstyle1);
			// If the Style is box-shadow
			if(newstyle1 == 'box-shadow'){

				var parent = $(this).parent().children();

				val1 = parent.eq(1).val();
				val2 = parent.eq(2).val();
				val3 = parent.eq(3).val();
				val4 = parent.eq(4).val();
				val5 = parent.eq(5).val();
				val6 = parent.eq(6).val();

				if(val1 == '' || val1 == undefined || val1 == 'Normal'){
					val1 = '';
				}

				if(val2 == '' || val2 == undefined){
					val2 = 0;
				}

				if(val3 == '' || val3 == undefined){
					val3 = 0;
				}

				if(val4 == '' || val4 == undefined){
					val4 = 0;
				}

				if(val5 == '' || val5 == undefined){
					val5 = 0;
				}

				newstyle2 = val1+' '+val2+'px '+val3+'px '+val4+'px '+val5+'px #'+val6;
				console.log(newstyle2);
			}

			switch(forwhat) {
			    case 'maintitle':
			        element = '#wpbooklist_title_div';
			        break;
			    case 'sharetitle':
			    	element = '.wpbooklist-share-text';
			        break;
			    case 'purchasetitle':
			        element = '.wpbooklist-purchase-title';
			        break;
			    case 'similartitle':
			    	element = '#wpbooklist-similar-titles-id';
			        break;
			    case 'descriptiontitle':
			    	element = '#wpbooklist-desc-title-id';
			        break;
			    case 'kindletitle':
			    	element = '#wpbooklist-kindle-title-id';
			        break;
			    case 'amazonreviewtitle':
			    	element = '#wpbooklist-amazon-review-title-id';
			        break;
			    case 'notestitle':
			    	element = '#wpbooklist-notes-title-id';
			        break;
			    case 'purchaseimages':
			    	element = '.wpbooklist-purchase-img img';
			        break;
			    case 'coverimage':
			    	element = '#wpbooklist_cover_image_popup';
			        break;
			    case 'similarcoverimage':
			    	element = '.wpbooklist-similar-image';
			        break;
			    case 'sharingimage':
			    	element = '#colorbox #wpbooklist_display_image_container .addthis_sharing_toolbox .at-icon, #colorbox #wpbooklist_display_image_container .addthis_sharing_toolbox .at-icon-wrapper';
			        break;
			    case 'detaillabels':
			    	element = '.wpbooklist-bold-stats-class';
			        break;
			    case 'bookdetails':
			    	element = '.wpbooklist-bold-stats-value';
			        break;
			    case 'bookpagelink':
			    	element = '.wpbooklist-bold-stats-page';
			        break;
			    case 'storefrontpurchase':
			    	element = '.wpbooklist-purchase-book-text-link';
			        break;
			    case 'sortby':
			    	element = '#wpbooklist-sort-select-box';
			        break;
			    case 'searchtext':
			    	element = '#wpbooklist-search-checkboxes p';
			        break;
			    case 'searchbox':
			    	element = '#wpbooklist-search-text';
			        break;
			    case 'searchbutton':
			    	element = '#wpbooklist-search-sub-button';
			        break;
			    case 'statsdiv':
			    	element = '.wpbooklist_stats_tdiv';
			        break;
			    case 'statsdivp':
			    	element = '.wpbooklist_stats_tdiv p';
			        break;
			    case 'quote':
			    	element = '#wpbooklist-quote-actual';
			        break;
			    case 'quoteattribution':
			    	element = '#wpbooklist-attribution-actual';
			        break;
			   	case 'outerbookcontainer':
			    	element = '.wpbooklist_entry_div';
			        break;
			    case 'innerbookcontainer':
			    	element = '.wpbooklist_inner_main_display_div';
			        break;
			    case 'libcoverimage':
			    	element = '.wpbooklist_cover_image_class';
			        break;
			    case 'booktitle':
			    	element = '.wpbooklist_saved_title_link';
			        break;
			    case 'storefrontpurchasetext':
			    	element = '.wpbooklist-frontend-library-price a';
			        break;
			    case 'storefrontpurchaseimage':
			    	element = '.wpbooklist-frontend-library-buy-img img';
			        break;
			    case 'storefrontpurchaseimgtext':
			    	element = '.wpbooklist-frontend-library-buy-img';
			        break; 
			    default:
			}

			// Getting the element to modify
			var domelement = $("#wpbooklist-stylizer-iframe").contents().find(element);

			// Reset css to be changed and get existing inline styles
			domelement.css(newstyle1,'initial');
			var existingstyle = $("#wpbooklist-stylizer-iframe").contents().find(element).attr('style');

			// If there is existing inline styles...
			if(existingstyle != undefined){
          		existbefore = existingstyle.substr(0, existingstyle.indexOf(newstyle1)); 
          		existafter = existingstyle.substr(existingstyle.indexOf(newstyle1)+newstyle1.length+10);
          	}

          	// Apply final styling
	        domelement.attr('style', existbefore+existafter+newstyle1+': '+newstyle2+'!important;');
				


		
		});
  });
  </script>
  <?php
}


/*
 * Below is a boilerplate function with Javascript
 *
/*

// For 
add_action( 'admin_footer', 'wppb_boilerplate_javascript' );

function wppb_boilerplate_action_javascript() { 
  ?>
    <script type="text/javascript" >
    "use strict";
    jQuery(document).ready(function($) {
      $(document).on("click",".wppb-trigger-actions-checkbox", function(event){

        event.preventDefault ? event.preventDefault() : event.returnValue = false;
      });
  });
  </script>
  <?php
}
*/



?>