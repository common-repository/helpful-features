<?php

defined('ABSPATH') or die('No script kiddies please!');

/*
Plugin Name: Helpful Features
Plugin URI: https://wordpress.org/plugins/hefe/
Description: Helpful Features adds features to WordPress.
Author: OXSN
Author URI: https://profiles.wordpress.org/oxsn
Version: 0.3.24
*/

define('hefe_basename', plugin_basename(__FILE__));
define('hefe_dir_path', plugin_dir_path(__FILE__));
define('hefe_dir_url', plugin_dir_url(__FILE__));

if(get_option('hefe_control_customizer_control_shortcode_name')){
	define('hefe_shortcode_name', str_replace(' ', '', strtolower(get_option('hefe_control_customizer_control_shortcode_name'))));
}else{
	define('hefe_shortcode_name', 'hefe');
}

/* Action Links
------------------------------ */

if(!function_exists('hefe_action_links')){
	add_filter('plugin_action_links', 'hefe_action_links', 10, 2);
	function hefe_action_links($links, $file){
		if($file != hefe_basename)
			return $links;
			$settings_page = '<a href="'.site_url().'/wp-admin/customize.php?autofocus[panel]=hefe_customizer_panel">'.esc_html(__('Settings', 'hefe')).'</a>';
			$documentation_page = '<a href="'.site_url().'/wp-admin/tools.php?page=hefe-tools&tab=getting-started">'.esc_html(__('Documentation', 'hefe')).'</a>';
			array_unshift($links, $settings_page, $documentation_page);
			return $links;
	}
}

/* Tools
------------------------------ */

if(is_admin()){

	// Create Menu Link
	if(!function_exists('hefe_wp_dashboard_tools_menu_tools')){
		add_action( 'admin_menu', 'hefe_wp_dashboard_tools_menu_tools' );
		function hefe_wp_dashboard_tools_menu_tools() {
			add_management_page( 'Helpful Features', 'Helpful Features', 'manage_options', 'hefe-tools', 'hefe_menu_tools_page' );
		}
	}

	// Create Menu Page
	if(!function_exists('hefe_menu_tools_page')){
		function hefe_menu_tools_page() {

			if ( !current_user_can( 'manage_options' ) )  {
				wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
			}

			$hefe_tools_page = '';

			$hefe_tools_page .= '<div class="wrap">';

				$hefe_tools_page .= '<h1><center><strong>Helpful Features</strong></center></h1>';

				settings_errors();
				if(isset($_GET['tab'])){
					$active_tab = isset($_GET['tab']) ? $_GET['tab'] : 'features';
				}else{
					$active_tab = 'getting-started';
				}

				$hefe_tools_page .= '<h2 class="nav-tab-wrapper">';

					$hefe_tools_page .= '<a href="?page=hefe-tools&tab=getting-started" class="nav-tab '; 
					$hefe_tools_page .= $active_tab == 'getting-started' ? 'nav-tab-active' : '';
					$hefe_tools_page .= '">Getting Started</a>';
					$hefe_tools_page .= '<a href="?page=hefe-tools&tab=controls" class="nav-tab '; 
					$hefe_tools_page .= $active_tab == 'controls' ? 'nav-tab-active' : '';
					$hefe_tools_page .= '">Controls</a>';
					$hefe_tools_page .= '<a href="?page=hefe-tools&tab=enqueues" class="nav-tab '; 
					$hefe_tools_page .= $active_tab == 'enqueues' ? 'nav-tab-active' : '';
					$hefe_tools_page .= '">Enqueues</a>';
					$hefe_tools_page .= '<a href="?page=hefe-tools&tab=widgets" class="nav-tab '; 
					$hefe_tools_page .= $active_tab == 'widgets' ? 'nav-tab-active' : '';
					$hefe_tools_page .= '">Widgets</a>';

				$hefe_tools_page .= '</h2>';
				
				// Controls
				if($active_tab == 'controls'){
					$hefe_tools_page .= '<div class="card">';
						$hefe_tools_page .= '<h1><strong>Controls</strong></h1>';
						$hefe_tools_page .= '<p>These are custom controls that you can include in your site.</p>';
						$hefe_tools_page .= '<p>Interested in customizing the <a href="'.site_url().'/wp-admin/customize.php?autofocus[section]=hefe_control_customizer_section" class="button">Settings</a> of this feature?</p>';
					$hefe_tools_page .= '</div>';
					$hefe_tools_page .= '<div class="card">';
						$hefe_tools_page .= '<small>CONTROL</small><h2>ADMIN BAR DISABLE FOR</h2>';
						$hefe_tools_page .= '<p>This is a control that disables the admin bar for only a specific set of user roles. All other user roles are able to see the admin bar when this is set.</p>';
					$hefe_tools_page .= '</div>';
					$hefe_tools_page .= '<div class="card">';
						$hefe_tools_page .= '<small>CONTROL</small><h2>ADMIN BAR ENABLE FOR</h2>';
						$hefe_tools_page .= '<p>This is a control that enables the admin bar for only a specific set of user roles. All other user roles are not able to see the admin bar when this is set.</p>';
					$hefe_tools_page .= '</div>';
					$hefe_tools_page .= '<div class="card">';
						$hefe_tools_page .= '<small>CONTROL</small><h2>BANNER PER PAGE</h2>';
						$hefe_tools_page .= '<p>This is a control that enables a banner custom field on every page. You are able to view this content via a shortcode or widget (if the widget is also enabled).</p>';
					$hefe_tools_page .= '</div>';
					$hefe_tools_page .= '<div class="card">';
						$hefe_tools_page .= '<small>CONTROL</small><h2>CUSTOM AUTHOR BASE</h2>';
						$hefe_tools_page .= '<p>This is a control that changes the default author base in the url.</p>';
					$hefe_tools_page .= '</div>';
					$hefe_tools_page .= '<div class="card">';
						$hefe_tools_page .= '<small>CONTROL</small><h2>FORCED LOGIN</h2>';
						$hefe_tools_page .= '<p>This is a control that forces people to login to a site.</p>';
					$hefe_tools_page .= '</div>';
					$hefe_tools_page .= '<div class="card">';
						$hefe_tools_page .= '<small>CONTROL</small><h2>GOOGLE ANALYTICS</h2>';
						$hefe_tools_page .= '<p>This is a control that uses <a href="https://analytics.google.com" target="_blank">Google Analytics</a> to track the who, what, where, and when people are visiting this site. When you setup a website on <a href="https://analytics.google.com" target="_blank">Google Analytics</a> they give you a tracking number, it usually looks something like this, UA-XXXXXX-X. If you take that tracking number and place it in the input field provided in this section, we will do the rest of the integration.</p>';
					$hefe_tools_page .= '</div>';
					$hefe_tools_page .= '<div class="card">';
						$hefe_tools_page .= '<small>CONTROL</small><h2>GOOGLE FONTS</h2>';
						$hefe_tools_page .= '<p>This is a control that uses <a href="https://fonts.google.com" target="_blank">Google Fonts</a> to add custom fonts to your site. When you setup a font on Google Fonts they give you a URL, it usually looks like this, https://fonts.googleapis.com/.. If you take that URL and place it in the input field provided in this section, we will enqueue the fonts in WordPress. Note, you will still have to add the css into your site manually.</p>';
					$hefe_tools_page .= '</div>';
					$hefe_tools_page .= '<div class="card">';
						$hefe_tools_page .= '<small>CONTROL</small><h2>HOME PAGE ORDERBY</h2>';
						$hefe_tools_page .= '<p>This is a control that changes the order of your home page posts. You can learn more about the parameter options at <a href="https://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters" target="_blank">WordPress</a>.</p>';
						$hefe_tools_page .= '<hr />';
						$hefe_tools_page .= '<p><strong>Options</strong>:<br>none, ID, author, title, name, type, date, modified, parent, rand, comment_count, relevance, menu_order, meta_value, meta_value_num, post__in, post_name__in, post_parent__in</p>';
					$hefe_tools_page .= '</div>';
					$hefe_tools_page .= '<div class="card">';
						$hefe_tools_page .= '<small>CONTROL</small><h2>HOME PAGE ORDER</h2>';
						$hefe_tools_page .= '<p>This is a control that changes the order of your home page posts. You can learn more about the parameter options at <a href="https://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters" target="_blank">WordPress</a>.</p>';
						$hefe_tools_page .= '<hr />';
						$hefe_tools_page .= '<p><strong>Options</strong>:<br>ASC (1, 2, 3; a, b, c), DESC (3, 2, 1; c, b, a)</p>';
					$hefe_tools_page .= '</div>';
					$hefe_tools_page .= '<div class="card">';
						$hefe_tools_page .= '<small>INCLUDE</small><h2>INJECTION</h2>';
						$hefe_tools_page .= '<p>This is a control that creates a custom post type "INJECTION" that will allow you to inject content into your site. Each injection post with ask you if you want to inject it into the header or footer. There is also a shortcode that allows you to inject that post wherever your shortcode is placed.</p>';
					$hefe_tools_page .= '</div>';
					$hefe_tools_page .= '<div class="card">';
						$hefe_tools_page .= '<small>INCLUDE</small><h2>POP OUT SIDEBAR</h2>';
						$hefe_tools_page .= '<p>This is a control that creates a custom sidebar called "POP OUT SIDEBAR" that will allow you to pop out widgets on your site. There is a shortcode called "POP OUT SIDBAR LINK" that acts as the button to open and close your sidebar on your site.</p>';
					$hefe_tools_page .= '</div>';
					$hefe_tools_page .= '<div class="card">';
						$hefe_tools_page .= '<small>CONTROL</small><h2>POST TYPES ON HOME PAGE</h2>';
						$hefe_tools_page .= '<p>This is a control that changes the default post types on the home page.</p>';
					$hefe_tools_page .= '</div>';
					$hefe_tools_page .= '<div class="card">';
						$hefe_tools_page .= '<small>CONTROL</small><h2>POST TYPE STATUS ON HOME PAGE</h2>';
						$hefe_tools_page .= '<p>This is a control that defines the post type status on the home page. You can learn more about the parameter options at <a href="https://codex.wordpress.org/Class_Reference/WP_Query#Status_Parameters" target="_blank">WordPress</a>.</p>';
						$hefe_tools_page .= '<hr />';
						$hefe_tools_page .= '<p><strong>Options</strong>:<br>publish, pending, draft, auto-draft, future, private, inherit, trash, any</p>';
					$hefe_tools_page .= '</div>';
					$hefe_tools_page .= '<div class="card">';
						$hefe_tools_page .= '<small>CONTROL</small><h2>RECAPTCHA PUBLIC KEY</h2>';
						$hefe_tools_page .= '<p>This is a control that changes helps add reCaptcha to your WordPress login screen.</p>';
					$hefe_tools_page .= '</div>';
					$hefe_tools_page .= '<div class="card">';
						$hefe_tools_page .= '<small>CONTROL</small><h2>RECAPTCHA PRIVATE KEY</h2>';
						$hefe_tools_page .= '<p>This is a control that changes helps add reCaptcha to your WordPress login screen.</p>';
					$hefe_tools_page .= '</div>';
					$hefe_tools_page .= '<div class="card">';
						$hefe_tools_page .= '<small>INCLUDE</small><h2>SCROLL UP BOX AUTOMATIC</h2>';
						$hefe_tools_page .= '<p>This is a control that will automatically include the scroll up box link in the bottom right corner of the site as the user scrolls.</p>';
					$hefe_tools_page .= '</div>';
					$hefe_tools_page .= '<div class="card">';
						$hefe_tools_page .= '<small>CONTROL</small><h2>SEARCH PAGE EXCLUDED PAGE IDS</h2>';
						$hefe_tools_page .= '<p>This is a control that excludes specific page ids from the search page results.</p>';
					$hefe_tools_page .= '</div>';
					$hefe_tools_page .= '<div class="card">';
						$hefe_tools_page .= '<small>CONTROL</small><h2>SEARCH PAGE EXCLUDED POST TYPES</h2>';
						$hefe_tools_page .= '<p>This is a control that excludes specific post types from the search page results.</p>';
					$hefe_tools_page .= '</div>';
					$hefe_tools_page .= '<div class="card">';
						$hefe_tools_page .= '<small>INCLUDE</small><h2>SEO</h2>';
						$hefe_tools_page .= '<p>This is a control that will add simple SEO fields into each page for custom SEO options.</p>';
					$hefe_tools_page .= '</div>';
					$hefe_tools_page .= '<div class="card">';
						$hefe_tools_page .= '<small>CONTROL</small><h2>SIDEBAR</h2>';
						$hefe_tools_page .= '<p>This is a a control that allows you to create custom sidebars that you can call with a shortcode on your site.</p>';
					$hefe_tools_page .= '</div>';

				// Enqueues
				}elseif($active_tab == 'enqueues'){
					$hefe_tools_page .= '<div class="card">';
						$hefe_tools_page .= '<h1><strong>Enqueues</strong></h1>';
						$hefe_tools_page .= '<p>These are custom css/js files that you can include in your site. By adding an enqueue it automatically loads these files on every page of your site.</p>';
						$hefe_tools_page .= '<p>Interested in customizing the <a href="'.site_url().'/wp-admin/customize.php?autofocus[section]=hefe_enqueue_customizer_section" class="button">Settings</a> of this feature?</p>';
					$hefe_tools_page .= '</div>';
					$hefe_tools_page .= '<div class="card">';
						$hefe_tools_page .= '<small>ENQUEUE</small><h2>ACCORDION</h2>';
						$hefe_tools_page .= '<p>This is an "Enqueue" for the necessary "Accordion" css/js, in order to work without a shortcode.</p>';
						$hefe_tools_page .= '<hr />';
						$hefe_tools_page .= '<p><strong>Link</strong><br>&lt;div class="hefe-accordion-link" data-paired="PAIRED_ID"&gt;CONTENT&lt;/div&gt;</p>';
						$hefe_tools_page .= '<hr />';
						$hefe_tools_page .= '<p><strong>Content</strong><br>&lt;div class="hefe-accordion-content" data-paired="PAIRED_ID"&gt;CONTENT&lt;/div&gt;</p>';
					$hefe_tools_page .= '</div>';
					$hefe_tools_page .= '<div class="card">';
						$hefe_tools_page .= '<small>ENQUEUE</small><h2>ANIMATE.CSS</h2>';
						$hefe_tools_page .= '<p>This is an "Enqueue" for the necessary "Animate.CSS" css/js, in order to work without a shortcode.</p>';
						$hefe_tools_page .= '<hr />';
						$hefe_tools_page .= '<p><strong>Item</strong><br>&lt;div class="hefe-animate-css-item animated" data-effect="" data-delay=""&gt;CONTENT&lt;/div&gt;</p>';
					$hefe_tools_page .= '</div>';
					$hefe_tools_page .= '<div class="card">';
						$hefe_tools_page .= '<small>ENQUEUE</small><h2>BANNER</h2>';
						$hefe_tools_page .= '<p>This is an "Enqueue" for the necessary "Banner" css/js, in order to work without a shortcode.</p>';
						$hefe_tools_page .= '<hr />';
						$hefe_tools_page .= '<p><strong>Banner</strong><br>&lt;div class="hefe-banner"&gt;CONTENT&lt;div class="hefe-banner-image" style="background-image: url(IMAGE_URL);"&gt;&lt;/div&gt;&lt;/div&gt;</p>';
					$hefe_tools_page .= '</div>';
					$hefe_tools_page .= '<div class="card">';
						$hefe_tools_page .= '<small>ENQUEUE</small><h2>BOOTSTRAP</h2>';
						$hefe_tools_page .= '<p>This is an "Enqueue" for the necessary "Bootstrap" css/js, in order to work without a shortcode.</p>';
						$hefe_tools_page .= '<hr />';
						$hefe_tools_page .= '<p><strong>Container Fluid</strong><br>&lt;div class="hefe-bootstrap-container-fluid container-fluid"&gt;CONTENT&lt;/div&gt;</p>';
						$hefe_tools_page .= '<hr />';
						$hefe_tools_page .= '<p><strong>Container</strong><br>&lt;div class="hefe-bootstrap-container container"&gt;CONTENT&lt;/div&gt;</p>';
						$hefe_tools_page .= '<hr />';
						$hefe_tools_page .= '<p><strong>Row</strong><br>&lt;div class="hefe-bootstrap-row row"&gt;CONTENT&lt;/div&gt;</p>';
						$hefe_tools_page .= '<hr />';
						$hefe_tools_page .= '<p><strong>Column</strong><br>&lt;div class="hefe-bootstrap-column col-md-12"&gt;CONTENT&lt;/div&gt;</p>';
					$hefe_tools_page .= '</div>';
					$hefe_tools_page .= '<div class="card">';
						$hefe_tools_page .= '<small>ENQUEUE</small><h2>BOOTSTRAP GRID ONLY</h2>';
						$hefe_tools_page .= '<p>This is an "Enqueue" for the necessary "Bootstrap Grid" css/js, in order to work without a shortcode.</p>';
						$hefe_tools_page .= '<hr />';
						$hefe_tools_page .= '<p><strong>Container Fluid</strong><br>&lt;div class="hefe-bootstrap-container-fluid container-fluid"&gt;CONTENT&lt;/div&gt;</p>';
						$hefe_tools_page .= '<hr />';
						$hefe_tools_page .= '<p><strong>Container</strong><br>&lt;div class="hefe-bootstrap-container container"&gt;CONTENT&lt;/div&gt;</p>';
						$hefe_tools_page .= '<hr />';
						$hefe_tools_page .= '<p><strong>Row</strong><br>&lt;div class="hefe-bootstrap-row row"&gt;CONTENT&lt;/div&gt;</p>';
						$hefe_tools_page .= '<hr />';
						$hefe_tools_page .= '<p><strong>Column</strong><br>&lt;div class="hefe-bootstrap-column col-md-12"&gt;CONTENT&lt;/div&gt;</p>';
					$hefe_tools_page .= '</div>';
					$hefe_tools_page .= '<div class="card">';
						$hefe_tools_page .= '<small>ENQUEUE</small><h2>BUTTON</h2>';
						$hefe_tools_page .= '<p>This is an "Enqueue" for the necessary "Button" css/js, in order to work without a shortcode.</p>';
						$hefe_tools_page .= '<hr />';
						$hefe_tools_page .= '<p><strong>Button</strong><br>&lt;a class="hefe-button" href=""&gt;CONTENT&lt;/a&gt;</p>';
					$hefe_tools_page .= '</div>';
					$hefe_tools_page .= '<div class="card">';
						$hefe_tools_page .= '<small>ENQUEUE</small><h2>CENTER</h2>';
						$hefe_tools_page .= '<p>This is an "Enqueue" for the necessary "Center" css/js, in order to work without a shortcode.</p>';
						$hefe_tools_page .= '<hr />';
						$hefe_tools_page .= '<p><strong>Center</strong><br>&lt;div class="hefe-center-parent"&gt;&lt;div class="hefe-center-child"&gt;CONTENT&lt;/div&gt;&lt;/div&gt;</p>';
					$hefe_tools_page .= '</div>';
					$hefe_tools_page .= '<div class="card">';
						$hefe_tools_page .= '<small>ENQUEUE</small><h2>FANCYBOX</h2>';
						$hefe_tools_page .= '<p>This is an "Enqueue" for the necessary "fancyBox" css/js, in order to work without a shortcode.</p>';
						$hefe_tools_page .= '<hr />';
						$hefe_tools_page .= '<p><strong>Link</strong><br>&lt;a data-fancybox class="hefe-fancybox-link" href="image.jpg"&gt;&lt;/a&gt;</p>';
						$hefe_tools_page .= '<hr />';
						$hefe_tools_page .= '<p><strong>Inline Link</strong><br>&lt;a data-fancybox data-src="#PAIRED_ID" class="hefe-fancybox-inline-link" href="javascript:;"&gt;CONTENT&lt;/a&gt;</p>';
						$hefe_tools_page .= '<hr />';
						$hefe_tools_page .= '<p><strong>Inline Content</strong><br>&lt;div id="PAIRED_ID" class="hefe-fancybox-inline-content" style="display: none"&gt;CONTENT&lt;/div&gt;</p>';
					$hefe_tools_page .= '</div>';
					$hefe_tools_page .= '<div class="card">';
						$hefe_tools_page .= '<small>ENQUEUE</small><h2>FANCYBOX AUTOMATIC LINK</h2>';
						$hefe_tools_page .= '<p>This is an "Enqueue" for the necessary "fancyBox Automatic Links" css/js, in order to work without a shortcode.</p>';
					$hefe_tools_page .= '</div>';
					$hefe_tools_page .= '<div class="card">';
						$hefe_tools_page .= '<small>ENQUEUE</small><h2>FONT AWESOME</h2>';
						$hefe_tools_page .= '<p>This is an "Enqueue" for the necessary "Font Awesome" css/js, in order to work without a shortcode.</p>';
						$hefe_tools_page .= '<hr />';
						$hefe_tools_page .= '<p><strong>Icon</strong><br>&lt;i class="hefe-font-awesome fas fa-check"&gt;&lt;/i&gt;</p>';
					$hefe_tools_page .= '</div>';
					$hefe_tools_page .= '<div class="card">';
						$hefe_tools_page .= '<small>ENQUEUE</small><h2>FRONT-END MEDIA</h2>';
						$hefe_tools_page .= '<p>This is an "Enqueue" for the necessary "Front-End Media" css/js, in order to work.</p>';
						$hefe_tools_page .= '<hr />';
						$hefe_tools_page .= '<p><strong>Link</strong><br>&lt;a data-attachment="ATTACHMENT_ID" class="hefe-front-end-media-link" href="#"&gt;CONTENT&lt;/a&gt;</p>';
					$hefe_tools_page .= '</div>';
					$hefe_tools_page .= '<div class="card">';
						$hefe_tools_page .= '<small>ENQUEUE</small><h2>HORIZONTAL LIST</h2>';
						$hefe_tools_page .= '<p>This is an "Enqueue" for the necessary "Horizontal List" css/js, in order to work without a shortcode.</p>';
						$hefe_tools_page .= '<hr />';
						$hefe_tools_page .= '<p><strong>Parent</strong><br>&lt;ul class="hefe-horizontal-list-parent"&gt;CONTENT&lt;/ul&gt;</p>';
						$hefe_tools_page .= '<hr />';
						$hefe_tools_page .= '<p><strong>Child</strong><br>&lt;li class="hefe-horizontal-list-child"&gt;CONTENT&lt;/li&gt;</p>';
					$hefe_tools_page .= '</div>';
					$hefe_tools_page .= '<div class="card">';
						$hefe_tools_page .= '<small>ENQUEUE</small><h2>HTML5SHIV</h2>';
						$hefe_tools_page .= '<p>This is an "Enqueue" for the necessary "HTML5SHIV" css/js, in order to work.</p>';
					$hefe_tools_page .= '</div>';
					$hefe_tools_page .= '<div class="card">';
						$hefe_tools_page .= '<small>ENQUEUE</small><h2>ISOTOPE</h2>';
						$hefe_tools_page .= '<p>This is an "Enqueue" for the necessary "Isotope" css/js, in order to work without a shortcode.</p>';
						$hefe_tools_page .= '<hr />';
						$hefe_tools_page .= '<p><strong>Parent</strong><br>&lt;div class="hefe-isotope-wrap"&gt;&lt;div class="hefe-isotope-parent hefe-isotope-parent-default"&gt;&lt;div class="hefe-isotope-child-column-width"&gt;&lt;/div&gt;&lt;div class="hefe-isotope-gutter-width"&gt;&lt;/div&gt;CONTENT&lt;/div&gt;&lt;/div&gt;</p>';
						$hefe_tools_page .= '<hr />';
						$hefe_tools_page .= '<p><strong>Child</strong><br>&lt;div class="hefe-isotope-child"&gt;CONTENT&lt;/div&gt;</p>';
					$hefe_tools_page .= '</div>';
					$hefe_tools_page .= '<div class="card">';
						$hefe_tools_page .= '<small>ENQUEUE</small><h2>MATCHHEIGHT</h2>';
						$hefe_tools_page .= '<p>This is an "Enqueue" for the necessary "MatchHeight" css/js, in order to work without a shortcode.</p>';
						$hefe_tools_page .= '<hr />';
						$hefe_tools_page .= '<p><strong>HTML</strong><br>&lt;div class="hefe-match-height-item"&gt;CONTENT&lt;/div&gt;</p>';
					$hefe_tools_page .= '</div>';
					$hefe_tools_page .= '<div class="card">';
						$hefe_tools_page .= '<small>ENQUEUE</small><h2>NORMALIZE</h2>';
						$hefe_tools_page .= '<p>This is an "Enqueue" for the necessary "Normalize" css/js, in order to work.</p>';
					$hefe_tools_page .= '</div>';
					$hefe_tools_page .= '<div class="card">';
						$hefe_tools_page .= '<small>ENQUEUE</small><h2>PLACEHOLDERS</h2>';
						$hefe_tools_page .= '<p>This is an "Enqueue" for the necessary "Placeholders" css/js, in order to work.</p>';
					$hefe_tools_page .= '</div>';
					$hefe_tools_page .= '<div class="card">';
						$hefe_tools_page .= '<small>ENQUEUE</small><h2>POP OUT SIDEBAR</h2>';
						$hefe_tools_page .= '<p>This is an "Enqueue" for the necessary "Pop Out Sidebar" css/js, in order to work without a shortcode.</p>';
						$hefe_tools_page .= '<p>You must active this feature in "Control" before this will work.</p>';
						$hefe_tools_page .= '<hr />';
						$hefe_tools_page .= '<p><strong>Link</strong><br>&lt;a class="hefe-pop-out-sidebar-link hefe-pop-out-sidebar-toggle" data-side="right"&gt;CONTENT&lt;/a&gt;</p>';
					$hefe_tools_page .= '</div>';
					$hefe_tools_page .= '<div class="card">';
						$hefe_tools_page .= '<small>ENQUEUE</small><h2>RANDOM DISPLAY</h2>';
						$hefe_tools_page .= '<p>This is an "Enqueue" for the necessary "Random Display" css/js, in order to work without a shortcode.</p>';
						$hefe_tools_page .= '<hr />';
						$hefe_tools_page .= '<p><strong>Parent</strong><br>&lt;div class="hefe-random-display-parent"&gt;CHILD&lt;/div&gt;</p>';
						$hefe_tools_page .= '<hr />';
						$hefe_tools_page .= '<p><strong>Child</strong><br>&lt;div class="hefe-random-display-child"&gt;CONTENT&lt;/div&gt;</p>';
					$hefe_tools_page .= '</div>';
					$hefe_tools_page .= '<div class="card">';
						$hefe_tools_page .= '<small>ENQUEUE</small><h2>RANDOM ORDER</h2>';
						$hefe_tools_page .= '<p>This is an "Enqueue" for the necessary "Random Order" css/js, in order to work without a shortcode.</p>';
						$hefe_tools_page .= '<hr />';
						$hefe_tools_page .= '<p><strong>Parent</strong><br>&lt;div class="hefe-random-order-parent"&gt;CHILD&lt;/div&gt;</p>';
						$hefe_tools_page .= '<hr />';
						$hefe_tools_page .= '<p><strong>Child</strong><br>&lt;div class="hefe-random-order-child"&gt;CONTENT&lt;/div&gt;</p>';
					$hefe_tools_page .= '</div>';
					$hefe_tools_page .= '<div class="card">';
						$hefe_tools_page .= '<small>ENQUEUE</small><h2>REVEAL</h2>';
						$hefe_tools_page .= '<p>This is an "Enqueue" for the necessary "Reveal" css/js, in order to work without a shortcode.</p>';
						$hefe_tools_page .= '<hr />';
						$hefe_tools_page .= '<p><strong>Parent</strong><br>&lt;div class="hefe-reveal-parent"&gt;CHILD&lt;/div&gt;</p>';
						$hefe_tools_page .= '<hr />';
						$hefe_tools_page .= '<p><strong>Child Over</strong><br>&lt;div class="hefe-reveal-child hefe-reveal-child-over"&gt;CONTENT&lt;/div&gt;</p>';
						$hefe_tools_page .= '<hr />';
						$hefe_tools_page .= '<p><strong>Child Under</strong><br>&lt;div class="hefe-reveal-child hefe-reveal-child-under"&gt;CONTENT&lt;/div&gt;</p>';
					$hefe_tools_page .= '</div>';
					$hefe_tools_page .= '<div class="card">';
						$hefe_tools_page .= '<small>ENQUEUE</small><h2>RESPOND</h2>';
						$hefe_tools_page .= '<p>This is an "Enqueue" for the necessary "Respond" css/js, in order to work.</p>';
					$hefe_tools_page .= '</div>';
					$hefe_tools_page .= '<div class="card">';
						$hefe_tools_page .= '<small>ENQUEUE</small><h2>SCROLL TO</h2>';
						$hefe_tools_page .= '<p>This is an "Enqueue" for the necessary "Scroll To" css/js, in order to work without a shortcode.</p>';
						$hefe_tools_page .= '<hr />';
						$hefe_tools_page .= '<p><strong>Link</strong><br>&lt;div class="hefe-scroll-to-link" data-paired="PAIRED_ID"&gt;CONTENT&lt;/div&gt;</p>';
						$hefe_tools_page .= '<hr />';
						$hefe_tools_page .= '<p><strong>Content</strong><br>&lt;div class="hefe-scroll-to-content" data-paired="PAIRED_ID"&gt;CONTENT&lt;/div&gt;</p>';
					$hefe_tools_page .= '</div>';
					$hefe_tools_page .= '<div class="card">';
						$hefe_tools_page .= '<small>ENQUEUE</small><h2>SCROLL UP BOX</h2>';
						$hefe_tools_page .= '<p>This is an "Enqueue" for the necessary "Scroll Up Box" css/js, in order to work without a shortcode.</p>';
						$hefe_tools_page .= '<hr />';
						$hefe_tools_page .= '<p><strong>Item</strong><br>&lt;div class="hefe-scroll-up-box"&gt;CONTENT&lt;/div&gt;</p>';
					$hefe_tools_page .= '</div>';
					$hefe_tools_page .= '<div class="card">';
						$hefe_tools_page .= '<small>ENQUEUE</small><h2>SEARCH MODAL</h2>';
						$hefe_tools_page .= '<p>This is an "Enqueue" for the necessary "Search Modal" css/js, in order to work without a shortcode.</p>';
						$hefe_tools_page .= '<hr />';
						$hefe_tools_page .= '<p><strong>Link</strong><br>&lt;a class="hefe-search-modal-link hefe-search-modal-toggle-in"&gt;CONTENT&lt;/a&gt;</p>';
					$hefe_tools_page .= '</div>';
					$hefe_tools_page .= '<div class="card">';
						$hefe_tools_page .= '<small>ENQUEUE</small><h2>SELECTIVIZR</h2>';
						$hefe_tools_page .= '<p>This is an "Enqueue" for the necessary "Selectivizr" css/js, in order to work.</p>';
					$hefe_tools_page .= '</div>';
					$hefe_tools_page .= '<div class="card">';
						$hefe_tools_page .= '<small>ENQUEUE</small><h2>SIMPLE CAROUSEL</h2>';
						$hefe_tools_page .= '<p>TThis is an "Enqueue" for the necessary "Simple Carousel" css/js, in order to work without a shortcode.</p>';
						$hefe_tools_page .= '<hr />';
						$hefe_tools_page .= '<p><strong>Parent</strong><br>&lt;div class="hefe-simple-carousel-parent hefe-simple-carousel-parent-default"&gt;CHILD&lt;/div&gt;</p>';
						$hefe_tools_page .= '<hr />';
						$hefe_tools_page .= '<p><strong>Child</strong><br>&lt;div class="hefe-simple-carousel-child"&gt;CONTENT&lt;/div&gt;</p>';
					$hefe_tools_page .= '</div>';
					$hefe_tools_page .= '<div class="card">';
						$hefe_tools_page .= '<small>ENQUEUE</small><h2>STICKY</h2>';
						$hefe_tools_page .= '<p>This is an "Enqueue" for the necessary "Sticky" css/js, in order to work without a shortcode.</p>';
						$hefe_tools_page .= '<hr />';
						$hefe_tools_page .= '<p><strong>Item</strong><br>&lt;div class="hefe-sticky-placement"&gt&lt;/div&gt&lt;div class="hefe-sticky-item"&gt;CONTENT&lt;/div&gt;</p>';
					$hefe_tools_page .= '</div>';
					$hefe_tools_page .= '<div class="card">';
						$hefe_tools_page .= '<small>ENQUEUE</small><h2>SUDO SLIDER</h2>';
						$hefe_tools_page .= '<p>TThis is an "Enqueue" for the necessary "Sudo Slider" css/js, in order to work without a shortcode.</p>';
						$hefe_tools_page .= '<hr />';
						$hefe_tools_page .= '<p><strong>Parent</strong><br>&lt;div class="hefe-sudo-slider-parent hefe-sudo-slider-parent-default"&gt;&lt;div class="hefe-sudo-slider-parent-interior"&gt;CHILD&lt;/div&gt;&lt;/div&gt;</p>';
						$hefe_tools_page .= '<hr />';
						$hefe_tools_page .= '<p><strong>Child</strong><br>&lt;div class="hefe-sudo-slider-child"&gt;&lt;/div&gt;</p>';
					$hefe_tools_page .= '</div>';
					$hefe_tools_page .= '<div class="card">';
						$hefe_tools_page .= '<small>ENQUEUE</small><h2>TABS</h2>';
						$hefe_tools_page .= '<p>This is an "Enqueue" for the necessary "Tabs" css/js, in order to work without a shortcode.</p>';
						$hefe_tools_page .= '<hr />';
						$hefe_tools_page .= '<p><strong>Link</strong><br>&lt;div class="hefe-tabs-link" data-paired="PAIRED_ID"&gt;&lt;/div&gt;</p>';
						$hefe_tools_page .= '<hr />';
						$hefe_tools_page .= '<p><strong>Content</strong><br>&lt;div class="hefe-tabs-content" data-paired="PAIRED_ID"&gt;&lt;/div&gt;</p>';
					$hefe_tools_page .= '</div>';
					$hefe_tools_page .= '<div class="card">';
						$hefe_tools_page .= '<small>ENQUEUE</small><h2>TOOLTIP</h2>';
						$hefe_tools_page .= '<p>This is an "Enqueue" for the necessary "Tooltip" css/js, in order to work without a shortcode.</p>';
						$hefe_tools_page .= '<hr />';
						$hefe_tools_page .= '<p><strong>Parent</strong><br>&lt;div class="hefe-tooltip-parent hefe-top-center-sm-tooltip"&gt;&lt;/div&gt;</p>';
						$hefe_tools_page .= '<hr />';
						$hefe_tools_page .= '<p><strong>Child</strong><br>&lt;span class="hefe-tooltip-child"&gt;CONTENT&lt;/span&gt;</p>';
					$hefe_tools_page .= '</div>';
					$hefe_tools_page .= '<div class="card">';
						$hefe_tools_page .= '<small>ENQUEUE</small><h2>TWENTYTWENTY</h2>';
						$hefe_tools_page .= '<p>This is an "Enqueue" for the necessary "TwentyTwenty" css/js, in order to work without a shortcode.</p>';
						$hefe_tools_page .= '<hr />';
						$hefe_tools_page .= '<p><strong>Parent</strong><br>&lt;div class="hefe-twentytwenty-parent"&gt;&lt;/div&gt;</p>';
						$hefe_tools_page .= '<hr />';
						$hefe_tools_page .= '<p><strong>Child</strong><br>&lt;img class="hefe-twentytwenty-child" src="" /&gt;</p>';
					$hefe_tools_page .= '</div>';
					$hefe_tools_page .= '<div class="card">';
						$hefe_tools_page .= '<small>ENQUEUE</small><h2>VIDEO PLAYER</h2>';
						$hefe_tools_page .= '<p>This is an "Enqueue" for the necessary "Video Player" css/js, in order to work without a shortcode.</p>';
						$hefe_tools_page .= '<hr />';
						$hefe_tools_page .= '<p><strong>Video</strong><br>&lt;div class="hefe-video-player-html-parent"&gt;&lt;video width="560" height="315" preload="" poster="" controls&gt;&lt;source src="video.mp4" type="video/mp4"&gt;&lt;source src="video.ogv" type="video/ogg"&gt;&lt;source src="video.webm" type="video/webm"&gt;&lt;/video&gt;&lt;/div&gt;</p>';
					$hefe_tools_page .= '</div>';

				// Widgets
				}elseif($active_tab == 'widgets'){
					$hefe_tools_page .= '<div class="card">';
						$hefe_tools_page .= '<h1><strong>Widgets</strong></h1>';
						$hefe_tools_page .= '<p>These are custom widgets that you can include in your site.</p>';
						$hefe_tools_page .= '<p>Interested in customizing the <a href="'.site_url().'/wp-admin/customize.php?autofocus[section]=hefe_widget_customizer_section" class="button">Settings</a> of this feature?</p>';
					$hefe_tools_page .= '</div>';
					$hefe_tools_page .= '<div class="card">';
						$hefe_tools_page .= '<small>WIDGET</small><h2>ACCORDION</h2>';
						$hefe_tools_page .= '<p>This is a widget that displays an accordion. It has input fields that allow you to add link text, content text, an active default, and select one of our prebuilt styles.</p>';
					$hefe_tools_page .= '</div>';
					$hefe_tools_page .= '<div class="card">';
						$hefe_tools_page .= '<small>WIDGET</small><h2>ACCORDION CONTENT</h2>';
						$hefe_tools_page .= '<p>This is a widget that displays an accordion content. It has input fields that allow you to add content text, a paired ID, an active default, and select one of our prebuilt styles.</p>';
					$hefe_tools_page .= '</div>';
					$hefe_tools_page .= '<div class="card">';
						$hefe_tools_page .= '<small>WIDGET</small><h2>ACCORDION LINK</h2>';
						$hefe_tools_page .= '<p>This is a widget that displays an accordion link. It has input fields that allow you to add link text, a paired ID, an active default, and select one of our prebuilt styles.</p>';
					$hefe_tools_page .= '</div>';
					$hefe_tools_page .= '<div class="card">';
						$hefe_tools_page .= '<small>WIDGET</small><h2>BANNER</h2>';
						$hefe_tools_page .= '<p>This is a widget that displays a banner. It has input fields that allow you to add src image/video urls, custom banner heights, and banner content.</p>';
					$hefe_tools_page .= '</div>';
					$hefe_tools_page .= '<div class="card">';
						$hefe_tools_page .= '<small>WIDGET</small><h2>BANNER PER PAGE</h2>';
						$hefe_tools_page .= '<p>This is a widget that displays a banner per page with custom fields.</p>';
					$hefe_tools_page .= '</div>';
					$hefe_tools_page .= '<div class="card">';
						$hefe_tools_page .= '<small>WIDGET</small><h2>BREADCRUMBS</h2>';
						$hefe_tools_page .= '<p>This is a widget that displays site breadcrumbs.</p>';
					$hefe_tools_page .= '</div>';
					$hefe_tools_page .= '<div class="card">';
						$hefe_tools_page .= '<small>WIDGET</small><h2>BUTTON</h2>';
						$hefe_tools_page .= '<p>This is a widget that displays a button. It has input fields that allow you to add link content, a link url, and select one of our prebuilt styles.</p>';
					$hefe_tools_page .= '</div>';
					$hefe_tools_page .= '<div class="card">';
						$hefe_tools_page .= '<small>WIDGET</small><h2>COPYRIGHT</h2>';
						$hefe_tools_page .= '<p>This is a widget that displays copyright information. It has an input field that allows you to replace the copyright information with unique text.</p>';
					$hefe_tools_page .= '</div>';
					$hefe_tools_page .= '<div class="card">';
						$hefe_tools_page .= '<small>WIDGET</small><h2>FANCYBOX INLINE</h2>';
						$hefe_tools_page .= '<p>This is a widget that displays a fancyBox Inline. It has input fields that allow you to add link text, content text, and select a group.</p>';
					$hefe_tools_page .= '</div>';
					$hefe_tools_page .= '<div class="card">';
						$hefe_tools_page .= '<small>WIDGET</small><h2>HORIZONTAL LIST</h2>';
						$hefe_tools_page .= '<p>This is a widget that displays a horizontal list. It has input fields that allow you to add link text, open new tab dropdown, content text, and responsive activation dropdown.</p>';
					$hefe_tools_page .= '</div>';
					$hefe_tools_page .= '<div class="card">';
						$hefe_tools_page .= '<small>WIDGET</small><h2>RELATED ARTICLE</h2>';
						$hefe_tools_page .= '<p>This is a widget that displays a related article. It has input fields that allow you to change which related articles are displayed.</p>';
					$hefe_tools_page .= '</div>';
					$hefe_tools_page .= '<div class="card">';
						$hefe_tools_page .= '<small>WIDGET</small><h2>REVEAL</h2>';
						$hefe_tools_page .= '<p>This is a widget that displays a reveal area. It has input fields that allow you to create "over" content when you see first, and "under" content which you see when you hover over the "over" content.</p>';
					$hefe_tools_page .= '</div>';
					$hefe_tools_page .= '<div class="card">';
						$hefe_tools_page .= '<small>WIDGET</small><h2>SITE IDENTITY</h2>';
						$hefe_tools_page .= '<p>This is a widget that displays site identity information. It has a dropdown field that allows you to replace the site identity with specific site identity content.</p>';
						$hefe_tools_page .= '<hr />';
						$hefe_tools_page .= '<p><strong>Atts:</strong><br>Site Title, Site Title & Description, Site Logo</p>';
					$hefe_tools_page .= '</div>';
					$hefe_tools_page .= '<div class="card">';
						$hefe_tools_page .= '<small>WIDGET</small><h2>TABS</h2>';
						$hefe_tools_page .= '<p>This is a widget that displays an tabs. It has input fields that allow you to add link text, content text, an active default, and select one of our prebuilt styles.</p>';
					$hefe_tools_page .= '</div>';
					$hefe_tools_page .= '<div class="card">';
						$hefe_tools_page .= '<small>WIDGET</small><h2>TABS CONTENT</h2>';
						$hefe_tools_page .= '<p>This is a widget that displays a tabs content. It has input fields that allow you to add content text, a paired ID, an active default, and select one of our prebuilt styles.</p>';
					$hefe_tools_page .= '</div>';
					$hefe_tools_page .= '<div class="card">';
						$hefe_tools_page .= '<small>WIDGET</small><h2>TABS LINK</h2>';
						$hefe_tools_page .= '<p>This is a widget that displays a tabs link. It has input fields that allow you to add link text, a paired ID, an active default, and select one of our prebuilt styles.</p>';
					$hefe_tools_page .= '</div>';
					$hefe_tools_page .= '<div class="card">';
						$hefe_tools_page .= '<small>WIDGET</small><h2>TWENTYTWENTY</h2>';
						$hefe_tools_page .= '<p>This is a widget that displays a twentytwenty before/after image. It has input fields that allow you to create a "before" image and "after" image.</p>';
					$hefe_tools_page .= '</div>';

				// Getting Started
				}else{
					$hefe_tools_page .= '<div class="card">';
						$hefe_tools_page .= '<h1><strong>Getting Started</strong></h1>';
						$hefe_tools_page .= '<p>Welcome to the "Helpful Features" getting started page. Click through the tabs above to learn more about all of the included features within this plugin. Also, click the button below to see all the customizable options we have for each feature.</p>';
						$hefe_tools_page .= '<p>Also, unless otherwise noted, when using the shortcodes below, you do not need to enqueue any code. It just works like magic.</p>';
						$hefe_tools_page .= '<p>View the <a href="'.site_url().'/wp-admin/customize.php?autofocus[panel]=hefe_customizer_panel" class="button">Settings</a> of all features.</p>';
					$hefe_tools_page .= '</div>';
					$hefe_tools_page .= '<div class="card">';
						$hefe_tools_page .= '<small>SHORTCODE</small><h2>ACCORDION CONTENT</h2>';
						$hefe_tools_page .= '<p>This is a shortcode that acts as a content section for an accordion function. Copy the "PAIRED_ID" attribute with the "Accordion Link" shortcode and it will be toggled open and closed.</p>';
						$hefe_tools_page .= '<p><strong>Usage</strong><br>['.hefe_shortcode_name.'_accordion_content class="" paired_id=""]<br>CONTENT<br>[/'.hefe_shortcode_name.'_accordion_content]</p>';
						$hefe_tools_page .= '<hr />';
						$hefe_tools_page .= '<p><strong>Alts</strong><br>'.hefe_shortcode_name.'_click_slide_content</p>';
						$hefe_tools_page .= '<hr />';
						$hefe_tools_page .= '<p><strong>Atts</strong><br>id, class, paired_id, style (none, 01, 02), active (true, false, blank), etc</p>';
					$hefe_tools_page .= '</div>';
					$hefe_tools_page .= '<div class="card">';
						$hefe_tools_page .= '<small>SHORTCODE</small><h2>ACCORDION LINK</h2>';
						$hefe_tools_page .= '<p>This is a shortcode that acts as a link for an accordion function. Copy the "PAIRED_ID" attribute with the "Accordion Content" shortcode and it will toggle open and close the content.</p>';
						$hefe_tools_page .= '<p>You can automatically open an accordion via a URL. Simply add the parameter ?accordion_01=paired_id (_02, _03, _04, _05, _06).</p>';
						$hefe_tools_page .= '<p><strong>Usage</strong><br>['.hefe_shortcode_name.'_accordion_link class="" paired_id=""]<br>CONTENT<br>[/'.hefe_shortcode_name.'_accordion_link]</p>';
						$hefe_tools_page .= '<hr />';
						$hefe_tools_page .= '<p><strong>Alts</strong><br>'.hefe_shortcode_name.'_click_slide_link</p>';
						$hefe_tools_page .= '<hr />';
						$hefe_tools_page .= '<p><strong>Atts</strong><br>id, class, paired_id, wrap (defaults: div), style (none, 01, 02), active (true, false, blank), etc</p>';
					$hefe_tools_page .= '</div>';
					$hefe_tools_page .= '<div class="card">';
						$hefe_tools_page .= '<small>SHORTCODE</small><h2>ACF FIELD</h2>';
						$hefe_tools_page .= '<p>This is a shortcode that will display acf field content.</p>';
						$hefe_tools_page .= '<p><strong>Usage</strong><br>['.hefe_shortcode_name.'_acf_field name=""]</p>';
						$hefe_tools_page .= '<hr />';
						$hefe_tools_page .= '<p><strong>Atts:</strong><br>name, page_id, format</p>';
					$hefe_tools_page .= '</div>';
					$hefe_tools_page .= '<div class="card">';
						$hefe_tools_page .= '<small>SHORTCODE</small><h2>ACF FORM</h2>';
						$hefe_tools_page .= '<p>This is a shortcode that will display an acf form. For this shortcode to work, you must place "acf_form_head();" before "get_header();"</p>';
						$hefe_tools_page .= '<p><strong>Usage</strong><br>New Content: ['.hefe_shortcode_name.'_acf_form user_roles="" post_id="new_post" post_title="true" post_content="true" return="%post_url%" field_groups="" post_type="post" post_status="publish" supdated_message="" submit_value="Submit"]<br>Edit Content: ['.hefe_shortcode_name.'_acf_form user_roles="administrator, current_author" post_id="get_id" post_title="true" post_content="true" return="%post_url%" field_groups="" post_type="" post_status="" updated_message="" submit_value="Update"]</p>';
						$hefe_tools_page .= '<hr />';
						$hefe_tools_page .= '<p><strong>Atts:</strong><br>role_visiblity, id, post_id, post_type, post_status, field_groups, fields, post_title, post_content, form, form_attributes, return, html_before_fields, html_after_fields, submit_value, updated_message, label_placement, instruction_placement, field_el, uploader, honeypot, html_updated_message, html_submit_button, html_submit_spinner, kses</p>';
					$hefe_tools_page .= '</div>';
					$hefe_tools_page .= '<div class="card">';
						$hefe_tools_page .= '<small>SHORTCODE</small><h2>ANIMATE.CSS ITEM</h2>';
						$hefe_tools_page .= '<p>This is a shortcode that will animate content using <a href="https://daneden.github.io/animate.css/" target="_blank">ANIMATE.CSS</a>.</p>';
						$hefe_tools_page .= '<p><strong>Usage</strong><br>['.hefe_shortcode_name.'_animate_item class="" effect=""]<br>CONTENT<br>[/'.hefe_shortcode_name.'_animate_item]</p>';
						$hefe_tools_page .= '<hr />';
						$hefe_tools_page .= '<p><strong>Alts</strong><br>'.hefe_shortcode_name.'_animate, '.hefe_shortcode_name.'_animation, '.hefe_shortcode_name.'_animate_css_item, '.hefe_shortcode_name.'_animate_item</p>';
						$hefe_tools_page .= '<hr />';
						$hefe_tools_page .= '<p><strong>Atts</strong><br>id, class, effect (<a href="https://daneden.github.io/animate.css/" target="_blank">Options</a>), delay (milliseconds), etc</p>';
					$hefe_tools_page .= '</div>';
					$hefe_tools_page .= '<div class="card">';
						$hefe_tools_page .= '<small>SHORTCODE</small><h2>ARCHIVE TITLE</h2>';
						$hefe_tools_page .= '<p>This is a shortcode that will display an archive title.</p>';
						$hefe_tools_page .= '<p><strong>Usage</strong><br>['.hefe_shortcode_name.'_archive_title pre=""]</p>';
						$hefe_tools_page .= '<hr />';
						$hefe_tools_page .= '<p><strong>Atts</strong><br>pre</p>';
					$hefe_tools_page .= '</div>';
					$hefe_tools_page .= '<div class="card">';
						$hefe_tools_page .= '<small>SHORTCODE</small><h2>BANNER</h2>';
						$hefe_tools_page .= '<p>This is a shortcode used when creating a banner.</p>';
						$hefe_tools_page .= '<p><strong>Usage</strong><br>['.hefe_shortcode_name.'_banner src="" class=""]<br>CONTENT<br>[/'.hefe_shortcode_name.'_banner]</p>';
						$hefe_tools_page .= '<hr />';
						$hefe_tools_page .= '<p><strong>Atts</strong><br>id, class, height, src, etc</p>';
					$hefe_tools_page .= '</div>';
					$hefe_tools_page .= '<div class="card">';
						$hefe_tools_page .= '<small>SHORTCODE</small><h2>BANNER PER PAGE</h2>';
						$hefe_tools_page .= '<p>This is a shortcode used when displaying a banner per page custom field content.</p>';
						$hefe_tools_page .= '<p><strong>Usage</strong><br>['.hefe_shortcode_name.'_banner_per_page class=""]<br>CONTENT<br>[/'.hefe_shortcode_name.'_banner_per_page]</p>';
						$hefe_tools_page .= '<hr />';
						$hefe_tools_page .= '<p><strong>Atts</strong><br>id, class, height, etc</p>';
					$hefe_tools_page .= '</div>';
					$hefe_tools_page .= '<div class="card">';
						$hefe_tools_page .= '<small>SHORTCODE</small><h2>BOOTSTRAP CONTAINER FLUID</h2>';
						$hefe_tools_page .= '<p>This is a shortcode used when creating a container fluid with <a href="http://getbootstrap.com" target="_blank">Bootstrap</a>. Wrap this shortcode around a "Bootstrap Row" shortcode to contain it.</p>';
						$hefe_tools_page .= '<p><strong>Usage</strong><br>['.hefe_shortcode_name.'_bootstrap_container_fluid class=""]<br>CHILD<br>[/'.hefe_shortcode_name.'_bootstrap_container_fluid]</p>';
						$hefe_tools_page .= '<hr />';
						$hefe_tools_page .= '<p><strong>Alts</strong><br>'.hefe_shortcode_name.'_bootstrap_container_fluid_1, '.hefe_shortcode_name.'_bootstrap_container_fluid_2, '.hefe_shortcode_name.'_bootstrap_container_fluid_3, '.hefe_shortcode_name.'_bootstrap_container_fluid_4, '.hefe_shortcode_name.'_bootstrap_container_fluid_grandparent, '.hefe_shortcode_name.'_bootstrap_container_fluid_parent, '.hefe_shortcode_name.'_bootstrap_container_fluid_child, '.hefe_shortcode_name.'_bootstrap_container_fluid_grandchild</p>';
						$hefe_tools_page .= '<hr />';
						$hefe_tools_page .= '<p><strong>Atts</strong><br>id, class, etc</p>';
					$hefe_tools_page .= '</div>';
					$hefe_tools_page .= '<div class="card">';
						$hefe_tools_page .= '<small>SHORTCODE</small><h2>BOOTSTRAP CONTAINER</h2>';
						$hefe_tools_page .= '<p>This is a shortcode used in creating a container from <a href="http://getbootstrap.com" target="_blank">Bootstrap</a>. Wrap this shortcode around a "Bootstrap Row" shortcode to contain it.</p>';
						$hefe_tools_page .= '<p><strong>Usage</strong><br>['.hefe_shortcode_name.'_bootstrap_container class=""]<br>CHILD<br>[/'.hefe_shortcode_name.'_bootstrap_container]</p>';
						$hefe_tools_page .= '<hr />';
						$hefe_tools_page .= '<p><strong>Alts</strong><br>'.hefe_shortcode_name.'_bootstrap_container_1, '.hefe_shortcode_name.'_bootstrap_container_2, '.hefe_shortcode_name.'_bootstrap_container_3, '.hefe_shortcode_name.'_bootstrap_container_4, '.hefe_shortcode_name.'_bootstrap_container_grandparent, '.hefe_shortcode_name.'_bootstrap_container_parent, '.hefe_shortcode_name.'_bootstrap_container_child, '.hefe_shortcode_name.'_bootstrap_container_grandchild</p>';
						$hefe_tools_page .= '<hr />';
						$hefe_tools_page .= '<p><strong>Atts:</strong><br>id, class, etc</p>';
					$hefe_tools_page .= '</div>';
					$hefe_tools_page .= '<div class="card">';
						$hefe_tools_page .= '<small>SHORTCODE</small><h2>BOOTSTRAP ROW</h2>';
						$hefe_tools_page .= '<p>This is a shortcode used when creating a row with <a href="http://getbootstrap.com" target="_blank">Bootstrap</a>. Wrap this shortcode around a "Bootstrap Column" shortcode to contain it.</p>';
						$hefe_tools_page .= '<p><strong>Usage</strong><br>['.hefe_shortcode_name.'_bootstrap_row class=""]<br>CHILD<br>[/'.hefe_shortcode_name.'_bootstrap_row]</p>';
						$hefe_tools_page .= '<hr />';
						$hefe_tools_page .= '<p><strong>Alts</strong><br>'.hefe_shortcode_name.'_bootstrap_row_1, '.hefe_shortcode_name.'_bootstrap_row_2, '.hefe_shortcode_name.'_bootstrap_row_3, '.hefe_shortcode_name.'_bootstrap_row_4, '.hefe_shortcode_name.'_bootstrap_row_grandparent, '.hefe_shortcode_name.'_bootstrap_row_parent, '.hefe_shortcode_name.'_bootstrap_row_child, '.hefe_shortcode_name.'_bootstrap_row_grandchild</p>';
						$hefe_tools_page .= '<hr />';
						$hefe_tools_page .= '<p><strong>Atts:</strong><br>id, class, etc</p>';
					$hefe_tools_page .= '</div>';
					$hefe_tools_page .= '<div class="card">';
						$hefe_tools_page .= '<small>SHORTCODE</small><h2>BOOTSTRAP COLUMN</h2>';
						$hefe_tools_page .= '<p>This is a shortcode used when creating a column with <a href="http://getbootstrap.com" target="_blank">Bootstrap</a>. Wrap this shortcode around your content to contain it.</p>';
						$hefe_tools_page .= '<p><strong>Usage</strong><br>['.hefe_shortcode_name.'_bootstrap_column class="" col_md=""]<br>CONTENT<br>[/'.hefe_shortcode_name.'_bootstrap_column]</p>';
						$hefe_tools_page .= '<hr />';
						$hefe_tools_page .= '<p><strong>Alts</strong><br>'.hefe_shortcode_name.'_bootstrap_column_1, '.hefe_shortcode_name.'_bootstrap_column_2, '.hefe_shortcode_name.'_bootstrap_column_3, '.hefe_shortcode_name.'_bootstrap_column_4, '.hefe_shortcode_name.'_bootstrap_column_grandparent, '.hefe_shortcode_name.'_bootstrap_column_parent, '.hefe_shortcode_name.'_bootstrap_column_child, '.hefe_shortcode_name.'_bootstrap_column_grandchild, '.hefe_shortcode_name.'_bootstrap_col</p>';
						$hefe_tools_page .= '<hr />';
						$hefe_tools_page .= '<p><strong>Atts:</strong><br>id, class, col (1-12), col_sm (1-12), col_md (1-12), col_lg (1-12), col_xl (1-12), etc</p>';
					$hefe_tools_page .= '</div>';
					$hefe_tools_page .= '<div class="card">';
						$hefe_tools_page .= '<small>SHORTCODE</small><h2>BREADCRUMBS</h2>';
						$hefe_tools_page .= '<p>This is a shortcode that creates a site breadcrumb.</p>';
						$hefe_tools_page .= '<p><strong>Usage</strong><br>['.hefe_shortcode_name.'_breadcrumbs]</p>';
						$hefe_tools_page .= '<hr />';
						$hefe_tools_page .= '<p><strong>Alts</strong><br>'.hefe_shortcode_name.'_breadcrumb</p>';
						$hefe_tools_page .= '<hr />';
						$hefe_tools_page .= '<p><strong>Atts</strong><br>id, class, etc</p>';
					$hefe_tools_page .= '</div>';
					$hefe_tools_page .= '<div class="card">';
						$hefe_tools_page .= '<small>SHORTCODE</small><h2>BUTTON</h2>';
						$hefe_tools_page .= '<p>This is a shortcode that creates a button.</p>';
						$hefe_tools_page .= '<p><strong>Usage</strong><br>['.hefe_shortcode_name.'_button class="" href=""]<br>CONTENT<br>[/'.hefe_shortcode_name.'_button]</p>';
						$hefe_tools_page .= '<hr />';
						$hefe_tools_page .= '<p><strong>Atts</strong><br>id, class, href, etc</p>';
					$hefe_tools_page .= '</div>';
					$hefe_tools_page .= '<div class="card">';
						$hefe_tools_page .= '<small>SHORTCODE</small><h2>CATEGORY LIST</h2>';
						$hefe_tools_page .= '<p>This is a shortcode that displays all of the site categories.</p>';
						$hefe_tools_page .= '<p><strong>Usage</strong><br>['.hefe_shortcode_name.'_category_list class=""]</p>';
						$hefe_tools_page .= '<hr />';
						$hefe_tools_page .= '<p><strong>Alts</strong><br>'.hefe_shortcode_name.'_categories_list</p>';
						$hefe_tools_page .= '<hr />';
						$hefe_tools_page .= '<p><strong>Atts</strong><br>id, class, limit, etc</p>';
					$hefe_tools_page .= '</div>';
					$hefe_tools_page .= '<div class="card">';
						$hefe_tools_page .= '<small>SHORTCODE</small><h2>CENTER</h2>';
						$hefe_tools_page .= '<p>This is a shortcode that centers content.</p>';
						$hefe_tools_page .= '<p><strong>Usage</strong><br>['.hefe_shortcode_name.'_center class=""]<br>CONTENT<br>[/'.hefe_shortcode_name.'_center]</p>';
						$hefe_tools_page .= '<hr />';
						$hefe_tools_page .= '<p><strong>Atts</strong><br>id, class, etc</p>';
					$hefe_tools_page .= '</div>';
					$hefe_tools_page .= '<div class="card">';
						$hefe_tools_page .= '<small>SHORTCODE</small><h2>CURRENT DATE</h2>';
						$hefe_tools_page .= '<p>This is a shortcode that displays the current date.</p>';
						$hefe_tools_page .= '<p><strong>Usage</strong><br>['.hefe_shortcode_name.'_current_date format=""]</p>';
						$hefe_tools_page .= '<hr />';
						$hefe_tools_page .= '<p><strong>Atts</strong><br>format</p>';
					$hefe_tools_page .= '</div>';
					$hefe_tools_page .= '<div class="card">';
						$hefe_tools_page .= '<small>SHORTCODE</small><h2>CURRENT USER</h2>';
						$hefe_tools_page .= '<p>This is a shortcode displays current user meta information.</p>';
						$hefe_tools_page .= '<p><strong>Usage</strong><br>['.hefe_shortcode_name.'_current_user parameter=""]</p>';
						$hefe_tools_page .= '<hr />';
						$hefe_tools_page .= '<p><strong>Atts</strong><br>parameter (admin_color, aim, comment_shortcuts, description, display_name, first_name, ID, jabber, last_name, nickname, plugins_last_view, plugins_per_page, rich_editing, syntax_highlighting, user_activation_key, user_description, user_email, user_firstname, user_lastname, user_level, user_login, user_nicename, user_pass, user_registered, user_status, user_url, yim, etc)</p>';
					$hefe_tools_page .= '</div>';
					$hefe_tools_page .= '<div class="card">';
						$hefe_tools_page .= '<small>SHORTCODE</small><h2>DIV</h2>';
						$hefe_tools_page .= '<p>This is a shortcode creates a div.</p>';
						$hefe_tools_page .= '<p><strong>Usage</strong><br>['.hefe_shortcode_name.'_div class=""]<br>CONTENT<br>[/'.hefe_shortcode_name.'_div]</p>';
						$hefe_tools_page .= '<hr />';
						$hefe_tools_page .= '<p><strong>Atts</strong><br>id, class, etc</p>';
					$hefe_tools_page .= '</div>';
					$hefe_tools_page .= '<div class="card">';
						$hefe_tools_page .= '<small>SHORTCODE</small><h2>FANCYBOX LINK</h2>';
						$hefe_tools_page .= '<p>This is a shortcode that creates a modal link using <a href="http://fancyapps.com/fancybox/3/" target="_blank">fancyBox</a>. Wrap this shortcode around an item to make it link to a modal.</p>';
						$hefe_tools_page .= '<p><strong>Usage</strong><br>['.hefe_shortcode_name.'_fancybox_link class="" href=""]<br>CONTENT<br>[/'.hefe_shortcode_name.'_fancybox_link]</p>';
						$hefe_tools_page .= '<hr />';
						$hefe_tools_page .= '<p><strong>Alts</strong><br>'.hefe_shortcode_name.'_fancybox</p>';
						$hefe_tools_page .= '<hr />';
						$hefe_tools_page .= '<p><strong>Atts</strong><br>id, class, href (vimeo url, youtube url, google maps url, image url), caption, group (any, default), width, height, srcset, src, type, etc</p>';
					$hefe_tools_page .= '</div>';
					$hefe_tools_page .= '<div class="card">';
						$hefe_tools_page .= '<small>SHORTCODE</small><h2>FANCYBOX INLINE LINK</h2>';
						$hefe_tools_page .= '<p>This is a shortcode that creates an inline modal link with <a href="http://fancyapps.com/fancybox/3/" target="_blank">fancyBox</a>. Copy the "PAIRED_ID" attribute with the "Fancybox Inline Content" shortcode and it will toggle a modal with the content.</p>';
						$hefe_tools_page .= '<p><strong>Usage</strong><br>['.hefe_shortcode_name.'_fancybox_inline_link class="" paired_id=""]<br>CONTENT<br>[/'.hefe_shortcode_name.'_fancybox_inline_link]</p>';
						$hefe_tools_page .= '<hr />';
						$hefe_tools_page .= '<p><strong>Atts</strong><br>id, class, group, paired_id, etc</p>';
					$hefe_tools_page .= '</div>';
					$hefe_tools_page .= '<div class="card">';
						$hefe_tools_page .= '<small>SHORTCODE</small><h2>FANCYBOX INLINE CONTENT</h2>';
						$hefe_tools_page .= '<p>This is a shortcode that creates an inline modal of content with <a href="http://fancyapps.com/fancybox/3/" target="_blank">fancyBox</a>. Copy the "PAIRED_ID" attribute with the "Fancybox Inline Link" shortcode and it will display the content from the link.</p>';
						$hefe_tools_page .= '<p><strong>Usage</strong><br>['.hefe_shortcode_name.'_fancybox_inline_content class="" paired_id=""]<br>CONTENT<br>[/'.hefe_shortcode_name.'_fancybox_inline_content]</p>';
						$hefe_tools_page .= '<hr />';
						$hefe_tools_page .= '<p><strong>Atts</strong><br>class, paired_id, etc</p>';
					$hefe_tools_page .= '</div>';
					$hefe_tools_page .= '<div class="card">';
						$hefe_tools_page .= '<small>SHORTCODE</small><h2>FEATURED IMAGE</h2>';
						$hefe_tools_page .= '<p>This is a shortcode that displays a pages featured image.</p>';
						$hefe_tools_page .= '<p><strong>Usage</strong><br>['.hefe_shortcode_name.'_featured_image class=""]</p>';
						$hefe_tools_page .= '<hr />';
						$hefe_tools_page .= '<p><strong>Alts:</strong><br>'.hefe_shortcode_name.'_page_featured_image</p>';
						$hefe_tools_page .= '<hr />';
						$hefe_tools_page .= '<p><strong>Atts:</strong><br>id, class, width, page_id, image_size, etc</p>';
					$hefe_tools_page .= '</div>';
					$hefe_tools_page .= '<div class="card">';
						$hefe_tools_page .= '<small>SHORTCODE</small><h2>FEATURED IMAGE URL</h2>';
						$hefe_tools_page .= '<p>This is a shortcode that displays a pages featured image url.</p>';
						$hefe_tools_page .= '<p><strong>Usage</strong><br>['.hefe_shortcode_name.'_featured_image_url page_id=""]</p>';
						$hefe_tools_page .= '<hr />';
						$hefe_tools_page .= '<p><strong>Atts:</strong><br>page_id, image_size</p>';
					$hefe_tools_page .= '</div>';
					$hefe_tools_page .= '<div class="card">';
						$hefe_tools_page .= '<small>SHORTCODE</small><h2>FONT AWESOME ICON</h2>';
						$hefe_tools_page .= '<p>This is a shortcode that displays an icon using <a href="http://fontawesome.com" target="_blank">Font Awesome</a>.</p>';
						$hefe_tools_page .= '<p><strong>Usage</strong><br>['.hefe_shortcode_name.'_font_awesome icon_pre="" icon=""]</p>';
						$hefe_tools_page .= '<hr />';
						$hefe_tools_page .= '<p><strong>Alts</strong><br>'.hefe_shortcode_name.'_font_awesome_icon</p>';
						$hefe_tools_page .= '<hr />';
						$hefe_tools_page .= '<p><strong>Atts</strong><br> id, class, icon_pre (defaults as fas, fab, etc), icon (<a href="https://fontawesome.com/icons?d=gallery" target="_blank">options</a>), style (none, 01, 02), official_color (facebook, twitter, googleplus, linkedin, youtube, instagram, pinterest, flickr, tumblr, foursquare, dribbble, vine, behance, github, skype, snapchat, whatsapp), etc</p>';
					$hefe_tools_page .= '</div>';
					$hefe_tools_page .= '<div class="card">';
						$hefe_tools_page .= '<small>SHORTCODE</small><h2>FRONT-END MEDIA LINK</h2>';
						$hefe_tools_page .= '<p>This is a shortcode that creates a front-end media link. You must "Enqueue" the files in order for this shortcode to work.</p>';
						$hefe_tools_page .= '<p><strong>Usage</strong><br>['.hefe_shortcode_name.'_front_end_media_link attachment_id=""]<br>CONTENT<br>[/'.hefe_shortcode_name.'_front_end_media_link]</p>';
						$hefe_tools_page .= '<hr />';
						$hefe_tools_page .= '<p><strong>Atts</strong><br>id, class, attachment_id, etc</p>';
					$hefe_tools_page .= '</div>';
					$hefe_tools_page .= '<div class="card">';
						$hefe_tools_page .= '<small>SHORTCODE</small><h2>GET</h2>';
						$hefe_tools_page .= '<p>This is a shortcode that displays GET information from a url parameter.</p>';
						$hefe_tools_page .= '<p><strong>Usage</strong><br>['.hefe_shortcode_name.'_get name=""]</p>';
						$hefe_tools_page .= '<hr />';
						$hefe_tools_page .= '<p><strong>Atts</strong><br>name, exclude, exclude_1, exclude_2, exclude_3, exclude_4, exclude_5, exclude_6</p>';
					$hefe_tools_page .= '</div>';
					$hefe_tools_page .= '<div class="card">';
						$hefe_tools_page .= '<small>SHORTCODE</small><h2>HORIZONTAL LIST PARENT</h2>';
						$hefe_tools_page .= '<p>This is a shortcode that acts as a parent for a horizontal list.</p>';
						$hefe_tools_page .= '<p><strong>Usage</strong><br>['.hefe_shortcode_name.'_horizontal_list_parent class=""]<br>CONTENT<br>[/'.hefe_shortcode_name.'_horizontal_list_parent]</p>';
						$hefe_tools_page .= '<hr />';
						$hefe_tools_page .= '<p><strong>Atts</strong><br>id, class, separator (line, dot, none, blank), responsive (true, false, blank), etc</p>';
					$hefe_tools_page .= '</div>';
					$hefe_tools_page .= '<div class="card">';
						$hefe_tools_page .= '<small>SHORTCODE</small><h2>HORIZONTAL LIST CHILD</h2>';
						$hefe_tools_page .= '<p>This is a shortcode that acts as a child for a horizontal list.</p>';
						$hefe_tools_page .= '<p><strong>Usage</strong><br>['.hefe_shortcode_name.'_horizontal_list_child class=""]<br>CONTENT<br>[/'.hefe_shortcode_name.'_horizontal_list_child]</p>';
						$hefe_tools_page .= '<hr />';
						$hefe_tools_page .= '<p><strong>Atts</strong><br>id, class, etc</p>';
					$hefe_tools_page .= '</div>';
					$hefe_tools_page .= '<div class="card">';
						$hefe_tools_page .= '<small>SHORTCODE</small><h2>HR</h2>';
						$hefe_tools_page .= '<p>This is a shortcode that creates an hr.</p>';
						$hefe_tools_page .= '<p><strong>Usage</strong><br>['.hefe_shortcode_name.'_hr class=""]</p>';
						$hefe_tools_page .= '<hr />';
						$hefe_tools_page .= '<p><strong>Atts</strong><br>id, class, etc</p>';
					$hefe_tools_page .= '</div>';
					$hefe_tools_page .= '<div class="card">';
						$hefe_tools_page .= '<small>SHORTCODE</small><h2>INJECTION</h2>';
						$hefe_tools_page .= '<p>This is a shortcode used to manually place content from the injection post type. You must activate the "Control" in order for this to work.</p>';
						$hefe_tools_page .= '<p>You must activate this feature in "Control" in order for this to work.</p>';
						$hefe_tools_page .= '<p><strong>Usage</strong><br>['.hefe_shortcode_name.'_injection injection_id=""]</p>';
						$hefe_tools_page .= '<hr />';
						$hefe_tools_page .= '<p><strong>Atts</strong><br>injection_id (PAGE_ID), injection_filter (TAXONOMY_ID), exclude (PAGE_ID)</p>';
					$hefe_tools_page .= '</div>';
					$hefe_tools_page .= '<div class="card">';
						$hefe_tools_page .= '<small>SHORTCODE</small><h2>IS PAGE</h2>';
						$hefe_tools_page .= '<p>This is a shortcode that displays content if the pages ID matches the shortcode attribute "PAGE_ID".</p>';
						$hefe_tools_page .= '<p><strong>Usage</strong><br>['.hefe_shortcode_name.'_is_page page_id=""]<br>CONTENT<br>[/'.hefe_shortcode_name.'_is_page]</p>';
						$hefe_tools_page .= '<hr />';
						$hefe_tools_page .= '<p><strong>Atts</strong><br>page_id</p>';
					$hefe_tools_page .= '</div>';
					$hefe_tools_page .= '<div class="card">';
						$hefe_tools_page .= '<small>SHORTCODE</small><h2>IS HOME</h2>';
						$hefe_tools_page .= '<p>This is a shortcode that displays content if the current page is the home page.</p>';
						$hefe_tools_page .= '<p><strong>Usage</strong><br>['.hefe_shortcode_name.'_is_home]<br>CONTENT<br>[/'.hefe_shortcode_name.'_is_home]</p>';
					$hefe_tools_page .= '</div>';
					$hefe_tools_page .= '<div class="card">';
						$hefe_tools_page .= '<small>SHORTCODE</small><h2>ISOTOPE PARENT</h2>';
						$hefe_tools_page .= '<p>This is a shortcode that creates a masonry grid of content using <a href="https://isotope.metafizzy.co" target="_blank">Isotope</a>. Wrap this shortcode around Isotope children shortcodes to contain them.</p>';
						$hefe_tools_page .= '<p><strong>Usage</strong><br>['.hefe_shortcode_name.'_isotope_parent class=""]<br>CHILD<br>[/'.hefe_shortcode_name.'_isotope_parent]</p>';
						$hefe_tools_page .= '<hr />';
						$hefe_tools_page .= '<p><strong>Atts</strong><br>id, class, id_number, etc</p>';
					$hefe_tools_page .= '</div>';
					$hefe_tools_page .= '<div class="card">';
						$hefe_tools_page .= '<small>SHORTCODE</small><h2>ISOTOPE CHILD</h2>';
						$hefe_tools_page .= '<p>This is a shortcode that creates a masonry grid of content using <a href="https://isotope.metafizzy.co" target="_blank">Isotope</a>. Wrap this shortcode around your content to contain it.</p>';
						$hefe_tools_page .= '<p><strong>Usage</strong><br>['.hefe_shortcode_name.'_isotope_child class=""]<br>CONTENT<br>[/'.hefe_shortcode_name.'_isotope_child]</p>';
						$hefe_tools_page .= '<hr />';
						$hefe_tools_page .= '<p><strong>Atts</strong><br>id, class, etc</p>';
					$hefe_tools_page .= '</div>';
					$hefe_tools_page .= '<div class="card">';
						$hefe_tools_page .= '<small>SHORTCODE</small><h2>LI</h2>';
						$hefe_tools_page .= '<p>This is a shortcode that creates a list item.</p>';
						$hefe_tools_page .= '<p><strong>Usage</strong><br>['.hefe_shortcode_name.'_li class=""]<br>CONTENT<br>[/'.hefe_shortcode_name.'_li]</p>';
						$hefe_tools_page .= '<hr />';
						$hefe_tools_page .= '<p><strong>Atts</strong><br>id, class, etc</p>';
					$hefe_tools_page .= '</div>';
					$hefe_tools_page .= '<div class="card">';
						$hefe_tools_page .= '<small>SHORTCODE</small><h2>LINK</h2>';
						$hefe_tools_page .= '<p>This is a shortcode that creates a link.</p>';
						$hefe_tools_page .= '<p><strong>Usage</strong><br>['.hefe_shortcode_name.'_link class="" href=""]<br>CONTENT<br>[/'.hefe_shortcode_name.'_link]</p>';
						$hefe_tools_page .= '<hr />';
						$hefe_tools_page .= '<p><strong>Alts:</strong><br>'.hefe_shortcode_name.'_a, '.hefe_shortcode_name.'_hyperlink</p>';
						$hefe_tools_page .= '<hr />';
						$hefe_tools_page .= '<p><strong>Atts</strong><br>id, class, href, etc</p>';
					$hefe_tools_page .= '</div>';
					$hefe_tools_page .= '<div class="card">';
						$hefe_tools_page .= '<small>SHORTCODE</small><h2>LIST PAGES</h2>';
						$hefe_tools_page .= '<p>This is a shortcode that displays a list of the site pages.</p>';
						$hefe_tools_page .= '<p><strong>Usage</strong><br>['.hefe_shortcode_name.'_list_pages class=""]</p>';
						$hefe_tools_page .= '<hr />';
						$hefe_tools_page .= '<p><strong>Alts</strong><br>'.hefe_shortcode_name.'_sitemap</p>';
						$hefe_tools_page .= '<hr />';
						$hefe_tools_page .= '<p><strong>Atts</strong><br>id, class, child_of, authors, date_format, depth, echo, exclude, include, link_after, link_before, post_type, post_status, show_date, sort_column, title_li, walker, etc</p>';
					$hefe_tools_page .= '</div>';
					$hefe_tools_page .= '<div class="card">';
						$hefe_tools_page .= '<small>SHORTCODE</small><h2>LIST PAGE CHILDREN</h2>';
						$hefe_tools_page .= '<p>This is a shortcode that displays a list of child pages.</p>';
						$hefe_tools_page .= '<p><strong>Usage</strong><br>['.hefe_shortcode_name.'_list_page_children class=""]</p>';
						$hefe_tools_page .= '<hr />';
						$hefe_tools_page .= '<p><strong>Atts</strong><br>id, class, page_id, sort_column, etc</p>';
					$hefe_tools_page .= '</div>';
					$hefe_tools_page .= '<div class="card">';
						$hefe_tools_page .= '<small>SHORTCODE</small><h2>LOGIN LINK</h2>';
						$hefe_tools_page .= '<p>This is a shortcode that creates a login link.</p>';
						$hefe_tools_page .= '<p><strong>Usage</strong><br>['.hefe_shortcode_name.'_login_link class=""]<br>CONTENT<br>[/'.hefe_shortcode_name.'_login_link]</p>';
						$hefe_tools_page .= '<hr />';
						$hefe_tools_page .= '<p><strong>Atts</strong><br>id, class, redirect, etc</p>';
					$hefe_tools_page .= '</div>';
					$hefe_tools_page .= '<div class="card">';
						$hefe_tools_page .= '<small>SHORTCODE</small><h2>LOGOUT LINK</h2>';
						$hefe_tools_page .= '<p>This is a shortcode that creates a logout link.</p>';
						$hefe_tools_page .= '<p><strong>Usage</strong><br>['.hefe_shortcode_name.'_logout_link class=""]<br>CONTENT<br>[/'.hefe_shortcode_name.'_logout_link]</p>';
						$hefe_tools_page .= '<hr />';
						$hefe_tools_page .= '<p><strong>Atts</strong><br>id, class, redirect, etc</p>';
					$hefe_tools_page .= '</div>';
					$hefe_tools_page .= '<div class="card">';
						$hefe_tools_page .= '<small>SHORTCODE</small><h2>LOGGED IN ONLY</h2>';
						$hefe_tools_page .= '<p>This is a shortcode that displays content for logged in users only.</p>';
						$hefe_tools_page .= '<p><strong>Usage</strong><br>['.hefe_shortcode_name.'_logged_in_only user_roles=""]<br>CONTENT<br>[/'.hefe_shortcode_name.'_logged_in_only]</p>';
						$hefe_tools_page .= '<hr />';
						$hefe_tools_page .= '<p><strong>Atts</strong><br>user_roles</p>';
					$hefe_tools_page .= '</div>';
					$hefe_tools_page .= '<div class="card">';
						$hefe_tools_page .= '<small>SHORTCODE</small><h2>LOGGED OUT ONLY</h2>';
						$hefe_tools_page .= '<p>This is a shortcode that displays content for logged out users only.</p>';
						$hefe_tools_page .= '<p><strong>Usage</strong><br>['.hefe_shortcode_name.'_logged_out_only]<br>CONTENT<br>[/'.hefe_shortcode_name.'_logged_out_only]</p>';
					$hefe_tools_page .= '</div>';
					$hefe_tools_page .= '<div class="card">';
						$hefe_tools_page .= '<small>SHORTCODE</small><h2>MATCHHEIGHT ITEM</h2>';
						$hefe_tools_page .= '<p>This is a shortcode that matches the height of row content using <a href="http://brm.io/jquery-match-height/" target="_blank">matchHeight</a>.</p>';
						$hefe_tools_page .= '<p>This is a shortcode used when creating a item.</p>';
						$hefe_tools_page .= '<p><strong>Usage</strong><br>['.hefe_shortcode_name.'_match_height_item class=""]<br>CONTENT<br>[/'.hefe_shortcode_name.'_match_height_item]</p>';
						$hefe_tools_page .= '<hr />';
						$hefe_tools_page .= '<p><strong>Atts</strong><br>id, class, etc</p>';
					$hefe_tools_page .= '</div>';
					$hefe_tools_page .= '<div class="card">';
						$hefe_tools_page .= '<small>SHORTCODE</small><h2>MENU</h2>';
						$hefe_tools_page .= '<p>This is a shortcode that displays a menu.</p>';
						$hefe_tools_page .= '<p><strong>Usage</strong><br>['.hefe_shortcode_name.'_menu menu=""]</p>';
						$hefe_tools_page .= '<hr />';
						$hefe_tools_page .= '<p><strong>Alts:</strong><br>'.hefe_shortcode_name.'_navigation</p>';
						$hefe_tools_page .= '<hr />';
						$hefe_tools_page .= '<p><strong>Atts:</strong><br>menu, menu_class, menu_id, container, container_class, container_id, before, after, link_before, link_after, depth, walker, fallback_cb, theme_location, echo, style (none, 01, 02, 03)</p>';
					$hefe_tools_page .= '</div>';
					$hefe_tools_page .= '<div class="card">';
						$hefe_tools_page .= '<small>SHORTCODE</small><h2>OL</h2>';
						$hefe_tools_page .= '<p>This is a shortcode that creates an ordered list tag.</p>';
						$hefe_tools_page .= '<p><strong>Usage</strong><br>['.hefe_shortcode_name.'_ol class=""]<br>CONTENT<br>[/'.hefe_shortcode_name.'_ol]</p>';
						$hefe_tools_page .= '<hr />';
						$hefe_tools_page .= '<p><strong>Atts</strong><br>id, class, etc</p>';
					$hefe_tools_page .= '</div>';
					$hefe_tools_page .= '<div class="card">';
						$hefe_tools_page .= '<small>SHORTCODE</small><h2>P</h2>';
						$hefe_tools_page .= '<p>This is a shortcode creates a paragraph tag.</p>';
						$hefe_tools_page .= '<p><strong>Usage</strong><br>['.hefe_shortcode_name.'_p class=""]<br>CONTENT<br>[/'.hefe_shortcode_name.'_p]</p>';
						$hefe_tools_page .= '<hr />';
						$hefe_tools_page .= '<p><strong>Atts</strong><br>id, class, etc</p>';
					$hefe_tools_page .= '</div>';
					$hefe_tools_page .= '<div class="card">';
						$hefe_tools_page .= '<small>SHORTCODE</small><h2>PAGE CATEGORIES</h2>';
						$hefe_tools_page .= '<p>This is a shortcode that displays page categories.</p>';
						$hefe_tools_page .= '<p><strong>Usage</strong><br>['.hefe_shortcode_name.'_page_categories class=""]</p>';
						$hefe_tools_page .= '<hr />';
						$hefe_tools_page .= '<p><strong>Alts</strong><br>'.hefe_shortcode_name.'_categories</p>';
						$hefe_tools_page .= '<hr />';
						$hefe_tools_page .= '<p><strong>Atts</strong><br>id, class, page_id, etc</p>';
					$hefe_tools_page .= '</div>';
					$hefe_tools_page .= '<div class="card">';
						$hefe_tools_page .= '<small>SHORTCODE</small><h2>PAGE CONTENT</h2>';
						$hefe_tools_page .= '<p>This is a shortcode that displays a page content.</p>';
						$hefe_tools_page .= '<p><strong>Usage</strong><br>['.hefe_shortcode_name.'_page_content characters=""]</p>';
						$hefe_tools_page .= '<hr />';
						$hefe_tools_page .= '<p><strong>Alts</strong><br>'.hefe_shortcode_name.'_content</p>';
						$hefe_tools_page .= '<hr />';
						$hefe_tools_page .= '<p><strong>Atts</strong><br>characters, page_id</p>';
					$hefe_tools_page .= '</div>';
					$hefe_tools_page .= '<div class="card">';
						$hefe_tools_page .= '<small>SHORTCODE</small><h2>PAGE DATE</h2>';
						$hefe_tools_page .= '<p>This is a shortcode that displays a page date.</p>';
						$hefe_tools_page .= '<p><strong>Usage</strong><br>['.hefe_shortcode_name.'_page_date page_id=""]</p>';
						$hefe_tools_page .= '<hr />';
						$hefe_tools_page .= '<p><strong>Alts:</strong><br>'.hefe_shortcode_name.'_date</p>';
						$hefe_tools_page .= '<hr />';
						$hefe_tools_page .= '<p><strong>Atts:</strong><br>page_id, format</p>';
					$hefe_tools_page .= '</div>';
					$hefe_tools_page .= '<div class="card">';
						$hefe_tools_page .= '<small>SHORTCODE</small><h2>PAGE EXCERPT</h2>';
						$hefe_tools_page .= '<p>This is a shortcode that displays a page excerpt.</p>';
						$hefe_tools_page .= '<p><strong>Usage</strong><br>['.hefe_shortcode_name.'_page_excerpt characters=""]</p>';
						$hefe_tools_page .= '<hr />';
						$hefe_tools_page .= '<p><strong>Alts</strong><br>'.hefe_shortcode_name.'_excerpt</p>';
						$hefe_tools_page .= '<hr />';
						$hefe_tools_page .= '<p><strong>Atts</strong><br>characters, page_id</p>';
					$hefe_tools_page .= '</div>';
					$hefe_tools_page .= '<div class="card">';
						$hefe_tools_page .= '<small>SHORTCODE</small><h2>PAGE ID</h2>';
						$hefe_tools_page .= '<p>This is a shortcode that displays the current page id.</p>';
						$hefe_tools_page .= '<p><strong>Usage</strong><br>['.hefe_shortcode_name.'_page_id]</p>';
					$hefe_tools_page .= '</div>';
					$hefe_tools_page .= '<div class="card">';
						$hefe_tools_page .= '<small>SHORTCODE</small><h2>PAGE TAGS</h2>';
						$hefe_tools_page .= '<p>This is a shortcode that displays page tags.</p>';
						$hefe_tools_page .= '<p><strong>Usage</strong><br>['.hefe_shortcode_name.'_page_tags class=""]</p>';
						$hefe_tools_page .= '<hr />';
						$hefe_tools_page .= '<p><strong>Alts</strong><br>'.hefe_shortcode_name.'_tags</p>';
						$hefe_tools_page .= '<hr />';
						$hefe_tools_page .= '<p><strong>Atts</strong><br>id, class, page_id, etc</p>';
					$hefe_tools_page .= '</div>';
					$hefe_tools_page .= '<div class="card">';
						$hefe_tools_page .= '<small>SHORTCODE</small><h2>PAGE TAXONOMY</h2>';
						$hefe_tools_page .= '<p>This is a shortcode that displays page taxonomy.</p>';
						$hefe_tools_page .= '<p><strong>Usage</strong><br>['.hefe_shortcode_name.'_page_taxonomy class=""]</p>';
						$hefe_tools_page .= '<hr />';
						$hefe_tools_page .= '<p><strong>Atts</strong><br>id, class, page_id, taxonomy, etc</p>';
					$hefe_tools_page .= '</div>';
					$hefe_tools_page .= '<div class="card">';
						$hefe_tools_page .= '<small>SHORTCODE</small><h2>PAGE TITLE</h2>';
						$hefe_tools_page .= '<p>This is a shortcode that displays a page title.</p>';
						$hefe_tools_page .= '<p><strong>Usage</strong><br>['.hefe_shortcode_name.'_page_title characters=""]</p>';
						$hefe_tools_page .= '<hr />';
						$hefe_tools_page .= '<p><strong>Atts</strong><br>characters, page_id</p>';
					$hefe_tools_page .= '</div>';
					$hefe_tools_page .= '<div class="card">';
						$hefe_tools_page .= '<small>SHORTCODE</small><h2>PAGE URL</h2>';
						$hefe_tools_page .= '<p>This is a shortcode that displays a page url.</p>';
						$hefe_tools_page .= '<p><strong>Usage</strong><br>['.hefe_shortcode_name.'_page_url]</p>';
						$hefe_tools_page .= '<hr />';
						$hefe_tools_page .= '<p><strong>Atts</strong><br>page_id</p>';
					$hefe_tools_page .= '</div>';
					$hefe_tools_page .= '<div class="card">';
						$hefe_tools_page .= '<small>SHORTCODE</small><h2>PAGE URL LINK</h2>';
						$hefe_tools_page .= '<p>This is a shortcode that creates a page url link.</p>';
						$hefe_tools_page .= '<p><strong>Usage</strong><br>['.hefe_shortcode_name.'_page_url_link class="" href=""]<br>CONTENT<br>[/'.hefe_shortcode_name.'_page_url_link]</p>';
						$hefe_tools_page .= '<hr />';
						$hefe_tools_page .= '<p><strong>Atts</strong><br>id, class, page_id, etc</p>';
					$hefe_tools_page .= '</div>';
					$hefe_tools_page .= '<div class="card">';
						$hefe_tools_page .= '<small>SHORTCODE</small><h2>POP OUT SIDEBAR LINK</h2>';
						$hefe_tools_page .= '<p>This is a shortcode that creates a link to open the Pop Out Sidebar. You must activate this feature in "Control" in order for this to work.</p>';
						$hefe_tools_page .= '<p><strong>Usage</strong><br>['.hefe_shortcode_name.'_pop_out_sidebar_link class=""]<br>CONTENT<br>[/'.hefe_shortcode_name.'_pop_out_sidebar_link]</p>';
						$hefe_tools_page .= '<hr />';
						$hefe_tools_page .= '<p><strong>Alts</strong><br>'.hefe_shortcode_name.'_pop_out_widgets_link, '.hefe_shortcode_name.'_pop_out_sidebar</p>';
						$hefe_tools_page .= '<hr />';
						$hefe_tools_page .= '<p><strong>Atts</strong><br>id, class, side (left, right), style (none, 01), etc</p>';
					$hefe_tools_page .= '</div>';
					$hefe_tools_page .= '<div class="card">';
						$hefe_tools_page .= '<small>SHORTCODE</small><h2>POST TYPE TITLE</h2>';
						$hefe_tools_page .= '<p>This is a shortcode that displays a post type title.</p>';
						$hefe_tools_page .= '<p><strong>Usage</strong><br>['.hefe_shortcode_name.'_post_type_title post_type_id=""]</p>';
						$hefe_tools_page .= '<hr />';
						$hefe_tools_page .= '<p><strong>Atts</strong><br>post_type_id</p>';
					$hefe_tools_page .= '</div>';
					$hefe_tools_page .= '<div class="card">';
						$hefe_tools_page .= '<small>SHORTCODE</small><h2>RANDOM DISPLAY PARENT</h2>';
						$hefe_tools_page .= '<p>This is a shortcode that randomly displays content on page load. Wrap this parent shortcode around the child shortcodes.</p>';
						$hefe_tools_page .= '<p><strong>Usage</strong><br>['.hefe_shortcode_name.'_random_display_parent class=""]<br>CHILD<br>[/'.hefe_shortcode_name.'_random_display_parent]</p>';
						$hefe_tools_page .= '<hr />';
						$hefe_tools_page .= '<p><strong>Atts</strong><br>id, class, etc</p>';
					$hefe_tools_page .= '</div>';
					$hefe_tools_page .= '<div class="card">';
						$hefe_tools_page .= '<small>SHORTCODE</small><h2>RANDOM DISPLAY CHILD</h2>';
						$hefe_tools_page .= '<p>This is a shortcode that randomly displays content on page load. Place these child shortcodes within the parent shortcode.</p>';
						$hefe_tools_page .= '<p><strong>Usage</strong><br>['.hefe_shortcode_name.'_random_display_child class=""]<br>CONTENT<br>[/'.hefe_shortcode_name.'_random_display_child]</p>';
						$hefe_tools_page .= '<hr />';
						$hefe_tools_page .= '<p><strong>Atts</strong><br>id, class, etc</p>';
					$hefe_tools_page .= '</div>';
					$hefe_tools_page .= '<div class="card">';
						$hefe_tools_page .= '<small>SHORTCODE</small><h2>RANDOM ORDER PARENT</h2>';
						$hefe_tools_page .= '<p>This is a shortcode that randomly orders content on page load. Wrap this parent shortcode around the child shortcodes.</p>';
						$hefe_tools_page .= '<p><strong>Usage</strong><br>['.hefe_shortcode_name.'_random_order_parent class=""]<br>CHILD<br>[/'.hefe_shortcode_name.'_random_order_parent]</p>';
						$hefe_tools_page .= '<hr />';
						$hefe_tools_page .= '<p><strong>Atts</strong><br>id, class, etc</p>';
					$hefe_tools_page .= '</div>';
					$hefe_tools_page .= '<div class="card">';
						$hefe_tools_page .= '<small>SHORTCODE</small><h2>RANDOM ORDER CHILD</h2>';
						$hefe_tools_page .= '<p>This is a shortcode that randomly orders content on page load. Place these child shortcodes within the parent shortcode.</p>';
						$hefe_tools_page .= '<p><strong>Usage</strong><br>['.hefe_shortcode_name.'_random_order_child class=""]<br>CONTENT<br>[/'.hefe_shortcode_name.'_random_order_child]</p>';
						$hefe_tools_page .= '<hr />';
						$hefe_tools_page .= '<p><strong>Atts</strong><br>id, class, etc</p>';
					$hefe_tools_page .= '</div>';
					$hefe_tools_page .= '<div class="card">';
						$hefe_tools_page .= '<small>SHORTCODE</small><h2>REGISTER LINK</h2>';
						$hefe_tools_page .= '<p>This is a shortcode that creates a register link.</p>';
						$hefe_tools_page .= '<p><strong>Usage</strong><br>['.hefe_shortcode_name.'_register_link class=""]<br>CONTENT<br>[/'.hefe_shortcode_name.'_register_link]</p>';
						$hefe_tools_page .= '<hr />';
						$hefe_tools_page .= '<p><strong>Atts</strong><br>id, class, redirect, etc</p>';
					$hefe_tools_page .= '</div>';
					$hefe_tools_page .= '<div class="card">';
						$hefe_tools_page .= '<small>SHORTCODE</small><h2>RELATED ARTICLE</h2>';
						$hefe_tools_page .= '<p>This is a shortcode that creates a box of a related page by category to the current page.</p>';
						$hefe_tools_page .= '<p><strong>Usage</strong><br>['.hefe_shortcode_name.'_related_article class=""]</p>';
						$hefe_tools_page .= '<hr />';
						$hefe_tools_page .= '<p><strong>Alts</strong><br>'.hefe_shortcode_name.'_related_articles</p>';
						$hefe_tools_page .= '<hr />';
						$hefe_tools_page .= '<p><strong>Atts</strong><br>id, class, post_type, page_id, cat, posts_per_page, order, orderby, post_not_in, style (none, 01), etc</p>';
					$hefe_tools_page .= '</div>';
					$hefe_tools_page .= '<div class="card">';
						$hefe_tools_page .= '<small>SHORTCODE</small><h2>REVEAL PARENT</h2>';
						$hefe_tools_page .= '<p>This is a shortcode that reveals under (child shortcode) content when hovered over over (child shortcode). Wrap this parent shortcode around the child shortcodes.</p>';
						$hefe_tools_page .= '<p><strong>Usage</strong><br>['.hefe_shortcode_name.'_reveal_parent class=""]<br>CHILD<br>[/'.hefe_shortcode_name.'_reveal_parent]</p>';
						$hefe_tools_page .= '<hr />';
						$hefe_tools_page .= '<p><strong>Atts:</strong><br>id, class, etc</p>';
					$hefe_tools_page .= '</div>';
					$hefe_tools_page .= '<div class="card">';
						$hefe_tools_page .= '<small>SHORTCODE</small><h2>REVEAL CHILD</h2>';
						$hefe_tools_page .= '<p>This is a shortcode that acts as the over/under content in reveal. Place these child shortcodes within the parent shortcode. Use the position attribute "over" to contain the content you first see, use the position attribute "under" to contain the content you want to see when you hover your mouse.</p>';
						$hefe_tools_page .= '<p><strong>Usage</strong><br>['.hefe_shortcode_name.'_reveal_child position="over"]<br>CONTENT<br>[/'.hefe_shortcode_name.'_reveal_child]</p>';
						$hefe_tools_page .= '<hr />';
						$hefe_tools_page .= '<p><strong>Atts:</strong><br>id, class, position (over, under), etc</p>';
					$hefe_tools_page .= '</div>';
					$hefe_tools_page .= '<div class="card">';
						$hefe_tools_page .= '<small>SHORTCODE</small><h2>SCROLL TO LINK</h2>';
						$hefe_tools_page .= '<p>This is a shortcode that acts as a link to scroll to the content shortcode. Copy the "PAIRED_ID" attribute with the "Scroll To Content" shortcode and it will toggle a window scroll to the content.</p>';
						$hefe_tools_page .= '<p><strong>Usage</strong><br>['.hefe_shortcode_name.'_scroll_to_link paired_id=""]<br>CONTENT<br>[/'.hefe_shortcode_name.'_scroll_to_link]</p>';
						$hefe_tools_page .= '<hr />';
						$hefe_tools_page .= '<p><strong>Alts</strong><br>'.hefe_shortcode_name.'_click_scroll_link</p>';
						$hefe_tools_page .= '<hr />';
						$hefe_tools_page .= '<p><strong>Atts</strong><br>id, class, paired_id, wrap (defaults: div), etc</p>';
					$hefe_tools_page .= '</div>';
					$hefe_tools_page .= '<div class="card">';
						$hefe_tools_page .= '<small>SHORTCODE</small><h2>SCROLL TO CONTENT</h2>';
						$hefe_tools_page .= '<p>This is a shortcode that acts as a content section for a scroll link. Copy the "PAIRED_ID" attribute with the "Scroll To Link" shortcode and it will be toggled to this content.</p>';
						$hefe_tools_page .= '<p><strong>Usage</strong><br>['.hefe_shortcode_name.'_scroll_to_content paired_id=""]<br>CONTENT<br>[/'.hefe_shortcode_name.'_scroll_to_content]</p>';
						$hefe_tools_page .= '<hr />';
						$hefe_tools_page .= '<p><strong>Alts</strong><br>'.hefe_shortcode_name.'_click_scroll_content</p>';
						$hefe_tools_page .= '<hr />';
						$hefe_tools_page .= '<p><strong>Atts</strong><br>id, class, paired_id, etc</p>';
					$hefe_tools_page .= '</div>';
					$hefe_tools_page .= '<div class="card">';
						$hefe_tools_page .= '<small>SHORTCODE</small><h2>SCROLL UP BOX ITEM</h2>';
						$hefe_tools_page .= '<p>This is a shortcode that creates a link to jump back to the top of the page.</p>';
						$hefe_tools_page .= '<p><strong>Usage</strong><br>['.hefe_shortcode_name.'_scroll_up_box class=""]<br>CONTENT<br>[/'.hefe_shortcode_name.'_scroll_up_box]</p>';
						$hefe_tools_page .= '<hr />';
						$hefe_tools_page .= '<p><strong>Atts</strong><br>id, class, automatic (true, false, blank), etc</p>';
					$hefe_tools_page .= '</div>';
					$hefe_tools_page .= '<div class="card">';
						$hefe_tools_page .= '<small>SHORTCODE</small><h2>SEARCH FORM</h2>';
						$hefe_tools_page .= '<p>This is a shortcode that display the WordPress search form.</p>';
						$hefe_tools_page .= '<p><strong>Usage</strong><br>['.hefe_shortcode_name.'_search_form]</p>';
					$hefe_tools_page .= '</div>';
					$hefe_tools_page .= '<div class="card">';
						$hefe_tools_page .= '<small>SHORTCODE</small><h2>SEARCH MODAL LINK</h2>';
						$hefe_tools_page .= '<p>This is a shortcode that creates a modal link with a search field. </p>';
						$hefe_tools_page .= '<p><strong>Usage</strong><br>['.hefe_shortcode_name.'_search_modal_link class=""]<br>CONTENT<br>[/'.hefe_shortcode_name.'_search_modal_link]</p>';
						$hefe_tools_page .= '<hr />';
						$hefe_tools_page .= '<p><strong>Atts</strong><br>id, class, etc</p>';
					$hefe_tools_page .= '</div>';
					$hefe_tools_page .= '<div class="card">';
						$hefe_tools_page .= '<small>SHORTCODE</small><h2>SEARCH QUERY</h2>';
						$hefe_tools_page .= '<p>This is a shortcode that displays the search query.</p>';
						$hefe_tools_page .= '<p><strong>Usage</strong><br>['.hefe_shortcode_name.'_search_query]</p>';
					$hefe_tools_page .= '</div>';
					$hefe_tools_page .= '<div class="card">';
						$hefe_tools_page .= '<small>SHORTCODE</small><h2>SIDEBAR</h2>';
						$hefe_tools_page .= '<p>This is a shortcode that displays the sidebar widgets of the relevant sidebar. You must create a sidebar through "Control" in order for this to work.</p>';
						$hefe_tools_page .= '<p><strong>Usage</strong><br>['.hefe_shortcode_name.'_sidebar_01 class=""]</p>';
						$hefe_tools_page .= '<hr />';
						$hefe_tools_page .= '<p><strong>Alts</strong><br>'.hefe_shortcode_name.'_sidebar_02, '.hefe_shortcode_name.'_sidebar_03, '.hefe_shortcode_name.'_sidebar_04, '.hefe_shortcode_name.'_sidebar_05, '.hefe_shortcode_name.'_sidebar_06</p>';
						$hefe_tools_page .= '<hr />';
						$hefe_tools_page .= '<p><strong>Atts</strong><br>id, class, etc</p>';
					$hefe_tools_page .= '</div>';
					$hefe_tools_page .= '<div class="card">';
						$hefe_tools_page .= '<small>SHORTCODE</small><h2>SIMPLE CAROUSEL PARENT</h2>';
						$hefe_tools_page .= '<p>This is a shortcode that creates a simple carousel. Wrap this parent shortcode around the child shortcodes.</p>';
						$hefe_tools_page .= '<p><strong>Usage</strong><br>['.hefe_shortcode_name.'_simple_carousel_parent id_number="" class=""]<br>CHILD<br>[/'.hefe_shortcode_name.'_simple_carousel_parent]</p>';
						$hefe_tools_page .= '<hr />';
						$hefe_tools_page .= '<p><strong>Atts</strong><br>id, class, id_number (unique to each carousel), speed (milliseconds), etc</p>';
					$hefe_tools_page .= '</div>';
					$hefe_tools_page .= '<div class="card">';
						$hefe_tools_page .= '<small>SHORTCODE</small><h2>SIMPLE CAROUSEL CHILD</h2>';
						$hefe_tools_page .= '<p>This is a shortcode that creates a simple carousel. Place these child shortcodes within the parent shortcode.</p>';
						$hefe_tools_page .= '<p><strong>Usage</strong><br>['.hefe_shortcode_name.'_simple_carousel_child class=""]<br>CONTENT<br>[/'.hefe_shortcode_name.'_simple_carousel_child]</p>';
						$hefe_tools_page .= '<hr />';
						$hefe_tools_page .= '<p><strong>Atts</strong><br>id, class, etc</p>';
					$hefe_tools_page .= '</div>';
					$hefe_tools_page .= '<div class="card">';
						$hefe_tools_page .= '<small>SHORTCODE</small><h2>SITE DESCRIPTION</h2>';
						$hefe_tools_page .= '<p>This is a shortcode displays the "Site Identity" site description.</p>';
						$hefe_tools_page .= '<p><strong>Usage</strong><br>['.hefe_shortcode_name.'_site_description]</p>';
					$hefe_tools_page .= '</div>';
					$hefe_tools_page .= '<div class="card">';
						$hefe_tools_page .= '<small>SHORTCODE</small><h2>SITE LOGO</h2>';
						$hefe_tools_page .= '<p>This is a shortcode displays the "Site Identity" site logo.</p>';
						$hefe_tools_page .= '<p><strong>Usage</strong><br>['.hefe_shortcode_name.'_site_logo class=""]</p>';
						$hefe_tools_page .= '<hr />';
						$hefe_tools_page .= '<p><strong>Atts</strong><br>id, class, alt, title, width, etc</p>';
					$hefe_tools_page .= '</div>';
					$hefe_tools_page .= '<div class="card">';
						$hefe_tools_page .= '<small>SHORTCODE</small><h2>SITE TITLE</h2>';
						$hefe_tools_page .= '<p>This is a shortcode displays the "Site Identity" site title.</p>';
						$hefe_tools_page .= '<p><strong>Usage</strong><br>['.hefe_shortcode_name.'_site_title]</p>';
					$hefe_tools_page .= '</div>';
					$hefe_tools_page .= '<div class="card">';
						$hefe_tools_page .= '<small>SHORTCODE</small><h2>SITE URL</h2>';
						$hefe_tools_page .= '<p>This is a shortcode that displays the site url.</p>';
						$hefe_tools_page .= '<p><strong>Usage</strong><br>['.hefe_shortcode_name.'_site_url]</p>';
					$hefe_tools_page .= '</div>';
					$hefe_tools_page .= '<div class="card">';
						$hefe_tools_page .= '<small>SHORTCODE</small><h2>SITE URL LINK</h2>';
						$hefe_tools_page .= '<p>This is a shortcode that creates a site url link.</p>';
						$hefe_tools_page .= '<p><strong>Usage</strong><br>['.hefe_shortcode_name.'_site_url_link class="" href=""]<br>CONTENT<br>[/'.hefe_shortcode_name.'_site_url_link]</p>';
						$hefe_tools_page .= '<hr />';
						$hefe_tools_page .= '<p><strong>Atts</strong><br>id, class, etc</p>';
					$hefe_tools_page .= '</div>';
					$hefe_tools_page .= '<div class="card">';
						$hefe_tools_page .= '<small>SHORTCODE</small><h2>SOCIAL SHARE LINK</h2>';
						$hefe_tools_page .= '<p>This is a shortcode that creates a social share link.</p>';
						$hefe_tools_page .= '<p><strong>Usage</strong><br>['.hefe_shortcode_name.'_social_share_link class="" company=""]<br>CONTENT<br>[/'.hefe_shortcode_name.'_social_share_link]</p>';
						$hefe_tools_page .= '<hr />';
						$hefe_tools_page .= '<p><strong>Atts</strong><br>id, class, company (facebook, twitter, google+, pinterest, linkedin, buffer, digg, tumblr, reddit, stumbleupon, delicious, blogger, livejournal, myspace, yahoo, friendfeed, newsvine, evernote, getpocket, flipboard, instapaper, line.me, skype, viber, whatsapp, telegram.me, vk, okru, douban, baidu, qzone, xing, renren, weibo, email), url, etc</p>';
					$hefe_tools_page .= '</div>';
					$hefe_tools_page .= '<div class="card">';
						$hefe_tools_page .= '<small>SHORTCODE</small><h2>SPACE</h2>';
						$hefe_tools_page .= '<p>This is a shortcode that creates a "&nbsp;" space.</p>';
						$hefe_tools_page .= '<p><strong>Usage</strong><br>['.hefe_shortcode_name.'_space]</p>';
						$hefe_tools_page .= '<hr />';
						$hefe_tools_page .= '<p><strong>Alts:</strong><br>'.hefe_shortcode_name.'_nbsp</p>';
					$hefe_tools_page .= '</div>';
					$hefe_tools_page .= '<div class="card">';
						$hefe_tools_page .= '<small>SHORTCODE</small><h2>STICKY ITEM</h2>';
						$hefe_tools_page .= '<p>This is a shortcode that creates a sticky item at the top of the page as you scroll.</p>';
						$hefe_tools_page .= '<p><strong>Usage</strong><br>['.hefe_shortcode_name.'_sticky_item class=""]<br>CONTENT<br>[/'.hefe_shortcode_name.'_sticky_item]</p>';
						$hefe_tools_page .= '<hr />';
						$hefe_tools_page .= '<p><strong>Atts</strong><br>id, class, etc</p>';
					$hefe_tools_page .= '</div>';
					$hefe_tools_page .= '<div class="card">';
						$hefe_tools_page .= '<small>SHORTCODE</small><h2>SUDO SLIDER PARENT</h2>';
						$hefe_tools_page .= '<p>This is a shortcode that creates a carousel using <a href="http://webbies.dk/SudoSlider/" target="_blank">Sudo Slider</a>. Wrap this shortcode around the "Sudo Slider" children shortcodes to contain them.</p>';
						$hefe_tools_page .= '<p><strong>Usage</strong><br>['.hefe_shortcode_name.'_sudo_slider_parent id_number="" class=""]<br>CHILD<br>[/'.hefe_shortcode_name.'_sudo_slider_parent]</p>';
						$hefe_tools_page .= '<hr />';
						$hefe_tools_page .= '<p><strong>Atts</strong><br>id, class, id_number, pause (xs_, sm_, md_, lg_, xl_), speed (xs_, sm_, md_, lg_, xl_), effect (xs_, sm_, md_, lg_, xl_), auto (xs_, sm_, md_, lg_, xl_), numeric (xs_, sm_, md_, lg_, xl_), slide_count (xs_, sm_, md_, lg_, xl_), move_count (xs_, sm_, md_, lg_, xl_), prev_next (xs_, sm_, md_, lg_, xl_), prev_icon (xs_, sm_, md_, lg_, xl_), next_icon (xs_, sm_, md_, lg_, xl_), etc</p>';
					$hefe_tools_page .= '</div>';
					$hefe_tools_page .= '<div class="card">';
						$hefe_tools_page .= '<small>SHORTCODE</small><h2>SUDO SLIDER CHILD</h2>';
						$hefe_tools_page .= '<p>This is a shortcode that creates a carousel using <a href="http://webbies.dk/SudoSlider/" target="_blank">Sudo Slider</a>. Wrap this shortcode around your content to create a carousel slide from it.</p>';
						$hefe_tools_page .= '<p><strong>Usage</strong><br>['.hefe_shortcode_name.'_sudo_slider_child class=""]<br>CONTENT<br>[/'.hefe_shortcode_name.'_sudo_slider_child]</p>';
						$hefe_tools_page .= '<hr />';
						$hefe_tools_page .= '<p><strong>Atts</strong><br>id, class, etc</p>';
					$hefe_tools_page .= '</div>';
					$hefe_tools_page .= '<div class="card">';
						$hefe_tools_page .= '<small>SHORTCODE</small><h2>TABS LINK</h2>';
						$hefe_tools_page .= '<p>This is a shortcode that acts as a link for a tabs function. Copy the "PAIRED_ID" attribute with the "Tabs Content" shortcode and it will toggle display the content.</p>';
						$hefe_tools_page .= '<p>You can automatically open a tab via a URL. Simply add the parameter ?tab_01=paired_id (_02, _03, _04, _05, _06).</p>';
						$hefe_tools_page .= '<p><strong>Usage</strong><br>['.hefe_shortcode_name.'_tabs_link paired_id=""]<br>CONTENT<br>[/'.hefe_shortcode_name.'_tabs_link]</p>';
						$hefe_tools_page .= '<hr />';
						$hefe_tools_page .= '<p><strong>Alts</strong><br>'.hefe_shortcode_name.'_click_fade_link</p>';
						$hefe_tools_page .= '<hr />';
						$hefe_tools_page .= '<p><strong>Atts</strong><br>id, class, paired_id, wrap (defaults: div), style (none, 01), active (true, false, blank), etc</p>';
					$hefe_tools_page .= '</div>';
					$hefe_tools_page .= '<div class="card">';
						$hefe_tools_page .= '<small>SHORTCODE</small><h2>TABS CONTENT</h2>';
						$hefe_tools_page .= '<p>This is a shortcode that acts as a content section for a tabs function. Copy the "PAIRED_ID" attribute with the "Tabs Link" shortcode and it will be toggled display.</p>';
						$hefe_tools_page .= '<p><strong>Usage</strong><br>['.hefe_shortcode_name.'_tabs_content paired_id=""]<br>CONTENT<br>[/'.hefe_shortcode_name.'_tabs_content]</p>';
						$hefe_tools_page .= '<hr />';
						$hefe_tools_page .= '<p><strong>Alts</strong><br>'.hefe_shortcode_name.'_click_fade_content</p>';
						$hefe_tools_page .= '<hr />';
						$hefe_tools_page .= '<p><strong>Atts</strong><br>id, class, paired_id, style (none, 01), active (true, false, blank), etc</p>';
					$hefe_tools_page .= '</div>';
					$hefe_tools_page .= '<div class="card">';
						$hefe_tools_page .= '<small>SHORTCODE</small><h2>TAG LIST</h2>';
						$hefe_tools_page .= '<p>This is a shortcode that displays all tags on the site.</p>';
						$hefe_tools_page .= '<p><strong>Usage</strong><br>['.hefe_shortcode_name.'_tag_list class=""]</p>';
						$hefe_tools_page .= '<hr />';
						$hefe_tools_page .= '<p><strong>Alts</strong><br>'.hefe_shortcode_name.'_tags_list</p>';
						$hefe_tools_page .= '<hr />';
						$hefe_tools_page .= '<p><strong>Atts</strong><br>id, class, limit, etc</p>';
					$hefe_tools_page .= '</div>';
					$hefe_tools_page .= '<div class="card">';
						$hefe_tools_page .= '<small>SHORTCODE</small><h2>TOOLTIP PARENT</h2>';
						$hefe_tools_page .= '<p>This is a shortcode used when creating a parent element for a tooltip. Wrap this shortcode around the child tooltip shortcode.</p>';
						$hefe_tools_page .= '<p><strong>Usage</strong><br>['.hefe_shortcode_name.'_tooltip_parent vertical_position="" horizontal_position=""]<br>CONTENT<br>[/'.hefe_shortcode_name.'_tooltip_parent]</p>';
						$hefe_tools_page .= '<hr />';
						$hefe_tools_page .= '<p><strong>Atts</strong><br>id, class, vertical_position (top, bottom), horizontal_position (center, left, right), size (xs, sm, md, lg, xl), etc</p>';
					$hefe_tools_page .= '</div>';
					$hefe_tools_page .= '<div class="card">';
						$hefe_tools_page .= '<small>SHORTCODE</small><h2>TOOLTIP CHILD</h2>';
						$hefe_tools_page .= '<p>This is a shortcode used when creating a child element for a tooltip. Wrap this shortcode around the content you want to pop up as a tooltip.</p>';
						$hefe_tools_page .= '<p><strong>Usage</strong><br>['.hefe_shortcode_name.'_tooltip_child class=""]<br>CONTENT<br>[/'.hefe_shortcode_name.'_tooltip_child]</p>';
						$hefe_tools_page .= '<hr />';
						$hefe_tools_page .= '<p><strong>Atts</strong><br>id, class, etc</p>';
					$hefe_tools_page .= '</div>';
					$hefe_tools_page .= '<div class="card">';
						$hefe_tools_page .= '<small>SHORTCODE</small><h2>TWENTYTWENTY PARENT</h2>';
						$hefe_tools_page .= '<p>This is a shortcode that creates a before/after box using <a href="https://zurb.com/playground/twentytwenty" target="_blank">TwentyTwenty</a>. Wrap this shortcode around a the child shortcodes to contain them.</p>';
						$hefe_tools_page .= '<p><strong>Usage</strong><br>['.hefe_shortcode_name.'_twentytwenty_parent class=""]<br>CHILD<br>[/'.hefe_shortcode_name.'_twentytwenty_parent]</p>';
						$hefe_tools_page .= '<hr />';
						$hefe_tools_page .= '<p><strong>Alts</strong><br>'.hefe_shortcode_name.'_before_after_parent</p>';
						$hefe_tools_page .= '<hr />';
						$hefe_tools_page .= '<p><strong>Atts</strong><br>id, class, etc</p>';
					$hefe_tools_page .= '</div>';
					$hefe_tools_page .= '<div class="card">';
						$hefe_tools_page .= '<small>SHORTCODE</small><h2>TWENTYTWENTY CHILD</h2>';
						$hefe_tools_page .= '<p>This is a shortcode used when creating a child.</p>';
						$hefe_tools_page .= '<p>This is a shortcode that creates a before/after box using <a href="https://zurb.com/playground/twentytwenty" target="_blank">TwentyTwenty</a>. Add two of these child shortcodes to create the before/after images. The first shortcode should have a "SRC" attribute with the "Before" image url. The second shortcode should have a "SRC" attribute with the "After" image url.</p>';
						$hefe_tools_page .= '<p><strong>Usage</strong><br>['.hefe_shortcode_name.'_twentytwenty_child src=""]</p>';
						$hefe_tools_page .= '<hr />';
						$hefe_tools_page .= '<p><strong>Alts</strong><br>'.hefe_shortcode_name.'_before_after_child, '.hefe_shortcode_name.'_before_after_child_before, '.hefe_shortcode_name.'_before_after_child_after, '.hefe_shortcode_name.'_twentytwenty_child_before, '.hefe_shortcode_name.'_twentytwenty_child_after</p>';
						$hefe_tools_page .= '<hr />';
						$hefe_tools_page .= '<p><strong>Atts</strong><br>id, class, src, etc</p>';
					$hefe_tools_page .= '</div>';
					$hefe_tools_page .= '<div class="card">';
						$hefe_tools_page .= '<small>SHORTCODE</small><h2>UL</h2>';
						$hefe_tools_page .= '<p>This is a shortcode that creates an unordered list tag.</p>';
						$hefe_tools_page .= '<p><strong>Usage</strong><br>['.hefe_shortcode_name.'_ul class=""]<br>CONTENT<br>[/'.hefe_shortcode_name.'_ul]</p>';
						$hefe_tools_page .= '<hr />';
						$hefe_tools_page .= '<p><strong>Atts</strong><br>id, class, etc</p>';
					$hefe_tools_page .= '</div>';
					$hefe_tools_page .= '<div class="card">';
						$hefe_tools_page .= '<small>SHORTCODE</small><h2>USER META</h2>';
						$hefe_tools_page .= '<p>This is a shortcode that displays user meta information.</p>';
						$hefe_tools_page .= '<p><strong>Usage</strong><br>['.hefe_shortcode_name.'_user_meta parameter="" user_id=""]</p>';
						$hefe_tools_page .= '<hr />';
						$hefe_tools_page .= '<p><strong>Atts</strong><br>parameter (admin_color, aim, comment_shortcuts, description, display_name, first_name, ID, jabber, last_name, nickname, plugins_last_view, plugins_per_page, rich_editing, syntax_highlighting, user_activation_key, user_description, user_email, user_firstname, user_lastname, user_level, user_login, user_nicename, user_pass, user_registered, user_status, user_url, yim, etc), user_id</p>';
					$hefe_tools_page .= '</div>';
					$hefe_tools_page .= '<div class="card">';
						$hefe_tools_page .= '<small>SHORTCODE</small><h2>IF USER ROLE</h2>';
						$hefe_tools_page .= '<p>This is a shortcode that displays content based on certain user roles.</p>';
						$hefe_tools_page .= '<p><strong>Usage</strong><br>['.hefe_shortcode_name.'_if_user_role role=""]<br>CONTENT<br>[/'.hefe_shortcode_name.'_if_user_role]</p>';
						$hefe_tools_page .= '<hr />';
						$hefe_tools_page .= '<p><strong>Atts</strong><br>role</p>';
					$hefe_tools_page .= '</div>';
					$hefe_tools_page .= '<div class="card">';
						$hefe_tools_page .= '<small>SHORTCODE</small><h2>IF NOT USER ROLE</h2>';
						$hefe_tools_page .= '<p>This is a shortcode that displays content based on not a certain user roles.</p>';
						$hefe_tools_page .= '<p><strong>Usage</strong><br>['.hefe_shortcode_name.'_if_not_user_role role=""]<br>CONTENT<br>[/'.hefe_shortcode_name.'_if_not_user_role]</p>';
						$hefe_tools_page .= '<hr />';
						$hefe_tools_page .= '<p><strong>Atts</strong><br>role</p>';
					$hefe_tools_page .= '</div>';
					$hefe_tools_page .= '<div class="card">';
						$hefe_tools_page .= '<small>SHORTCODE</small><h2>UNFORMAT</h2>';
						$hefe_tools_page .= '<p>This is a shortcode that unformats WordPress default content.</p>';
						$hefe_tools_page .= '<p><strong>Usage</strong><br>['.hefe_shortcode_name.'_unformat]<br>CONTENT<br>[/'.hefe_shortcode_name.'_unformat]</p>';
					$hefe_tools_page .= '</div>';
					$hefe_tools_page .= '<div class="card">';
						$hefe_tools_page .= '<small>SHORTCODE</small><h2>VIDEO PLAYER</h2>';
						$hefe_tools_page .= '<p>This is a shortcode that displays a video player.</p>';
						$hefe_tools_page .= '<p><strong>Usage</strong><br>['.hefe_shortcode_name.'_video_player class=""]</p>';
						$hefe_tools_page .= '<hr />';
						$hefe_tools_page .= '<p><strong>Atts</strong><br>id, class, src, preload, poster, autoplay, controls, loop, muted, modestbranding, autohide, showinfo, playsinline, etc</p>';
					$hefe_tools_page .= '</div>';
					$hefe_tools_page .= '<div class="card">';
						$hefe_tools_page .= '<small>SHORTCODE</small><h2>WP QUERY</h2>';
						$hefe_tools_page .= '<p>This is a shortcode that creates a custom WP query.</p>';
						$hefe_tools_page .= '<p><strong>Usage</strong><br>['.hefe_shortcode_name.'_wp_query]<br>CONTENT<br>[/'.hefe_shortcode_name.'_wp_query]</p>';
						$hefe_tools_page .= '<hr />';
						$hefe_tools_page .= '<p><strong>Alts</strong><br>'.hefe_shortcode_name.'_query</p>';
						$hefe_tools_page .= '<hr />';
						$hefe_tools_page .= '<p><strong>Atts:</strong><br>author, author_name, author__in, author__not_in, cat, category_name, category__and, category__in, category__not_in, tag, tag_id, tag__and, tag__in, tag__not_in, tag_slug__and, tag_slug__in, tax_query_relation, tax_query_one_taxonomy, tax_query_one_field, tax_query_one_terms, tax_query_one_include_children, tax_query_one_operator, tax_query_two_taxonomy, tax_query_two_field, tax_query_two_terms, tax_query_two_include_children, tax_query_two_operator, p, name, page_id, pagename, post_parent, post_parent__in, post_parent__not_in, post__in, post__not_in, has_password, post_password, post_typeany, post_status, posts_per_page, posts_per_archive_page, pagination, offset, order, orderby, year, monthnum, w, day, hour, minute, second, m, date_query_year, date_query_month, date_query_week, date_query_day, date_query_hour, date_query_minute, date_query_second, date_query_after, date_query_after_year, date_query_after_month, date_query_after_day, date_query_before, date_query_before_year, date_query_before_month, date_query_before_day, date_query_inclusive, date_query_compare, date_query_column, date_query_relation, meta_key, meta_value, meta_value_num, meta_compare, meta_query_relation, meta_query_one_key, meta_query_one_value, meta_query_one_type, meta_query_one_compare, meta_query_two_key, meta_query_two_value, meta_query_two_type, meta_query_two_compare, perm, s</p>';
					$hefe_tools_page .= '</div>';

				}

			$hefe_tools_page .= '</div>';

			echo $hefe_tools_page;

		}
	}

}

/* Customizer
------------------------------ */

if(!function_exists('hefe_customizer')){
	add_action('customize_register', 'hefe_customizer');
	function hefe_customizer($wp_customize){

		// Helpful Features
		$wp_customize->add_panel('hefe_customizer_panel', array(
			'priority' => '10',
			'capability' => 'edit_theme_options',
			'theme_supports' => '',
			'title' => 'Helpful Features',
			'description' => '',
		));	

			// Controls
			$wp_customize->add_section('hefe_control_customizer_section' , array(
				'priority' => '',
				'capability' => 'edit_theme_options',
				'theme_supports' => '',
				'title' => 'Controls',
				'description' => '<p>We encourage you to <a href="'.site_url().'/wp-admin/tools.php?page=hefe-tools&tab=controls" class="button">Learn More</a> about this feature!</p>',
				'panel' => 'hefe_customizer_panel',
			));
				// Admin Bar Disable For
				$wp_customize->add_setting('hefe_control_customizer_control_disable_admin_bar_by_user_role', array(
					'type' => 'option',
				));
				$wp_customize->add_control(new WP_Customize_Control($wp_customize, 'hefe_control_customizer_control_disable_admin_bar_by_user_role', array(
					'type' => '',
					'priority' => '',
					'section' => 'hefe_control_customizer_section',
					'label' => 'Admin Bar Disable For',
					'description' => 'Disable the admin bar for only a list of comma seperated user roles.',
					'input_attrs' => array(
						'placeholder' => 'EX: admin,editor,subscriber..',
					),
				)));
				// Admin Bar Enable For
				$wp_customize->add_setting('hefe_control_customizer_control_enable_admin_bar_by_user_role', array(
					'type' => 'option',
				));
				$wp_customize->add_control(new WP_Customize_Control($wp_customize, 'hefe_control_customizer_control_enable_admin_bar_by_user_role', array(
					'type' => '',
					'priority' => '',
					'section' => 'hefe_control_customizer_section',
					'label' => 'Admin Bar Enable For',
					'description' => 'Enable the admin bar for only a list of comma seperated user roles.',
					'input_attrs' => array(
						'placeholder' => 'EX: admin,editor,subscriber..',
					),
				)));
				// Banner Per Page
				$wp_customize->add_setting('hefe_control_customizer_control_banner_per_page', array(
					'type' => 'option',
				));
				$wp_customize->add_control(new WP_Customize_Control($wp_customize, 'hefe_control_customizer_control_banner_per_page', array(
					'type' => '',
					'priority' => '',
					'section' => 'hefe_control_customizer_section',
					'label' => 'Banner Per Page',
					'description' => 'Enable the banner per page for only a list of comma seperated post types.',
					'input_attrs' => array(
						'placeholder' => 'EX: page,post,etc..',
					),
				)));
				// Custom Author Base
				$wp_customize->add_setting('hefe_control_customizer_control_custom_author_base', array(
					'type' => 'option',
					'default' => '',
				));
				$wp_customize->add_control(new WP_Customize_Control($wp_customize, 'hefe_control_customizer_control_custom_author_base', array(
					'type' => '',
					'priority' => '',
					'section' => 'hefe_control_customizer_section',
					'label' => 'Custom Author Base',
					'description' => 'Enter the slug you would like to replace the default author base. You must update permalinks for effect to take place.',
					'input_attrs' => array(
						'placeholder' => 'EX: author',
					),
				)));
				// Forced Login
				$wp_customize->add_setting('hefe_control_customizer_control_enable_forced_login', array(
					'type' => 'option',
					'sanitize_callback' => 'sanitize_text_field',
					'default' => '',
				));
				$wp_customize->add_control(new WP_Customize_Control($wp_customize, 'hefe_control_customizer_control_enable_forced_login', array(
					'type' => 'checkbox',
					'priority' => '',
					'section' => 'hefe_control_customizer_section',
					'label' => 'Enable Forced Login',
					'description' => 'Enable forcing people to login when visiting this site.',
				)));
				// Google Analytics
				$wp_customize->add_setting('hefe_control_customizer_control_google_analytics_ua_code', array(
					'type' => 'option',
					'sanitize_callback' => 'sanitize_text_field',
					'default' => '',
				));
				$wp_customize->add_control(new WP_Customize_Control($wp_customize, 'hefe_control_customizer_control_google_analytics_ua_code', array(
					'type' => '',
					'priority' => '',
					'section' => 'hefe_control_customizer_section',
					'label' => 'Google Analytics',
					'description' => 'Enter your tracking code here.',
					'input_attrs' => array(
						'placeholder' => 'EX: UA-########-#',
					),
				)));
				// Google Fonts
				$wp_customize->add_setting('hefe_control_customizer_control_google_fonts_fonts_url', array(
					'type' => 'option',
					'sanitize_callback' => 'sanitize_text_field',
					'default' => '',
				));
				$wp_customize->add_control(new WP_Customize_Control($wp_customize, 'hefe_control_customizer_control_google_fonts_fonts_url', array(
					'type' => '',
					'priority' => '',
					'section' => 'hefe_control_customizer_section',
					'label' => 'Google Fonts URL',
					'description' => 'Enter the fonts url here.',
					'input_attrs' => array(
						'placeholder' => 'EX: https://fonts.googleapis.com/..',
					),
				)));
				// Home Page Orderby
				$wp_customize->add_setting('hefe_control_customizer_control_home_page_orderby', array(
					'type' => 'option',
					'sanitize_callback' => 'sanitize_text_field',
					'default' => '',
				));
				$wp_customize->add_control(new WP_Customize_Control($wp_customize, 'hefe_control_customizer_control_home_page_orderby', array(
					'type' => '',
					'priority' => '',
					'section' => 'hefe_control_customizer_section',
					'label' => 'Home Page Orderby',
					'description' => 'Order the home page in a unique way?',
					'input_attrs' => array(
						'placeholder' => 'EX: rand, date, title..',
					),
				)));
				// Home Page Order
				$wp_customize->add_setting('hefe_control_customizer_control_home_page_order', array(
					'type' => 'option',
					'sanitize_callback' => 'sanitize_text_field',
					'default' => '',
				));
				$wp_customize->add_control(new WP_Customize_Control($wp_customize, 'hefe_control_customizer_control_home_page_order', array(
					'type' => '',
					'priority' => '',
					'section' => 'hefe_control_customizer_section',
					'label' => 'Home Page Order',
					'description' => 'Order the home page in a unique way?',
					'input_attrs' => array(
						'placeholder' => 'EX: ASC, DESC',
					),
				)));
				// Home Page Post Types
				$wp_customize->add_setting('hefe_control_customizer_control_post_types_on_home_page', array(
					'type' => 'option',
					'default' => '',
				));
				$wp_customize->add_control(new WP_Customize_Control($wp_customize, 'hefe_control_customizer_control_post_types_on_home_page', array(
					'type' => '',
					'priority' => '',
					'section' => 'hefe_control_customizer_section',
					'label' => 'Home Page Post Types',
					'description' => 'Home post types by comma seperated list of post types',
					'input_attrs' => array(
						'placeholder' => 'EX: post,page..',
					),
				)));
				// Home Page Post Types Status
				$wp_customize->add_setting('hefe_control_customizer_control_post_type_status_on_home_page', array(
					'type' => 'option',
					'default' => '',
				));
				$wp_customize->add_control(new WP_Customize_Control($wp_customize, 'hefe_control_customizer_control_post_type_status_on_home_page', array(
					'type' => '',
					'priority' => '',
					'section' => 'hefe_control_customizer_section',
					'label' => 'Home Page Post Types Status',
					'description' => 'Enter a comma seperated list of the post status for the home page post types.',
					'input_attrs' => array(
						'placeholder' => 'EX: any, publish, draft..',
					),
				)));
				// Injection
				$wp_customize->add_setting('hefe_control_customizer_control_injection', array(
					'type' => 'option',
					'sanitize_callback' => 'sanitize_text_field',
					'default' => '',
				));
				$wp_customize->add_control(new WP_Customize_Control($wp_customize, 'hefe_control_customizer_control_injection', array(
					'type' => 'checkbox',
					'priority' => '',
					'section' => 'hefe_control_customizer_section',
					'label' => 'Injection',
					'description' => 'Would you like include "Injection" features onto this site?</small>',
				)));
				// Pop Out Sidebar
				$wp_customize->add_setting('hefe_control_customizer_control_pop_out_sidebar', array(
					'type' => 'option',
					'sanitize_callback' => 'sanitize_text_field',
					'default' => '',
				));
				$wp_customize->add_control(new WP_Customize_Control($wp_customize, 'hefe_control_customizer_control_pop_out_sidebar', array(
					'type' => 'checkbox',
					'priority' => '',
					'section' => 'hefe_control_customizer_section',
					'label' => 'Pop Out Sidebar',
					'description' => 'Would you like include "Pop Out Sidebar" on your site?',
				)));
				// reCatpcha Public Key
				$wp_customize->add_setting('hefe_control_customizer_control_rcapk', array(
					'type' => 'option',
				));
				$wp_customize->add_control(new WP_Customize_Control($wp_customize, 'hefe_control_customizer_control_rcapk', array(
					'type' => '',
					'priority' => '',
					'section' => 'hefe_control_customizer_section',
					'label' => 'reCaptcha Public Key',
					'description' => 'Public & Private keys add reCaptcha to your WordPress login screen.',
					'input_attrs' => array(
						'placeholder' => 'EX: 234qkl4j1234..',
					),
				)));
				// reCatpcha Private Key
				$wp_customize->add_setting('hefe_control_customizer_control_rcappk', array(
					'type' => 'option',
				));
				$wp_customize->add_control(new WP_Customize_Control($wp_customize, 'hefe_control_customizer_control_rcappk', array(
					'type' => '',
					'priority' => '',
					'section' => 'hefe_control_customizer_section',
					'label' => 'reCaptcha Private Key',
					'description' => 'Public & Private keys add reCaptcha to your WordPress login screen.',
					'input_attrs' => array(
						'placeholder' => 'EX: 234qkl4j1234..',
					),
				)));
				// Scroll Up Box Automatic
				$wp_customize->add_setting('hefe_control_customizer_control_scroll_up_box', array(
					'type' => 'option',
					'sanitize_callback' => 'sanitize_text_field',
					'default' => '',
				));
				$wp_customize->add_control(new WP_Customize_Control($wp_customize, 'hefe_control_customizer_control_scroll_up_box', array(
					'type' => 'checkbox',
					'priority' => '',
					'section' => 'hefe_control_customizer_section',
					'label' => 'Scroll Up Box Automatic',
					'description' => 'Would you like "Scroll Up Box" automatically loaded on the bottom right corner of every page of your site?',
				)));
				// Search Page Excluded Page IDs
				$wp_customize->add_setting('hefe_control_customizer_control_page_ids_excluded_from_search', array(
					'type' => 'option',
				));
				$wp_customize->add_control(new WP_Customize_Control($wp_customize, 'hefe_control_customizer_control_page_ids_excluded_from_search', array(
					'type' => '',
					'priority' => '',
					'section' => 'hefe_control_customizer_section',
					'label' => 'Search Page Excluded Page IDs',
					'description' => 'Exclude pages from the search by a list of comma seperated ids.',
					'input_attrs' => array(
						'placeholder' => 'EX: 1,2,3..',
					),
				)));
				// Search Page Excluded Post Types
				$wp_customize->add_setting('hefe_control_customizer_control_post_types_excludes_from_search', array(
					'type' => 'option',
				));
				$wp_customize->add_control(new WP_Customize_Control($wp_customize, 'hefe_control_customizer_control_post_types_excludes_from_search', array(
					'type' => '',
					'priority' => '',
					'section' => 'hefe_control_customizer_section',
					'label' => 'Search Page Excluded Post Types',
					'description' => 'Exclude pages from the search by a list of comma seperated post types.',
					'input_attrs' => array(
						'placeholder' => 'EX: page,post..',
					),
				)));
				// SEO
				$wp_customize->add_setting('hefe_control_customizer_control_seo', array(
					'type' => 'option',
					'sanitize_callback' => 'sanitize_text_field',
					'default' => '',
				));
				$wp_customize->add_control(new WP_Customize_Control($wp_customize, 'hefe_control_customizer_control_seo', array(
					'type' => 'checkbox',
					'priority' => '',
					'section' => 'hefe_control_customizer_section',
					'label' => 'SEO',
					'description' => 'Would you like to include "SEO fields" on your site pages?',
				)));
				// Shortcode Name
				$wp_customize->add_setting('hefe_control_customizer_control_shortcode_name', array(
					'type' => 'option',
				));
				$wp_customize->add_control(new WP_Customize_Control($wp_customize, 'hefe_control_customizer_control_shortcode_name', array(
					'type' => '',
					'priority' => '',
					'section' => 'hefe_control_customizer_section',
					'label' => 'Shortcode Name',
					'description' => 'Enter text to replace "hefe" on all shortcodes.',
					'input_attrs' => array(
						'placeholder' => 'hefe',
					),
				)));
				// Sidebar 01
				$wp_customize->add_setting('hefe_control_customizer_control_sidebar_01', array(
					'type' =>'option',
				));
				$wp_customize->add_control(new WP_Customize_Control($wp_customize, 'hefe_control_customizer_control_sidebar_01', array(
					'type'     =>'',
					'priority' =>'',
					'section'  =>'hefe_control_customizer_section',
					'label'    =>'Sidebar 01',
					'description' =>'['.hefe_shortcode_name.'_sidebar_01]',
					'input_attrs' => array(
						'placeholder' => 'Custom Title',
					),
				)));
				// Sidebar 01 Header
				$wp_customize->add_setting('hefe_control_customizer_control_sidebar_01_header', array(
					'default'     => '',
					'transport'   => 'refresh',
					'type' => 'option',
				));
				$wp_customize->add_control(new WP_Customize_Control($wp_customize, 'hefe_control_customizer_control_sidebar_01_header', array(
					'type' => 'select',
					'priority' => '',
					'section' => 'hefe_control_customizer_section',
					'label' => '',
					'description' => 'Select a header tag.',
					'choices'        => array(
						''   => __( 'Select' ),
						'h1'   => __( 'H1' ),
						'h2'   => __( 'H2' ),
						'h3'   => __( 'H3' ),
						'h4'   => __( 'H4' ),
						'h5'   => __( 'H5' ),
						'h6'   => __( 'H6' ),
					)
				)));
				// Sidebar 02
				$wp_customize->add_setting('hefe_control_customizer_control_sidebar_02', array(
					'type' =>'option',
				));
				$wp_customize->add_control(new WP_Customize_Control($wp_customize, 'hefe_control_customizer_control_sidebar_02', array(
					'type'     =>'',
					'priority' =>'',
					'section'  =>'hefe_control_customizer_section',
					'label'    =>'Sidebar 02',
					'description' =>'['.hefe_shortcode_name.'_sidebar_02]',
					'input_attrs' => array(
						'placeholder' => 'Custom Title',
					),
				)));
				// Sidebar 02 Header
				$wp_customize->add_setting('hefe_control_customizer_control_sidebar_02_header', array(
					'default'     => '',
					'transport'   => 'refresh',
					'type' => 'option',
				));
				$wp_customize->add_control(new WP_Customize_Control($wp_customize, 'hefe_control_customizer_control_sidebar_02_header', array(
					'type' => 'select',
					'priority' => '',
					'section' => 'hefe_control_customizer_section',
					'label' => '',
					'description' => 'Select a header tag.',
					'choices'        => array(
						''   => __( 'Select' ),
						'h1'   => __( 'H1' ),
						'h2'   => __( 'H2' ),
						'h3'   => __( 'H3' ),
						'h4'   => __( 'H4' ),
						'h5'   => __( 'H5' ),
						'h6'   => __( 'H6' ),
					)
				)));
				// Sidebar 03
				$wp_customize->add_setting('hefe_control_customizer_control_sidebar_03', array(
					'type' =>'option',
				));
				$wp_customize->add_control(new WP_Customize_Control($wp_customize, 'hefe_control_customizer_control_sidebar_03', array(
					'type'     =>'',
					'priority' =>'',
					'section'  =>'hefe_control_customizer_section',
					'label'    =>'Sidebar 03',
					'description' =>'['.hefe_shortcode_name.'_sidebar_03]',
					'input_attrs' => array(
						'placeholder' => 'Custom Title',
					),
				)));
				// Sidebar 03 Header
				$wp_customize->add_setting('hefe_control_customizer_control_sidebar_03_header', array(
					'default'     => '',
					'transport'   => 'refresh',
					'type' => 'option',
				));
				$wp_customize->add_control(new WP_Customize_Control($wp_customize, 'hefe_control_customizer_control_sidebar_03_header', array(
					'type' => 'select',
					'priority' => '',
					'section' => 'hefe_control_customizer_section',
					'label' => '',
					'description' => 'Select a header tag.',
					'choices'        => array(
						''   => __( 'Select' ),
						'h1'   => __( 'H1' ),
						'h2'   => __( 'H2' ),
						'h3'   => __( 'H3' ),
						'h4'   => __( 'H4' ),
						'h5'   => __( 'H5' ),
						'h6'   => __( 'H6' ),
					)
				)));
				// Sidebar 04
				$wp_customize->add_setting('hefe_control_customizer_control_sidebar_04', array(
					'type' =>'option',
				));
				$wp_customize->add_control(new WP_Customize_Control($wp_customize, 'hefe_control_customizer_control_sidebar_04', array(
					'type'     =>'',
					'priority' =>'',
					'section'  =>'hefe_control_customizer_section',
					'label'    =>'Sidebar 04',
					'description' =>'['.hefe_shortcode_name.'_sidebar_04]',
					'input_attrs' => array(
						'placeholder' => 'Custom Title',
					),
				)));
				// Sidebar 04 Header
				$wp_customize->add_setting('hefe_control_customizer_control_sidebar_04_header', array(
					'default'     => '',
					'transport'   => 'refresh',
					'type' => 'option',
				));
				$wp_customize->add_control(new WP_Customize_Control($wp_customize, 'hefe_control_customizer_control_sidebar_04_header', array(
					'type' => 'select',
					'priority' => '',
					'section' => 'hefe_control_customizer_section',
					'label' => '',
					'description' => 'Select a header tag.',
					'choices'        => array(
						''   => __( 'Select' ),
						'h1'   => __( 'H1' ),
						'h2'   => __( 'H2' ),
						'h3'   => __( 'H3' ),
						'h4'   => __( 'H4' ),
						'h5'   => __( 'H5' ),
						'h6'   => __( 'H6' ),
					)
				)));
				// Sidebar 05
				$wp_customize->add_setting('hefe_control_customizer_control_sidebar_05', array(
					'type' =>'option',
				));
				$wp_customize->add_control(new WP_Customize_Control($wp_customize, 'hefe_control_customizer_control_sidebar_05', array(
					'type'     =>'',
					'priority' =>'',
					'section'  =>'hefe_control_customizer_section',
					'label'    =>'Sidebar 05',
					'description' =>'['.hefe_shortcode_name.'_sidebar_05]',
					'input_attrs' => array(
						'placeholder' => 'Custom Title',
					),
				)));
				// Sidebar 05 Header
				$wp_customize->add_setting('hefe_control_customizer_control_sidebar_05_header', array(
					'default'     => '',
					'transport'   => 'refresh',
					'type' => 'option',
				));
				$wp_customize->add_control(new WP_Customize_Control($wp_customize, 'hefe_control_customizer_control_sidebar_05_header', array(
					'type' => 'select',
					'priority' => '',
					'section' => 'hefe_control_customizer_section',
					'label' => '',
					'description' => 'Select a header tag.',
					'choices'        => array(
						''   => __( 'Select' ),
						'h1'   => __( 'H1' ),
						'h2'   => __( 'H2' ),
						'h3'   => __( 'H3' ),
						'h4'   => __( 'H4' ),
						'h5'   => __( 'H5' ),
						'h6'   => __( 'H6' ),
					)
				)));
				// Sidebar 06
				$wp_customize->add_setting('hefe_control_customizer_control_sidebar_06', array(
					'type' =>'option',
				));
				$wp_customize->add_control(new WP_Customize_Control($wp_customize, 'hefe_control_customizer_control_sidebar_06', array(
					'type'     =>'',
					'priority' =>'',
					'section'  =>'hefe_control_customizer_section',
					'label'    =>'Sidebar 06',
					'description' =>'['.hefe_shortcode_name.'_sidebar_06]',
					'input_attrs' => array(
						'placeholder' => 'Custom Title',
					),
				)));
				// Sidebar 06 Header
				$wp_customize->add_setting('hefe_control_customizer_control_sidebar_06_header', array(
					'default'     => '',
					'transport'   => 'refresh',
					'type' => 'option',
				));
				$wp_customize->add_control(new WP_Customize_Control($wp_customize, 'hefe_control_customizer_control_sidebar_06_header', array(
					'type' => 'select',
					'priority' => '',
					'section' => 'hefe_control_customizer_section',
					'label' => '',
					'description' => 'Select a header tag.',
					'choices'        => array(
						''   => __( 'Select' ),
						'h1'   => __( 'H1' ),
						'h2'   => __( 'H2' ),
						'h3'   => __( 'H3' ),
						'h4'   => __( 'H4' ),
						'h5'   => __( 'H5' ),
						'h6'   => __( 'H6' ),
					)
				)));

			// Enqueues
			$wp_customize->add_section('hefe_enqueue_customizer_section' , array(
				'priority' => '',
				'capability' => 'edit_theme_options',
				'theme_supports' => '',
				'title' => 'Enqueues',
				'description' => '<p>We encourage you to <a href="'.site_url().'/wp-admin/tools.php?page=hefe-tools&tab=enqueues" class="button">Learn More</a> about this feature!</p>',
				'panel' => 'hefe_customizer_panel',
			));
				// Accordion
				$wp_customize->add_setting('hefe_enqueue_customizer_control_accordion', array(
					'type' => 'option',
					'sanitize_callback' => 'sanitize_text_field',
					'default' => '',
				));
				$wp_customize->add_control(new WP_Customize_Control($wp_customize, 'hefe_enqueue_customizer_control_accordion', array(
					'type' => 'checkbox',
					'priority' => '',
					'section' => 'hefe_enqueue_customizer_section',
					'label' => 'Accordion',
					'description' => 'Would you like "Accordion" css/js loaded on every page of your site? <br /><small>(Shortcodes work with/without this)</small>',
				)));
				// Animate.CSS
				$wp_customize->add_setting('hefe_enqueue_customizer_control_animate_css', array(
					'type' => 'option',
					'sanitize_callback' => 'sanitize_text_field',
					'default' => '',
				));
				$wp_customize->add_control(new WP_Customize_Control($wp_customize, 'hefe_enqueue_customizer_control_animate_css', array(
					'type' => 'checkbox',
					'priority' => '',
					'section' => 'hefe_enqueue_customizer_section',
					'label' => 'Animate.CSS',
					'description' => 'Would you like "Animate CSS" css/js loaded on every page of your site? <br /><small>(Shortcodes work with/without this)</small>',
				)));
				// Banner
				$wp_customize->add_setting('hefe_enqueue_customizer_control_banner', array(
					'type' => 'option',
					'sanitize_callback' => 'sanitize_text_field',
					'default' => '',
				));
				$wp_customize->add_control(new WP_Customize_Control($wp_customize, 'hefe_enqueue_customizer_control_banner', array(
					'type' => 'checkbox',
					'priority' => '',
					'section' => 'hefe_enqueue_customizer_section',
					'label' => 'Banner',
					'description' => 'Would you like "Banner" css/js loaded on every page of your site? <br /><small>(Shortcodes work with/without this)</small>',
				)));
				// Bootstrap
				$wp_customize->add_setting('hefe_enqueue_customizer_control_bootstrap', array(
					'type' => 'option',
					'sanitize_callback' => 'sanitize_text_field',
					'default' => '',
				));
				$wp_customize->add_control(new WP_Customize_Control($wp_customize, 'hefe_enqueue_customizer_control_bootstrap', array(
					'type' => 'checkbox',
					'priority' => '',
					'section' => 'hefe_enqueue_customizer_section',
					'label' => 'Bootstrap',
					'description' => 'Would you like "Bootstrap" css/js loaded on every page of your site? <br /><small>(Shortcodes work with/without this)</small>',
				)));
				// Bootstrap Grid Only
				$wp_customize->add_setting('hefe_enqueue_customizer_control_bootstrap_grid', array(
					'type' => 'option',
					'sanitize_callback' => 'sanitize_text_field',
					'default' => '',
				));
				$wp_customize->add_control(new WP_Customize_Control($wp_customize, 'hefe_enqueue_customizer_control_bootstrap_grid', array(
					'type' => 'checkbox',
					'priority' => '',
					'section' => 'hefe_enqueue_customizer_section',
					'label' => 'Bootstrap Grid Only',
					'description' => 'Would you like "Bootstrap Grid" css/js loaded on every page of your site? <br /><small>(Shortcodes work with/without this)</small>',
				)));
				// Button
				$wp_customize->add_setting('hefe_enqueue_customizer_control_button', array(
					'type' => 'option',
					'sanitize_callback' => 'sanitize_text_field',
					'default' => '',
				));
				$wp_customize->add_control(new WP_Customize_Control($wp_customize, 'hefe_enqueue_customizer_control_button', array(
					'type' => 'checkbox',
					'priority' => '',
					'section' => 'hefe_enqueue_customizer_section',
					'label' => 'Button',
					'description' => 'Would you like "Button" css/js loaded on every page of your site? <br /><small>(Shortcodes work with/without this)</small>',
				)));
				// Center
				$wp_customize->add_setting('hefe_enqueue_customizer_control_center', array(
					'type' => 'option',
					'sanitize_callback' => 'sanitize_text_field',
					'default' => '',
				));
				$wp_customize->add_control(new WP_Customize_Control($wp_customize, 'hefe_enqueue_customizer_control_center', array(
					'type' => 'checkbox',
					'priority' => '',
					'section' => 'hefe_enqueue_customizer_section',
					'label' => 'Center',
					'description' => 'Would you like "Center" css/js loaded on every page of your site? <br /><small>(Shortcodes work with/without this)</small>',
				)));
				// fancyBox
				$wp_customize->add_setting('hefe_enqueue_customizer_control_fancybox', array(
					'type' => 'option',
					'sanitize_callback' => 'sanitize_text_field',
					'default' => '',
				));
				$wp_customize->add_control(new WP_Customize_Control($wp_customize, 'hefe_enqueue_customizer_control_fancybox', array(
					'type' => 'checkbox',
					'priority' => '',
					'section' => 'hefe_enqueue_customizer_section',
					'label' => 'fancyBox',
					'description' => 'Would you like "fancyBox" css/js loaded on every page of your site? <br /><small>(Shortcodes work with/without this)</small>',
				)));
				// fancyBox Automatic Link
				$wp_customize->add_setting('hefe_enqueue_customizer_control_fancybox_automatic', array(
					'type' => 'option',
					'sanitize_callback' => 'sanitize_text_field',
					'default' => '',
				));
				$wp_customize->add_control(new WP_Customize_Control($wp_customize, 'hefe_enqueue_customizer_control_fancybox_automatic', array(
					'type' => 'checkbox',
					'priority' => '',
					'section' => 'hefe_enqueue_customizer_section',
					'label' => 'fancyBox Automatic Link',
					'description' => 'Would you like "fancyBox" css/js loaded on every image link of your site? <br /><small>(Shortcodes work with/without this)</small>',
				)));
				// Font Awesome
				$wp_customize->add_setting('hefe_enqueue_customizer_control_font_awesome', array(
					'type' => 'option',
					'sanitize_callback' => 'sanitize_text_field',
					'default' => '',
				));
				$wp_customize->add_control(new WP_Customize_Control($wp_customize, 'hefe_enqueue_customizer_control_font_awesome', array(
					'type' => 'checkbox',
					'priority' => '',
					'section' => 'hefe_enqueue_customizer_section',
					'label' => 'Font Awesome',
					'description' => 'Would you like "Font Awesome" css/js loaded on every page of your site? <br /><small>(Shortcodes work with/without this)</small>',
				)));
				// Front-End Media
				$wp_customize->add_setting('hefe_enqueue_customizer_control_front_end_media', array(
					'type' => 'option',
					'sanitize_callback' => 'sanitize_text_field',
					'default' => '',
				));
				$wp_customize->add_control(new WP_Customize_Control($wp_customize, 'hefe_enqueue_customizer_control_front_end_media', array(
					'type' => 'checkbox',
					'priority' => '',
					'section' => 'hefe_enqueue_customizer_section',
					'label' => 'Front-End Media',
					'description' => 'Would you like "Front-End Media" css/js loaded on every page of your site? <br /><small>(Shortcodes work with/without this)</small>',
				)));
				// Horizontal List
				$wp_customize->add_setting('hefe_enqueue_customizer_control_horizontal_list', array(
					'type' => 'option',
					'sanitize_callback' => 'sanitize_text_field',
					'default' => '',
				));
				$wp_customize->add_control(new WP_Customize_Control($wp_customize, 'hefe_enqueue_customizer_control_horizontal_list', array(
					'type' => 'checkbox',
					'priority' => '',
					'section' => 'hefe_enqueue_customizer_section',
					'label' => 'Horizontal List',
					'description' => 'Would you like "Horizontal List" css/js loaded on every page of your site? <br /><small>(Shortcodes work with/without this)</small>',
				)));
				// HTML5SHIV
				$wp_customize->add_setting('hefe_enqueue_customizer_control_html5shiv', array(
					'type' => 'option',
					'sanitize_callback' => 'sanitize_text_field',
					'default' => '',
				));
				$wp_customize->add_control(new WP_Customize_Control($wp_customize, 'hefe_enqueue_customizer_control_html5shiv', array(
					'type' => 'checkbox',
					'priority' => '',
					'section' => 'hefe_enqueue_customizer_section',
					'label' => 'HTML5SHIV',
					'description' => 'Would you like "HTML5SHIV" code loaded on every page of your site?</small>',
				)));
				// Isotope
				$wp_customize->add_setting('hefe_enqueue_customizer_control_isotope', array(
					'type' => 'option',
					'sanitize_callback' => 'sanitize_text_field',
					'default' => '',
				));
				$wp_customize->add_control(new WP_Customize_Control($wp_customize, 'hefe_enqueue_customizer_control_isotope', array(
					'type' => 'checkbox',
					'priority' => '',
					'section' => 'hefe_enqueue_customizer_section',
					'label' => 'Isotope',
					'description' => 'Would you like "Isotope" css/js loaded on every page of your site?',
				)));
				// matchHeight
				$wp_customize->add_setting('hefe_enqueue_customizer_control_match_height', array(
					'type' => 'option',
					'sanitize_callback' => 'sanitize_text_field',
					'default' => '',
				));
				$wp_customize->add_control(new WP_Customize_Control($wp_customize, 'hefe_enqueue_customizer_control_match_height', array(
					'type' => 'checkbox',
					'priority' => '',
					'section' => 'hefe_enqueue_customizer_section',
					'label' => 'matchHeight',
					'description' => 'Would you like "matchHeight" css/js loaded on every page of your site? <br /><small>(Shortcodes work with/without this)</small>',
				)));
				// Normalize
				$wp_customize->add_setting('hefe_enqueue_customizer_control_normalize', array(
					'type' => 'option',
					'sanitize_callback' => 'sanitize_text_field',
					'default' => '',
				));
				$wp_customize->add_control(new WP_Customize_Control($wp_customize, 'hefe_enqueue_customizer_control_normalize', array(
					'type' => 'checkbox',
					'priority' => '',
					'section' => 'hefe_enqueue_customizer_section',
					'label' => 'Normalize',
					'description' => 'Would you like "Normalize" css/js loaded on every page of your site?</small>',
				)));
				// Placeholders
				$wp_customize->add_setting('hefe_enqueue_customizer_control_placeholders', array(
					'type' => 'option',
					'sanitize_callback' => 'sanitize_text_field',
					'default' => '',
				));
				$wp_customize->add_control(new WP_Customize_Control($wp_customize, 'hefe_enqueue_customizer_control_placeholders', array(
					'type' => 'checkbox',
					'priority' => '',
					'section' => 'hefe_enqueue_customizer_section',
					'label' => 'Placeholders',
					'description' => 'Would you like "Placeholders" css/js loaded on every page of your site?</small>',
				)));
				// Pop Out Sidebar
				$wp_customize->add_setting('hefe_enqueue_customizer_control_pop_out_sidebar', array(
					'type' => 'option',
					'sanitize_callback' => 'sanitize_text_field',
					'default' => '',
				));
				$wp_customize->add_control(new WP_Customize_Control($wp_customize, 'hefe_enqueue_customizer_control_pop_out_sidebar', array(
					'type' => 'checkbox',
					'priority' => '',
					'section' => 'hefe_enqueue_customizer_section',
					'label' => 'Pop Out Sidebar',
					'description' => 'Would you like "Pop Out Sidebar" css/js loaded on every page of your site? You must activate this feature in "Control" in order for this to work.</small>',
				)));
				// Random Display
				$wp_customize->add_setting('hefe_enqueue_customizer_control_random_display', array(
					'type' => 'option',
					'sanitize_callback' => 'sanitize_text_field',
					'default' => '',
				));
				$wp_customize->add_control(new WP_Customize_Control($wp_customize, 'hefe_enqueue_customizer_control_random_display', array(
					'type' => 'checkbox',
					'priority' => '',
					'section' => 'hefe_enqueue_customizer_section',
					'label' => 'Random Display',
					'description' => 'Would you like "Random Display" css/js loaded on every page of your site? <br /><small>(Shortcodes work with/without this)</small>',
				)));
				// Random Order
				$wp_customize->add_setting('hefe_enqueue_customizer_control_random_order', array(
					'type' => 'option',
					'sanitize_callback' => 'sanitize_text_field',
					'default' => '',
				));
				$wp_customize->add_control(new WP_Customize_Control($wp_customize, 'hefe_enqueue_customizer_control_random_order', array(
					'type' => 'checkbox',
					'priority' => '',
					'section' => 'hefe_enqueue_customizer_section',
					'label' => 'Random Order',
					'description' => 'Would you like "Random Order" css/js loaded on every page of your site? <br /><small>(Shortcodes work with/without this)</small>',
				)));
				// Reveal
				$wp_customize->add_setting('hefe_enqueue_customizer_control_reveal', array(
					'type' => 'option',
					'sanitize_callback' => 'sanitize_text_field',
					'default' => '',
				));
				$wp_customize->add_control(new WP_Customize_Control($wp_customize, 'hefe_enqueue_customizer_control_reveal', array(
					'type' => 'checkbox',
					'priority' => '',
					'section' => 'hefe_enqueue_customizer_section',
					'label' => 'Reveal',
					'description' => 'Would you like "Reveal" css/js loaded on every page of your site?',
				)));
				// Respond
				$wp_customize->add_setting('hefe_enqueue_customizer_control_respond', array(
					'type' => 'option',
					'sanitize_callback' => 'sanitize_text_field',
					'default' => '',
				));
				$wp_customize->add_control(new WP_Customize_Control($wp_customize, 'hefe_enqueue_customizer_control_respond', array(
					'type' => 'checkbox',
					'priority' => '',
					'section' => 'hefe_enqueue_customizer_section',
					'label' => 'Respond',
					'description' => 'Would you like "Respond" css/js loaded on every page of your site?</small>',
				)));
				// Scroll To
				$wp_customize->add_setting('hefe_enqueue_customizer_control_scroll_to', array(
					'type' => 'option',
					'sanitize_callback' => 'sanitize_text_field',
					'default' => '',
				));
				$wp_customize->add_control(new WP_Customize_Control($wp_customize, 'hefe_enqueue_customizer_control_scroll_to', array(
					'type' => 'checkbox',
					'priority' => '',
					'section' => 'hefe_enqueue_customizer_section',
					'label' => 'Scroll To',
					'description' => 'Would you like "Scroll To" css/js loaded on every page of your site? <br /><small>(Shortcodes work with/without this)</small>',
				)));
				// Scroll Up Box
				$wp_customize->add_setting('hefe_enqueue_customizer_control_scroll_up_box', array(
					'type' => 'option',
					'sanitize_callback' => 'sanitize_text_field',
					'default' => '',
				));
				$wp_customize->add_control(new WP_Customize_Control($wp_customize, 'hefe_enqueue_customizer_control_scroll_up_box', array(
					'type' => 'checkbox',
					'priority' => '',
					'section' => 'hefe_enqueue_customizer_section',
					'label' => 'Scroll Up Box',
					'description' => 'Would you like "Scroll Up Box" css/js loaded on every page of your site?',
				)));
				// Search Modal
				$wp_customize->add_setting('hefe_enqueue_customizer_control_search_modal', array(
					'type' => 'option',
					'sanitize_callback' => 'sanitize_text_field',
					'default' => '',
				));
				$wp_customize->add_control(new WP_Customize_Control($wp_customize, 'hefe_enqueue_customizer_control_search_modal', array(
					'type' => 'checkbox',
					'priority' => '',
					'section' => 'hefe_enqueue_customizer_section',
					'label' => 'Search Modal',
					'description' => 'Would you like "Search Modal" css/js loaded on every page of your site?',
				)));
				// Selectivizr
				$wp_customize->add_setting('hefe_enqueue_customizer_control_selectivizr', array(
					'type' => 'option',
					'sanitize_callback' => 'sanitize_text_field',
					'default' => '',
				));
				$wp_customize->add_control(new WP_Customize_Control($wp_customize, 'hefe_enqueue_customizer_control_selectivizr', array(
					'type' => 'checkbox',
					'priority' => '',
					'section' => 'hefe_enqueue_customizer_section',
					'label' => 'Selectivizr',
					'description' => 'Would you like "Selectivizr" css/js loaded on every page of your site?</small>',
				)));
				// Simple Carousel
				$wp_customize->add_setting('hefe_enqueue_customizer_control_simple_carousel', array(
					'type' => 'option',
					'sanitize_callback' => 'sanitize_text_field',
					'default' => '',
				));
				$wp_customize->add_control(new WP_Customize_Control($wp_customize, 'hefe_enqueue_customizer_control_simple_carousel', array(
					'type' => 'checkbox',
					'priority' => '',
					'section' => 'hefe_enqueue_customizer_section',
					'label' => 'Simple Sarousel',
					'description' => 'Would you like "Simple Carousel" css/js loaded on every page of your site?',
				)));
				// Sticky
				$wp_customize->add_setting('hefe_enqueue_customizer_control_sticky', array(
					'type' => 'option',
					'sanitize_callback' => 'sanitize_text_field',
					'default' => '',
				));
				$wp_customize->add_control(new WP_Customize_Control($wp_customize, 'hefe_enqueue_customizer_control_sticky', array(
					'type' => 'checkbox',
					'priority' => '',
					'section' => 'hefe_enqueue_customizer_section',
					'label' => 'Sticky',
					'description' => 'Would you like "Sticky" css/js loaded on every page of your site? <br /><small>(Shortcodes work with/without this)</small>',
				)));
				// Sudo Slider
				$wp_customize->add_setting('hefe_enqueue_customizer_control_sudo_slider', array(
					'type' => 'option',
					'sanitize_callback' => 'sanitize_text_field',
					'default' => '',
				));
				$wp_customize->add_control(new WP_Customize_Control($wp_customize, 'hefe_enqueue_customizer_control_sudo_slider', array(
					'type' => 'checkbox',
					'priority' => '',
					'section' => 'hefe_enqueue_customizer_section',
					'label' => 'Sudo Slider',
					'description' => 'Would you like Sudo Slider code loaded on every page of your site?',
				)));
				// Tabs
				$wp_customize->add_setting('hefe_enqueue_customizer_control_tabs', array(
					'type' => 'option',
					'sanitize_callback' => 'sanitize_text_field',
					'default' => '',
				));
				$wp_customize->add_control(new WP_Customize_Control($wp_customize, 'hefe_enqueue_customizer_control_tabs', array(
					'type' => 'checkbox',
					'priority' => '',
					'section' => 'hefe_enqueue_customizer_section',
					'label' => 'Tabs',
					'description' => 'Would you like "Tabs" css/js loaded on every page of your site? <br /><small>(Shortcodes work with/without this)</small>',
				)));
				// Tooltip
				$wp_customize->add_setting('hefe_enqueue_customizer_control_tooltip', array(
					'type' => 'option',
					'sanitize_callback' => 'sanitize_text_field',
					'default' => '',
				));
				$wp_customize->add_control(new WP_Customize_Control($wp_customize, 'hefe_enqueue_customizer_control_tooltip', array(
					'type' => 'checkbox',
					'priority' => '',
					'section' => 'hefe_enqueue_customizer_section',
					'label' => 'Tooltip',
					'description' => 'Would you like "Tooltip" css/js loaded on every page of your site? <br /><small>(Shortcodes work with/without this)</small>',
				)));
				// TwentyTwenty
				$wp_customize->add_setting('hefe_enqueue_customizer_control_twentytwenty', array(
					'type' => 'option',
					'sanitize_callback' => 'sanitize_text_field',
					'default' => '',
				));
				$wp_customize->add_control(new WP_Customize_Control($wp_customize, 'hefe_enqueue_customizer_control_twentytwenty', array(
					'type' => 'checkbox',
					'priority' => '',
					'section' => 'hefe_enqueue_customizer_section',
					'label' => 'TwentyTwenty',
					'description' => 'Would you like "TwentyTwenty" css/js loaded on every page of your site? <br /><small>(Shortcodes work with/without this)</small>',
				)));
				// Video Player
				$wp_customize->add_setting('hefe_enqueue_customizer_control_video_player', array(
					'type' => 'option',
					'sanitize_callback' => 'sanitize_text_field',
					'default' => '',
				));
				$wp_customize->add_control(new WP_Customize_Control($wp_customize, 'hefe_enqueue_customizer_control_video_player', array(
					'type' => 'checkbox',
					'priority' => '',
					'section' => 'hefe_enqueue_customizer_section',
					'label' => 'Video Player',
					'description' => 'Would you like "Video Player" css/js loaded on every page of your site? <br /><small>(Shortcodes work with/without this)</small>',
				)));

			// Widgets
			$wp_customize->add_section('hefe_widget_customizer_section' , array(
				'priority' => '',
				'capability' => 'edit_theme_options',
				'theme_supports' => '',
				'title' => 'Widgets',
				'description' => '<p>We encourage you to <a href="'.site_url().'/wp-admin/tools.php?page=hefe-tools&tab=widgets" class="button">Learn More</a> about this feature!</p>',
				'panel' => 'hefe_customizer_panel',
			));
				// Accordion
				$wp_customize->add_setting('hefe_widget_customizer_control_accordion', array(
					'type' => 'option',
					'sanitize_callback' => 'sanitize_text_field',
					'default' => '',
				));
				$wp_customize->add_control(new WP_Customize_Control($wp_customize, 'hefe_widget_customizer_control_accordion', array(
					'type' => 'checkbox',
					'priority' => '',
					'section' => 'hefe_widget_customizer_section',
					'label' => 'Accordion',
					'description' => 'Would you like to use the "Accordion" widget?</small>',
				)));
				// Accordion Content
				$wp_customize->add_setting('hefe_widget_customizer_control_accordion_content', array(
					'type' => 'option',
					'sanitize_callback' => 'sanitize_text_field',
					'default' => '',
				));
				$wp_customize->add_control(new WP_Customize_Control($wp_customize, 'hefe_widget_customizer_control_accordion_content', array(
					'type' => 'checkbox',
					'priority' => '',
					'section' => 'hefe_widget_customizer_section',
					'label' => 'Accordion Content',
					'description' => 'Would you like to use the "Accordion Content" widget?</small>',
				)));
				// Accordion Link
				$wp_customize->add_setting('hefe_widget_customizer_control_accordion_link', array(
					'type' => 'option',
					'sanitize_callback' => 'sanitize_text_field',
					'default' => '',
				));
				$wp_customize->add_control(new WP_Customize_Control($wp_customize, 'hefe_widget_customizer_control_accordion_link', array(
					'type' => 'checkbox',
					'priority' => '',
					'section' => 'hefe_widget_customizer_section',
					'label' => 'Accordion Link',
					'description' => 'Would you like to use the "Accordion Link" widget?</small>',
				)));
				// Banner
				$wp_customize->add_setting('hefe_widget_customizer_control_banner', array(
					'type' => 'option',
					'sanitize_callback' => 'sanitize_text_field',
					'default' => '',
				));
				$wp_customize->add_control(new WP_Customize_Control($wp_customize, 'hefe_widget_customizer_control_banner', array(
					'type' => 'checkbox',
					'priority' => '',
					'section' => 'hefe_widget_customizer_section',
					'label' => 'Banner',
					'description' => 'Would you like to use the "Banner" widget?</small>',
				)));
				// Banner Per Page
				$wp_customize->add_setting('hefe_widget_customizer_control_banner_per_page', array(
					'type' => 'option',
					'sanitize_callback' => 'sanitize_text_field',
					'default' => '',
				));
				$wp_customize->add_control(new WP_Customize_Control($wp_customize, 'hefe_widget_customizer_control_banner_per_page', array(
					'type' => 'checkbox',
					'priority' => '',
					'section' => 'hefe_widget_customizer_section',
					'label' => 'Banner Per Page',
					'description' => 'Would you like to use the "Banner Per Page" widget?</small>',
				)));
				// Breadcrumbs
				$wp_customize->add_setting('hefe_widget_customizer_control_breadcrumbs', array(
					'type' => 'option',
					'sanitize_callback' => 'sanitize_text_field',
					'default' => '',
				));
				$wp_customize->add_control(new WP_Customize_Control($wp_customize, 'hefe_widget_customizer_control_breadcrumbs', array(
					'type' => 'checkbox',
					'priority' => '',
					'section' => 'hefe_widget_customizer_section',
					'label' => 'Breadcrumbs',
					'description' => 'Would you like to use the "Breadcrumbs" widget?</small>',
				)));
				// Button
				$wp_customize->add_setting('hefe_widget_customizer_control_button', array(
					'type' => 'option',
					'sanitize_callback' => 'sanitize_text_field',
					'default' => '',
				));
				$wp_customize->add_control(new WP_Customize_Control($wp_customize, 'hefe_widget_customizer_control_button', array(
					'type' => 'checkbox',
					'priority' => '',
					'section' => 'hefe_widget_customizer_section',
					'label' => 'Button',
					'description' => 'Would you like to use the "Button" widget?</small>',
				)));
				// Copyright
				$wp_customize->add_setting('hefe_widget_customizer_control_copyright', array(
					'type' => 'option',
					'sanitize_callback' => 'sanitize_text_field',
					'default' => '',
				));
				$wp_customize->add_control(new WP_Customize_Control($wp_customize, 'hefe_widget_customizer_control_copyright', array(
					'type' => 'checkbox',
					'priority' => '',
					'section' => 'hefe_widget_customizer_section',
					'label' => 'Copyright',
					'description' => 'Would you like to use the "Copyright" widget?</small>',
				)));
				// fancyBox Inline
				$wp_customize->add_setting('hefe_widget_customizer_control_fancybox_inline', array(
					'type' => 'option',
					'sanitize_callback' => 'sanitize_text_field',
					'default' => '',
				));
				$wp_customize->add_control(new WP_Customize_Control($wp_customize, 'hefe_widget_customizer_control_fancybox_inline', array(
					'type' => 'checkbox',
					'priority' => '',
					'section' => 'hefe_widget_customizer_section',
					'label' => 'fancyBox Inline',
					'description' => 'Would you like to use the "fancyBox Inline" widget?</small>',
				)));
				// Horizontal List
				$wp_customize->add_setting('hefe_widget_customizer_control_horizontal_list', array(
					'type' => 'option',
					'sanitize_callback' => 'sanitize_text_field',
					'default' => '',
				));
				$wp_customize->add_control(new WP_Customize_Control($wp_customize, 'hefe_widget_customizer_control_horizontal_list', array(
					'type' => 'checkbox',
					'priority' => '',
					'section' => 'hefe_widget_customizer_section',
					'label' => 'Horizontal List',
					'description' => 'Would you like to use the "Horizontal List" widget?</small>',
				)));
				// Related Article
				$wp_customize->add_setting('hefe_widget_customizer_control_related_article', array(
					'type' => 'option',
					'sanitize_callback' => 'sanitize_text_field',
					'default' => '',
				));
				$wp_customize->add_control(new WP_Customize_Control($wp_customize, 'hefe_widget_customizer_control_related_article', array(
					'type' => 'checkbox',
					'priority' => '',
					'section' => 'hefe_widget_customizer_section',
					'label' => 'Related Article',
					'description' => 'Would you like to use the "Related Article" widget?</small>',
				)));
				// Reveal
				$wp_customize->add_setting('hefe_widget_customizer_control_reveal', array(
					'type' => 'option',
					'sanitize_callback' => 'sanitize_text_field',
					'default' => '',
				));
				$wp_customize->add_control(new WP_Customize_Control($wp_customize, 'hefe_widget_customizer_control_reveal', array(
					'type' => 'checkbox',
					'priority' => '',
					'section' => 'hefe_widget_customizer_section',
					'label' => 'Reveal',
					'description' => 'Would you like to use the "Reveal" widget?</small>',
				)));
				// Site Identity
				$wp_customize->add_setting('hefe_widget_customizer_control_site_identity', array(
					'type' => 'option',
					'sanitize_callback' => 'sanitize_text_field',
					'default' => '',
				));
				$wp_customize->add_control(new WP_Customize_Control($wp_customize, 'hefe_widget_customizer_control_site_identity', array(
					'type' => 'checkbox',
					'priority' => '',
					'section' => 'hefe_widget_customizer_section',
					'label' => 'Site Identity',
					'description' => 'Would you like to use the "Site Identity" widget?</small>',
				)));
				// Tabs
				$wp_customize->add_setting('hefe_widget_customizer_control_tabs', array(
					'type' => 'option',
					'sanitize_callback' => 'sanitize_text_field',
					'default' => '',
				));
				$wp_customize->add_control(new WP_Customize_Control($wp_customize, 'hefe_widget_customizer_control_tabs', array(
					'type' => 'checkbox',
					'priority' => '',
					'section' => 'hefe_widget_customizer_section',
					'label' => 'Tabs',
					'description' => 'Would you like to use the "Tabs" widget?</small>',
				)));
				// Tabs Content
				$wp_customize->add_setting('hefe_widget_customizer_control_tabs_content', array(
					'type' => 'option',
					'sanitize_callback' => 'sanitize_text_field',
					'default' => '',
				));
				$wp_customize->add_control(new WP_Customize_Control($wp_customize, 'hefe_widget_customizer_control_tabs_content', array(
					'type' => 'checkbox',
					'priority' => '',
					'section' => 'hefe_widget_customizer_section',
					'label' => 'Tabs Content',
					'description' => 'Would you like to use the "Tabs Content" widget?</small>',
				)));
				// Tabs Link
				$wp_customize->add_setting('hefe_widget_customizer_control_tabs_link', array(
					'type' => 'option',
					'sanitize_callback' => 'sanitize_text_field',
					'default' => '',
				));
				$wp_customize->add_control(new WP_Customize_Control($wp_customize, 'hefe_widget_customizer_control_tabs_link', array(
					'type' => 'checkbox',
					'priority' => '',
					'section' => 'hefe_widget_customizer_section',
					'label' => 'Tabs Link',
					'description' => 'Would you like to use the "Tabs Link" widget?</small>',
				)));
				// TwentyTwenty
				$wp_customize->add_setting('hefe_widget_customizer_control_twentytwenty', array(
					'type' => 'option',
					'sanitize_callback' => 'sanitize_text_field',
					'default' => '',
				));
				$wp_customize->add_control(new WP_Customize_Control($wp_customize, 'hefe_widget_customizer_control_twentytwenty', array(
					'type' => 'checkbox',
					'priority' => '',
					'section' => 'hefe_widget_customizer_section',
					'label' => 'TwentyTwenty',
					'description' => 'Would you like to use the "TwentyTwenty" widget?</small>',
				)));

	}
}

/* CSS
------------------------------ */

if(!function_exists('hefe_inc_css')){
	add_action('wp_enqueue_scripts', 'hefe_inc_css', 999999);
	function hefe_inc_css(){

		// Accordion
		wp_register_style('hefe-accordion-style', hefe_dir_url.'css/hefe-accordion-min.css', array(), '1.0.0', 'all');
		if(get_option('hefe_enqueue_customizer_control_accordion')){
			wp_enqueue_style('hefe-accordion-style', hefe_dir_url.'css/hefe-accordion-min.css', array(), '1.0.0', 'all');
		}
		// Accordion Style 01
		wp_register_style('hefe-accordion-style-01', hefe_dir_url.'css/hefe-accordion-style-01-min.css', array(), '1.0.0', 'all');
		// Accordion Style 02
		wp_register_style('hefe-accordion-style-02', hefe_dir_url.'css/hefe-accordion-style-02-min.css', array(), '1.0.0', 'all');
		// Animate
		wp_register_style('hefe-animate-css-style', hefe_dir_url.'css/animate.min.css', array(), '3.5.2', 'all');
		wp_register_style('hefe-animate-css-inc-style', hefe_dir_url.'css/hefe-animate-css-inc-min.css', array(), '1.0.0', 'all');
		if(get_option('hefe_enqueue_customizer_control_animate_css')){
			wp_enqueue_style('hefe-animate-css-style', hefe_dir_url.'css/animate.min.css', array(), '3.5.2', 'all');
			wp_enqueue_style('hefe-animate-css-inc-style', hefe_dir_url.'css/hefe-animate-css-inc-min.css', array(), '1.0.0', 'all');
		}
		// Banner
		wp_register_style('hefe-banner-style', hefe_dir_url.'css/hefe-banner-min.css', array(), '1.0.0', 'all');
		if(get_option('hefe_enqueue_customizer_control_banner')){
			wp_enqueue_style('hefe-banner-style', hefe_dir_url.'css/hefe-banner-min.css', array(), '1.0.0', 'all');
		}
		// Bootstrap
		wp_register_style('hefe-bootstrap-style', hefe_dir_url.'css/bootstrap.min.css', array(), '4.0.0', 'all');
		if(get_option('hefe_enqueue_customizer_control_bootstrap')){
			wp_enqueue_style('hefe-bootstrap-style', hefe_dir_url.'css/bootstrap.min.css', array(), '4.0.0', 'all');
		}
		// Bootstrap Grid Only
		wp_register_style('hefe-bootstrap-grid-style', hefe_dir_url.'css/bootstrap-grid.min.css', array(), '4.0.0', 'all');
		if(get_option('hefe_enqueue_customizer_control_bootstrap_grid')){
			wp_enqueue_style('hefe-bootstrap-grid-style', hefe_dir_url.'css/bootstrap-grid.min.css', array(), '4.0.0', 'all');
		}
		// Breadcrumbs
		wp_register_style('hefe-breadcrumbs-style', hefe_dir_url.'css/hefe-breadcrumbs-min.css', array(), '1.0.0', 'all');
		if(get_option('hefe_enqueue_customizer_control_breadcrumbs')){
			wp_enqueue_style('hefe-breadcrumbs-style', hefe_dir_url.'css/hefe-breadcrumbs-min.css', array(), '1.0.0', 'all');
		}
		// Button
		wp_register_style('hefe-button-style', hefe_dir_url.'css/hefe-button-min.css', array(), '1.0.0', 'all');
		if(get_option('hefe_enqueue_customizer_control_button')){
			wp_enqueue_style('hefe-button-style', hefe_dir_url.'css/hefe-button-min.css', array(), '1.0.0', 'all');
		}
		// Button Style 01
		wp_register_style('hefe-button-style-01', hefe_dir_url.'css/hefe-button-style-01-min.css', array(), '1.0.0', 'all');
		// Center
		wp_register_style('hefe-center-style', hefe_dir_url.'css/hefe-center-min.css', array(), '1.0.0', 'all');
		if(get_option('hefe_enqueue_customizer_control_center')){
			wp_enqueue_style('hefe-center-style', hefe_dir_url.'css/hefe-center-min.css', array(), '1.0.0', 'all');
		}
		// Horizontal List
		wp_register_style('hefe-horizontal-list-style', hefe_dir_url.'css/hefe-horizontal-list-min.css', array(), '1.0.0', 'all');
		if(get_option('hefe_enqueue_customizer_control_horizontal_list')){
			wp_enqueue_style('hefe-horizontal-list-style', hefe_dir_url.'css/hefe-horizontal-list-min.css', array(), '1.0.0', 'all');
		}
		// fancyBox
		wp_register_style('hefe-fancybox-style', hefe_dir_url.'css/jquery.fancybox.min.css', array(), '1.0.0', 'all');
		if(get_option('hefe_enqueue_customizer_control_fancybox')){
			wp_enqueue_style('hefe-fancybox-style', hefe_dir_url.'css/jquery.fancybox.min.css', array(), '1.0.0', 'all');
		}
		// Font Awesome
		wp_register_style('hefe-font-awesome-style', hefe_dir_url.'css/fa-svg-with-js.css', array(), '5.0.6', 'all');
		if(get_option('hefe_enqueue_customizer_control_font_awesome')){
			wp_enqueue_style('hefe-font-awesome-style', hefe_dir_url.'css/fa-svg-with-js.css', array(), '5.0.6', 'all');
		}
		// Font Awesome 00
		wp_register_style('hefe-font-awesome-style-00', hefe_dir_url.'css/hefe-font-awesome-style-00-min.css', array(), '1.0.0', 'all');
		// Font Awesome 01
		wp_register_style('hefe-font-awesome-style-01', hefe_dir_url.'css/hefe-font-awesome-style-01-min.css', array(), '1.0.0', 'all');
		// Font Awesome 02
		wp_register_style('hefe-font-awesome-style-02', hefe_dir_url.'css/hefe-font-awesome-style-02-min.css', array(), '1.0.0', 'all');
		// Google Fonts
		if(get_option('hefe_control_customizer_control_google_fonts_fonts_url')){
			wp_enqueue_style('hefe-google-fonts-style', get_option('hefe_control_customizer_control_google_fonts_fonts_url'), array(), '1.0.0', 'all'); 
		}
		// Isotope
		wp_register_style('hefe-isotope-inc-style', hefe_dir_url.'css/hefe-isotope-inc-min.css', array(), '1.0.0', 'all');
		if(get_option('hefe_enqueue_customizer_control_isotope')){
			wp_enqueue_style('hefe-isotope-inc-style', hefe_dir_url.'css/hefe-isotope-inc-min.css', array(), '1.0.0', 'all');
		}
		// matchHeight
		wp_register_style('hefe-match-height-style', hefe_dir_url.'css/hefe-match-height-inc-min.css', array(), '1.0.0', 'all');
		if(get_option('hefe_enqueue_customizer_control_match_height')){
			wp_enqueue_style('hefe-match-height-style', hefe_dir_url.'css/hefe-match-height-inc-min.css', array(), '1.0.0', 'all');
		}
		// Menu Style 01
		wp_register_style('hefe-menu-style-01', hefe_dir_url.'css/hefe-menu-style-01-min.css', array(), '1.0.0', 'all');
		// Menu Style 02
		wp_register_style('hefe-menu-style-02', hefe_dir_url.'css/hefe-menu-style-02-min.css', array(), '1.0.0', 'all');
		// Menu Style 03
		wp_register_style('hefe-menu-style-03', hefe_dir_url.'css/hefe-menu-style-03-min.css', array(), '1.0.0', 'all');
		// Normalize
		if(get_option('hefe_enqueue_customizer_control_normalize')){
			wp_enqueue_style('hefe-enqueues-normalize-style', hefe_dir_url.'css/normalize.min.css', array(), '4.1.1', 'all'); 
		}
		// Pop Out Sidebar
		wp_register_style('hefe-pop-out-sidebar-style', hefe_dir_url.'css/hefe-pop-out-sidebar-min.css', array(), '1.0.0', 'all');
		if(get_option('hefe_enqueue_customizer_control_pop_out_sidebar')){
			wp_enqueue_style('hefe-pop-out-sidebar-style', hefe_dir_url.'css/hefe-pop-out-sidebar-min.css', array(), '1.0.0', 'all');
		}
		// Pop Out Sidebar Style 01
		wp_register_style('hefe-pop-out-sidebar-style-01', hefe_dir_url.'css/hefe-pop-out-sidebar-style-01-min.css', array(), '1.0.0', 'all');
		// Related Article Style 01
		wp_register_style('hefe-related-article-style-01', hefe_dir_url.'css/hefe-related-article-style-01-min.css', array(), '1.0.0', 'all');
		// Reveal
		wp_register_style('hefe-reveal-style', hefe_dir_url.'css/hefe-reveal-min.css', array(), '3.5.1', 'all');
		if(get_option('hefe_enqueue_customizer_control_reveal')){
			wp_enqueue_style('hefe-reveal-style', hefe_dir_url.'css/hefe-reveal-min.css', array(), '3.5.1', 'all');
		}
		// Scroll To
		wp_register_style('hefe-scroll-to-style', hefe_dir_url.'css/hefe-scroll-to-min.css', array(), '1.0.0', 'all');
		if(get_option('hefe_enqueue_customizer_control_scroll_to')){
			wp_enqueue_style('hefe-scroll-to-style', hefe_dir_url.'css/hefe-scroll-to-min.css', array(), '1.0.0', 'all');
		}
		// Scroll Up Box
		wp_register_style('hefe-scroll-up-box-style', hefe_dir_url.'css/hefe-scroll-up-box-min.css', array(), '1.0.0', 'all');
		if(get_option('hefe_control_customizer_control_scroll_up_box')){
			wp_enqueue_style('hefe-scroll-up-box-style', hefe_dir_url.'css/hefe-scroll-up-box-min.css', array(), '1.0.0', 'all');
		}
		// Search Modal
		wp_register_style('hefe-search-modal-style', hefe_dir_url.'css/hefe-search-modal-min.css', array(), '1.0.0', 'all');
		if(get_option('hefe_enqueue_customizer_control_search_modal')){
			wp_enqueue_style('hefe-search-modal-style', hefe_dir_url.'css/hefe-search-modal-min.css', array(), '1.0.0', 'all');
		}
		// Sudo Slider
		wp_register_style('hefe-sudo-slider-inc-style', hefe_dir_url.'css/hefe-sudo-slider-inc-min.css', array(), '1.0.0', 'all');
		if(get_option('hefe_enqueue_customizer_control_sudo_slider')){
			wp_enqueue_style('hefe-sudo-slider-inc-style', hefe_dir_url.'css/hefe-sudo-slider-inc-min.css', array(), '1.0.0', 'all');
		}
		// Tabs
		wp_register_style('hefe-tabs-style', hefe_dir_url.'css/hefe-tabs-min.css', array(), '1.0.0', 'all');
		if(get_option('hefe_enqueue_customizer_control_tabs')){
			wp_enqueue_style('hefe-tabs-style', hefe_dir_url.'css/hefe-tabs-min.css', array(), '1.0.0', 'all');
		}
		// Tabs Style 01
		wp_register_style('hefe-tabs-style-01', hefe_dir_url.'css/hefe-tabs-style-01-min.css', array(), '1.0.0', 'all');
		// Tabs Style 02
		wp_register_style('hefe-tabs-style-02', hefe_dir_url.'css/hefe-tabs-style-02-min.css', array(), '1.0.0', 'all');
		// Tooltip
		wp_register_style('hefe-tooltip-style', hefe_dir_url.'css/hefe-tooltip-min.css', array(), '1.0.0', 'all');
		if(get_option('hefe_enqueue_customizer_control_tooltip')){
			wp_enqueue_style('hefe-tabs-style', hefe_dir_url.'css/hefe-tooltip-min.css', array(), '1.0.0', 'all');
		}
		// TwentyTwenty
		wp_register_style('hefe-twentytwenty-style', hefe_dir_url.'css/twentytwenty.css', array(), '1.0.0', 'all');
		if(get_option('hefe_enqueue_customizer_control_twentytwenty')){
			wp_enqueue_style('hefe-twentytwenty-style', hefe_dir_url.'css/twentytwenty.css', array(), '1.0.0', 'all');
		}
		// Video Player
		wp_register_style('hefe-video-player-style', hefe_dir_url.'css/hefe-video-player-min.css', array(), '1.0.0', 'all');
		if(get_option('hefe_enqueue_customizer_control_video_player')){
			wp_enqueue_style('hefe-video-player-style', hefe_dir_url.'css/hefe-video-player-min.css', array(), '1.0.0', 'all');
		}

	}
}

/* JS
------------------------------ */

if(!function_exists('hefe_inc_js')){
	add_action('wp_enqueue_scripts', 'hefe_inc_js', 999999);
	function hefe_inc_js(){

		// Accordion
		wp_register_script('hefe-accordion-script', hefe_dir_url.'js/hefe-accordion-min.js', array('jquery'), '1.0.0', true);
		if(get_option('hefe_enqueue_customizer_control_accordion')){
			wp_enqueue_script('hefe-accordion-script', hefe_dir_url.'js/hefe-accordion-min.js', array('jquery'), '1.0.0', true);
		}
		// Animate
		wp_register_script('hefe-animate-css-appear-script', hefe_dir_url.'js/jquery.appear.min.js', array('jquery'), '1.0.0', true);
		wp_register_script('hefe-animate-css-inc-script', hefe_dir_url.'js/hefe-animate-css-inc-min.js', array('jquery'), '1.0.0', true);
		if(get_option('hefe_enqueue_customizer_control_animate_css')){
			wp_enqueue_script('hefe-animate-css-appear-script', hefe_dir_url.'js/jquery.appear.min.js', array('jquery'), '1.0.0', true);
			wp_enqueue_script('hefe-animate-css-inc-script', hefe_dir_url.'js/hefe-animate-css-inc-min.js', array('jquery'), '1.0.0', true);
		}
		// Banner
		wp_register_script('hefe-banner-script', hefe_dir_url.'js/hefe-banner-min.js', array('jquery'), '1.0.0', true);
		if(get_option('hefe_enqueue_customizer_control_banner')){
			wp_enqueue_script('hefe-banner-script', hefe_dir_url.'js/hefe-banner-min.js', array('jquery'), '1.0.0', true);
		}
		// Bootstrap
		wp_register_script('hefe-bootstrap-tether-script', hefe_dir_url.'js/tether.min.js', array('jquery'), '1.3.3', true);
		wp_register_script('hefe-bootstrap-script', hefe_dir_url.'js/bootstrap.min.js', array('jquery'), '4.0.0', true);
		if(get_option('hefe_enqueue_customizer_control_bootstrap')){
			wp_enqueue_script('hefe-bootstrap-tether-script', hefe_dir_url.'js/tether.min.js', array('jquery'), '1.3.3', true);
			wp_enqueue_script('hefe-bootstrap-script', hefe_dir_url.'js/bootstrap.min.js', array('jquery'), '4.0.0', true);
		}
		// fancyBox Automatic Link
		if(get_option('hefe_enqueue_customizer_control_fancybox_automatic')){
			wp_enqueue_script('hefe-fancybox-automatic-script', hefe_dir_url.'js/hefe-fancybox-automatic-min.js', array('jquery'), '4.0.0', true);
		}
		// fancyBox
		wp_register_script('hefe-fancybox-script', hefe_dir_url.'js/jquery.fancybox.min.js', array('jquery'), '3.2.10', true);
		wp_register_script('hefe-fancybox-inc-script', hefe_dir_url.'js/hefe-fancybox-inc-min.js', array('jquery'), '1.0.0', true);
		if(get_option('hefe_enqueue_customizer_control_fancybox')){
			wp_enqueue_script('hefe-fancybox-script', hefe_dir_url.'js/jquery.fancybox.min.js', array('jquery'), '3.2.10', true);
			wp_enqueue_script('hefe-fancybox-inc-script', hefe_dir_url.'js/hefe-fancybox-inc-min.js', array('jquery'), '1.0.0', true);
		}
		// Font Awesome
		wp_register_script('hefe-font-awesome-script', hefe_dir_url.'js/fontawesome-all.min.js', array('jquery'), '5.0.6', true);
		if(get_option('hefe_enqueue_customizer_control_font_awesome')){
			wp_enqueue_script('hefe-font-awesome-script', hefe_dir_url.'js/fontawesome-all.min.js', array('jquery'), '5.0.6', true);
		}
		// Front-End Media
		wp_register_script('hefe-front-end-media-script', hefe_dir_url.'js/hefe-front-end-media-min.js', array('jquery'), '1.0.0', true);
		if(get_option('hefe_enqueue_customizer_control_front_end_media')){
			wp_enqueue_script('hefe-front-end-media-script', hefe_dir_url.'js/hefe-front-end-media-min.js', array('jquery'), '1.0.0', true);
		}
		// HTML5SHIV
		if(get_option('hefe_enqueue_customizer_control_html5shiv')){
			wp_enqueue_script('hefe-starter-html5shiv-js-script', hefe_dir_url.'js/html5shiv.min.js', array('jquery'), '3.7.3', true);
		}
		// Isotope
		wp_register_script('hefe-isotope-imagesloaded-script', hefe_dir_url.'js/imagesloaded.pkgd.min.js', array('jquery'), '1.0.0', true);
		wp_register_script('hefe-isotope-script', hefe_dir_url.'js/isotope.pkgd.min.js', array('jquery'), '3.0.4', true);
		wp_register_script('hefe-isotope-inc-script', hefe_dir_url.'js/hefe-isotope-inc-min.js', array('jquery'), '1.0.0', true);
		if(get_option('hefe_enqueue_customizer_control_isotope')){
			wp_enqueue_script('hefe-isotope-imagesloaded-script', hefe_dir_url.'js/imagesloaded.pkgd.min.js', array('jquery'), '1.0.0', true);
			wp_enqueue_script('hefe-isotope-script', hefe_dir_url.'js/isotope.pkgd.min.js', array('jquery'), '3.0.4', true);
			wp_enqueue_script('hefe-isotope-inc-script', hefe_dir_url.'js/hefe-isotope-inc-min.js', array('jquery'), '1.0.0', true);
		}
		// matchHeight
		wp_register_script('hefe-match-height-script', hefe_dir_url.'js/jquery.matchHeight.js', array('jquery'), '1.3.3', true);
		wp_register_script('hefe-match-height-inc-script', hefe_dir_url.'js/hefe-match-height-inc-min.js', array('jquery'), '1.0.0', true);
		if(get_option('hefe_enqueue_customizer_control_match_height')){
			wp_enqueue_script('hefe-match-height-script', hefe_dir_url.'js/jquery.matchHeight.js', array('jquery'), '1.3.3', true);
			wp_enqueue_script('hefe-match-height-inc-script', hefe_dir_url.'js/hefe-match-height-inc-min.js', array('jquery'), '1.0.0', true);
		}
		// Menu Script 02
		wp_register_script('hefe-menu-script-02', hefe_dir_url.'js/hefe-menu-script-02-min.js', array('jquery'), '1.0.0', true);
		// Menu Script 03
		wp_register_script('hefe-menu-script-03', hefe_dir_url.'js/hefe-menu-script-03-min.js', array('jquery'), '1.0.0', true);
		// Placeholders
		if(get_option('hefe_enqueue_customizer_control_placeholders')){
			wp_enqueue_script('hefe-starter-placeholders-js-script', hefe_dir_url.'js/placeholders.min.js', array('jquery'), '4.0.1', true);
		}
		// Pop Out Sidebar
		wp_register_script('hefe-pop-out-sidebar-script', hefe_dir_url.'js/hefe-pop-out-sidebar-min.js', array('jquery'), '1.0.0', true);
		if(get_option('hefe_enqueue_customizer_control_pop_out_sidebar')){
			wp_enqueue_script('hefe-pop-out-sidebar-script', hefe_dir_url.'js/hefe-pop-out-sidebar-min.js', array('jquery'), '1.0.0', true);
		}
		// Random Display
		wp_register_script('hefe-random-display-script', hefe_dir_url.'js/hefe-random-display-min.js', array('jquery'), '1.0.0', true);
		if(get_option('hefe_enqueue_customizer_control_random_display')){
			wp_enqueue_script('hefe-random-display-script', hefe_dir_url.'js/hefe-random-display-min.js', array('jquery'), '1.0.0', true);
		}
		// Random Order
		wp_register_script('hefe-random-order-script', hefe_dir_url.'js/hefe-random-order-min.js', array('jquery'), '1.0.0', true);
		if(get_option('hefe_enqueue_customizer_control_random_order')){
			wp_enqueue_script('hefe-random-order-script', hefe_dir_url.'js/hefe-random-order-min.js', array('jquery'), '1.0.0', true);
		}
		// Respond
		if(get_option('hefe_enqueue_customizer_control_respond')){
			wp_enqueue_script('hefe-starter-respond-js-script', hefe_dir_url.'js/respond.min.js', array('jquery'), '1.0.2', true);
		}
		// Reveal
		wp_register_script('hefe-reveal-script', hefe_dir_url.'js/hefe-reveal-min.js', array('jquery'), '1.0.0', true);
		if(get_option('hefe_enqueue_customizer_control_reveal')){
			wp_enqueue_script('hefe-reveal-script', hefe_dir_url.'js/hefe-reveal-min.js', array('jquery'), '1.0.0', true);
		}
		// Scroll To
		wp_register_script('hefe-scroll-to-script', hefe_dir_url.'js/hefe-scroll-to-min.js', array('jquery'), '1.0.0', true);
		if(get_option('hefe_enqueue_customizer_control_scroll_to')){
			wp_enqueue_script('hefe-scroll-to-script', hefe_dir_url.'js/hefe-scroll-to-min.js', array('jquery'), '1.0.0', true);
		}
		// Scroll Up Box
		wp_register_script('hefe-scroll-up-box-script', hefe_dir_url.'js/hefe-scroll-up-box-min.js', array('jquery'), '1.0.0', true);
		if(get_option('hefe_enqueue_customizer_control_scroll_up_box')){
			wp_enqueue_script('hefe-scroll-up-box-script', hefe_dir_url.'js/hefe-scroll-up-box-min.js', array('jquery'), '1.0.0', true);
		}
		// Search Modal
		wp_register_script('hefe-search-modal-script', hefe_dir_url.'js/hefe-search-modal-min.js', array('jquery'), '1.0.0', true);
		if(get_option('hefe_enqueue_customizer_control_search_modal')){
			wp_enqueue_script('hefe-search-modal-script', hefe_dir_url.'js/hefe-search-modal-min.js', array('jquery'), '1.0.0', true);
		}
		// Selectivizr
		if(get_option('hefe_enqueue_customizer_control_selectivizr')){
			wp_enqueue_script('hefe-starter-selectivizr-js-script', hefe_dir_url.'js/selectivizr.min.js', array('jquery'), '1.0.2', true);
		}
		// Simple Carousel
		wp_register_script('hefe-simple-carousel-script', hefe_dir_url.'js/hefe-simple-carousel-min.js', array('jquery'), '1.0.0', true);
		if(get_option('hefe_enqueue_customizer_control_simple_carousel')){
			wp_enqueue_script('hefe-simple-carousel-script', hefe_dir_url.'js/hefe-simple-carousel-min.js', array('jquery'), '1.0.0', true);
		}
		// Sticky
		wp_register_script('hefe-sticky-script', hefe_dir_url.'js/hefe-sticky-min.js', array('jquery'), '1.0.0', true);
		if(get_option('hefe_enqueue_customizer_control_sticky')){
			wp_enqueue_script('hefe-sticky-script', hefe_dir_url.'js/hefe-sticky-min.js', array('jquery'), '1.0.0', true);
		}
		// Sudo Slider
		wp_register_script('hefe-sudo-slider-bbq-script', hefe_dir_url.'js/jquery.ba-bbq.min.js', array('jquery'), '1.3', true);
		wp_register_script('hefe-sudo-slider-hashchange-script', hefe_dir_url.'js/jquery.ba-hashchange.min.js', array('jquery'), '1.3', true);
		wp_register_script('hefe-sudo-slider-properload-script', hefe_dir_url.'js/jquery.properload.js', array('jquery'), '1.3', true);
		wp_register_script('hefe-sudo-slider-script', hefe_dir_url.'js/jquery.sudoSlider.min.js', array('jquery'), '3.4.9', true);
		wp_register_script('hefe-sudo-slider-inc-script', hefe_dir_url.'js/hefe-sudo-slider-inc-min.js', array('jquery'), '1.0.0', true);
		if(get_option('hefe_enqueue_customizer_control_sudo_slider')){
			wp_enqueue_script('hefe-sudo-slider-bbq-script', hefe_dir_url.'js/jquery.ba-bbq.min.js', array('jquery'), '1.3', true);
			wp_enqueue_script('hefe-sudo-slider-hashchange-script', hefe_dir_url.'js/jquery.ba-hashchange.min.js', array('jquery'), '1.3', true);
			wp_enqueue_script('hefe-sudo-slider-properload-script', hefe_dir_url.'js/jquery.properload.js', array('jquery'), '1.3', true);
			wp_enqueue_script('hefe-sudo-slider-script', hefe_dir_url.'js/jquery.sudoSlider.min.js', array('jquery'), '3.4.9', true);
			wp_enqueue_script('hefe-sudo-slider-inc-script', hefe_dir_url.'js/hefe-sudo-slider-inc-min.js', array('jquery'), '1.0.0', true);
		}
		// Tabs
		wp_register_script('hefe-tabs-script', hefe_dir_url.'js/hefe-tabs-min.js', array('jquery'), '1.0.0', true);
		if(get_option('hefe_enqueue_customizer_control_tabs')){
			wp_enqueue_script('hefe-tabs-script', hefe_dir_url.'js/hefe-tabs-min.js', array('jquery'), '1.0.0', true);
		}
		// TwentyTwenty
		wp_register_script('hefe-twentytwenty-event-move-script', hefe_dir_url.'js/jquery.event.move.js', array('jquery'), '1.3.6', true);
		wp_register_script('hefe-twentytwenty-script', hefe_dir_url.'js/jquery.twentytwenty.js', array('jquery'), '1.0.0', true);
		wp_register_script('hefe-twentytwenty-inc-script', hefe_dir_url.'js/hefe-twentytwenty-inc-min.js', array('jquery'), '1.0.0', true);
		if(get_option('hefe_enqueue_customizer_control_twentytwenty')){
			wp_enqueue_script('hefe-twentytwenty-event-move-script', hefe_dir_url.'js/jquery.event.move.js', array('jquery'), '1.3.6', true);
			wp_enqueue_script('hefe-twentytwenty-script', hefe_dir_url.'js/jquery.twentytwenty.js', array('jquery'), '1.0.0', true);
			wp_enqueue_script('hefe-twentytwenty-inc-script', hefe_dir_url.'js/hefe-twentytwenty-inc-min.js', array('jquery'), '1.0.0', true);
		}

	}
}

/* Shortcodes
------------------------------ */

// Accordion Link
if(!function_exists('hefe_shortcode_accordion_link')){
	if(get_option('hefe_control_customizer_control_shortcode_name')){
		add_shortcode(hefe_shortcode_name.'_accordion_link', 'hefe_shortcode_accordion_link');
		add_shortcode(hefe_shortcode_name.'_click_slide_link', 'hefe_shortcode_accordion_link');
	}
	add_shortcode('hefe_accordion_link', 'hefe_shortcode_accordion_link');
	add_shortcode('hefe_click_slide_link', 'hefe_shortcode_accordion_link');
	function hefe_shortcode_accordion_link($atts, $content = null){
		wp_enqueue_style('hefe-accordion-style');
		wp_enqueue_script('hefe-accordion-script');
		$a = shortcode_atts(array(
			'id' => '',
			'class' => '',
			'paired_id' => '',
			'wrap' => '',
			'style' => '',
			'active' => '',
			'etc' => '',
		), $atts);
		if(esc_attr($a['paired_id'])){
			$paired_id = esc_attr($a['paired_id']);
		}else{
			$paired_id = get_the_ID();
		}
		if(esc_attr($a['wrap'])){
			$wrap = esc_attr($a['wrap']);
		}else{
			$wrap = 'div';
		}
		$style = '';
		if(esc_attr($a['style'])){
			$style = 'hefe-accordion-style-'.esc_attr($a['style']);
			wp_enqueue_style('hefe-accordion-style-'.esc_attr($a['style']));
		}
		$active = '';
		if(esc_attr($a['active']) == 'false' || esc_attr($a['active']) == ''){
			$active = '';
		}else{
			$active = 'hefe-accordion-active';
		}
		return '<'.$wrap.' id="'.esc_attr($a['id']).'" class="hefe-accordion-link '.$style.' '.$active.' '.esc_attr($a['class']).'" data-paired="'.$paired_id.'" '.esc_attr($a['etc']).'>'.do_shortcode($content).'</'.$wrap.'>';
	}
}
// Accordion Content
if(!function_exists('hefe_shortcode_accordion_content')){
	if(get_option('hefe_control_customizer_control_shortcode_name')){
		add_shortcode(hefe_shortcode_name.'_accordion_content', 'hefe_shortcode_accordion_content');
		add_shortcode(hefe_shortcode_name.'_click_slide_content', 'hefe_shortcode_accordion_content');
	}
	add_shortcode('hefe_accordion_content', 'hefe_shortcode_accordion_content');
	add_shortcode('hefe_click_slide_content', 'hefe_shortcode_accordion_content');
	function hefe_shortcode_accordion_content($atts, $content = null){
		wp_enqueue_style('hefe-accordion-style');
		wp_enqueue_script('hefe-accordion-script');
		$a = shortcode_atts(array(
			'id' => '',
			'class' => '',
			'paired_id' => '',
			'style' => '',
			'active' => '',
			'etc' => '',
		), $atts);
		if(esc_attr($a['paired_id'])){
			$paired_id = esc_attr($a['paired_id']);
		}else{
			$paired_id = get_the_ID();
		}
		$style = '';
		if(esc_attr($a['style'])){
			$style = 'hefe-accordion-style-'.esc_attr($a['style']);
			wp_enqueue_style('hefe-accordion-style-'.esc_attr($a['style']));
		}
		$active = '';
		if(esc_attr($a['active']) == 'false' || esc_attr($a['active']) == ''){
			$active = '';
		}else{
			$active = 'hefe-accordion-active';
		}
		return '<div id="'.esc_attr($a['id']).'" class="hefe-accordion-content '.$style.' '.$active.' '.esc_attr($a['class']).'" data-paired="'.$paired_id.'" '.esc_attr($a['etc']).'>'.do_shortcode($content).'</div>';
	}
}
// ACF / acf Field
if(!function_exists('hefe_shortcode_acf_field')){
	if(get_option('hefe_control_customizer_control_shortcode_name')){
		add_shortcode(hefe_shortcode_name.'_acf_field', 'hefe_shortcode_acf_field');
	}
	add_shortcode('hefe_acf_field', 'hefe_shortcode_acf_field');
	function hefe_shortcode_acf_field($atts, $content = null){
		$a = shortcode_atts(array(
			'name' => '',
			'page_id' => '',
			'format' => '',
		), $atts);
		if(function_exists('get_field')){
			return get_field(esc_attr($a['name']), esc_attr($a['page_id']), esc_attr($a['format']));
		}
	}
}
// ACF Form
if(!function_exists('hefe_shortcode_acf_form')){
	if(get_option('hefe_control_customizer_control_shortcode_name')){
		add_shortcode(hefe_shortcode_name.'_acf_form', 'hefe_shortcode_acf_form');
	}
	add_shortcode('hefe_acf_form', 'hefe_shortcode_acf_form');
	function hefe_shortcode_acf_form($atts) {
		//add_action('wp_head', 'hefe_acf_form_header_addy');
		add_action('wp_footer', 'hefe_acf_form_footer_addy');
		$a = shortcode_atts( array(
			'user_role' => '',
			'id' => '',
			'post_id' => '',
			'post_type' => '',
			'post_status' => '',
			'field_groups' => '',
			'fields' => '',
			'post_title' => '',
			'post_content' => '',
			'form' => '',
			'form_attributes' => '',
			'return' => '',
			'html_before_fields' => '',
			'html_after_fields' => '',
			'submit_value' => '',
			'updated_message' => '',
			'label_placement' => '',
			'instruction_placement' => '',
			'field_el' => '',
			'uploader' => '',
			'honeypot' => '',
			'html_updated_message' => '',
			'html_submit_button' => '',
			'html_submit_spinner' => '',
			'kses' => '',
		), $atts );

		global $current_user;
		wp_get_current_user();

		$form = '';

		if(esc_attr($a['user_role'])){

			$user_roles = explode(',', esc_attr($a['user_role']));
			$current_user_roles = (array) $current_user->roles;

			if(!isset($_REQUEST) || $_REQUEST['get_id']==''){
				$get_id = '';
			}else{
				$get_id = $_REQUEST['get_id'];
				$post_author = get_post_field('post_author',$get_id);
			}

			$current_author = '999999999999999999';
			if(in_array('current_author', $user_roles)){
				$current_author = $current_user->ID;
			}

			if(is_user_logged_in() && array_intersect($user_roles, $current_user_roles) || is_user_logged_in() && $current_author == $post_author){

				$options = array('');

				if(esc_attr($a['id'])){
					$options['id'] = esc_attr($a['id']);
				}
				if(esc_attr($a['post_id'])){
					if(esc_attr($a['post_id']) == 'get_id'){
						if(!isset($_REQUEST) || $_REQUEST['get_id']==''){
							$get_id = '';
						}else{
							$get_id = $_REQUEST['get_id'];
						}
						$options['post_id'] = $get_id;
					}elseif(esc_attr($a['post_id']) == 'get_user_id'){
						if(!isset($_REQUEST) || $_REQUEST['get_user_id']==''){
							$get_user_id = '';
						}else{
							$get_user_id = $_REQUEST['get_user_id'];
						}
						$options['post_id'] = 'user_'.$get_user_id;
					}elseif(esc_attr($a['post_id']) == 'current_user_id'){
						$options['post_id'] = 'user_'.$current_user->ID;
					}else{
						$options['post_id'] = esc_attr($a['post_id']);
					}
				}
				if(esc_attr($a['post_type']) || esc_attr($a['post_status'])){
					$acf_new_post_options = array();
					if(esc_attr($a['post_type'])){
						$acf_new_post_options['post_type'] = esc_attr($a['post_type']);
					}
					if(esc_attr($a['post_status'])){
						$acf_new_post_options['post_status'] = esc_attr($a['post_status']);
					}
					$options['new_post'] = $acf_new_post_options;
				}
				if(esc_attr($a['field_groups'])){
					$options['field_groups'] = explode(',', esc_attr($a['field_groups']));
				}
				if(esc_attr($a['fields'])){
					$options['fields'] = explode(',', esc_attr($a['fields']));
				}
				if(esc_attr($a['post_title']) == 'true'){
					$options['post_title'] = true;
				}else{
					$options['post_title'] = false;
				}
				if(esc_attr($a['post_content']) == 'true'){
					$options['post_content'] = true;
				}else{
					$options['post_content'] = false;
				}
				if(esc_attr($a['form']) == 'false'){
					$options['form'] = false;
				}else{
					$options['form'] = true;
				}
				if(esc_attr($a['form_attributes'])){
					$options['form_attributes'] = explode(',', esc_attr($a['form_attributes']));
				}
				if(esc_attr($a['return'])){
					$options['return'] = htmlspecialchars_decode(esc_attr($a['return']));
				}
				if(esc_attr($a['html_before_fields'])){
					$options['html_before_fields'] = esc_attr($a['html_before_fields']);
				}
				if(esc_attr($a['html_after_fields'])){
					$options['html_after_fields'] = esc_attr($a['html_after_fields']);
				}
				if(esc_attr($a['submit_value'])){
					$options['submit_value'] = esc_attr($a['submit_value']);
				}
				if(esc_attr($a['updated_message'])){
					$options['updated_message'] = esc_attr($a['updated_message']);
				}
				if(esc_attr($a['label_placement'])){
					$options['label_placement'] = esc_attr($a['label_placement']);
				}
				if(esc_attr($a['instruction_placement'])){
					$options['instruction_placement'] = esc_attr($a['instruction_placement']);
				}
				if(esc_attr($a['field_el'])){
					$options['field_el'] = esc_attr($a['field_el']);
				}
				if(esc_attr($a['uploader'])){
					$options['uploader'] = esc_attr($a['uploader']);
				}
				if(esc_attr($a['honeypot']) == 'false'){
					$options['honeypot'] = false;
				}else{
					$options['honeypot'] = true;
				}
				if(esc_attr($a['html_updated_message'])){
					$options['html_updated_message'] = esc_attr($a['html_updated_message']);
				}
				if(esc_attr($a['html_submit_button'])){
					$options['html_submit_button'] = esc_attr($a['html_submit_button']);
				}
				if(esc_attr($a['html_submit_spinner'])){
					$options['html_submit_spinner'] = esc_attr($a['html_submit_spinner']);
				}
				if(esc_attr($a['kses']) == 'false'){
					$options['kses'] = false;
				}else{
					$options['kses'] = true;
				}

				ob_start();
				acf_form($options);
				$form = ob_get_contents();
				ob_end_clean();

			}else{
				return '<p class="hefe-acf-form-error">You are not allowed to see this.</p>';
			}

		}else{

			$options = array('');

			if(esc_attr($a['id'])){
				$options['id'] = esc_attr($a['id']);
			}
			if(esc_attr($a['post_id'])){
				if(esc_attr($a['post_id']) == 'get_id'){
					if(!isset($_REQUEST) || $_REQUEST['get_id']==''){
						$get_id = '';
					}else{
						$get_id = $_REQUEST['get_id'];
					}
					$options['post_id'] = $get_id;
				}elseif(esc_attr($a['post_id']) == 'get_user_id'){
					if(!isset($_REQUEST) || $_REQUEST['get_user_id']==''){
						$get_user_id = '';
					}else{
						$get_user_id = $_REQUEST['get_user_id'];
					}
					$options['post_id'] = 'user_'.$get_user_id;
				}elseif(esc_attr($a['post_id']) == 'current_user_id'){
					$options['post_id'] = 'user_'.$current_user->ID;
				}else{
					$options['post_id'] = esc_attr($a['post_id']);
				}
			}
			if(esc_attr($a['post_type']) || esc_attr($a['post_status'])){
				$acf_new_post_options = array();
				if(esc_attr($a['post_type'])){
					$acf_new_post_options['post_type'] = esc_attr($a['post_type']);
				}
				if(esc_attr($a['post_status'])){
					$acf_new_post_options['post_status'] = esc_attr($a['post_status']);
				}
				$options['new_post'] = $acf_new_post_options;
			}
			if(esc_attr($a['field_groups'])){
				$options['field_groups'] = explode(',', esc_attr($a['field_groups']));
			}
			if(esc_attr($a['fields'])){
				$options['fields'] = explode(',', esc_attr($a['fields']));
			}
			if(esc_attr($a['post_title']) == 'true'){
				$options['post_title'] = true;
			}else{
				$options['post_title'] = false;
			}
			if(esc_attr($a['post_content']) == 'true'){
				$options['post_content'] = true;
			}else{
				$options['post_content'] = false;
			}
			if(esc_attr($a['form']) == 'false'){
				$options['form'] = false;
			}else{
				$options['form'] = true;
			}
			if(esc_attr($a['form_attributes'])){
				$options['form_attributes'] = explode(',', esc_attr($a['form_attributes']));
			}
			if(esc_attr($a['return'])){
				$options['return'] = htmlspecialchars_decode(esc_attr($a['return']));
			}
			if(esc_attr($a['html_before_fields'])){
				$options['html_before_fields'] = esc_attr($a['html_before_fields']);
			}
			if(esc_attr($a['html_after_fields'])){
				$options['html_after_fields'] = esc_attr($a['html_after_fields']);
			}
			if(esc_attr($a['submit_value'])){
				$options['submit_value'] = esc_attr($a['submit_value']);
			}
			if(esc_attr($a['updated_message'])){
				$options['updated_message'] = esc_attr($a['updated_message']);
			}
			if(esc_attr($a['label_placement'])){
				$options['label_placement'] = esc_attr($a['label_placement']);
			}
			if(esc_attr($a['instruction_placement'])){
				$options['instruction_placement'] = esc_attr($a['instruction_placement']);
			}
			if(esc_attr($a['field_el'])){
				$options['field_el'] = esc_attr($a['field_el']);
			}
			if(esc_attr($a['uploader'])){
				$options['uploader'] = esc_attr($a['uploader']);
			}
			if(esc_attr($a['honeypot']) == 'false'){
				$options['honeypot'] = false;
			}else{
				$options['honeypot'] = true;
			}
			if(esc_attr($a['html_updated_message'])){
				$options['html_updated_message'] = esc_attr($a['html_updated_message']);
			}
			if(esc_attr($a['html_submit_button'])){
				$options['html_submit_button'] = esc_attr($a['html_submit_button']);
			}
			if(esc_attr($a['html_submit_spinner'])){
				$options['html_submit_spinner'] = esc_attr($a['html_submit_spinner']);
			}
			if(esc_attr($a['kses']) == 'false'){
				$options['kses'] = false;
			}else{
				$options['kses'] = true;
			}

			ob_start();
			acf_form($options);
			$form = ob_get_contents();
			ob_end_clean();

		}
	  
	    return $form;
	}
}
// Animate.CSS Item
if(!function_exists('hefe_animate_css_shortcode_item')){
	if(get_option('hefe_control_customizer_control_shortcode_name')){
		add_shortcode(hefe_shortcode_name.'_animate_css_item', 'hefe_animate_css_shortcode_item');
		add_shortcode(hefe_shortcode_name.'_animate_item', 'hefe_animate_css_shortcode_item');
		add_shortcode(hefe_shortcode_name.'_animate', 'hefe_animate_css_shortcode_item');
		add_shortcode(hefe_shortcode_name.'_animation', 'hefe_animate_css_shortcode_item');
	}
	add_shortcode('hefe_animate_css_item', 'hefe_animate_css_shortcode_item');
	add_shortcode('hefe_animate_item', 'hefe_animate_css_shortcode_item');
	add_shortcode('hefe_animate', 'hefe_animate_css_shortcode_item');
	add_shortcode('hefe_animation', 'hefe_animate_css_shortcode_item');
	function hefe_animate_css_shortcode_item($atts, $content = null){
		wp_enqueue_style('hefe-animate-css-style');
		wp_enqueue_style('hefe-animate-css-inc-style');
		wp_enqueue_script('hefe-animate-css-appear-script');
		wp_enqueue_script('hefe-animate-css-inc-script');
		$a = shortcode_atts(array(
			'id' => '',
			'class' => '',
			'effect' => '',
			'delay' => '',
			'etc' => '',
		), $atts);
		return '<div id="'.esc_attr($a['id']).'" class="hefe-animate-css-item animated '.esc_attr($a['class']).'" data-effect="'.esc_attr($a['effect']).'" data-delay="'.esc_attr($a['delay']).'" '.esc_attr($a['etc']).'>'.do_shortcode($content).'</div>';
	}
}
// Archive Title
if(!function_exists('hefe_shortcode_archive_title')){
	if(get_option('hefe_control_customizer_control_shortcode_name')){
		add_shortcode(hefe_shortcode_name.'_archive_title', 'hefe_shortcode_archive_title');
	}
	add_shortcode('hefe_archive_title', 'hefe_shortcode_archive_title');
	function hefe_shortcode_archive_title($atts, $content = null){
		$a = shortcode_atts(array(
			'pre' => '',
		), $atts);
		$pre = '';
		if(esc_attr($a['pre'])){
			$pre = esc_attr($a['pre']).' ';
		}
		if(is_category()){
			return $pre.single_cat_title( '', false );
		}elseif(is_tag()){
			return $pre.single_tag_title( '', false );
		}elseif(is_author()){
			return $pre.'<span class="vcard">' . get_the_author() . '</span>';
		}elseif(is_post_type_archive()){
			return $pre.post_type_archive_title( '', false );
		}elseif(is_tax()) {
			return $pre.single_term_title( '', false );
		}else{
			return $pre.get_the_archive_title();
		}
	}
}
// Banner
if(!function_exists('hefe_shortcode_banner')){
	if(get_option('hefe_control_customizer_control_shortcode_name')){
		add_shortcode(hefe_shortcode_name.'_banner', 'hefe_shortcode_banner');
		add_shortcode(hefe_shortcode_name.'_query_banner', 'hefe_shortcode_banner');
		add_shortcode(hefe_shortcode_name.'_page_banner', 'hefe_shortcode_banner');
	}
	add_shortcode('hefe_banner', 'hefe_shortcode_banner');
	add_shortcode('hefe_query_banner', 'hefe_shortcode_banner');
	add_shortcode('hefe_page_banner', 'hefe_shortcode_banner');
	function hefe_shortcode_banner($atts, $content = null){
		wp_enqueue_style('hefe-banner-script');
		wp_enqueue_style('hefe-banner-style');
		$a = shortcode_atts(array(
			'id' => '',
			'class' => '',
			'height' => '',
			'src' => '',
			'etc' => '',
		), $atts);
		$banner_src = esc_attr($a['src']); 
		if(strpos($banner_src, '.mp4') !== false || strpos($banner_src, '.ogg') !== false || strpos($banner_src, '.ogv') !== false || strpos($banner_src, '.webm') !== false){
			$banner_src = str_replace(' ', '', $banner_src); $banner_src = explode(',', $banner_src); 
			foreach($banner_src as $banner_src_url){
				if(strpos($banner_src_url, '.mp4') !== false){ 
					$mp4_url = '<source src="'.$banner_src_url.'" type="video/mp4" />'; 
				}elseif(strpos($banner_src_url, '.ogg') !== false){ 
					$ogg_url = '<source src="'.$banner_src_url.'" type="video/ogg" />'; 
				}elseif(strpos($banner_src_url, '.ogv') !== false){ 
					$ogv_url = '<source src="'.$banner_src_url.'" type="video/ogg" />'; 
				}elseif(strpos($banner_src_url, '.webm') !== false){ 
					$webm_url = '<source src="'.$banner_src_url.'" type="video/webm" />'; 
				}
			}
			return '<div id="'.esc_attr($a['id']).'" class="hefe-banner '.esc_attr($a['class']).'" style="min-height: '.esc_attr($a['height']).'px;" '.esc_attr($a['etc']).'>'.do_shortcode($content).'<video class="hefe-banner-video" autoplay loop muted playsinline>'.$webm_url.' '.$mp4_url.' '.$ogg_url.' '.$ogv_url.'</video>'.'</div>';
		}elseif(strpos($banner_src, 'vimeo.com') !== false){ 
			preg_match("/(https?:\/\/)?(www\.)?(player\.)?vimeo\.com\/([a-z]*\/)*([0-9]{6,11})[?]?.*/", $banner_src, $hefe_return_id);
			return '<div id="'.esc_attr($a['id']).'" class="hefe-banner '.esc_attr($a['class']).'" '.esc_attr($a['etc']).'>'.do_shortcode($content).'<iframe class="hefe-banner-iframe" src="https://player.vimeo.com/video/'.$hefe_return_id[5].'?automute=1&autoplay=1&loop=1&background=1" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe></div>';
		}elseif(strpos($banner_src, 'youtube.com') !== false || strpos($banner_src, 'youtu.be') !== false){ 
			parse_str(parse_url($banner_src, PHP_URL_QUERY), $my_array_of_vars);
			$hefe_banner_url =  $my_array_of_vars['v'];
			return '<div id="'.esc_attr($a['id']).'" class="hefe-banner '.esc_attr($a['class']).'" style="min-height: '.esc_attr($a['height']).'px;" '.esc_attr($a['etc']).'>'.do_shortcode($content).'<iframe class="hefe-banner-iframe" src="https://www.youtube.com/embed/'.$hefe_banner_url.'?rel=0'.'&autoplay=1'.'&loop=1'.'" frameborder="0" allowfullscreen></iframe></div>';
		}else{ 
			return '<div id="'.esc_attr($a['id']).'" class="hefe-banner '.esc_attr($a['class']).'" style="min-height: '.esc_attr($a['height']).'px;" '.esc_attr($a['etc']).'>'.do_shortcode($content).'<div class="hefe-banner-image" style="background-image: url('.$banner_src.');"></div></div>';
		}
	}
}
// Banner Per Page
if(!function_exists('hefe_shortcode_banner_per_page')){
	if(get_option('hefe_control_customizer_control_shortcode_name')){
		add_shortcode(hefe_shortcode_name.'_banner_per_page', 'hefe_shortcode_banner_per_page');
		add_shortcode(hefe_shortcode_name.'_per_page_banner_banner', 'hefe_shortcode_banner_per_page');
		add_shortcode(hefe_shortcode_name.'_per_page_banner_query_banner', 'hefe_shortcode_banner_per_page');
		add_shortcode(hefe_shortcode_name.'_per_page_banner_page_banner', 'hefe_shortcode_banner_per_page');
	}
	add_shortcode('hefe_banner_per_page', 'hefe_shortcode_banner_per_page');
	add_shortcode('hefe_banner_per_page_banner', 'hefe_shortcode_banner_per_page');
	add_shortcode('hefe_banner_per_page_query_banner', 'hefe_shortcode_banner_per_page');
	add_shortcode('hefe_banner_per_page_page_banner', 'hefe_shortcode_banner_per_page');
	function hefe_shortcode_banner_per_page($atts, $content = null){
		if(is_singular()){
			$banner_id = '';
			if(get_post_meta(get_the_ID(), 'hefe_banner_per_page_meta_box_banner_id', true) != ''){
				$banner_id = get_post_meta(get_the_ID(), 'hefe_banner_per_page_meta_box_banner_id', true);
			}
			$banner_class = '';
			if(get_post_meta(get_the_ID(), 'hefe_banner_per_page_meta_box_banner_class', true) != ''){
				$banner_class = get_post_meta(get_the_ID(), 'hefe_banner_per_page_meta_box_banner_class', true);
			}
			$banner_height = '';
			if(get_post_meta(get_the_ID(), 'hefe_banner_per_page_meta_box_banner_height', true) != ''){
				$banner_height = get_post_meta(get_the_ID(), 'hefe_banner_per_page_meta_box_banner_height', true);
			}
			$banner_etc = '';
			if(get_post_meta(get_the_ID(), 'hefe_banner_per_page_meta_box_banner_etc', true) != ''){
				$banner_etc = get_post_meta(get_the_ID(), 'hefe_banner_per_page_meta_box_banner_etc', true);
			}
			$banner_content = '';
			if(get_post_meta(get_the_ID(), 'hefe_banner_per_page_meta_box_banner_content', true) != ''){
				$banner_content = get_post_meta(get_the_ID(), 'hefe_banner_per_page_meta_box_banner_content', true);
			}
			$banner_src = '';
			if(get_post_meta(get_the_ID(), 'hefe_banner_per_page_meta_box_banner_src', true) != ''){
				$banner_src = get_post_meta(get_the_ID(), 'hefe_banner_per_page_meta_box_banner_src', true);
			}
			return do_shortcode('[hefe_banner id="'.$banner_id.'" class="'.$banner_class.'" height="'.$banner_height.'" etc="'.$banner_etc.'" src="'.$banner_src.'"]'.$banner_content.'[/hefe_banner]');
		}else{
			return '';
		}
	}
}
// Bootstrap Container Fluid
if(!function_exists('hefe_shortcode_bootstrap_container_fluid')){
	if(get_option('hefe_control_customizer_control_shortcode_name')){
		add_shortcode(hefe_shortcode_name.'_bootstrap_container_fluid_1', 'hefe_shortcode_bootstrap_container_fluid');
		add_shortcode(hefe_shortcode_name.'_bootstrap_container_fluid_2', 'hefe_shortcode_bootstrap_container_fluid');
		add_shortcode(hefe_shortcode_name.'_bootstrap_container_fluid_3', 'hefe_shortcode_bootstrap_container_fluid');
		add_shortcode(hefe_shortcode_name.'_bootstrap_container_fluid_4', 'hefe_shortcode_bootstrap_container_fluid');
		add_shortcode(hefe_shortcode_name.'_bootstrap_container_fluid_grandparent', 'hefe_shortcode_bootstrap_container_fluid');
		add_shortcode(hefe_shortcode_name.'_bootstrap_container_fluid_parent', 'hefe_shortcode_bootstrap_container_fluid');
		add_shortcode(hefe_shortcode_name.'_bootstrap_container_fluid_child', 'hefe_shortcode_bootstrap_container_fluid');
		add_shortcode(hefe_shortcode_name.'_bootstrap_container_fluid_grandchild', 'hefe_shortcode_bootstrap_container_fluid');
		add_shortcode(hefe_shortcode_name.'_bootstrap_container_fluid', 'hefe_shortcode_bootstrap_container_fluid');
	}
	add_shortcode('hefe_bootstrap_container_fluid_1', 'hefe_shortcode_bootstrap_container_fluid');
	add_shortcode('hefe_bootstrap_container_fluid_2', 'hefe_shortcode_bootstrap_container_fluid');
	add_shortcode('hefe_bootstrap_container_fluid_3', 'hefe_shortcode_bootstrap_container_fluid');
	add_shortcode('hefe_bootstrap_container_fluid_4', 'hefe_shortcode_bootstrap_container_fluid');
	add_shortcode('hefe_bootstrap_container_fluid_grandparent', 'hefe_shortcode_bootstrap_container_fluid');
	add_shortcode('hefe_bootstrap_container_fluid_parent', 'hefe_shortcode_bootstrap_container_fluid');
	add_shortcode('hefe_bootstrap_container_fluid_child', 'hefe_shortcode_bootstrap_container_fluid');
	add_shortcode('hefe_bootstrap_container_fluid_grandchild', 'hefe_shortcode_bootstrap_container_fluid');
	add_shortcode('hefe_bootstrap_container_fluid', 'hefe_shortcode_bootstrap_container_fluid');
	function hefe_shortcode_bootstrap_container_fluid($atts, $content = null){
		wp_enqueue_style('hefe-bootstrap-grid-style');
		$a = shortcode_atts(array(
			'id' => '',
			'class' => '',
			'etc' => '',
		), $atts);
		return '<div id="'.esc_attr($a['id']).'" class="hefe-bootstrap-container-fluid container-fluid '.esc_attr($a['class']).'" '.esc_attr($a['etc']).'>'.do_shortcode($content).'</div>';
	}
}
// Bootstrap Container
if(!function_exists('hefe_shortcode_bootstrap_container')){
	if(get_option('hefe_control_customizer_control_shortcode_name')){
		add_shortcode(hefe_shortcode_name.'_bootstrap_container_1', 'hefe_shortcode_bootstrap_container');
		add_shortcode(hefe_shortcode_name.'_bootstrap_container_2', 'hefe_shortcode_bootstrap_container');
		add_shortcode(hefe_shortcode_name.'_bootstrap_container_3', 'hefe_shortcode_bootstrap_container');
		add_shortcode(hefe_shortcode_name.'_bootstrap_container_4', 'hefe_shortcode_bootstrap_container');
		add_shortcode(hefe_shortcode_name.'_bootstrap_container_grandparent', 'hefe_shortcode_bootstrap_container');
		add_shortcode(hefe_shortcode_name.'_bootstrap_container_parent', 'hefe_shortcode_bootstrap_container');
		add_shortcode(hefe_shortcode_name.'_bootstrap_container_child', 'hefe_shortcode_bootstrap_container');
		add_shortcode(hefe_shortcode_name.'_bootstrap_container_grandchild', 'hefe_shortcode_bootstrap_container');
		add_shortcode(hefe_shortcode_name.'_bootstrap_container', 'hefe_shortcode_bootstrap_container');
	}
	add_shortcode('hefe_bootstrap_container_1', 'hefe_shortcode_bootstrap_container');
	add_shortcode('hefe_bootstrap_container_2', 'hefe_shortcode_bootstrap_container');
	add_shortcode('hefe_bootstrap_container_3', 'hefe_shortcode_bootstrap_container');
	add_shortcode('hefe_bootstrap_container_4', 'hefe_shortcode_bootstrap_container');
	add_shortcode('hefe_bootstrap_container_grandparent', 'hefe_shortcode_bootstrap_container');
	add_shortcode('hefe_bootstrap_container_parent', 'hefe_shortcode_bootstrap_container');
	add_shortcode('hefe_bootstrap_container_child', 'hefe_shortcode_bootstrap_container');
	add_shortcode('hefe_bootstrap_container_grandchild', 'hefe_shortcode_bootstrap_container');
	add_shortcode('hefe_bootstrap_container', 'hefe_shortcode_bootstrap_container');
	function hefe_shortcode_bootstrap_container($atts, $content = null){
		wp_enqueue_style('hefe-bootstrap-grid-style');
		$a = shortcode_atts(array(
			'id' => '',
			'class' => '',
			'etc' => '',
		), $atts);
		return '<div id="'.esc_attr($a['id']).'" class="hefe-bootstrap-container container '.esc_attr($a['class']).'" '.esc_attr($a['etc']).'>'.do_shortcode($content).'</div>';
	}
}
// Bootstrap Row
if(!function_exists('hefe_shortcode_bootstrap_row')){
	if(get_option('hefe_control_customizer_control_shortcode_name')){
		add_shortcode(hefe_shortcode_name.'_bootstrap_row_1', 'hefe_shortcode_bootstrap_row');
		add_shortcode(hefe_shortcode_name.'_bootstrap_row_2', 'hefe_shortcode_bootstrap_row');
		add_shortcode(hefe_shortcode_name.'_bootstrap_row_3', 'hefe_shortcode_bootstrap_row');
		add_shortcode(hefe_shortcode_name.'_bootstrap_row_4', 'hefe_shortcode_bootstrap_row');
		add_shortcode(hefe_shortcode_name.'_bootstrap_row_grandparent', 'hefe_shortcode_bootstrap_row');
		add_shortcode(hefe_shortcode_name.'_bootstrap_row_parent', 'hefe_shortcode_bootstrap_row');
		add_shortcode(hefe_shortcode_name.'_bootstrap_row_child', 'hefe_shortcode_bootstrap_row');
		add_shortcode(hefe_shortcode_name.'_bootstrap_row_grandchild', 'hefe_shortcode_bootstrap_row');
		add_shortcode(hefe_shortcode_name.'_bootstrap_row', 'hefe_shortcode_bootstrap_row');
	}
	add_shortcode('hefe_bootstrap_row_1', 'hefe_shortcode_bootstrap_row');
	add_shortcode('hefe_bootstrap_row_2', 'hefe_shortcode_bootstrap_row');
	add_shortcode('hefe_bootstrap_row_3', 'hefe_shortcode_bootstrap_row');
	add_shortcode('hefe_bootstrap_row_4', 'hefe_shortcode_bootstrap_row');
	add_shortcode('hefe_bootstrap_row_grandparent', 'hefe_shortcode_bootstrap_row');
	add_shortcode('hefe_bootstrap_row_parent', 'hefe_shortcode_bootstrap_row');
	add_shortcode('hefe_bootstrap_row_child', 'hefe_shortcode_bootstrap_row');
	add_shortcode('hefe_bootstrap_row_grandchild', 'hefe_shortcode_bootstrap_row');
	add_shortcode('hefe_bootstrap_row', 'hefe_shortcode_bootstrap_row');
	function hefe_shortcode_bootstrap_row($atts, $content = null){
		wp_enqueue_style('hefe-bootstrap-grid-style');
		$a = shortcode_atts(array(
			'id' => '',
			'class' => '',
			'etc' => '',
		), $atts);
		return '<div id="'.esc_attr($a['id']).'" class="hefe-bootstrap-row row '.esc_attr($a['class']).'" '.esc_attr($a['etc']).'>'.do_shortcode($content).'</div>';
	}
}
// Bootstrap Column
if(!function_exists('hefe_shortcode_bootstrap_column')){
	if(get_option('hefe_control_customizer_control_shortcode_name')){
		add_shortcode(hefe_shortcode_name.'_bootstrap_column_1', 'hefe_shortcode_bootstrap_column');
		add_shortcode(hefe_shortcode_name.'_bootstrap_column_2', 'hefe_shortcode_bootstrap_column');
		add_shortcode(hefe_shortcode_name.'_bootstrap_column_3', 'hefe_shortcode_bootstrap_column');
		add_shortcode(hefe_shortcode_name.'_bootstrap_column_4', 'hefe_shortcode_bootstrap_column');
		add_shortcode(hefe_shortcode_name.'_bootstrap_column_grandparent', 'hefe_shortcode_bootstrap_column');
		add_shortcode(hefe_shortcode_name.'_bootstrap_column_parent', 'hefe_shortcode_bootstrap_column');
		add_shortcode(hefe_shortcode_name.'_bootstrap_column_child', 'hefe_shortcode_bootstrap_column');
		add_shortcode(hefe_shortcode_name.'_bootstrap_column_grandchild', 'hefe_shortcode_bootstrap_column');
		add_shortcode(hefe_shortcode_name.'_bootstrap_column', 'hefe_shortcode_bootstrap_column');
		add_shortcode(hefe_shortcode_name.'_bootstrap_col', 'hefe_shortcode_bootstrap_column');
	}
	add_shortcode('hefe_bootstrap_column_1', 'hefe_shortcode_bootstrap_column');
	add_shortcode('hefe_bootstrap_column_2', 'hefe_shortcode_bootstrap_column');
	add_shortcode('hefe_bootstrap_column_3', 'hefe_shortcode_bootstrap_column');
	add_shortcode('hefe_bootstrap_column_4', 'hefe_shortcode_bootstrap_column');
	add_shortcode('hefe_bootstrap_column_grandparent', 'hefe_shortcode_bootstrap_column');
	add_shortcode('hefe_bootstrap_column_parent', 'hefe_shortcode_bootstrap_column');
	add_shortcode('hefe_bootstrap_column_child', 'hefe_shortcode_bootstrap_column');
	add_shortcode('hefe_bootstrap_column_grandchild', 'hefe_shortcode_bootstrap_column');
	add_shortcode('hefe_bootstrap_column', 'hefe_shortcode_bootstrap_column');
	add_shortcode('hefe_bootstrap_col', 'hefe_shortcode_bootstrap_column');
	function hefe_shortcode_bootstrap_column($atts, $content = null){
		wp_enqueue_style('hefe-bootstrap-grid-style');
		$a = shortcode_atts(array(
			'id' => '',
			'class' => '',
			'col' => '',
			'col_sm' => '',
			'col_md' => '',
			'col_lg' => '',
			'col_xl' => '',
			'etc' => '',
		), $atts);
		$hefe_col_size = '';
		if(esc_attr($a['col'])) {
			$hefe_col_size .= 'col-'.esc_attr($a['col']).' ';
		}
		if(esc_attr($a['col_sm'])) {
			$hefe_col_size .= 'col-sm-'.esc_attr($a['col_sm']).' ';
		}
		if(esc_attr($a['col_md'])) {
			$hefe_col_size .= 'col-md-'.esc_attr($a['col_md']).' ';
		}
		if(esc_attr($a['col_lg'])) {
			$hefe_col_size .= 'col-lg-'.esc_attr($a['col_lg']).' ';
		}
		if(esc_attr($a['col_xl'])) {
			$hefe_col_size .= 'col-xl-'.esc_attr($a['col_xl']).' ';
		}
		return '<div id="'.esc_attr($a['id']).'" class="hefe-bootstrap-column '.esc_attr($a['class']).' '.$hefe_col_size.'" '.esc_attr($a['etc']).'>'.do_shortcode($content).'</div>';
	}
}
// Breadcrumbs
if(!function_exists('hefe_shortcode_breadcrumbs')){
	if(get_option('hefe_control_customizer_control_shortcode_name')){
		add_shortcode(hefe_shortcode_name.'_breadcrumbs', 'hefe_shortcode_breadcrumbs');
		add_shortcode(hefe_shortcode_name.'_breadcrumb', 'hefe_shortcode_breadcrumbs');
	}
	add_shortcode('hefe_breadcrumbs', 'hefe_shortcode_breadcrumbs');
	add_shortcode('hefe_breadcrumb', 'hefe_shortcode_breadcrumbs');
	function hefe_shortcode_breadcrumbs($atts, $content = null){
		wp_enqueue_style('hefe-breadcrumbs-style');
		$a = shortcode_atts(array(
			'id' => '',
			'class' => '',
			'etc' => '',
		), $atts);
		$hefe_breadcrumbs = '<div id="'.esc_attr($a['id']).'" class="hefe-breadcrumbs '.esc_attr($a['class']).'" '.esc_attr($a['etc']).'>';
			$hefe_breadcrumbs .= '<ul>';
				if(!is_front_page()): 
					$hefe_breadcrumbs .= '<li><a href="'.get_home_url().'">Home</a></li>';
					if(is_home()):
						$blog_title = get_the_title(get_option('page_for_posts', true));
						$hefe_breadcrumbs .= '<li>'.$blog_title.'</li>';
					endif;
					if(is_post_type_archive()): 
						$post_type = get_post_type();
						$post_type_object = get_post_type_object($post_type);
						$post_type_name = $post_type_object->labels->singular_name;
						$hefe_breadcrumbs .= '<li>'.$post_type_name.'</li>';
					endif;
					if(!is_singular('post') && !is_page()):
						if(is_singular()): 
							$post_type = get_post_type();
							$post_type_object = get_post_type_object($post_type);
							$post_type_name = $post_type_object->labels->singular_name;
							$hefe_breadcrumbs .= '<li><a href="'.get_post_type_archive_link($post_type).'">'.$post_type_name.'</a></li>';
						endif;
					endif;
					if(is_singular()): 
						global $post;
						if($post->post_parent):
							$ancestors = get_post_ancestors($post->ID);
							foreach(array_reverse($ancestors) as $ancestor):
								$ancestor_id = get_page($ancestor)->ID;
								$hefe_breadcrumbs .= '<li><a href="'.get_permalink($ancestor_id).'">'.get_page($ancestor)->post_title.'</a></li>';
							endforeach;
						endif;
					endif;
					if(is_singular()):
						$hefe_breadcrumbs .= '<li>'.get_the_title().'</li>';
					endif;
					if(is_archive() && !is_post_type_archive()):
						$hefe_breadcrumbs .= '<li>'.get_the_archive_title().'</li>';
					endif;
					if(is_search()):
						$hefe_breadcrumbs .= '<li>Search</li>';
						$hefe_breadcrumbs .= '<li>'.get_search_query().'</li>';
					endif;
					if(is_author()):
						$hefe_breadcrumbs .= '<li>User</li>';
						$hefe_breadcrumbs .= '<li>'.get_the_author().'</li>';
					endif;
					if(is_404()):
						$hefe_breadcrumbs .= '<li>404</li>';
					endif;
				endif;
			$hefe_breadcrumbs .= '</ul>';
		$hefe_breadcrumbs .= '</div>';
		return $hefe_breadcrumbs;
	}
}
// Button
if(!function_exists('hefe_shortcode_button')){
	if(get_option('hefe_control_customizer_control_shortcode_name')){
		add_shortcode(hefe_shortcode_name.'_button', 'hefe_shortcode_button');
	}
	add_shortcode('hefe_button', 'hefe_shortcode_button');
	function hefe_shortcode_button($atts, $content = null){
		wp_enqueue_style('hefe-button-style');
		$a = shortcode_atts(array(
			'id' => '',
			'class' => '',
			'href' => '',
			'style' => '',
			'etc' => '',
		), $atts);
		$style = '';
		if(esc_attr($a['style'])){
			$style = 'hefe-button-style-'.esc_attr($a['style']);
			wp_enqueue_style('hefe-button-style-'.esc_attr($a['style']));
		}
		return '<a id="'.esc_attr($a['id']).'" class="hefe-button '.$style.' '.esc_attr($a['class']).'" href="'.esc_attr($a['href']).'" '.esc_attr($a['etc']).'>'.do_shortcode($content).'</a>';
	}
}
// Category List
if(!function_exists('hefe_shortcode_category_list')){
	if(get_option('hefe_control_customizer_control_shortcode_name')){
		add_shortcode(hefe_shortcode_name.'_category_list', 'hefe_shortcode_category_list');
		add_shortcode(hefe_shortcode_name.'_categories_list', 'hefe_shortcode_category_list');
	}
	add_shortcode('hefe_category_list', 'hefe_shortcode_category_list');
	add_shortcode('hefe_categories_list', 'hefe_shortcode_category_list');
	function hefe_shortcode_category_list($atts, $content = null){
		$a = shortcode_atts(array(
			'id' => '',
			'class' => '',
			'limit' => '',
			'etc' => '',
		), $atts);
		$categories = get_categories();
		$category_list = '';
		if($categories){
			$category_list = '<ul id="'.esc_attr($a['id']).'" class="hefe-category-list '.esc_attr($a['class']).'" '.esc_attr($a['etc']).'>';
			shuffle($categories);
			$i = 0;
			foreach ( $categories as $category ) {
				if(esc_attr($a['limit'])){
					if($i == esc_attr($a['limit'])) break;
				}
				$category_link = get_tag_link( $category->term_id );
				$category_list .= '<li><a href="'.$category_link.'" title="'.$category->name.' Tag" class="'.$category->slug.'">';
				$category_list .= $category->name.'</a></li>';
				$i++;
			}
			$category_list .= '</ul>';
		}
		return $category_list;
	}
}
// Center
if(!function_exists('hefe_shortcode_center')){
	if(get_option('hefe_control_customizer_control_shortcode_name')){
		add_shortcode(hefe_shortcode_name.'_center', 'hefe_shortcode_center');
	}
	add_shortcode('hefe_center', 'hefe_shortcode_center');
	function hefe_shortcode_center($atts, $content = null){
		wp_enqueue_style('hefe-center-style');
		$a = shortcode_atts(array(
			'id' => '',
			'class' => '',
			'etc' => '',
		), $atts);
		return '<div id="'.esc_attr($a['id']).'" class="hefe-center-parent '.esc_attr($a['class']).'" '.esc_attr($a['etc']).'><div class="hefe-center-child" >'.do_shortcode($content).'</div></div>';
	}	
}
// Current Date
if(!function_exists('hefe_shortcode_current_date')){
	if(get_option('hefe_control_customizer_control_shortcode_name')){
		add_shortcode(hefe_shortcode_name.'_current_date', 'hefe_shortcode_current_date');
	}
	add_shortcode('hefe_current_date', 'hefe_shortcode_current_date');
	function hefe_shortcode_current_date($atts, $content = null){
		$a = shortcode_atts(array(
			'format' => '',
		), $atts);
		if(esc_attr($a['format'])){
			return date(esc_attr($a['format']));
		}else{
			return date('d/m/Y');
		}
	}
}
// Current User
if(!function_exists('hefe_shortcode_current_user')){
	if(get_option('hefe_control_customizer_control_shortcode_name')){
		add_shortcode(hefe_shortcode_name.'_current_user', 'hefe_shortcode_current_user');
	}
	add_shortcode('hefe_current_user', 'hefe_shortcode_current_user');
	function hefe_shortcode_current_user($atts, $content = null){
		$current_user = wp_get_current_user();
		$a = shortcode_atts(array(
			'parameter' => '',
		), $atts);
		$parameter = 'display_name';
		if(esc_attr($a['parameter'])){
			$parameter = esc_attr($a['parameter']);
		}
		return get_the_author_meta($parameter, $current_user->ID);
	}
}
// DIV
if(!function_exists('hefe_shortcode_div')){
	if(get_option('hefe_control_customizer_control_shortcode_name')){
		add_shortcode(hefe_shortcode_name.'_div', 'hefe_shortcode_div');
	}
	add_shortcode('hefe_div', 'hefe_shortcode_div');
	function hefe_shortcode_div($atts, $content = null){
		$a = shortcode_atts(array(
			'id' => '',
			'class' => '',
			'etc' => '',
		), $atts);
		return '<div id="'.esc_attr($a['id']).'" class="hefe-div '.esc_attr($a['class']).'" '.esc_attr($a['etc']).'>'.do_shortcode($content).'</div>';
	}
}
// fancyBox - Link
if(!function_exists('hefe_fancybox_shortcode_link')){
	if(get_option('hefe_control_customizer_control_shortcode_name')){
		add_shortcode(hefe_shortcode_name.'_fancybox_link', 'hefe_fancybox_shortcode_link');
		add_shortcode(hefe_shortcode_name.'_fancybox', 'hefe_fancybox_shortcode_link');
	}
	add_shortcode('hefe_fancybox_link', 'hefe_fancybox_shortcode_link');
	add_shortcode('hefe_fancybox', 'hefe_fancybox_shortcode_link');
	function hefe_fancybox_shortcode_link($atts, $content = null){
		wp_enqueue_style('hefe-fancybox-style');
		wp_enqueue_script('hefe-fancybox-script');
		wp_enqueue_script('hefe-fancybox-inc-script');
		$a = shortcode_atts(array(
			'id' => '',
			'class' => '',
			'href' => '',
			'caption' => '',
			'group' => '',
			'width' => '',
			'height' => '',
			'srcset' => '',
			'src' => '',
			'type' => '',
			'etc' => '',
		), $atts);
		$hefe_fancybox_googlemap_class = '';
		$hefe_fancybox_youtube_class = '';
		$hefe_fancybox_vimeo_class = '';
		if(esc_attr($a['group']) != ''){
			$hefe_fancybox_group = esc_attr($a['group']);
		}else{
			$hefe_fancybox_group = 'default';
		}
		return '<a data-fancybox="'.$hefe_fancybox_group.'" data-caption="'.esc_attr($a['caption']).'" data-type="'.esc_attr($a['type']).'" data-src="'.esc_attr($a['src']).'" data-srcset="'.esc_attr($a['srcset']).'" id="'.esc_attr($a['id']).'" class="hefe-fancybox-link '.esc_attr($a['class']).'" href="'.esc_attr($a['href']).'" '.esc_attr($a['etc']).'>'.do_shortcode($content).'</a>';
	}
}
// fancyBox Inline Link
if(!function_exists('hefe_fancybox_shortcode_inline_link')){
	if(get_option('hefe_control_customizer_control_shortcode_name')){
		add_shortcode(hefe_shortcode_name.'_fancybox_inline_link', 'hefe_fancybox_shortcode_inline_link');
	}
	add_shortcode('hefe_fancybox_inline_link', 'hefe_fancybox_shortcode_inline_link');
	function hefe_fancybox_shortcode_inline_link($atts, $content = null){
		wp_enqueue_style('hefe-fancybox-style');
		wp_enqueue_script('hefe-fancybox-script');
		wp_enqueue_script('hefe-fancybox-inc-script');
		$a = shortcode_atts(array(
			'id' => '',
			'class' => '',
			'group' => '',
			'paired_id' => '',
			'etc' => '',
		), $atts);
		$hefe_fancybox_paired_id = '';
		if(esc_attr($a['paired_id'])){
			$hefe_fancybox_paired_id = preg_replace("/[\s_]/", "-", preg_replace("/[\s-]+/", " ", strtolower(esc_attr($a['paired_id']))));
		}
		return '<a data-fancybox="'.esc_attr($a['group']).'" data-src="#'.$hefe_fancybox_paired_id.'" href="javascript:;" id="'.esc_attr($a['id']).'" class="hefe-fancybox-inline-link '.esc_attr($a['class']).'" '.esc_attr($a['etc']).'>'.do_shortcode($content).'</a>';
	}
}
// fancyBox Inline Content
if(!function_exists('hefe_fancybox_shortcode_inline_content')){
	if(get_option('hefe_control_customizer_control_shortcode_name')){
		add_shortcode(hefe_shortcode_name.'_fancybox_inline_content', 'hefe_fancybox_shortcode_inline_content');
	}
	add_shortcode('hefe_fancybox_inline_content', 'hefe_fancybox_shortcode_inline_content');
	function hefe_fancybox_shortcode_inline_content($atts, $content = null){
		wp_enqueue_style('hefe-fancybox-style');
		wp_enqueue_script('hefe-fancybox-script');
		wp_enqueue_script('hefe-fancybox-inc-script');
		$a = shortcode_atts(array(
			'class' => '',
			'paired_id' => '',
			'etc' => '',
		), $atts);
		$hefe_fancybox_paired_id = '';
		if(esc_attr($a['paired_id'])){
			$hefe_fancybox_paired_id = preg_replace("/[\s_]/", "-", preg_replace("/[\s-]+/", " ", strtolower(esc_attr($a['paired_id']))));
		}
		return '<div id="'.esc_attr($a['paired_id']).'" class="hefe-fancybox-inline-content '.esc_attr($a['class']).'" style="display: none" '.esc_attr($a['etc']).'>'.do_shortcode($content).'</div>';
	}
}
// Featured Image
if(!function_exists('hefe_shortcode_featured_image')){
	if(get_option('hefe_control_customizer_control_shortcode_name')){
		add_shortcode(hefe_shortcode_name.'_featured_image', 'hefe_shortcode_featured_image');
		add_shortcode(hefe_shortcode_name.'_page_featured_image', 'hefe_shortcode_featured_image');
	}
	add_shortcode('hefe_featured_image', 'hefe_shortcode_featured_image');
	add_shortcode('hefe_page_featured_image', 'hefe_shortcode_featured_image');
	function hefe_shortcode_featured_image($atts, $content = null){
		$a = shortcode_atts(array(
			'id' => '',
			'class' => '',
			'width' => '',
			'page_id' => '',
			'image_size' => '',
			'etc' => '',
		), $atts);
		$hefe_featured_image_url = wp_get_attachment_image_src(get_post_thumbnail_id(esc_attr($a['page_id'])), esc_attr($a['image_size'])); 
		if($hefe_featured_image_url){
			return '<img id="'.esc_attr($a['id']).'" class="hefe-featured-image '.esc_attr($a['class']).'" src="'.$hefe_featured_image_url[0].'" width="'.esc_attr($a['width']).'" '.esc_attr($a['etc']).' />';
		}
	}
}
// Featured Image URL
if(!function_exists('hefe_shortcode_featured_image_url')){
	if(get_option('hefe_control_customizer_control_shortcode_name')){
		add_shortcode(hefe_shortcode_name.'_featured_image_url', 'hefe_shortcode_featured_image_url');
	}
	add_shortcode('hefe_featured_image_url', 'hefe_shortcode_featured_image_url');
	function hefe_shortcode_featured_image_url($atts, $content = null){
		$a = shortcode_atts(array(
			'page_id' => '',
			'image_size' => '',
		), $atts);
		$hefe_featured_image_url = wp_get_attachment_image_src(get_post_thumbnail_id(esc_attr($a['page_id'])), esc_attr($a['image_size']));
		return $hefe_featured_image_url[0];
	}
}
// Font Awesome Icon
if(!function_exists('hefe_shortcode_font_awesome_icon')){
	if(get_option('hefe_control_customizer_control_shortcode_name')){
		add_shortcode(hefe_shortcode_name.'_font_awesome_icon', 'hefe_shortcode_font_awesome_icon');
		add_shortcode(hefe_shortcode_name.'_font_awesome', 'hefe_shortcode_font_awesome_icon');
	}
	add_shortcode('hefe_font_awesome_icon', 'hefe_shortcode_font_awesome_icon');
	add_shortcode('hefe_font_awesome', 'hefe_shortcode_font_awesome_icon');
	function hefe_shortcode_font_awesome_icon($atts, $content = null){
		wp_enqueue_style('hefe-font-awesome-style');
		wp_enqueue_script('hefe-font-awesome-script');
		$a = shortcode_atts(array(
			'id' => '',
			'class' => '',
			'icon_pre' => '',
			'pre_icon' => '',
			'icon' => '',
			'style' => '',
			'official_color' => '',
			'etc' => '',
		), $atts);
		if(esc_attr($a['icon_pre'])){
			$icon_pre = esc_attr($a['icon_pre']);
		}elseif(esc_attr($a['pre_icon'])){
			$icon_pre = esc_attr($a['pre_icon']);
		}else{
			$icon_pre = 'fas';
		}
		$official_color = '';
		if(esc_attr($a['official_color'])){
			$official_color = 'hefe-'.esc_attr($a['official_color']).'-official-color';
		}
		$style_begin = '';
		$style_end = '';
		if(esc_attr($a['style'])){
			$style_begin = '<span id="'.esc_attr($a['id']).'" class="hefe-font-awesome-style-'.esc_attr($a['style']).' '.$official_color.' '.esc_attr($a['class']).'" '.esc_attr($a['etc']).'>';
			$style_end = '</span>';
			wp_enqueue_style('hefe-font-awesome-style-'.esc_attr($a['style']));
		}else{
			$style_begin = '<span id="'.esc_attr($a['id']).'" class="hefe-font-awesome-style-00 '.$official_color.' '.esc_attr($a['class']).'" '.esc_attr($a['etc']).'>';
			$style_end = '</span>';
			wp_enqueue_style('hefe-font-awesome-style-00');
		}
		return $style_begin.'<i class="hefe-font-awesome '.$icon_pre.' fa-'.esc_attr($a['icon']).'">&nbsp;</i>'.$style_end;
	}
}
// Front-End Media Link
if(!function_exists('hefe_shortcode_front_end_media_link')){
	if(get_option('hefe_control_customizer_control_shortcode_name')){
		add_shortcode(hefe_shortcode_name.'_front_end_media_link', 'hefe_shortcode_front_end_media_link');
	}
	add_shortcode('hefe_front_end_media_link', 'hefe_shortcode_front_end_media_link');
	function hefe_shortcode_front_end_media_link($atts, $content = null){
		wp_enqueue_script('hefe-front-end-media-script');
		$a = shortcode_atts(array(
			'id' => '',
			'class' => '',
			'attachment_id' => '',
			'etc' => '',
		), $atts);
		return '<a data-attachment="'.esc_attr($a['attachment_id']).'" id="'.esc_attr($a['id']).'" href="#" class="hefe-front-end-media-link '.esc_attr($a['class']).'" '.esc_attr($a['etc']).'>'.do_shortcode($content).'</a>'.$below_shortcode;
	}
	
}
// GET
if(!function_exists('hefe_shortcode_get')){
	if(get_option('hefe_control_customizer_control_shortcode_name')){
		add_shortcode(hefe_shortcode_name.'_get', 'hefe_shortcode_get');
	}
	add_shortcode('hefe_get', 'hefe_shortcode_get');
	function hefe_shortcode_get($atts, $content = null){
		$a = shortcode_atts(array(
			'name' => '',
			'exclude' => '',
			'exclude_1' => '',
			'exclude_2' => '',
			'exclude_3' => '',
			'exclude_4' => '',
			'exclude_5' => '',
			'exclude_6' => '',
		), $atts);
		if(isset($_GET[esc_attr($a['name'])])) {
			if($_GET[esc_attr($a['name'])] != esc_attr($a['exclude']) && $_GET[esc_attr($a['name'])] != esc_attr($a['exclude_1']) && $_GET[esc_attr($a['name'])] != esc_attr($a['exclude_2']) && $_GET[esc_attr($a['name'])] != esc_attr($a['exclude_3']) && $_GET[esc_attr($a['name'])] != esc_attr($a['exclude_4']) && $_GET[esc_attr($a['name'])] != esc_attr($a['exclude_5']) && $_GET[esc_attr($a['name'])] != esc_attr($a['exclude_6'])){
				return $_GET[esc_attr($a['name'])];
			}
		}
	}
}
// Horizontal List Parent
if(!function_exists('hefe_shortcode_horizontal_list_parent')){
	if(get_option('hefe_control_customizer_control_shortcode_name')){
		add_shortcode(hefe_shortcode_name.'_horizontal_list_parent', 'hefe_shortcode_horizontal_list_parent');
	}
	add_shortcode('hefe_horizontal_list_parent', 'hefe_shortcode_horizontal_list_parent');
	function hefe_shortcode_horizontal_list_parent($atts, $content = null){
		wp_enqueue_style('hefe-horizontal-list-style');
		$a = shortcode_atts(array(
			'id' => '',
			'class' => '',
			'separator' => '',
			'responsive' => '',
			'etc' => '',
		), $atts);
		if(esc_attr($a['separator']) == 'line'){
			$separator = 'hefe-horizontal-list-separator-line';
		}elseif(esc_attr($a['separator']) == 'dot'){
			$separator = 'hefe-horizontal-list-separator-dot';
		}else{
			$separator = '';
		}
		if(esc_attr($a['responsive']) == 'false' || esc_attr($a['responsive']) == ''){
			$responsive = '';
		}else{
			$responsive = 'hefe-horizontal-list-responsive';
		}
		return '<ul id="'.esc_attr($a['id']).'" class="hefe-horizontal-list-parent '.$separator.' '.$responsive.' '.esc_attr($a['class']).'" '.esc_attr($a['etc']).'>'.do_shortcode($content).'</ul>';
	}
}
// Horizontal List Child
if(!function_exists('hefe_shortcode_horizontal_list_child')){
	if(get_option('hefe_control_customizer_control_shortcode_name')){
		add_shortcode(hefe_shortcode_name.'_horizontal_list_child', 'hefe_shortcode_horizontal_list_child');
	}
	add_shortcode('hefe_horizontal_list_child', 'hefe_shortcode_horizontal_list_child');
	function hefe_shortcode_horizontal_list_child($atts, $content = null){
		wp_enqueue_style('hefe-horizontal-list-style');
		$a = shortcode_atts(array(
			'id' => '',
			'class' => '',
			'etc' => '',
		), $atts);
		return '<li id="'.esc_attr($a['id']).'" class="hefe-horizontal-list-child '.esc_attr($a['class']).'" '.esc_attr($a['etc']).'>'.do_shortcode($content).'</li>';
	}
}
// HR
if(!function_exists('hefe_shortcode_hr')){
	if(get_option('hefe_control_customizer_control_shortcode_name')){
		add_shortcode(hefe_shortcode_name.'_hr', 'hefe_shortcode_hr');
	}
	add_shortcode('hefe_hr', 'hefe_shortcode_hr');
	function hefe_shortcode_hr($atts, $content = null){
		$a = shortcode_atts(array(
			'id' => '',
			'class' => '',
			'etc' => '',
		), $atts);
		return '<hr id="'.esc_attr($a['id']).'" class="hefe-hr '.esc_attr($a['class']).'" '.esc_attr($a['etc']).' />';
	}
}
// Injection Item
if(!function_exists('hefe_shortcode_injection_item')){
	if(get_option('hefe_control_customizer_control_shortcode_name')){
		add_shortcode(hefe_shortcode_name.'_injection', 'hefe_shortcode_injection_item');
		add_shortcode(hefe_shortcode_name.'_injection_item', 'hefe_shortcode_injection_item');
	}
	add_shortcode('hefe_injection', 'hefe_shortcode_injection_item');
	add_shortcode('hefe_injection_item', 'hefe_shortcode_injection_item');
	function hefe_shortcode_injection_item($atts, $content = null){
		$a = shortcode_atts(array(
			'injection_id' => '',
			'injection_filter' => '',
			'exclude' => '',
		), $atts);
		$hefe_injection_id = esc_attr($a['injection_id']);
		$hefe_injection_filter = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', esc_attr($a['injection_filter']))));
		$hefe_injection_exclude = explode(',', str_replace(' ', '', strtolower(esc_attr($a['exclude']))));
		if($hefe_injection_filter != ''){
			$args = array(
				'posts_per_page' => 1,
				'post_type' => 'injection',
				'page_id' => $hefe_injection_id,
				'orderby' => 'rand',
				'hide_empty' => 0,
				'post__not_in' => $hefe_injection_exclude,
				'tax_query' => array(
					array(
						'taxonomy' => 'ad_filter',
						'field' => 'slug',
						'terms' => $hefe_injection_filter,
					),
				),
			);
		}else{
			$args = array(
				'posts_per_page' => 1,
				'post_type' => 'injection',
				'page_id' => $hefe_injection_id,
				'hide_empty' => 0,
				'post__not_in' => $hefe_injection_exclude,
			);
		}
		$query = new WP_Query($args);
		$output = '';
		while($query->have_posts()): $query->the_post();
			$output = apply_filters('the_content', do_shortcode(get_the_content()));
		endwhile;
		wp_reset_postdata();
		return $output;
	}
}
// Is Page
if(!function_exists('hefe_shortcode_is_page')){
	if(get_option('hefe_control_customizer_control_shortcode_name')){
		add_shortcode(hefe_shortcode_name.'_is_page', 'hefe_shortcode_is_page');
	}
	add_shortcode('hefe_is_page', 'hefe_shortcode_is_page');
	function hefe_shortcode_is_page($atts, $content = null){
		$a = shortcode_atts(array(
			'page_id' => '',
		), $atts);
		global $post;
		if($post->ID == esc_attr($a['page_id'])){
			return do_shortcode($content);
		}else{
			return '';
		}
	}
}
// Is Home
if(!function_exists('hefe_shortcode_is_home')){
	if(get_option('hefe_control_customizer_control_shortcode_name')){
		add_shortcode(hefe_shortcode_name.'_is_home', 'hefe_shortcode_is_home');
	}
	add_shortcode('hefe_is_home', 'hefe_shortcode_is_home');
	function hefe_shortcode_is_home($atts, $content = null){
		if(is_home()){
			return do_shortcode($content);
		}else{
			return '';
		}
	}
}
// Isotope Parent
if(!function_exists('hefe_shortcode_isotope_parent')){
	if(get_option('hefe_control_customizer_control_shortcode_name')){
		add_shortcode(hefe_shortcode_name.'_isotope_parent', 'hefe_shortcode_isotope_parent');
	}
	add_shortcode('hefe_isotope_parent', 'hefe_shortcode_isotope_parent');
	function hefe_shortcode_isotope_parent($atts, $content = null){
		wp_enqueue_style('hefe-isotope-inc-style');
		wp_enqueue_script('hefe-isotope-imagesloaded-script');
		wp_enqueue_script('hefe-isotope-script');
		wp_enqueue_script('hefe-isotope-inc-script');
		$a = shortcode_atts(array(
			'id' => '',
			'class' => '',
			'id_number' => '',
			'etc' => '',
		), $atts);
		if(esc_attr($a['id_number'])){
			$id_number = esc_attr($a['id_number']);
		}else{
			$id_number = 'hefe-isotope-parent-default';
		}
		return '<div id="'.esc_attr($a['id']).'" class="hefe-isotope-wrap '.esc_attr($a['class']).'"  '.esc_attr($a['etc']).'><div class="hefe-isotope-parent '.$id_number.'"><div class="hefe-isotope-child-column-width">&nbsp;</div><div class="hefe-isotope-gutter-width">&nbsp;</div>'.do_shortcode($content).'</div></div>';
	}
}
// Isotope Child
if(!function_exists('hefe_shortcode_isotope_child')){
	if(get_option('hefe_control_customizer_control_shortcode_name')){
		add_shortcode(hefe_shortcode_name.'_isotope_child', 'hefe_shortcode_isotope_child');
	}
	add_shortcode('hefe_isotope_child', 'hefe_shortcode_isotope_child');
	function hefe_shortcode_isotope_child($atts, $content = null){
		wp_enqueue_style('hefe-isotope-inc-style');
		wp_enqueue_script('hefe-isotope-imagesloaded-script');
		wp_enqueue_script('hefe-isotope-script');
		wp_enqueue_script('hefe-isotope-inc-script');
		$a = shortcode_atts(array(
			'id' => '',
			'class' => '',
			'etc' => '',
		), $atts);
		return '<div id="'.esc_attr($a['id']).'" class="hefe-isotope-child '.esc_attr($a['class']).'" '.esc_attr($a['etc']).'>'.do_shortcode($content).'</div>';
	}
}
// LI
if(!function_exists('hefe_shortcode_li')){
	if(get_option('hefe_control_customizer_control_shortcode_name')){
		add_shortcode(hefe_shortcode_name.'_li', 'hefe_shortcode_li');
	}
	add_shortcode('hefe_li', 'hefe_shortcode_li');
	function hefe_shortcode_li($atts, $content = null){
		$a = shortcode_atts(array(
			'id' => '',
			'class' => '',
			'etc' => '',
		), $atts);
		return '<li id="'.esc_attr($a['id']).'" class="hefe-li '.esc_attr($a['class']).'" '.esc_attr($a['etc']).'>'.do_shortcode($content).'</li>';
	}
}
// Link
if(!function_exists('hefe_shortcode_link')){
	if(get_option('hefe_control_customizer_control_shortcode_name')){
		add_shortcode(hefe_shortcode_name.'_a', 'hefe_shortcode_link');
		add_shortcode(hefe_shortcode_name.'_link', 'hefe_shortcode_link');
		add_shortcode(hefe_shortcode_name.'_hyperlink', 'hefe_shortcode_link');
	}
	add_shortcode('hefe_a', 'hefe_shortcode_link');
	add_shortcode('hefe_link', 'hefe_shortcode_link');
	add_shortcode('hefe_hyperlink', 'hefe_shortcode_link');
	function hefe_shortcode_link($atts, $content = null){
		$a = shortcode_atts(array(
			'id' => '',
			'class' => '',
			'href' => '',
			'etc' => '',
		), $atts);
		return '<a id="'.esc_attr($a['id']).'" class="hefe-link '.esc_attr($a['class']).'" href="'.esc_attr($a['href']).'" '.esc_attr($a['etc']).'>'.do_shortcode($content).'</a>';
	}
}
// List Pages
if(!function_exists('hefe_shortcode_list_pages')){
	if(get_option('hefe_control_customizer_control_shortcode_name')){
		add_shortcode(hefe_shortcode_name.'_list_pages', 'hefe_shortcode_list_pages');
		add_shortcode(hefe_shortcode_name.'_sitemap', 'hefe_shortcode_list_pages');
	}
	add_shortcode('hefe_list_pages', 'hefe_shortcode_list_pages');
	add_shortcode('hefe_sitemap', 'hefe_shortcode_list_pages');
	function hefe_shortcode_list_pages($atts, $content = null){
		$a = shortcode_atts(array(
			'id' => '',
			'class' => '',
			'child_of' => '',
			'authors' => '',
			'date_format' => '',
			'depth' => '',
			'echo' => '0',
			'exclude' => '',
			'include' => '',
			'link_after' => '',
			'link_before' => '',
			'post_type' => 'page',
			'post_status' => '',
			'show_date' => '',
			'sort_column' => 'menu_order',
			'title_li' => '',
			'walker' => '',
			'etc' => '',
		), $atts);
		$args = array();
		if(esc_attr($a['child_of']) != ''){
			$args['child_of'] = esc_attr($a['child_of']);
		}
		if(esc_attr($a['authors']) != ''){
			$args['authors'] = esc_attr($a['authors']);
		}
		if(esc_attr($a['date_format']) != ''){
			$args['date_format'] = esc_attr($a['date_format']);
		}
		if(esc_attr($a['depth']) != ''){
			$args['depth'] = esc_attr($a['depth']);
		}
		if(esc_attr($a['echo']) != ''){
			$args['echo'] = esc_attr($a['echo']);
		}
		if(esc_attr($a['exclude']) != ''){
			$args['exclude'] = esc_attr($a['exclude']);
		}
		if(esc_attr($a['include']) != ''){
			$args['include'] = esc_attr($a['include']);
		}
		if(esc_attr($a['link_after']) != ''){
			$args['link_after'] = esc_attr($a['link_after']);
		}
		if(esc_attr($a['link_before']) != ''){
			$args['link_before'] = esc_attr($a['link_before']);
		}
		if(esc_attr($a['post_type']) != ''){
			$args['post_type'] = esc_attr($a['post_type']);
		}
		if(esc_attr($a['post_status']) != ''){
			$args['post_status'] = esc_attr($a['post_status']);
		}
		if(esc_attr($a['show_date']) != ''){
			$args['show_date'] = esc_attr($a['show_date']);
		}
		if(esc_attr($a['sort_column']) != ''){
			$args['sort_column'] = esc_attr($a['sort_column']);
		}
		if(esc_attr($a['title_li']) != ''){
			$args['title_li'] = esc_attr($a['title_li']);
		}else{
			$args['title_li'] = '';
		}
		if(esc_attr($a['walker']) != ''){
			$args['walker'] = esc_attr($a['walker']);
		}
		return '<ul id="'.esc_attr($a['id']).'" class="hefe-list-pages '.esc_attr($a['class']).'" '.esc_attr($a['etc']).'>'.wp_list_pages($args).'</ul>';
	}
}
// List Page Children
if(!function_exists('hefe_shortcode_list_page_children')){
	if(get_option('hefe_control_customizer_control_shortcode_name')){
		add_shortcode(hefe_shortcode_name.'_list_page_children', 'hefe_shortcode_list_page_children');
	}
	add_shortcode('hefe_list_page_children', 'hefe_shortcode_list_page_children');
	function hefe_shortcode_list_page_children($atts, $content = null){
		$a = shortcode_atts(array(
			'id' => '',
			'class' => '',
			'page_id' => '',
			'sort_column' => 'menu_order',
			'etc' => '',
		), $atts);
		if(esc_attr($a['page_id']) != ''){
			$ID = esc_attr($a['page_id']);
		}else{
			$ID = get_the_ID();
		}
		$ancestors = get_post_ancestors($ID);
		$ancestors = array_reverse($ancestors);
		$ancestors[] = $ID;
		$pages = get_pages('sort_column=post_date');
		$exclude = '';
		foreach ($pages as $page){
			if(!in_array($page->post_parent,$ancestors)){
				$exclude .=','.$page->ID;
			}
		}
		$listPages = wp_list_pages('title_li=&child_of='.$ancestors[0].'&exclude='.$exclude.'&sort_column='.esc_attr($a['sort_column']).'&echo=0');
		return '<div id="'.esc_attr($a['id']).'" class="hefe-list-page-children '.esc_attr($a['class']).'" '.esc_attr($a['etc']).'><h3><a href="'.get_permalink($ancestors[0]).'">'.get_the_title($ancestors[0]).'</a></h3>'.'<ul class="hefe-list-page-children-parent">'.$listPages.'</ul></div>';
	}
}
// Login Link
if(!function_exists('hefe_shortcode_login_link')){
	if(get_option('hefe_control_customizer_control_shortcode_name')){
		add_shortcode(hefe_shortcode_name.'_login_link', 'hefe_shortcode_login_link');
	}
	add_shortcode('hefe_login_link', 'hefe_shortcode_login_link');
	function hefe_shortcode_login_link($atts, $content = null){
		$a = shortcode_atts(array(
			'id' => '',
			'class' => '',
			'redirect' => '',
			'etc' => '',
		), $atts);
		return '<a id="'.esc_attr($a['id']).'" href="'.wp_login_url(esc_attr($a['redirect'])).'" class="hefe-login-link '.esc_attr($a['class']).'" '.esc_attr($a['etc']).'>'.do_shortcode($content).'</a>';
	}
}
// Logout Link
if(!function_exists('hefe_shortcode_logout_link')){
	if(get_option('hefe_control_customizer_control_shortcode_name')){
		add_shortcode(hefe_shortcode_name.'_logout_link', 'hefe_shortcode_logout_link');
	}
	add_shortcode('hefe_logout_link', 'hefe_shortcode_logout_link');
	function hefe_shortcode_logout_link($atts, $content = null){
		$a = shortcode_atts(array(
			'id' => '',
			'class' => '',
			'redirect' => '',
			'etc' => '',
		), $atts);
		return '<a id="'.esc_attr($a['id']).'" href="'.wp_logout_url(esc_attr($a['redirect'])).'" class="hefe-logout-link '.esc_attr($a['class']).'" '.esc_attr($a['etc']).'>'.do_shortcode($content).'</a>';
	}
}
// Logged In Only
if(!function_exists('hefe_shortcode_logged_in_only')){
	if(get_option('hefe_control_customizer_control_shortcode_name')){
		add_shortcode(hefe_shortcode_name.'_logged_in_only', 'hefe_shortcode_logged_in_only');
	}
	add_shortcode('hefe_logged_in_only', 'hefe_shortcode_logged_in_only');
	function hefe_shortcode_logged_in_only($atts, $content = null){
		$a = shortcode_atts(array(
			'user_roles' => '',
		), $atts);
		if(is_user_logged_in()){
			$hefe_logged_in_only_user_id = get_current_user_id();
			$hefe_logged_in_only_user_data = get_userdata($hefe_logged_in_only_user_id);
			$hefe_logged_in_only_user_roles = implode($hefe_logged_in_only_user_data->roles);
			$hefe_logged_in_only_user_roles_specificity = strtolower(esc_attr($a['user_roles']));
			if(esc_attr($a['user_roles'])){
				if($hefe_logged_in_only_user_roles_specificity == $hefe_logged_in_only_user_roles){
					return do_shortcode($content);
				}else{
					return '';
				}
			}else{
				return do_shortcode($content);
			}
		}else{
			return '';
		}
	}
}
// Logged Out Only
if(!function_exists('hefe_shortcode_logged_out_only')){
	if(get_option('hefe_control_customizer_control_shortcode_name')){
		add_shortcode(hefe_shortcode_name.'_logged_out_only', 'hefe_shortcode_logged_out_only');
	}
	add_shortcode('hefe_logged_out_only', 'hefe_shortcode_logged_out_only');
	function hefe_shortcode_logged_out_only($atts, $content = null){
		if(!is_user_logged_in()){
			return do_shortcode($content);
		}else{
			return '';
		}
	}
}
// matchHeight Item
if(!function_exists('hefe_match_height_shortcode_item')){
	if(get_option('hefe_control_customizer_control_shortcode_name')){
		add_shortcode(hefe_shortcode_name.'_match_height_item', 'hefe_match_height_shortcode_item');
	}
	add_shortcode('hefe_match_height_item', 'hefe_match_height_shortcode_item');
	function hefe_match_height_shortcode_item($atts, $content = null){
		wp_enqueue_style('hefe-match-height-style');
		wp_enqueue_script('hefe-match-height-script');
		wp_enqueue_script('hefe-match-height-inc-script');
		$a = shortcode_atts(array(
			'id' => '',
			'class' => '',
			'etc' => '',
		), $atts);
		return '<div id="'.esc_attr($a['id']).'" class="hefe-match-height-item '.esc_attr($a['class']).'" '.esc_attr($a['etc']).'>'.do_shortcode($content).'</div>';
	}
}
// Menu
if(!function_exists('hefe_shortcode_menu')){
	if(get_option('hefe_control_customizer_control_shortcode_name')){
		add_shortcode(hefe_shortcode_name.'_menu', 'hefe_shortcode_menu');
		add_shortcode(hefe_shortcode_name.'_navigation', 'hefe_shortcode_menu');
	}
	add_shortcode('hefe_menu', 'hefe_shortcode_menu');
	add_shortcode('hefe_navigation', 'hefe_shortcode_menu');
	function hefe_shortcode_menu($atts, $content = null){
		$a = shortcode_atts(array(
			'menu' => '',
			'menu_class' => '',
			'menu_id' => '',
			'container' => 'nav',
			'container_class' => '',
			'container_id' => '',
			'before' => '',
			'after' => '',
			'link_before' => '',
			'link_after' => '',
			'depth' => '',
			'walker' => '',
			'fallback_cb' => '',
			'theme_location' => '',
			'echo' => '',
			'style' => '',
		), $atts);
		$style = '';
		if(esc_attr($a['style']) == ''){
			$style = '';
		}elseif(in_array(esc_attr($a['style']), array('02','03'))){
			$style = 'hefe-menu-style-'.esc_attr($a['style']);
			wp_enqueue_style('hefe-menu-style-'.esc_attr($a['style']));
			wp_enqueue_script('hefe-menu-script-'.esc_attr($a['style']));
		}else{
			$style = 'hefe-menu-style-'.esc_attr($a['style']);
			wp_enqueue_style('hefe-menu-style-'.esc_attr($a['style']));
		}
		$args = array();
		$args['menu'] = esc_attr($a['menu']);
		$args['menu_class'] = esc_attr($a['menu_class']);
		$args['menu_id'] = esc_attr($a['menu_id']);
		$args['container'] = esc_attr($a['container']);
		$args['container_class'] .= ' '.esc_attr($a['container_class']).' '.$style.' ';
		$args['container_id'] = esc_attr($a['container_id']);
		$args['before'] = esc_attr($a['before']);
		$args['after'] = esc_attr($a['after']);
		$args['link_before'] = esc_attr($a['link_before']);
		$args['link_after'] = esc_attr($a['link_after']);
		$args['depth'] = esc_attr($a['depth']);
		$args['walker'] = esc_attr($a['walker']);
		$args['theme_location'] = esc_attr($a['theme_location']);
		if(esc_attr($a['fallback_cb'])){
			$args['fallback_cb'] = esc_attr($a['fallback_cb']);
		}else{
			$args['fallback_cb'] = false;
		}
		if(esc_attr($a['echo'])){
			$args['echo'] = esc_attr($a['echo']);
		}else{
			$args['echo'] = false;
		}
		return wp_nav_menu($args);
	}
}
// OL
if(!function_exists('hefe_shortcode_ol')){
	if(get_option('hefe_control_customizer_control_shortcode_name')){
		add_shortcode(hefe_shortcode_name.'_ol', 'hefe_shortcode_ol');
	}
	add_shortcode('hefe_ol', 'hefe_shortcode_ol');
	function hefe_shortcode_ol($atts, $content = null){
		$a = shortcode_atts(array(
			'id' => '',
			'class' => '',
			'etc' => '',
		), $atts);
		return '<ol id="'.esc_attr($a['id']).'" class="hefe-ol '.esc_attr($a['class']).'" '.esc_attr($a['etc']).'>'.do_shortcode($content).'</ol>';
	}
}
// P
if(!function_exists('hefe_shortcode_p')){
	if(get_option('hefe_control_customizer_control_shortcode_name')){
		add_shortcode(hefe_shortcode_name.'_p', 'hefe_shortcode_p');
	}
	add_shortcode('hefe_p', 'hefe_shortcode_p');
	function hefe_shortcode_p($atts, $content = null){
		$a = shortcode_atts(array(
			'id' => '',
			'class' => '',
			'etc' => '',
		), $atts);
		return '<p id="'.esc_attr($a['id']).'" class="hefe-p '.esc_attr($a['class']).'" '.esc_attr($a['etc']).'>'.do_shortcode($content).'</p>';
	}
}
// Page Categories
if(!function_exists('hefe_shortcode_categories')){
	if(get_option('hefe_control_customizer_control_shortcode_name')){
		add_shortcode(hefe_shortcode_name.'_categories', 'hefe_shortcode_categories');
		add_shortcode(hefe_shortcode_name.'_page_categories', 'hefe_shortcode_categories');
	}
	add_shortcode('hefe_categories', 'hefe_shortcode_categories');
	add_shortcode('hefe_page_categories', 'hefe_shortcode_categories');
	function hefe_shortcode_categories($atts, $content = null){
		$a = shortcode_atts(array(
			'id' => '',
			'class' => '',
			'page_id' => '',
			'etc' => '',
		), $atts);
		$postcategories = get_the_category(esc_attr($a['page_id']));
		$hefe_categories = '';
		if($postcategories){
			$hefe_categories .= '<ul id="'.esc_attr($a['id']).'" class="hefe-categories '.esc_attr($a['class']).'" '.esc_attr($a['etc']).'>';
			foreach($postcategories as $category){
				$hefe_category_url = get_category_link($category->term_id);
				$hefe_categories .= '<li><a href="'.$hefe_category_url.'">'.$category->name.'</a></li>'; 
			}
			$hefe_categories .= '</ul>';
		}
		return $hefe_categories;
	}
}
// Page Content
if(!function_exists('hefe_shortcode_page_content')){
	if(get_option('hefe_control_customizer_control_shortcode_name')){
		add_shortcode(hefe_shortcode_name.'_page_content', 'hefe_shortcode_page_content');
		add_shortcode(hefe_shortcode_name.'_content', 'hefe_shortcode_page_content');
	}
	add_shortcode('hefe_page_content', 'hefe_shortcode_page_content');
	add_shortcode('hefe_content', 'hefe_shortcode_page_content');
	function hefe_shortcode_page_content($atts, $content = null){
		$a = shortcode_atts(array(
			'characters' => '',
			'page_id' => '',
		), $atts);
		if(esc_attr($a['page_id']) != ''){
			if(esc_attr($a['characters']) != ''){
				$post = get_post(esc_attr($a['page_id']));
				$content = strip_shortcodes($post->post_content);
				$content = strip_tags($content);
	 			if(strlen($content) > esc_attr($a['characters'])){
					return substr($content, 0, esc_attr($a['characters'])).'...';
				}else{
					return $content;
				}
			}else{
				$post = get_post(esc_attr($a['page_id']));
				return apply_filters('the_content', $post->post_content);
			}
		}else{
			if(esc_attr($a['characters']) != ''){
				global $post;
				$content = strip_shortcodes($post->post_content);
				$content = strip_tags($content);
				if(strlen($content) > esc_attr($a['characters'])){
					return substr($content, 0, esc_attr($a['characters'])).'...';
				}else{
					return $content;
				}
			}else{
				global $post;
				return apply_filters('the_content', $post->post_content);
			}
		}
	}
}
// Page Date
if(!function_exists('hefe_shortcode_page_date')){
	if(get_option('hefe_control_customizer_control_shortcode_name')){
		add_shortcode(hefe_shortcode_name.'_page_date', 'hefe_shortcode_page_date');
		add_shortcode(hefe_shortcode_name.'_date', 'hefe_shortcode_page_date');
	}
	add_shortcode('hefe_page_date', 'hefe_shortcode_page_date');
	add_shortcode('hefe_date', 'hefe_shortcode_page_date');
	function hefe_shortcode_page_date($atts, $content = null){
		$a = shortcode_atts(array(
			'page_id' => '',
			'format' => '',
		), $atts);
		if(esc_attr($a['format'])){
			$format = esc_attr($a['format']);
		}else{
			$format = 'd/m/Y';
		}
		return get_the_time($format, esc_attr($a['page_id']));
	}
}
// Page Excerpt
if(!function_exists('hefe_shortcode_page_excerpt')){
	if(get_option('hefe_control_customizer_control_shortcode_name')){
		add_shortcode(hefe_shortcode_name.'_page_excerpt', 'hefe_shortcode_page_excerpt');
		add_shortcode(hefe_shortcode_name.'_excerpt', 'hefe_shortcode_page_excerpt');
	}
	add_shortcode('hefe_page_excerpt', 'hefe_shortcode_page_excerpt');
	add_shortcode('hefe_excerpt', 'hefe_shortcode_page_excerpt');
	function hefe_shortcode_page_excerpt($atts, $content = null){
		$a = shortcode_atts(array(
			'characters' => '',
			'page_id' => '',
		), $atts);
		if(esc_attr($a['page_id']) != ''){
			if(esc_attr($a['characters']) != ''){
				$post = get_post(esc_attr($a['page_id']));
				$excerpt = strip_shortcodes($post->post_excerpt);
				$excerpt = strip_tags($excerpt);
				if(strlen($excerpt) > esc_attr($a['characters'])){
					return substr($excerpt, 0, esc_attr($a['characters'])).'...';
				}else{
					return $excerpt;
				}
			}else{
				$post = get_post(esc_attr($a['page_id']));
				return strip_shortcodes($post->post_excerpt);
	 		}
		}else{
			if(esc_attr($a['characters']) != ''){
				global $post;
				$excerpt = strip_shortcodes($post->post_excerpt);
				$excerpt = strip_tags($excerpt);
				if(strlen($excerpt) > esc_attr($a['characters'])){
					return substr($excerpt, 0, esc_attr($a['characters'])).'...';
				}else{
					return $excerpt;
				}
			}else{
				global $post;
				return strip_shortcodes($post->post_excerpt);
			}
		}
	}
}
// Page ID
if(!function_exists('hefe_shortcode_page_id')){
	if(get_option('hefe_control_customizer_control_shortcode_name')){
		add_shortcode(hefe_shortcode_name.'_page_id', 'hefe_shortcode_page_id');
	}
	add_shortcode('hefe_page_id', 'hefe_shortcode_page_id');
	function hefe_shortcode_page_id($atts, $content = null){
		return get_the_ID();
	}
}
// Page Tags
if(!function_exists('hefe_shortcode_page_tags')){
	if(get_option('hefe_control_customizer_control_shortcode_name')){
		add_shortcode(hefe_shortcode_name.'_tags', 'hefe_shortcode_page_tags');
		add_shortcode(hefe_shortcode_name.'_page_tags', 'hefe_shortcode_page_tags');
	}
	add_shortcode('hefe_tags', 'hefe_shortcode_page_tags');
	add_shortcode('hefe_page_tags', 'hefe_shortcode_page_tags');
	function hefe_shortcode_page_tags($atts, $content = null){
		$a = shortcode_atts(array(
			'id' => '',
			'class' => '',
			'page_id' => '',
			'etc' => '',
		), $atts);
		$posttags = get_the_tags(esc_attr($a['page_id']));
		$hefe_tags = '';
		if($posttags){
			$hefe_tags .= '<ul id="'.esc_attr($a['id']).'" class="hefe-tags '.esc_attr($a['class']).'" '.esc_attr($a['etc']).'>';
			foreach($posttags as $tag){
				$hefe_tag_url = get_tag_link($tag->term_id);
				$hefe_tags .= '<li><a href="'.$hefe_tag_url.'">'.$tag->name.'</a></li>'; 
			}
			$hefe_tags .= '</ul>';
		}
		return $hefe_tags;
	}
}
// Page Taxonomy
if(!function_exists('hefe_shortcode_taxonomy')){
	if(get_option('hefe_control_customizer_control_shortcode_name')){
		add_shortcode(hefe_shortcode_name.'_page_taxonomy', 'hefe_shortcode_taxonomy');
		add_shortcode(hefe_shortcode_name.'_taxonomy', 'hefe_shortcode_taxonomy');
	}
	add_shortcode('hefe_page_taxonomy', 'hefe_shortcode_taxonomy');
	add_shortcode('hefe_taxonomy', 'hefe_shortcode_taxonomy');
	function hefe_shortcode_taxonomy($atts, $content = null){
		$a = shortcode_atts(array(
			'id' => '',
			'class' => '',
			'page_id' => '',
			'taxonomy' => '',
			'etc' => '',
		), $atts);
		$hefe_page_id = '';
		if(esc_attr($a['page_id'])){
			$hefe_page_id = esc_attr($a['page_id']);
		}else{
			$hefe_page_id = get_the_ID();
		}
		$post_taxonomy = get_the_terms($hefe_page_id, esc_attr($a['taxonomy']));
		$hefe_taxonomy = '';
		if($post_taxonomy){
			$hefe_taxonomy .= '<ul id="'.esc_attr($a['id']).'" class="hefe-page-taxonomy '.esc_attr($a['class']).'" '.esc_attr($a['etc']).'>';
			foreach($post_taxonomy as $taxonomy){
				$hefe_taxonomy_url = get_term_link($taxonomy->term_id);
				$hefe_taxonomy .= '<li><a href="'.$hefe_taxonomy_url.'">'.$taxonomy->name.'</a></li>'; 
			}
			$hefe_taxonomy .= '</ul>';
		}
		return $hefe_taxonomy;
	}
}
// Page Title
if(!function_exists('hefe_shortcode_page_title')){
	if(get_option('hefe_control_customizer_control_shortcode_name')){
		add_shortcode(hefe_shortcode_name.'_title', 'hefe_shortcode_page_title');
		add_shortcode(hefe_shortcode_name.'_page_title', 'hefe_shortcode_page_title');
	}
	add_shortcode('hefe_page_title', 'hefe_shortcode_page_title');
	add_shortcode('hefe_title', 'hefe_shortcode_page_title');
	function hefe_shortcode_page_title($atts, $content = null){
		$a = shortcode_atts(array(
			'characters' => '',
			'page_id' => '',
		), $atts);
		if(esc_attr($a['page_id']) != ''){
			if(esc_attr($a['characters']) != ''){
				$post = get_post(esc_attr($a['page_id']));
				$title = strip_shortcodes($post->post_title);
				if(strlen($title) > esc_attr($a['characters'])){
					return substr($title, 0, esc_attr($a['characters'])).'...';
				}else{
					return $title;
				}
			}else{
				$post = get_post(esc_attr($a['page_id']));
				return apply_filters('the_title', $post->post_title);
			}
		}else{
			if(esc_attr($a['characters']) != ''){
				global $post;
				$title = strip_shortcodes($post->post_title);
				if(strlen($title) > esc_attr($a['characters'])){
					return substr($title, 0, $hefe_query_characters).'...';
				}else{
					return $title;
				}
			}else{
				global $post;
				return apply_filters('the_title', $post->post_title);
			}
		}
	}
}
// Page URL
if(!function_exists('hefe_shortcode_page_url')){
	if(get_option('hefe_control_customizer_control_shortcode_name')){
		add_shortcode(hefe_shortcode_name.'_page_url', 'hefe_shortcode_page_url');
		add_shortcode(hefe_shortcode_name.'_page_permalink', 'hefe_shortcode_page_url');
		add_shortcode(hefe_shortcode_name.'_permalink_url', 'hefe_shortcode_page_url');
	}
	add_shortcode('hefe_page_url', 'hefe_shortcode_page_url');
	add_shortcode('hefe_page_permalink', 'hefe_shortcode_page_url');
	add_shortcode('hefe_permalink_url', 'hefe_shortcode_page_url');
	function hefe_shortcode_page_url($atts, $content = null){
		$a = shortcode_atts(array(
			'page_id' => '',
		), $atts);
		return get_permalink(esc_attr($a['page_id']));
	}
}
// Page URL Link
if(!function_exists('hefe_shortcode_page_url_link')){
	if(get_option('hefe_control_customizer_control_shortcode_name')){
		add_shortcode(hefe_shortcode_name.'_page_url_link', 'hefe_shortcode_page_url_link');
		add_shortcode(hefe_shortcode_name.'_page_permalink_link', 'hefe_shortcode_page_url_link');
		add_shortcode(hefe_shortcode_name.'_permalink_link', 'hefe_shortcode_page_url_link');
	}
	add_shortcode('hefe_page_url_link', 'hefe_shortcode_page_url_link');
	add_shortcode('hefe_page_permalink_link', 'hefe_shortcode_page_url_link');
	add_shortcode('hefe_permalink_link', 'hefe_shortcode_page_url_link');
	function hefe_shortcode_page_url_link($atts, $content = null){
		$a = shortcode_atts(array(
			'id' => '',
			'class' => '',
			'page_id' => '',
			'etc' => '',
		), $atts);
		return '<a id="'.esc_attr($a['id']).'" class="hefe-page-url-link '.esc_attr($a['class']).'" href="'.get_permalink(esc_attr($a['page_id'])).'" '.esc_attr($a['etc']).'>'.do_shortcode($content).'</a>';
	}
}
// Pop Out Sidebar Link
if(!function_exists('hefe_shortcode_pop_out_sidebar_link')){
	if(get_option('hefe_control_customizer_control_shortcode_name')){
		add_shortcode(hefe_shortcode_name.'_pop_out_widgets_link', 'hefe_shortcode_pop_out_sidebar_link');
		add_shortcode(hefe_shortcode_name.'_pop_out_sidebar_link', 'hefe_shortcode_pop_out_sidebar_link');
		add_shortcode(hefe_shortcode_name.'_pop_out_sidebar', 'hefe_shortcode_pop_out_sidebar_link');
	}
	add_shortcode('hefe_pop_out_widgets_link', 'hefe_shortcode_pop_out_sidebar_link');
	add_shortcode('hefe_pop_out_sidebar_link', 'hefe_shortcode_pop_out_sidebar_link');
	add_shortcode('hefe_pop_out_sidebar', 'hefe_shortcode_pop_out_sidebar_link');
	function hefe_shortcode_pop_out_sidebar_link($atts, $content = null){
		wp_enqueue_script('hefe-pop-out-sidebar-script');
		add_action('wp_footer', 'hefe_pop_out_sidebar_footer_inc');
		$a = shortcode_atts(array(
			'id' => '',
			'class' => '',
			'side' => '',
			'style' => '',
			'etc' => '',
		), $atts);
		$hefe_pop_out_sidebar_link_side = 'right';
		if(esc_attr($a['side']) != ''){
			$hefe_pop_out_sidebar_link_side = strtolower(esc_attr($a['side']));
		}
		$style = '';
		if(esc_attr($a['style']) != 'none'){
			if(esc_attr($a['style'])){
				wp_enqueue_style('hefe-pop-out-sidebar-style-'.esc_attr($a['style']));
			}else{
				wp_enqueue_style('hefe-pop-out-sidebar-style');
			}
		}
		return '<a href="javascript:;" id="'.esc_attr($a['id']).'" class="hefe-pop-out-sidebar-link hefe-pop-out-sidebar-toggle '.esc_attr($a['class']).'" data-side="'.$hefe_pop_out_sidebar_link_side.'" '.esc_attr($a['etc']).'>'.do_shortcode($content).'</a>';
	}
}
// Post Type Title
if(!function_exists('hefe_shortcode_post_type_title')){
	if(get_option('hefe_control_customizer_control_shortcode_name')){
		add_shortcode(hefe_shortcode_name.'_post_type_title', 'hefe_shortcode_post_type_title');
	}
	add_shortcode('hefe_post_type_title', 'hefe_shortcode_post_type_title');
	function hefe_shortcode_post_type_title($atts, $content = null){
		$a = shortcode_atts(array(
			'post_type_id' => '',
		), $atts);
		$post_type = get_post_type(esc_attr($a['post_type_id']));
		$post_type_object = get_post_type_object($post_type);
		return $post_type_object->labels->singular_name;
	}
}
// Random Display Parent
if(!function_exists('hefe_shortcode_random_display_parent')){
	if(get_option('hefe_control_customizer_control_shortcode_name')){
		add_shortcode(hefe_shortcode_name.'_random_display_parent', 'hefe_shortcode_random_display_parent');
	}
	add_shortcode('hefe_random_display_parent', 'hefe_shortcode_random_display_parent');
	function hefe_shortcode_random_display_parent($atts, $content = null){
		wp_enqueue_script('hefe-random-display-script');
		$a = shortcode_atts(array(
			'id' => '',
			'class' => '',
			'etc' => '',
		), $atts);
		return '<div id="'.esc_attr($a['id']).'" class="hefe-random-display-parent '.esc_attr($a['class']).'" '.esc_attr($a['etc']).'>'.do_shortcode($content).'</div>';
	}
}
// Random Display Child
if(!function_exists('hefe_shortcode_random_display_child')){
	if(get_option('hefe_control_customizer_control_shortcode_name')){
		add_shortcode(hefe_shortcode_name.'_random_display_child', 'hefe_shortcode_random_display_child');
	}
	add_shortcode('hefe_random_display_child', 'hefe_shortcode_random_display_child');
	function hefe_shortcode_random_display_child($atts, $content = null){
		wp_enqueue_script('hefe-random-display-script');
		$a = shortcode_atts(array(
			'id' => '',
			'class' => '',
			'etc' => '',
		), $atts);
		return '<div id="'.esc_attr($a['id']).'" class="hefe-random-display-child '.esc_attr($a['class']).'" '.esc_attr($a['etc']).'>'.do_shortcode($content).'</div>';
	}
}
// Random Order Parent
if(!function_exists('hefe_shortcode_random_order_parent')){
	if(get_option('hefe_control_customizer_control_shortcode_name')){
		add_shortcode(hefe_shortcode_name.'_random_order_parent', 'hefe_shortcode_random_order_parent');
	}
	add_shortcode('hefe_random_order_parent', 'hefe_shortcode_random_order_parent');
	function hefe_shortcode_random_order_parent($atts, $content = null){
		wp_enqueue_script('hefe-random-order-script');
		$a = shortcode_atts(array(
			'id' => '',
			'class' => '',
			'etc' => '',
		), $atts);
		return '<div id="'.esc_attr($a['id']).'" class="hefe-random-order-parent '.esc_attr($a['class']).'" '.esc_attr($a['etc']).'>'.do_shortcode($content).'</div>';
	}
}
// Random Order Child
if(!function_exists('hefe_shortcode_random_order_child')){
	if(get_option('hefe_control_customizer_control_shortcode_name')){
		add_shortcode(hefe_shortcode_name.'_random_order_child', 'hefe_shortcode_random_order_child');
	}
	add_shortcode('hefe_random_order_child', 'hefe_shortcode_random_order_child');
	function hefe_shortcode_random_order_child($atts, $content = null){
		wp_enqueue_script('hefe-random-order-script');
		$a = shortcode_atts(array(
			'id' => '',
			'class' => '',
			'etc' => '',
		), $atts);
		return '<div id="'.esc_attr($a['id']).'" class="hefe-random-order-child '.esc_attr($a['class']).'" '.esc_attr($a['etc']).'>'.do_shortcode($content).'</div>';
	}
}
// Register Link
if(!function_exists('hefe_shortcode_register_link')){
	if(get_option('hefe_control_customizer_control_shortcode_name')){
		add_shortcode(hefe_shortcode_name.'_register_link', 'hefe_shortcode_register_link');
	}
	add_shortcode('hefe_register_link', 'hefe_shortcode_register_link');
	function hefe_shortcode_register_link($atts, $content = null){
		$a = shortcode_atts(array(
			'id' => '',
			'class' => '',
			'redirect' => '',
			'etc' => '',
		), $atts);
		return '<a id="'.esc_attr($a['id']).'" href="'.wp_login_url(esc_attr($a['redirect'])).'?action=register" class="hefe-login-link '.esc_attr($a['class']).'" '.esc_attr($a['etc']).'>'.do_shortcode($content).'</a>';
	}
}
// Related Article
if(!function_exists('hefe_shortcode_related_article')){
	if(get_option('hefe_control_customizer_control_shortcode_name')){
		add_shortcode(hefe_shortcode_name.'_related_article', 'hefe_shortcode_related_article');
		add_shortcode(hefe_shortcode_name.'_related_articles', 'hefe_shortcode_related_article');
	}
	add_shortcode('hefe_related_article', 'hefe_shortcode_related_article');
	add_shortcode('hefe_related_articles', 'hefe_shortcode_related_article');
	function hefe_shortcode_related_article($atts, $content = null){
		$a = shortcode_atts(array(
			'id' => '',
			'class' => '',
			'post_type' => '',
			'page_id' => '',
			'cat' => '',
			'posts_per_page' => '',
			'order' => '',
			'orderby' => '',
			'post_not_in' => '',
			'style' => '',
			'etc' => '',
		), $atts);
		$style = '';
		if(esc_attr($a['style'])){
			$style = 'hefe-related-article-style-'.esc_attr($a['style']);
			wp_enqueue_style('hefe-related-article-style-'.esc_attr($a['style']));
		}
		$args = array();
		if(esc_attr($a['cat'])){
			$args['category__in'] = explode(',', str_replace(' ', '', strtolower(esc_attr($a['cat']))));
		}else{
			$categories = get_the_category();
			$args['cat'] = $categories[0]->cat_ID;
		}
		if(esc_attr($a['page_id'])){
			$args['page_id'] = esc_attr($a['page_id']);
		}
		if(esc_attr($a['post_type'])){
			$args['post_type'] = explode(',', str_replace(' ', '', strtolower(esc_attr($a['post_type']))));
		}else{
			$args['post_type'] = 'post';
		}
		if(esc_attr($a['posts_per_page'])){
			$args['posts_per_page'] = esc_attr($a['posts_per_page']);
		}else{
			$args['posts_per_page'] = '1';
		}
		if(esc_attr($a['order'])){
			$args['order'] = esc_attr($a['order']);
		}else{
			$args['order'] = 'asc';
		}
		if(esc_attr($a['orderby'])){
			$args['orderby'] = esc_attr($a['orderby']);
		}else{
			$args['orderby'] = 'date';
		}
		if(esc_attr($a['post_not_in'])){
			$args['post__not_in'] = explode(',', str_replace(' ', '', strtolower(esc_attr($a['post_not_in']))));
		}else{
			$args['post__not_in'] = array(get_the_ID());
		}
		$the_query = new WP_Query($args);
		$content = '';
		$content .= '<div id="'.esc_attr($a['id']).'" class="hefe-related-article-wrap '.$style.' postid-'.get_the_ID().' '.esc_attr($a['class']).'" '.esc_attr($a['etc']).'>';
			$content .= '<div class="hefe-related-article-title">Related Articles</div>';
			$content .= '<div class="hefe-related-article-content">';
			if($the_query->have_posts()) { 
				while($the_query->have_posts()) { 
					$the_query->the_post();
					$content .= '<div class="hefe-related-article-content-wrap postid-'.get_the_ID().'">';
						$url = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), 'medium'); 
						if($url){
							$content .= '<a href="'.get_permalink().'"><img class="featured-image" src="'.$url[0].'" title="'.get_the_title().'" alt="'.get_the_title().'" width="100%" /></a>';
						}
						if(get_the_title()){
							$content .= '<h3 class="title-txt"><a href="'.get_permalink().'">'.get_the_title().'</a></h3>';
						}
						if(get_the_excerpt()){
							$content .= '<p>'.wp_trim_words(get_the_excerpt(), $num_words = 12).'</p>';
						}
						$content .= '<div class="hefe-related-article-button-wrap"><a href="'.get_permalink().'" class="here-related-article-button">Continue Reading</a></div>';
					$content .= '</div>';
				} 
				wp_reset_postdata(); 
			}
			$content .= '</div>';
		$content .= '</div>';
		return $content;
	}
}
// Reveal Parent
if(!function_exists('hefe_shortcode_reveal_parent')){
	if(get_option('hefe_control_customizer_control_shortcode_name')){
		add_shortcode(hefe_shortcode_name.'_reveal_parent', 'hefe_shortcode_reveal_parent');
	}
	add_shortcode('hefe_reveal_parent', 'hefe_shortcode_reveal_parent');
	function hefe_shortcode_reveal_parent($atts, $content = null){
		wp_enqueue_style('hefe-reveal-style');
		wp_enqueue_script('hefe-reveal-script');
		$a = shortcode_atts(array(
			'id' => '',
			'class' => '',
			'etc' => '',
		), $atts);
		return '<div id="'.esc_attr($a['id']).'" class="hefe-reveal-parent '.esc_attr($a['class']).'" '.esc_attr($a['etc']).'>'.do_shortcode($content).'</div>';
	}
}
// Reveal Child
if(!function_exists('hefe_shortcode_reveal_child')){
	if(get_option('hefe_control_customizer_control_shortcode_name')){
		add_shortcode(hefe_shortcode_name.'_reveal_child_over', 'hefe_shortcode_reveal_child');
	}
	add_shortcode('hefe_reveal_child', 'hefe_shortcode_reveal_child');
	function hefe_shortcode_reveal_child($atts, $content = null){
		wp_enqueue_style('hefe-reveal-style');
		wp_enqueue_script('hefe-reveal-script');
		$a = shortcode_atts(array(
			'id' => '',
			'class' => '',
			'position' => '',
			'etc' => '',
		), $atts);
		if(strtolower(esc_attr($a['position'])) == 'over'){
			$hefe_reveal_child_position = 'hefe-reveal-child-over';
		}elseif(strtolower(esc_attr($a['position'])) == 'under'){
			$hefe_reveal_child_position = 'hefe-reveal-child-under';
		}else{
			$hefe_reveal_child_position = 'hefe-reveal-child-over';
		}
		return '<div id="'.esc_attr($a['id']).'" class="hefe-reveal-child '.$hefe_reveal_child_position.' '.esc_attr($a['class']).'" '.esc_attr($a['etc']).'>'.do_shortcode($content).'</div>';

	}
}
// Scroll To Link
if(!function_exists('hefe_shortcode_scroll_to_link')){
	if(get_option('hefe_control_customizer_control_shortcode_name')){
		add_shortcode(hefe_shortcode_name.'_scroll_to_link', 'hefe_shortcode_scroll_to_link');
		add_shortcode(hefe_shortcode_name.'_click_scroll_link', 'hefe_shortcode_scroll_to_link');
	}
	add_shortcode('hefe_scroll_to_link', 'hefe_shortcode_scroll_to_link');
	add_shortcode('hefe_click_scroll_link', 'hefe_shortcode_scroll_to_link');
	function hefe_shortcode_scroll_to_link($atts, $content = null){
		wp_enqueue_style('hefe-scroll-to-style');
		wp_enqueue_script('hefe-scroll-to-script');
		$a = shortcode_atts(array(
			'id' => '',
			'class' => '',
			'paired_id' => '',
			'wrap' => '',
			'etc' => '',
		), $atts);
		if(esc_attr($a['paired_id'])){
			$paired_id = esc_attr($a['paired_id']);
		}else{
			$paired_id = get_the_ID();
		}
		if(esc_attr($a['wrap'])){
			$wrap = esc_attr($a['wrap']);
		}else{
			$wrap = 'div';
		}
		return '<'.$wrap.' id="'.esc_attr($a['id']).'" class="hefe-scroll-to-link '.esc_attr($a['class']).'" data-paired="'.$paired_id.'" '.esc_attr($a['etc']).'>'.do_shortcode($content).'</'.$wrap.'>';
	}
}
// Scroll To Content
if(!function_exists('hefe_shortcode_scroll_to_content')){
	if(get_option('hefe_control_customizer_control_shortcode_name')){
		add_shortcode(hefe_shortcode_name.'_scroll_to_content', 'hefe_shortcode_scroll_to_content');
		add_shortcode(hefe_shortcode_name.'_click_scroll_content', 'hefe_shortcode_scroll_to_content');
	}
	add_shortcode('hefe_scroll_to_content', 'hefe_shortcode_scroll_to_content');
	add_shortcode('hefe_click_scroll_content', 'hefe_shortcode_scroll_to_content');
	function hefe_shortcode_scroll_to_content($atts, $content = null){
		wp_enqueue_style('hefe-scroll-to-style');
		wp_enqueue_script('hefe-scroll-to-script');
		$a = shortcode_atts(array(
			'id' => '',
			'class' => '',
			'paired_id' => '',
			'etc' => '',
		), $atts);
		if(esc_attr($a['paired_id'])){
			$paired_id = esc_attr($a['paired_id']);
		}else{
			$paired_id = get_the_ID();
		}
		return '<div id="'.esc_attr($a['id']).'" class="hefe-scroll-to-content '.esc_attr($a['class']).'" data-paired="'.$paired_id.'" '.esc_attr($a['etc']).'>'.do_shortcode($content).'</div>';
	}
}
// Scroll Up Box Item
if(!function_exists('hefe_shortcode_scroll_up_box_item')){
	if(get_option('hefe_control_customizer_control_shortcode_name')){
		add_shortcode(hefe_shortcode_name.'_scroll_up_box', 'hefe_shortcode_scroll_up_box_item');
	}
	add_shortcode('hefe_scroll_up_box', 'hefe_shortcode_scroll_up_box_item');
	function hefe_shortcode_scroll_up_box_item($atts, $content = null){
		wp_enqueue_script('hefe-scroll-up-box-script');
		$a = shortcode_atts(array(
			'id' => '',
			'class' => '',
			'automatic' => '',
			'etc' => '',
		), $atts);
		if($content){
			$hefe_scroll_up_box_icon = do_shortcode($content);
		}else{
			$hefe_scroll_up_box_icon = '⇡';
		}
		$hefe_scroll_up_box_automatic = '';
		if(esc_attr($a['automatic']) == 'false' || esc_attr($a['automatic']) == ''){
			$hefe_scroll_up_box_automatic = '';
		}else{
			$hefe_scroll_up_box_automatic = 'hefe-scroll-up-box-automatic';
		}
		return '<div id="'.esc_attr($a['id']).'" class="hefe-scroll-up-box '.$hefe_scroll_up_box_automatic.' '.esc_attr($a['class']).'" '.esc_attr($a['etc']).'>'.$hefe_scroll_up_box_icon.'</div>';
	}
}
// Search Form
if(!function_exists('hefe_shortcode_search_form')){
	if(get_option('hefe_control_customizer_control_shortcode_name')){
		add_shortcode(hefe_shortcode_name.'_search_form', 'hefe_shortcode_search_form');
	}
	add_shortcode('hefe_search_form', 'hefe_shortcode_search_form');
	function hefe_shortcode_search_form($atts, $content = null){
		return get_search_form($echo = false);
	}
}
// Search Modal Link
if(!function_exists('hefe_shortcode_search_modal_link')){
	if(get_option('hefe_control_customizer_control_shortcode_name')){
		add_shortcode(hefe_shortcode_name.'_search_modal_link', 'hefe_shortcode_search_modal_link');
	}
	add_shortcode('hefe_search_modal_link', 'hefe_shortcode_search_modal_link');
	function hefe_shortcode_search_modal_link($atts, $content = null){
		wp_enqueue_style('hefe-search-modal-style');
		wp_enqueue_script('hefe-search-modal-script');
		add_action('wp_footer', 'hefe_search_modal_footer_inc');
		$a = shortcode_atts(array(
			'id' => '',
			'class' => '',
			'etc' => '',
		), $atts);
		return '<a id="'.esc_attr($a['id']).'" class="hefe-search-modal-link hefe-search-modal-toggle-in '.esc_attr($a['class']).'" '.esc_attr($a['etc']).'>'.do_shortcode($content).'</a>';
	}
}
// Search Query
if(!function_exists('hefe_shortcode_search_query')){
	if(get_option('hefe_control_customizer_control_shortcode_name')){
		add_shortcode(hefe_shortcode_name.'_search_query', 'hefe_shortcode_search_query');
	}
	add_shortcode('hefe_search_query', 'hefe_shortcode_search_query');
	function hefe_shortcode_search_query($atts, $content = null){
		return get_search_query();
	}
}
// Sidebar 01
if(!function_exists('hefe_shortcode_sidebar_01')){
	if(get_option('hefe_control_customizer_control_shortcode_name')){
		add_shortcode(hefe_shortcode_name.'_widgets_01', 'hefe_shortcode_sidebar_01');
		add_shortcode(hefe_shortcode_name.'_sidebar_01', 'hefe_shortcode_sidebar_01');
		add_shortcode(hefe_shortcode_name.'_sidebars_01', 'hefe_shortcode_sidebar_01');
	}
	add_shortcode('hefe_widgets_01', 'hefe_shortcode_sidebar_01');
	add_shortcode('hefe_sidebar_01', 'hefe_shortcode_sidebar_01');
	add_shortcode('hefe_sidebars_01', 'hefe_shortcode_sidebar_01');
	function hefe_shortcode_sidebar_01($atts, $content = null){
		$a = shortcode_atts(array(
			'id' => '',
			'class' => '',
			'etc' => '',
		), $atts);
		$hefe_sidebar_01 = '<div id="'.esc_attr($a['id']).'" class="hefe-sidebar-01 '.esc_attr($a['class']).'" '.esc_attr($a['etc']).'>';
			ob_start();
			include hefe_dir_path.'php/widgets-01.php';
			$hefe_sidebar_01 .= ob_get_contents();
			ob_end_clean();
		$hefe_sidebar_01 .= '</div>';
		return $hefe_sidebar_01;
	}
}
// Sidebar 02
if(!function_exists('hefe_shortcode_sidebar_02')){
	if(get_option('hefe_control_customizer_control_shortcode_name')){
		add_shortcode(hefe_shortcode_name.'_widgets_02', 'hefe_shortcode_sidebar_02');
		add_shortcode(hefe_shortcode_name.'_sidebar_02', 'hefe_shortcode_sidebar_02');
		add_shortcode(hefe_shortcode_name.'_sidebars_02', 'hefe_shortcode_sidebar_02');
	}
	add_shortcode('hefe_widgets_02', 'hefe_shortcode_sidebar_02');
	add_shortcode('hefe_sidebar_02', 'hefe_shortcode_sidebar_02');
	add_shortcode('hefe_sidebars_02', 'hefe_shortcode_sidebar_02');
	function hefe_shortcode_sidebar_02($atts, $content = null){
		$a = shortcode_atts(array(
			'id' => '',
			'class' => '',
			'etc' => '',
		), $atts);
		$hefe_sidebar_02 = '<div id="'.esc_attr($a['id']).'" class="hefe-sidebar-02 '.esc_attr($a['class']).'" '.esc_attr($a['etc']).'>';
			ob_start();
			include hefe_dir_path.'php/widgets-02.php';
			$hefe_sidebar_02 .= ob_get_contents();
			ob_end_clean();
		$hefe_sidebar_02 .= '</div>';
		return $hefe_sidebar_02;
	}
}
// Sidebar 03
if(!function_exists('hefe_shortcode_sidebar_03')){
	if(get_option('hefe_control_customizer_control_shortcode_name')){
		add_shortcode(hefe_shortcode_name.'_widgets_03', 'hefe_shortcode_sidebar_03');
		add_shortcode(hefe_shortcode_name.'_sidebar_03', 'hefe_shortcode_sidebar_03');
		add_shortcode(hefe_shortcode_name.'_sidebars_03', 'hefe_shortcode_sidebar_03');
	}
	add_shortcode('hefe_widgets_03', 'hefe_shortcode_sidebar_03');
	add_shortcode('hefe_sidebar_03', 'hefe_shortcode_sidebar_03');
	add_shortcode('hefe_sidebars_03', 'hefe_shortcode_sidebar_03');
	function hefe_shortcode_sidebar_03($atts, $content = null){
		$a = shortcode_atts(array(
			'id' => '',
			'class' => '',
			'etc' => '',
		), $atts);
		$hefe_sidebar_03 = '<div id="'.esc_attr($a['id']).'" class="hefe-sidebar-03 '.esc_attr($a['class']).'" '.esc_attr($a['etc']).'>';
			ob_start();
			include hefe_dir_path.'php/widgets-03.php';
			$hefe_sidebar_03 .= ob_get_contents();
			ob_end_clean();
		$hefe_sidebar_03 .= '</div>';
		return $hefe_sidebar_03;
	}
}
// Sidebar 04
if(!function_exists('hefe_shortcode_sidebar_04')){
	if(get_option('hefe_control_customizer_control_shortcode_name')){
		add_shortcode(hefe_shortcode_name.'_widgets_04', 'hefe_shortcode_sidebar_04');
		add_shortcode(hefe_shortcode_name.'_sidebar_04', 'hefe_shortcode_sidebar_04');
		add_shortcode(hefe_shortcode_name.'_sidebars_04', 'hefe_shortcode_sidebar_04');
	}
	add_shortcode('hefe_widgets_04', 'hefe_shortcode_sidebar_04');
	add_shortcode('hefe_sidebar_04', 'hefe_shortcode_sidebar_04');
	add_shortcode('hefe_sidebars_04', 'hefe_shortcode_sidebar_04');
	function hefe_shortcode_sidebar_04($atts, $content = null){
		$a = shortcode_atts(array(
			'id' => '',
			'class' => '',
			'etc' => '',
		), $atts);
		$hefe_sidebar_04 = '<div id="'.esc_attr($a['id']).'" class="hefe-sidebar-04 '.esc_attr($a['class']).'" '.esc_attr($a['etc']).'>';
			ob_start();
			include hefe_dir_path.'php/widgets-04.php';
			$hefe_sidebar_04 .= ob_get_contents();
			ob_end_clean();
		$hefe_sidebar_04 .= '</div>';
		return $hefe_sidebar_04;
	}
}
// Sidebar 05
if(!function_exists('hefe_shortcode_sidebar_05')){
	if(get_option('hefe_control_customizer_control_shortcode_name')){
		add_shortcode(hefe_shortcode_name.'_widgets_05', 'hefe_shortcode_sidebar_05');
		add_shortcode(hefe_shortcode_name.'_sidebar_05', 'hefe_shortcode_sidebar_05');
		add_shortcode(hefe_shortcode_name.'_sidebars_05', 'hefe_shortcode_sidebar_05');
	}
	add_shortcode('hefe_widgets_05', 'hefe_shortcode_sidebar_05');
	add_shortcode('hefe_sidebar_05', 'hefe_shortcode_sidebar_05');
	add_shortcode('hefe_sidebars_05', 'hefe_shortcode_sidebar_05');
	function hefe_shortcode_sidebar_05($atts, $content = null){
		$a = shortcode_atts(array(
			'id' => '',
			'class' => '',
			'etc' => '',
		), $atts);
		$hefe_sidebar_05 = '<div id="'.esc_attr($a['id']).'" class="hefe-sidebar-05 '.esc_attr($a['class']).'" '.esc_attr($a['etc']).'>';
			ob_start();
			include hefe_dir_path.'php/widgets-05.php';
			$hefe_sidebar_05 .= ob_get_contents();
			ob_end_clean();
		$hefe_sidebar_05 .= '</div>';
		return $hefe_sidebar_05;
	}
}
// Sidebar 06
if(!function_exists('hefe_shortcode_sidebar_06')){
	if(get_option('hefe_control_customizer_control_shortcode_name')){
		add_shortcode(hefe_shortcode_name.'_widgets_06', 'hefe_shortcode_sidebar_06');
		add_shortcode(hefe_shortcode_name.'_sidebar_06', 'hefe_shortcode_sidebar_06');
		add_shortcode(hefe_shortcode_name.'_sidebars_06', 'hefe_shortcode_sidebar_06');
	}
	add_shortcode('hefe_widgets_06', 'hefe_shortcode_sidebar_06');
	add_shortcode('hefe_sidebar_06', 'hefe_shortcode_sidebar_06');
	add_shortcode('hefe_sidebars_06', 'hefe_shortcode_sidebar_06');
	function hefe_shortcode_sidebar_06($atts, $content = null){
		$a = shortcode_atts(array(
			'id' => '',
			'class' => '',
			'etc' => '',
		), $atts);
		$hefe_sidebar_06 = '<div id="'.esc_attr($a['id']).'" class="hefe-sidebar-06 '.esc_attr($a['class']).'" '.esc_attr($a['etc']).'>';
			ob_start();
			include hefe_dir_path.'php/widgets-06.php';
			$hefe_sidebar_06 .= ob_get_contents();
			ob_end_clean();
		$hefe_sidebar_06 .= '</div>';
		return $hefe_sidebar_06;
	}
}
// Simple Carousel Parent
if(!function_exists('hefe_shortcode_simple_carousel_parent')){
	if(get_option('hefe_control_customizer_control_shortcode_name')){
		add_shortcode(hefe_shortcode_name.'_simple_carousel_parent', 'hefe_shortcode_simple_carousel_parent');
	}
	add_shortcode('hefe_simple_carousel_parent', 'hefe_shortcode_simple_carousel_parent');
	function hefe_shortcode_simple_carousel_parent($atts, $content = null){
		wp_enqueue_script('hefe-simple-carousel-script');
		$a = shortcode_atts(array(
			'id' => '',
			'class' => '',
			'id_number' => '',
			'speed' => '',
			'etc' => '',
		), $atts);
		if(esc_attr($a['id_number'])){
			$hefe_simple_carousel_id_class = 'hefe-simple-carousel-parent-'.esc_attr($a['id_number']);
			add_action('wp_footer', function() use ($atts){
				if($atts['id_number']){
					$hefe_simple_carousel_shortcode_id_number = $atts['id_number'];
				}else{
					$hefe_simple_carousel_shortcode_id_number = 'default';
				}
				if($atts['speed']){
					$hefe_simple_carousel_shortcode_speed = $atts['speed'];
				}else{
					$hefe_simple_carousel_shortcode_speed = '600';
				}
				echo '<script>(function($) {$(".hefe-simple-carousel-parent.hefe-simple-carousel-parent-'.$hefe_simple_carousel_shortcode_id_number.' > .hefe-simple-carousel-child:gt(0)").hide();setInterval(function() {$(".hefe-simple-carousel-parent.hefe-simple-carousel-parent-'.$hefe_simple_carousel_shortcode_id_number.' > .hefe-simple-carousel-child:first").hide().next().fadeIn(500).end().appendTo(".hefe-simple-carousel-parent.hefe-simple-carousel-parent-'.$hefe_simple_carousel_shortcode_id_number.'");}, '.$hefe_simple_carousel_shortcode_speed.');})(jQuery);</script>';
	        }, 999999);
		}else{
			$hefe_simple_carousel_id_class = 'hefe-simple-carousel-parent-default';
		}
		return '<div id="'.esc_attr($a['id']).'" class="hefe-simple-carousel-parent '.$hefe_simple_carousel_id_class.' '.esc_attr($a['class']).'" '.esc_attr($a['etc']).'>'.do_shortcode($content).'</div>';
	}
}
// Simple Carousel Child
if(!function_exists('hefe_shortcode_simple_carousel_child')){
	if(get_option('hefe_control_customizer_control_shortcode_name')){
		add_shortcode(hefe_shortcode_name.'_simple_carousel_child', 'hefe_shortcode_simple_carousel_child');
	}
	add_shortcode('hefe_simple_carousel_child', 'hefe_shortcode_simple_carousel_child');
	function hefe_shortcode_simple_carousel_child($atts, $content = null){
		wp_enqueue_script('hefe-simple-carousel-script');
		$a = shortcode_atts(array(
			'id' => '',
			'class' => '',
			'etc' => '',
		), $atts);
		return '<div id="'.esc_attr($a['id']).'" class="hefe-simple-carousel-child '.esc_attr($a['class']).'" '.esc_attr($a['etc']).'>'.do_shortcode($content).'</div>';
	}
}
// Site Description
if(!function_exists('hefe_shortcode_site_description')){
	if(get_option('hefe_control_customizer_control_shortcode_name')){
		add_shortcode(hefe_shortcode_name.'_site_description', 'hefe_shortcode_site_description');
	}
	add_shortcode('hefe_site_description', 'hefe_shortcode_site_description');
	function hefe_shortcode_site_description($atts, $content = null){
		return get_bloginfo('description');
	}
}
// Site Logo
if(!function_exists('hefe_shortcode_site_logo')){
	if(get_option('hefe_control_customizer_control_shortcode_name')){
		add_shortcode(hefe_shortcode_name.'_site_logo', 'hefe_shortcode_site_logo');
	}
	add_shortcode('hefe_site_logo', 'hefe_shortcode_site_logo');
	function hefe_shortcode_site_logo($atts, $content = null){
		$a = shortcode_atts(array(
			'id' => '',
			'class' => '',
			'alt' => '',
			'title' => '',
			'width' => '',
			'etc' => '',
		), $atts);
		return '<img src="'.wp_get_attachment_image_url(get_theme_mod('custom_logo'), 'full').'" id="'.esc_attr($a['id']).'" class="hefe-site-logo '.esc_attr($a['class']).'" '.esc_attr($a['etc']).' title="'.esc_attr($a['title']).'" alt="'.esc_attr($a['alt']).'" width="'.esc_attr($a['width']).'" />';
	}
}
// Site Title
if(!function_exists('hefe_shortcode_site_title')){
	if(get_option('hefe_control_customizer_control_shortcode_name')){
		add_shortcode(hefe_shortcode_name.'_site_title', 'hefe_shortcode_site_title');
	}
	add_shortcode('hefe_site_title', 'hefe_shortcode_site_title');
	function hefe_shortcode_site_title($atts, $content = null){
		return get_bloginfo('name');
	}
}
// Site URL
if(!function_exists('hefe_shortcode_site_url')){
	if(get_option('hefe_control_customizer_control_shortcode_name')){
		add_shortcode(hefe_shortcode_name.'_site_url', 'hefe_shortcode_site_url');
	}
	add_shortcode('hefe_site_url', 'hefe_shortcode_site_url');
	function hefe_shortcode_site_url($atts, $content = null){
		return get_site_url();
	}
}
// Site URL Link
if(!function_exists('hefe_shortcode_site_url_link')){
	if(get_option('hefe_control_customizer_control_shortcode_name')){
		add_shortcode(hefe_shortcode_name.'_site_url_link', 'hefe_shortcode_site_url_link');
	}
	add_shortcode('hefe_site_url_link', 'hefe_shortcode_site_url_link');
	function hefe_shortcode_site_url_link($atts, $content = null){
		$a = shortcode_atts(array(
			'id' => '',
			'class' => '',
			'etc' => '',
		), $atts);
		$hefe_site_url = get_site_url();
		return '<a id="'.esc_attr($a['id']).'" class="hefe-site-url-link '.esc_attr($a['class']).'" href="'.$hefe_site_url.'" '.esc_attr($a['etc']).'>'.do_shortcode($content).'</a>';
	}
}
// Social Share Link
if(!function_exists('hefe_shortcode_social_share_link')){
	if(get_option('hefe_control_customizer_control_shortcode_name')){
		add_shortcode(hefe_shortcode_name.'_social_share_link', 'hefe_shortcode_social_share_link');
		add_shortcode(hefe_shortcode_name.'_share_link', 'hefe_shortcode_social_share_link');
	}
	add_shortcode('hefe_social_share_link', 'hefe_shortcode_social_share_link');
	add_shortcode('hefe_share_link', 'hefe_shortcode_social_share_link');
	function hefe_shortcode_social_share_link($atts, $content = null){
		$a = shortcode_atts(array(
			'id' => '',
			'class' => '',
			'company' => '',
			'url' => '',
			'etc' => '',
		), $atts);
		if(strtolower(esc_attr($a['company'])) == 'facebook'){
			if(esc_attr($a['url'])){
				return '<a target="_blank" id="'.esc_attr($a['id']).'" class="hefe-social-share-link '.esc_attr($a['class']).'" href="https://www.facebook.com/sharer.php?u='.esc_attr($a['url']).'" '.esc_attr($a['etc']).'>'.do_shortcode($content).'</a>';
			}else{
				return '<a target="_blank" id="'.esc_attr($a['id']).'" class="hefe-social-share-link '.esc_attr($a['class']).'" href="https://www.facebook.com/sharer.php?u='.get_permalink().'" '.esc_attr($a['etc']).'>'.do_shortcode($content).'</a>';
			}
		}elseif(strtolower(esc_attr($a['company'])) == 'twitter'){
			if(esc_attr($a['url'])){
				return '<a target="_blank" id="'.esc_attr($a['id']).'" class="hefe-social-share-link '.esc_attr($a['class']).'" href="https://twitter.com/intent/tweet?url='.esc_attr($a['url']).'&text='.get_the_title().'" '.esc_attr($a['etc']).'>'.do_shortcode($content).'</a>';
			}else{
				return '<a target="_blank" id="'.esc_attr($a['id']).'" class="hefe-social-share-link '.esc_attr($a['class']).'" href="https://twitter.com/intent/tweet?url='.get_permalink().'&text='.get_the_title().'" '.esc_attr($a['etc']).'>'.do_shortcode($content).'</a>';
			}
		}elseif(strtolower(esc_attr($a['company'])) == 'google+' || strtolower(esc_attr($a['company'])) == 'googleplus' || strtolower(esc_attr($a['company'])) == 'google-plus' || strtolower(esc_attr($a['company'])) == 'google_plus' || strtolower(esc_attr($a['company'])) == 'google plus'){
			if(esc_attr($a['url'])){
				return '<a target="_blank" id="'.esc_attr($a['id']).'" class="hefe-social-share-link '.esc_attr($a['class']).'" href="https://plus.google.com/share?url='.esc_attr($a['url']).'" '.esc_attr($a['etc']).'>'.do_shortcode($content).'</a>';
			}else{
				return '<a target="_blank" id="'.esc_attr($a['id']).'" class="hefe-social-share-link '.esc_attr($a['class']).'" href="https://plus.google.com/share?url='.get_permalink().'" '.esc_attr($a['etc']).'>'.do_shortcode($content).'</a>';
			}
		}elseif(strtolower(esc_attr($a['company'])) == 'pinterest'){
			if(esc_attr($a['url'])){
				return '<a target="_blank" id="'.esc_attr($a['id']).'" class="hefe-social-share-link '.esc_attr($a['class']).'" href="https://pinterest.com/pin/create/bookmarklet/?url='.esc_attr($a['url']).'&description='.get_the_title().'" '.esc_attr($a['etc']).'>'.do_shortcode($content).'</a>';
			}else{
				return '<a target="_blank" id="'.esc_attr($a['id']).'" class="hefe-social-share-link '.esc_attr($a['class']).'" href="https://pinterest.com/pin/create/bookmarklet/?url='.get_permalink().'&description='.get_the_title().'" '.esc_attr($a['etc']).'>'.do_shortcode($content).'</a>';
			}
		}elseif(strtolower(esc_attr($a['company'])) == 'linkedin'){
			if(esc_attr($a['url'])){
				return '<a target="_blank" id="'.esc_attr($a['id']).'" class="hefe-social-share-link '.esc_attr($a['class']).'" href="https://www.linkedin.com/shareArticle?url='.esc_attr($a['url']).'&title='.get_the_title().'" '.esc_attr($a['etc']).'>'.do_shortcode($content).'</a>';
			}else{
				return '<a target="_blank" id="'.esc_attr($a['id']).'" class="hefe-social-share-link '.esc_attr($a['class']).'" href="https://www.linkedin.com/shareArticle?url='.get_permalink().'&title='.get_the_title().'" '.esc_attr($a['etc']).'>'.do_shortcode($content).'</a>';
			}
		}elseif(strtolower(esc_attr($a['company'])) == 'buffer'){
			if(esc_attr($a['url'])){
				return '<a target="_blank" id="'.esc_attr($a['id']).'" class="hefe-social-share-link '.esc_attr($a['class']).'" href="https://buffer.com/add?text='.get_the_title().'&url='.esc_attr($a['url']).'" '.esc_attr($a['etc']).'>'.do_shortcode($content).'</a>';
			}else{
				return '<a target="_blank" id="'.esc_attr($a['id']).'" class="hefe-social-share-link '.esc_attr($a['class']).'" href="https://buffer.com/add?text='.get_the_title().'&url='.get_permalink().'" '.esc_attr($a['etc']).'>'.do_shortcode($content).'</a>';
			}
		}elseif(strtolower(esc_attr($a['company'])) == 'digg'){
			if(esc_attr($a['url'])){
				return '<a target="_blank" id="'.esc_attr($a['id']).'" class="hefe-social-share-link '.esc_attr($a['class']).'" href="http://digg.com/submit?url='.esc_attr($a['url']).'&title='.get_the_title().'" '.esc_attr($a['etc']).'>'.do_shortcode($content).'</a>';
			}else{
				return '<a target="_blank" id="'.esc_attr($a['id']).'" class="hefe-social-share-link '.esc_attr($a['class']).'" href="http://digg.com/submit?url='.get_permalink().'&title='.get_the_title().'" '.esc_attr($a['etc']).'>'.do_shortcode($content).'</a>';
			}
		}elseif(strtolower(esc_attr($a['company'])) == 'tumblr'){
			if(esc_attr($a['url'])){
				return '<a target="_blank" id="'.esc_attr($a['id']).'" class="hefe-social-share-link '.esc_attr($a['class']).'" href="https://www.tumblr.com/widgets/share/tool?canonicalUrl='.esc_attr($a['url']).'&title='.get_the_title().'" '.esc_attr($a['etc']).'>'.do_shortcode($content).'</a>';
			}else{
				return '<a target="_blank" id="'.esc_attr($a['id']).'" class="hefe-social-share-link '.esc_attr($a['class']).'" href="https://www.tumblr.com/widgets/share/tool?canonicalUrl='.get_permalink().'&title='.get_the_title().'" '.esc_attr($a['etc']).'>'.do_shortcode($content).'</a>';
			}
		}elseif(strtolower(esc_attr($a['company'])) == 'reddit'){
			if(esc_attr($a['url'])){
				return '<a target="_blank" id="'.esc_attr($a['id']).'" class="hefe-social-share-link '.esc_attr($a['class']).'" href="https://reddit.com/submit?url='.esc_attr($a['url']).'&title='.get_the_title().'" '.esc_attr($a['etc']).'>'.do_shortcode($content).'</a>';
			}else{
				return '<a target="_blank" id="'.esc_attr($a['id']).'" class="hefe-social-share-link '.esc_attr($a['class']).'" href="https://reddit.com/submit?url='.get_permalink().'&title='.get_the_title().'" '.esc_attr($a['etc']).'>'.do_shortcode($content).'</a>';
			}
		}elseif(strtolower(esc_attr($a['company'])) == 'stumbleupon'){
			if(esc_attr($a['url'])){
				return '<a target="_blank" id="'.esc_attr($a['id']).'" class="hefe-social-share-link '.esc_attr($a['class']).'" href="http://www.stumbleupon.com/submit?url='.esc_attr($a['url']).'&title='.get_the_title().'" '.esc_attr($a['etc']).'>'.do_shortcode($content).'</a>';
			}else{
				return '<a target="_blank" id="'.esc_attr($a['id']).'" class="hefe-social-share-link '.esc_attr($a['class']).'" href="http://www.stumbleupon.com/submit?url='.get_permalink().'&title='.get_the_title().'" '.esc_attr($a['etc']).'>'.do_shortcode($content).'</a>';
			}
		}elseif(strtolower(esc_attr($a['company'])) == 'delicious'){
			if(esc_attr($a['url'])){
				return '<a target="_blank" id="'.esc_attr($a['id']).'" class="hefe-social-share-link '.esc_attr($a['class']).'" href="https://delicious.com/save?v=5&noui&jump=close&url='.esc_attr($a['url']).'&title='.get_the_title().'" '.esc_attr($a['etc']).'>'.do_shortcode($content).'</a>';
			}else{
				return '<a target="_blank" id="'.esc_attr($a['id']).'" class="hefe-social-share-link '.esc_attr($a['class']).'" href="https://delicious.com/save?v=5&noui&jump=close&url='.get_permalink().'&title='.get_the_title().'" '.esc_attr($a['etc']).'>'.do_shortcode($content).'</a>';
			}
		}elseif(strtolower(esc_attr($a['company'])) == 'blogger'){
			if(esc_attr($a['url'])){
				return '<a target="_blank" id="'.esc_attr($a['id']).'" class="hefe-social-share-link '.esc_attr($a['class']).'" href="https://www.blogger.com/blog-this.g?u='.esc_attr($a['url']).'&n='.get_the_title().'" '.esc_attr($a['etc']).'>'.do_shortcode($content).'</a>';
			}else{
				return '<a target="_blank" id="'.esc_attr($a['id']).'" class="hefe-social-share-link '.esc_attr($a['class']).'" href="https://www.blogger.com/blog-this.g?u='.get_permalink().'&n='.get_the_title().'" '.esc_attr($a['etc']).'>'.do_shortcode($content).'</a>';
			}
		}elseif(strtolower(esc_attr($a['company'])) == 'livejournal'){
			if(esc_attr($a['url'])){
				return '<a target="_blank" id="'.esc_attr($a['id']).'" class="hefe-social-share-link '.esc_attr($a['class']).'" href="http://www.livejournal.com/update.bml?subject='.get_the_title().'&event='.esc_attr($a['url']).'" '.esc_attr($a['etc']).'>'.do_shortcode($content).'</a>';
			}else{
				return '<a target="_blank" id="'.esc_attr($a['id']).'" class="hefe-social-share-link '.esc_attr($a['class']).'" href="http://www.livejournal.com/update.bml?subject='.get_the_title().'&event='.get_permalink().'" '.esc_attr($a['etc']).'>'.do_shortcode($content).'</a>';
			}
		}elseif(strtolower(esc_attr($a['company'])) == 'myspace'){
			if(esc_attr($a['url'])){
				return '<a target="_blank" id="'.esc_attr($a['id']).'" class="hefe-social-share-link '.esc_attr($a['class']).'" href="https://myspace.com/post?u='.esc_attr($a['url']).'&t='.get_the_title().'" '.esc_attr($a['etc']).'>'.do_shortcode($content).'</a>';
			}else{
				return '<a target="_blank" id="'.esc_attr($a['id']).'" class="hefe-social-share-link '.esc_attr($a['class']).'" href="https://myspace.com/post?u='.get_permalink().'&t='.get_the_title().'" '.esc_attr($a['etc']).'>'.do_shortcode($content).'</a>';
			}
		}elseif(strtolower(esc_attr($a['company'])) == 'yahoo'){
			if(esc_attr($a['url'])){
				return '<a target="_blank" id="'.esc_attr($a['id']).'" class="hefe-social-share-link '.esc_attr($a['class']).'" href="http://compose.mail.yahoo.com/?body='.esc_attr($a['url']).'" '.esc_attr($a['etc']).'>'.do_shortcode($content).'</a>';
			}else{
				return '<a target="_blank" id="'.esc_attr($a['id']).'" class="hefe-social-share-link '.esc_attr($a['class']).'" href="http://compose.mail.yahoo.com/?body='.get_permalink().'" '.esc_attr($a['etc']).'>'.do_shortcode($content).'</a>';
			}
		}elseif(strtolower(esc_attr($a['company'])) == 'friendfeed'){
			if(esc_attr($a['url'])){
				return '<a target="_blank" id="'.esc_attr($a['id']).'" class="hefe-social-share-link '.esc_attr($a['class']).'" href="http://friendfeed.com/?url='.esc_attr($a['url']).'" '.esc_attr($a['etc']).'>'.do_shortcode($content).'</a>';
			}else{
				return '<a target="_blank" id="'.esc_attr($a['id']).'" class="hefe-social-share-link '.esc_attr($a['class']).'" href="http://friendfeed.com/?url='.get_permalink().'" '.esc_attr($a['etc']).'>'.do_shortcode($content).'</a>';
			}
		}elseif(strtolower(esc_attr($a['company'])) == 'newsvine'){
			if(esc_attr($a['url'])){
				return '<a target="_blank" id="'.esc_attr($a['id']).'" class="hefe-social-share-link '.esc_attr($a['class']).'" href="http://www.newsvine.com/_tools/seed&save?u='.esc_attr($a['url']).'" '.esc_attr($a['etc']).'>'.do_shortcode($content).'</a>';
			}else{
				return '<a target="_blank" id="'.esc_attr($a['id']).'" class="hefe-social-share-link '.esc_attr($a['class']).'" href="http://www.newsvine.com/_tools/seed&save?u='.get_permalink().'" '.esc_attr($a['etc']).'>'.do_shortcode($content).'</a>';
			}
		}elseif(strtolower(esc_attr($a['company'])) == 'evernote'){
			if(esc_attr($a['url'])){
				return '<a target="_blank" id="'.esc_attr($a['id']).'" class="hefe-social-share-link '.esc_attr($a['class']).'" href="http://www.evernote.com/clip.action?url='.esc_attr($a['url']).'" '.esc_attr($a['etc']).'>'.do_shortcode($content).'</a>';
			}else{
				return '<a target="_blank" id="'.esc_attr($a['id']).'" class="hefe-social-share-link '.esc_attr($a['class']).'" href="http://www.evernote.com/clip.action?url='.get_permalink().'" '.esc_attr($a['etc']).'>'.do_shortcode($content).'</a>';
			}
		}elseif(strtolower(esc_attr($a['company'])) == 'getpocket'){
			if(esc_attr($a['url'])){
				return '<a target="_blank" id="'.esc_attr($a['id']).'" class="hefe-social-share-link '.esc_attr($a['class']).'" href="https://getpocket.com/save?url='.esc_attr($a['url']).'" '.esc_attr($a['etc']).'>'.do_shortcode($content).'</a>';
			}else{
				return '<a target="_blank" id="'.esc_attr($a['id']).'" class="hefe-social-share-link '.esc_attr($a['class']).'" href="https://getpocket.com/save?url='.get_permalink().'" '.esc_attr($a['etc']).'>'.do_shortcode($content).'</a>';
			}
		}elseif(strtolower(esc_attr($a['company'])) == 'flipboard'){
			if(esc_attr($a['url'])){
				return '<a target="_blank" id="'.esc_attr($a['id']).'" class="hefe-social-share-link '.esc_attr($a['class']).'" href="https://share.flipboard.com/bookmarklet/popout?v=2&title='.get_the_title().'&url='.esc_attr($a['url']).'" '.esc_attr($a['etc']).'>'.do_shortcode($content).'</a>';
			}else{
				return '<a target="_blank" id="'.esc_attr($a['id']).'" class="hefe-social-share-link '.esc_attr($a['class']).'" href="https://share.flipboard.com/bookmarklet/popout?v=2&title='.get_the_title().'&url='.get_permalink().'" '.esc_attr($a['etc']).'>'.do_shortcode($content).'</a>';
			}
		}elseif(strtolower(esc_attr($a['company'])) == 'instapaper'){
			if(esc_attr($a['url'])){
				return '<a target="_blank" id="'.esc_attr($a['id']).'" class="hefe-social-share-link '.esc_attr($a['class']).'" href="http://www.instapaper.com/edit?url='.esc_attr($a['url']).'&title='.get_the_title().'" '.esc_attr($a['etc']).'>'.do_shortcode($content).'</a>';
			}else{
				return '<a target="_blank" id="'.esc_attr($a['id']).'" class="hefe-social-share-link '.esc_attr($a['class']).'" href="http://www.instapaper.com/edit?url='.get_permalink().'&title='.get_the_title().'" '.esc_attr($a['etc']).'>'.do_shortcode($content).'</a>';
			}
		}elseif(strtolower(esc_attr($a['company'])) == 'line.me' || strtolower(esc_attr($a['company'])) == 'lineme' || strtolower(esc_attr($a['company'])) == 'line-me' || strtolower(esc_attr($a['company'])) == 'line_me'){
			if(esc_attr($a['url'])){
				return '<a target="_blank" id="'.esc_attr($a['id']).'" class="hefe-social-share-link '.esc_attr($a['class']).'" href="http://line.me/R/msg/text/?'.esc_attr($a['url']).'" '.esc_attr($a['etc']).'>'.do_shortcode($content).'</a>';
			}else{
				return '<a target="_blank" id="'.esc_attr($a['id']).'" class="hefe-social-share-link '.esc_attr($a['class']).'" href="http://line.me/R/msg/text/?'.get_permalink().'" '.esc_attr($a['etc']).'>'.do_shortcode($content).'</a>';
			}
		}elseif(strtolower(esc_attr($a['company'])) == 'skype'){
			if(esc_attr($a['url'])){
				return '<a target="_blank" id="'.esc_attr($a['id']).'" class="hefe-social-share-link '.esc_attr($a['class']).'" href="https://web.skype.com/share?url='.esc_attr($a['url']).'" '.esc_attr($a['etc']).'>'.do_shortcode($content).'</a>';
			}else{
				return '<a target="_blank" id="'.esc_attr($a['id']).'" class="hefe-social-share-link '.esc_attr($a['class']).'" href="https://web.skype.com/share?url='.get_permalink().'" '.esc_attr($a['etc']).'>'.do_shortcode($content).'</a>';
			}
		}elseif(strtolower(esc_attr($a['company'])) == 'viber'){
			if(esc_attr($a['url'])){
				return '<a target="_blank" id="'.esc_attr($a['id']).'" class="hefe-social-share-link '.esc_attr($a['class']).'" href="viber://forward?text='.esc_attr($a['url']).'" '.esc_attr($a['etc']).'>'.do_shortcode($content).'</a>';
			}else{
				return '<a target="_blank" id="'.esc_attr($a['id']).'" class="hefe-social-share-link '.esc_attr($a['class']).'" href="viber://forward?text='.get_permalink().'" '.esc_attr($a['etc']).'>'.do_shortcode($content).'</a>';
			}
		}elseif(strtolower(esc_attr($a['company'])) == 'whatsapp'){
			if(esc_attr($a['url'])){
				return '<a target="_blank" id="'.esc_attr($a['id']).'" class="hefe-social-share-link '.esc_attr($a['class']).'" href="whatsapp://send?text='.esc_attr($a['url']).'" '.esc_attr($a['etc']).'>'.do_shortcode($content).'</a>';
			}else{
				return '<a target="_blank" id="'.esc_attr($a['id']).'" class="hefe-social-share-link '.esc_attr($a['class']).'" href="whatsapp://send?text='.get_permalink().'" '.esc_attr($a['etc']).'>'.do_shortcode($content).'</a>';
			}
		}elseif(strtolower(esc_attr($a['company'])) == 'telegram.me' || strtolower(esc_attr($a['company'])) == 'telegramme' || strtolower(esc_attr($a['company'])) == 'telegram-me' || strtolower(esc_attr($a['company'])) == 'telegram_me'){
			if(esc_attr($a['url'])){
				return '<a target="_blank" id="'.esc_attr($a['id']).'" class="hefe-social-share-link '.esc_attr($a['class']).'" href="https://telegram.me/share/url?url='.esc_attr($a['url']).'&text='.get_the_title().'" '.esc_attr($a['etc']).'>'.do_shortcode($content).'</a>';
			}else{
				return '<a target="_blank" id="'.esc_attr($a['id']).'" class="hefe-social-share-link '.esc_attr($a['class']).'" href="https://telegram.me/share/url?url='.get_permalink().'&text='.get_the_title().'" '.esc_attr($a['etc']).'>'.do_shortcode($content).'</a>';
			}
		}elseif(strtolower(esc_attr($a['company'])) == 'vk'){
			if(esc_attr($a['url'])){
				return '<a target="_blank" id="'.esc_attr($a['id']).'" class="hefe-social-share-link '.esc_attr($a['class']).'" href="http://vk.com/share.php?url='.esc_attr($a['url']).'" '.esc_attr($a['etc']).'>'.do_shortcode($content).'</a>';
			}else{
				return '<a target="_blank" id="'.esc_attr($a['id']).'" class="hefe-social-share-link '.esc_attr($a['class']).'" href="http://vk.com/share.php?url='.get_permalink().'" '.esc_attr($a['etc']).'>'.do_shortcode($content).'</a>';
			}
		}elseif(strtolower(esc_attr($a['company'])) == 'okru'){
			if(esc_attr($a['url'])){
				return '<a target="_blank" id="'.esc_attr($a['id']).'" class="hefe-social-share-link '.esc_attr($a['class']).'" href="https://connect.ok.ru/dk?st.cmd=WidgetSharePreview&st.shareUrl='.esc_attr($a['url']).'&title='.get_the_title().'" '.esc_attr($a['etc']).'>'.do_shortcode($content).'</a>';
			}else{
				return '<a target="_blank" id="'.esc_attr($a['id']).'" class="hefe-social-share-link '.esc_attr($a['class']).'" href="https://connect.ok.ru/dk?st.cmd=WidgetSharePreview&st.shareUrl='.get_permalink().'&title='.get_the_title().'" '.esc_attr($a['etc']).'>'.do_shortcode($content).'</a>';
			}
		}elseif(strtolower(esc_attr($a['company'])) == 'douban'){
			if(esc_attr($a['url'])){
				return '<a target="_blank" id="'.esc_attr($a['id']).'" class="hefe-social-share-link '.esc_attr($a['class']).'" href="http://www.douban.com/recommend/?url='.esc_attr($a['url']).'&title='.get_the_title().'" '.esc_attr($a['etc']).'>'.do_shortcode($content).'</a>';
			}else{
				return '<a target="_blank" id="'.esc_attr($a['id']).'" class="hefe-social-share-link '.esc_attr($a['class']).'" href="http://www.douban.com/recommend/?url='.get_permalink().'&title='.get_the_title().'" '.esc_attr($a['etc']).'>'.do_shortcode($content).'</a>';
			}
		}elseif(strtolower(esc_attr($a['company'])) == 'baidu'){
			if(esc_attr($a['url'])){
				return '<a target="_blank" id="'.esc_attr($a['id']).'" class="hefe-social-share-link '.esc_attr($a['class']).'" href="http://cang.baidu.com/do/add?it='.get_the_title().'&iu='.esc_attr($a['url']).'" '.esc_attr($a['etc']).'>'.do_shortcode($content).'</a>';
			}else{
				return '<a target="_blank" id="'.esc_attr($a['id']).'" class="hefe-social-share-link '.esc_attr($a['class']).'" href="http://cang.baidu.com/do/add?it='.get_the_title().'&iu='.get_permalink().'" '.esc_attr($a['etc']).'>'.do_shortcode($content).'</a>';
			}
		}elseif(strtolower(esc_attr($a['company'])) == 'qzone'){
			if(esc_attr($a['url'])){
				return '<a target="_blank" id="'.esc_attr($a['id']).'" class="hefe-social-share-link '.esc_attr($a['class']).'" href="http://sns.qzone.qq.com/cgi-bin/qzshare/cgi_qzshare_onekey?url='.esc_attr($a['url']).'" '.esc_attr($a['etc']).'>'.do_shortcode($content).'</a>';
			}else{
				return '<a target="_blank" id="'.esc_attr($a['id']).'" class="hefe-social-share-link '.esc_attr($a['class']).'" href="http://sns.qzone.qq.com/cgi-bin/qzshare/cgi_qzshare_onekey?url='.get_permalink().'" '.esc_attr($a['etc']).'>'.do_shortcode($content).'</a>';
			}
		}elseif(strtolower(esc_attr($a['company'])) == 'xing'){
			if(esc_attr($a['url'])){
				return '<a target="_blank" id="'.esc_attr($a['id']).'" class="hefe-social-share-link '.esc_attr($a['class']).'" href="https://www.xing.com/app/user?op=share&url='.esc_attr($a['url']).'" '.esc_attr($a['etc']).'>'.do_shortcode($content).'</a>';
			}else{
				return '<a target="_blank" id="'.esc_attr($a['id']).'" class="hefe-social-share-link '.esc_attr($a['class']).'" href="https://www.xing.com/app/user?op=share&url='.get_permalink().'" '.esc_attr($a['etc']).'>'.do_shortcode($content).'</a>';
			}
		}elseif(strtolower(esc_attr($a['company'])) == 'renren'){
			if(esc_attr($a['url'])){
				return '<a target="_blank" id="'.esc_attr($a['id']).'" class="hefe-social-share-link '.esc_attr($a['class']).'" href="http://widget.renren.com/dialog/share?resourceUrl='.esc_attr($a['url']).'&srcUrl='.esc_attr($a['url']).'&title='.get_the_title().'" '.esc_attr($a['etc']).'>'.do_shortcode($content).'</a>';
			}else{
				return '<a target="_blank" id="'.esc_attr($a['id']).'" class="hefe-social-share-link '.esc_attr($a['class']).'" href="http://widget.renren.com/dialog/share?resourceUrl='.get_permalink().'&srcUrl='.get_permalink().'&title='.get_the_title().'" '.esc_attr($a['etc']).'>'.do_shortcode($content).'</a>';
			}
		}elseif(strtolower(esc_attr($a['company'])) == 'weibo'){
			if(esc_attr($a['url'])){
				return '<a target="_blank" id="'.esc_attr($a['id']).'" class="hefe-social-share-link '.esc_attr($a['class']).'" href="http://service.weibo.com/share/share.php?url='.esc_attr($a['url']).'&appkey=&title='.get_the_title().'&pic=&ralateUid=&srcUrl=" '.esc_attr($a['etc']).'>'.do_shortcode($content).'</a>';
			}else{
				return '<a target="_blank" id="'.esc_attr($a['id']).'" class="hefe-social-share-link '.esc_attr($a['class']).'" href="http://service.weibo.com/share/share.php?url='.get_permalink().'&appkey=&title='.get_the_title().'&pic=&ralateUid=&srcUrl=" '.esc_attr($a['etc']).'>'.do_shortcode($content).'</a>';
			}
		}elseif(strtolower(esc_attr($a['company'])) == 'email'){
			if(esc_attr($a['url'])){
				return '<a target="_blank" id="'.esc_attr($a['id']).'" class="hefe-social-share-link '.esc_attr($a['class']).'" href="mailto:?subject=I wanted you to see this - '.get_the_title().'&amp;body=Check out this site '.esc_attr($a['url']).'." '.esc_attr($a['etc']).'>'.do_shortcode($content).'</a>';
			}else{
				return '<a target="_blank" id="'.esc_attr($a['id']).'" class="hefe-social-share-link '.esc_attr($a['class']).'" href="mailto:?subject=I wanted you to see this - '.get_the_title().'&amp;body=Check out this site '.get_permalink().'." '.esc_attr($a['etc']).'>'.do_shortcode($content).'</a>';
			}
		}
	}
}
// Space
if(!function_exists('hefe_shortcode_space')){
	if(get_option('hefe_control_customizer_control_shortcode_name')){
		add_shortcode(hefe_shortcode_name.'_space', 'hefe_shortcode_space');
		add_shortcode(hefe_shortcode_name.'_nbsp', 'hefe_shortcode_space');
	}
	add_shortcode('hefe_nbsp', 'hefe_shortcode_space');
	add_shortcode('hefe_space', 'hefe_shortcode_space');
	function hefe_shortcode_space($atts, $content = null){
		return '&nbsp;';
	}
}
// Sticky Item
if(!function_exists('hefe_shortcode_sticky_item')){
	if(get_option('hefe_control_customizer_control_shortcode_name')){
		add_shortcode(hefe_shortcode_name.'_sticky_item', 'hefe_shortcode_sticky_item');
	}
	add_shortcode('hefe_sticky_item', 'hefe_shortcode_sticky_item');
	function hefe_shortcode_sticky_item($atts, $content = null){
		wp_enqueue_script('hefe-sticky-script');
		$a = shortcode_atts(array(
			'id' => '',
			'class' => '',
			'etc' => '',
		), $atts);
		return '<div class="hefe-sticky-placement"></div><div id="'.esc_attr($a['id']).'" class="hefe-sticky-item '.esc_attr($a['class']).'" '.esc_attr($a['etc']).'>'.do_shortcode($content).'</div>';
	}
}
// Sudo Slider Parent
if(!function_exists('hefe_shortcode_sudo_slider_parent')){
	if(get_option('hefe_control_customizer_control_shortcode_name')){
		add_shortcode(hefe_shortcode_name.'_sudo_slider_parent', 'hefe_shortcode_sudo_slider_parent');
	}
	add_shortcode('hefe_sudo_slider_parent', 'hefe_shortcode_sudo_slider_parent');
	function hefe_shortcode_sudo_slider_parent($atts, $content = null){
		wp_enqueue_style('hefe-sudo-slider-inc-style');
		wp_enqueue_script('hefe-sudo-slider-bbq-script');
		wp_enqueue_script('hefe-sudo-slider-hashchange-script');
		wp_enqueue_script('hefe-sudo-slider-properload-script');
		wp_enqueue_script('hefe-sudo-slider-script');
		wp_enqueue_script('hefe-sudo-slider-inc-script');
		$a = shortcode_atts(array(
			'id' => '',
			'class' => '',
			'id_number' => '',
			'pause' => '',
			'speed' => '',
			'effect' => '',
			'auto' => '',
			'numeric' => '',
			'slide_count' => '',
			'move_count' => '',
			'prev_next' => '',
			'prev_icon' => '',
			'next_icon' => '',
			'xs_pause' => '',
			'xs_speed' => '',
			'xs_effect' => '',
			'xs_auto' => '',
			'xs_numeric' => '',
			'xs_slide_count' => '',
			'xs_move_count' => '',
			'xs_prev_next' => '',
			'xs_prev_icon' => '',
			'xs_next_icon' => '',
			'sm_pause' => '',
			'sm_speed' => '',
			'sm_effect' => '',
			'sm_auto' => '',
			'sm_numeric' => '',
			'sm_slide_count' => '',
			'sm_move_count' => '',
			'sm_prev_next' => '',
			'sm_prev_icon' => '',
			'sm_next_icon' => '',
			'md_pause' => '',
			'md_speed' => '',
			'md_effect' => '',
			'md_auto' => '',
			'md_numeric' => '',
			'md_slide_count' => '',
			'md_move_count' => '',
			'md_prev_next' => '',
			'md_prev_icon' => '',
			'md_next_icon' => '',
			'lg_pause' => '',
			'lg_speed' => '',
			'lg_effect' => '',
			'lg_auto' => '',
			'lg_numeric' => '',
			'lg_slide_count' => '',
			'lg_move_count' => '',
			'lg_prev_next' => '',
			'lg_prev_icon' => '',
			'lg_next_icon' => '',
			'xl_pause' => '',
			'xl_speed' => '',
			'xl_effect' => '',
			'xl_auto' => '',
			'xl_numeric' => '',
			'xl_slide_count' => '',
			'xl_move_count' => '',
			'xl_prev_next' => '',
			'xl_prev_icon' => '',
			'xl_next_icon' => '',
			'etc' => '',
		), $atts);
		if(esc_attr($a['id_number'])){
			$hefe_sudo_slider_id_class = 'hefe-sudo-slider-parent-'.esc_attr($a['id_number']);
			add_action('wp_footer', function() use ($atts){
				if(!empty($atts['id_number'])){
					$hefe_sudo_slider_shortcode_id_number = $atts['id_number'];
				}else{
					$hefe_sudo_slider_shortcode_id_number = 'default';
				}
				if(!empty($atts['pause'])){
					$hefe_sudo_slider_shortcode_pause = $atts['pause'];
				}else{
					$hefe_sudo_slider_shortcode_pause = '4500';
				}
				if(!empty($atts['speed'])){
					$hefe_sudo_slider_shortcode_speed = $atts['speed'];
				}else{
					$hefe_sudo_slider_shortcode_speed = '600';
				}
				if(!empty($atts['effect'])){
					$hefe_sudo_slider_shortcode_effect = '"'.$atts['effect'].'"';
				}else{
					$hefe_sudo_slider_shortcode_effect = '"'.'slide'.'"';
				}
				if(!empty($atts['auto'])){
					$hefe_sudo_slider_shortcode_auto = $atts['auto'];
				}else{
					$hefe_sudo_slider_shortcode_auto = 'true';
				}
				if(!empty($atts['numeric'])){
					$hefe_sudo_slider_shortcode_numeric = $atts['numeric'];
				}else{
					$hefe_sudo_slider_shortcode_numeric = 'true';
				}
				if(!empty($atts['slide_count'])){
					$hefe_sudo_slider_shortcode_slide_count = $atts['slide_count'];
				}else{
					$hefe_sudo_slider_shortcode_slide_count = '1';
				}
				if(!empty($atts['move_count'])){
					$hefe_sudo_slider_shortcode_move_count = $atts['move_count'];
				}else{
					$hefe_sudo_slider_shortcode_move_count = '1';
				}
				if(!empty($atts['prev_next'])){
					$hefe_sudo_slider_shortcode_prev_next = $atts['prev_next'];
				}else{
					$hefe_sudo_slider_shortcode_prev_next = 'false';
				}
				if(!empty($atts['prev_icon'])){
					$hefe_sudo_slider_shortcode_prev_icon = $atts['prev_icon'];
				}else{
					$hefe_sudo_slider_shortcode_prev_icon = '◀';
				}
				if(!empty($atts['next_icon'])){
					$hefe_sudo_slider_shortcode_next_icon = $atts['next_icon'];
				}else{
					$hefe_sudo_slider_shortcode_next_icon = '▶';
				}
				echo '<script>(function($) {';
				echo 'if ($(window).width() > 9999) {}';
				if(!empty($atts['xl_pause']) || !empty($atts['xl_speed']) || !empty($atts['xl_effect']) || !empty($atts['xl_auto']) || !empty($atts['xl_numeric']) || !empty($atts['xl_slide_count']) || !empty($atts['xl_move_count']) || !empty($atts['xl_prev_next']) || !empty($atts['xl_prev_icon']) || !empty($atts['xl_next_icon'])){
					if(!empty($atts['id_number'])){
						$hefe_sudo_slider_shortcode_xl_id_number = $atts['id_number'];
					}else{
						$hefe_sudo_slider_shortcode_xl_id_number = 'default';
					}
					if(!empty($atts['xl_pause'])){
						$hefe_sudo_slider_shortcode_xl_pause = $atts['xl_pause'];
					}elseif(!empty($atts['pause'])){
						$hefe_sudo_slider_shortcode_xl_pause = $atts['pause'];
					}else{
						$hefe_sudo_slider_shortcode_xl_pause = '4500';
					}
					if(!empty($atts['xl_speed'])){
						$hefe_sudo_slider_shortcode_xl_speed = $atts['xl_speed'];
					}elseif(!empty($atts['speed'])){
						$hefe_sudo_slider_shortcode_xl_speed = $atts['speed'];
					}else{
						$hefe_sudo_slider_shortcode_xl_speed = '600';
					}
					if(!empty($atts['xl_effect'])){
						$hefe_sudo_slider_shortcode_xl_effect = '"'.$atts['xl_effect'].'"';
					}elseif(!empty($atts['effect'])){
						$hefe_sudo_slider_shortcode_xl_effect = '"'.$atts['effect'].'"';
					}else{
						$hefe_sudo_slider_shortcode_xl_effect = '"'.'slide'.'"';
					}
					if(!empty($atts['xl_auto'])){
						$hefe_sudo_slider_shortcode_xl_auto = $atts['xl_auto'];
					}elseif(!empty($atts['auto'])){
						$hefe_sudo_slider_shortcode_xl_auto = $atts['auto'];
					}else{
						$hefe_sudo_slider_shortcode_xl_auto = 'true';
					}
					if(!empty($atts['xl_numeric'])){
						$hefe_sudo_slider_shortcode_xl_numeric = $atts['xl_numeric'];
					}elseif(!empty($atts['numeric'])){
						$hefe_sudo_slider_shortcode_xl_numeric = $atts['numeric'];
					}else{
						$hefe_sudo_slider_shortcode_xl_numeric = 'true';
					}
					if(!empty($atts['xl_slide_count'])){
						$hefe_sudo_slider_shortcode_xl_slide_count = $atts['xl_slide_count'];
					}elseif(!empty($atts['slide_count'])){
						$hefe_sudo_slider_shortcode_xl_slide_count = $atts['slide_count'];
					}else{
						$hefe_sudo_slider_shortcode_xl_slide_count = '1';
					}
					if(!empty($atts['xl_move_count'])){
						$hefe_sudo_slider_shortcode_xl_move_count = $atts['xl_move_count'];
					}elseif(!empty($atts['move_count'])){
						$hefe_sudo_slider_shortcode_xl_move_count = $atts['move_count'];
					}else{
						$hefe_sudo_slider_shortcode_xl_move_count = '1';
					}
					if(!empty($atts['xl_prev_next'])){
						$hefe_sudo_slider_shortcode_xl_prev_next = $atts['xl_prev_next'];
					}elseif(!empty($atts['prev_next'])){
						$hefe_sudo_slider_shortcode_xl_prev_next = $atts['prev_next'];
					}else{
						$hefe_sudo_slider_shortcode_xl_prev_next = 'false';
					}
					if(!empty($atts['xl_prev_icon'])){
						$hefe_sudo_slider_shortcode_xl_prev_icon = $atts['xl_prev_icon'];
					}elseif(!empty($atts['prev_icon'])){
						$hefe_sudo_slider_shortcode_xl_prev_icon = $atts['prev_icon'];
					}else{
						$hefe_sudo_slider_shortcode_xl_prev_icon = '◀';
					}
					if(!empty($atts['xl_next_icon'])){
						$hefe_sudo_slider_shortcode_xl_next_icon = $atts['xl_next_icon'];
					}elseif(!empty($atts['next_icon'])){
						$hefe_sudo_slider_shortcode_xl_next_icon = $atts['next_icon'];
					}else{
						$hefe_sudo_slider_shortcode_xl_next_icon = '▶';
					}
					echo 'else if ($(window).width() > 1200) {var sudoSlider = $(".hefe-sudo-slider-parent.hefe-sudo-slider-parent-'.$hefe_sudo_slider_shortcode_xl_id_number.' .hefe-sudo-slider-parent-interior").sudoSlider({ effect: '.$hefe_sudo_slider_shortcode_xl_effect.',pause: '.$hefe_sudo_slider_shortcode_xl_pause.',speed: '.$hefe_sudo_slider_shortcode_xl_speed.',auto: '.$hefe_sudo_slider_shortcode_xl_auto.',prevNext: '.$hefe_sudo_slider_shortcode_xl_prev_next.',prevHtml:"<a class=prevBtn>'.$hefe_sudo_slider_shortcode_xl_prev_icon.'</a>",nextHtml:"<a class=nextBtn>'.$hefe_sudo_slider_shortcode_xl_next_icon.'</a>",numeric: '.$hefe_sudo_slider_shortcode_xl_numeric.',continuous: true,customLink: ".hefe-sudo-slider-'.$hefe_sudo_slider_shortcode_xl_id_number.'-link",slideCount: '.$hefe_sudo_slider_shortcode_xl_slide_count.', touch: true, mouseTouch: true, moveCount: '.$hefe_sudo_slider_shortcode_xl_move_count.'});}';
				}else{
					echo '';
				}
				if(!empty($atts['lg_pause']) || !empty($atts['lg_speed']) || !empty($atts['lg_effect']) || !empty($atts['lg_auto']) || !empty($atts['lg_numeric']) || !empty($atts['lg_slide_count']) || !empty($atts['lg_move_count']) || !empty($atts['lg_prev_next']) || !empty($atts['lg_prev_icon']) || !empty($atts['lg_next_icon'])){
					if(!empty($atts['id_number'])){
						$hefe_sudo_slider_shortcode_lg_id_number = $atts['id_number'];
					}else{
						$hefe_sudo_slider_shortcode_lg_id_number = 'default';
					}
					if(!empty($atts['lg_pause'])){
						$hefe_sudo_slider_shortcode_lg_pause = $atts['lg_pause'];
					}elseif(!empty($atts['pause'])){
						$hefe_sudo_slider_shortcode_lg_pause = $atts['pause'];
					}else{
						$hefe_sudo_slider_shortcode_lg_pause = '4500';
					}
					if(!empty($atts['lg_speed'])){
						$hefe_sudo_slider_shortcode_lg_speed = $atts['lg_speed'];
					}elseif(!empty($atts['speed'])){
						$hefe_sudo_slider_shortcode_lg_speed = $atts['speed'];
					}else{
						$hefe_sudo_slider_shortcode_lg_speed = '600';
					}
					if(!empty($atts['lg_effect'])){
						$hefe_sudo_slider_shortcode_lg_effect = '"'.$atts['lg_effect'].'"';
					}elseif(!empty($atts['effect'])){
						$hefe_sudo_slider_shortcode_lg_effect = '"'.$atts['effect'].'"';
					}else{
						$hefe_sudo_slider_shortcode_lg_effect = '"'.'slide'.'"';
					}
					if(!empty($atts['lg_auto'])){
						$hefe_sudo_slider_shortcode_lg_auto = $atts['lg_auto'];
					}elseif(!empty($atts['auto'])){
						$hefe_sudo_slider_shortcode_lg_auto = $atts['auto'];
					}else{
						$hefe_sudo_slider_shortcode_lg_auto = 'true';
					}
					if(!empty($atts['lg_numeric'])){
						$hefe_sudo_slider_shortcode_lg_numeric = $atts['lg_numeric'];
					}elseif(!empty($atts['numeric'])){
						$hefe_sudo_slider_shortcode_lg_numeric = $atts['numeric'];
					}else{
						$hefe_sudo_slider_shortcode_lg_numeric = 'true';
					}
					if(!empty($atts['lg_slide_count'])){
						$hefe_sudo_slider_shortcode_lg_slide_count = $atts['lg_slide_count'];
					}elseif(!empty($atts['slide_count'])){
						$hefe_sudo_slider_shortcode_lg_slide_count = $atts['slide_count'];
					}else{
						$hefe_sudo_slider_shortcode_lg_slide_count = '1';
					}
					if(!empty($atts['lg_move_count'])){
						$hefe_sudo_slider_shortcode_lg_move_count = $atts['lg_move_count'];
					}elseif(!empty($atts['move_count'])){
						$hefe_sudo_slider_shortcode_lg_move_count = $atts['move_count'];
					}else{
						$hefe_sudo_slider_shortcode_lg_move_count = '1';
					}
					if(!empty($atts['lg_prev_next'])){
						$hefe_sudo_slider_shortcode_lg_prev_next = $atts['lg_prev_next'];
					}elseif(!empty($atts['prev_next'])){
						$hefe_sudo_slider_shortcode_lg_prev_next = $atts['prev_next'];
					}else{
						$hefe_sudo_slider_shortcode_lg_prev_next = 'false';
					}
					if(!empty($atts['lg_prev_icon'])){
						$hefe_sudo_slider_shortcode_lg_prev_icon = $atts['lg_prev_icon'];
					}elseif(!empty($atts['prev_icon'])){
						$hefe_sudo_slider_shortcode_lg_prev_icon = $atts['prev_icon'];
					}else{
						$hefe_sudo_slider_shortcode_lg_prev_icon = '◀';
					}
					if(!empty($atts['lg_next_icon'])){
						$hefe_sudo_slider_shortcode_lg_next_icon = $atts['lg_next_icon'];
					}elseif(!empty($atts['next_icon'])){
						$hefe_sudo_slider_shortcode_lg_next_icon = $atts['next_icon'];
					}else{
						$hefe_sudo_slider_shortcode_lg_next_icon = '▶';
					}
					echo 'else if ($(window).width() > 992) {var sudoSlider = $(".hefe-sudo-slider-parent.hefe-sudo-slider-parent-'.$hefe_sudo_slider_shortcode_lg_id_number.' .hefe-sudo-slider-parent-interior").sudoSlider({ effect: '.$hefe_sudo_slider_shortcode_lg_effect.',pause: '.$hefe_sudo_slider_shortcode_lg_pause.',speed: '.$hefe_sudo_slider_shortcode_lg_speed.',auto: '.$hefe_sudo_slider_shortcode_lg_auto.',prevNext: '.$hefe_sudo_slider_shortcode_lg_prev_next.',prevHtml:"<a class=prevBtn>'.$hefe_sudo_slider_shortcode_lg_prev_icon.'</a>",nextHtml:"<a class=nextBtn>'.$hefe_sudo_slider_shortcode_lg_next_icon.'</a>",numeric: '.$hefe_sudo_slider_shortcode_lg_numeric.',continuous: true,customLink: ".hefe-sudo-slider-'.$hefe_sudo_slider_shortcode_lg_id_number.'-link",slideCount: '.$hefe_sudo_slider_shortcode_lg_slide_count.', touch: true, mouseTouch: true, moveCount: '.$hefe_sudo_slider_shortcode_lg_move_count.'});}';
				}else{
					echo '';
				}
				if(!empty($atts['md_pause']) || !empty($atts['md_speed']) || !empty($atts['md_effect']) || !empty($atts['md_auto']) || !empty($atts['md_numeric']) || !empty($atts['md_slide_count']) || !empty($atts['md_move_count']) || !empty($atts['md_prev_next']) || !empty($atts['md_prev_icon']) || !empty($atts['md_next_icon'])){
					if(!empty($atts['id_number'])){
						$hefe_sudo_slider_shortcode_md_id_number = $atts['id_number'];
					}else{
						$hefe_sudo_slider_shortcode_md_id_number = 'default';
					}
					if(!empty($atts['md_pause'])){
						$hefe_sudo_slider_shortcode_md_pause = $atts['md_pause'];
					}elseif(!empty($atts['pause'])){
						$hefe_sudo_slider_shortcode_md_pause = $atts['pause'];
					}else{
						$hefe_sudo_slider_shortcode_md_pause = '4500';
					}
					if(!empty($atts['md_speed'])){
						$hefe_sudo_slider_shortcode_md_speed = $atts['md_speed'];
					}elseif(!empty($atts['speed'])){
						$hefe_sudo_slider_shortcode_md_speed = $atts['speed'];
					}else{
						$hefe_sudo_slider_shortcode_md_speed = '600';
					}
					if(!empty($atts['md_effect'])){
						$hefe_sudo_slider_shortcode_md_effect = '"'.$atts['md_effect'].'"';
					}elseif(!empty($atts['effect'])){
						$hefe_sudo_slider_shortcode_md_effect = '"'.$atts['effect'].'"';
					}else{
						$hefe_sudo_slider_shortcode_md_effect = '"'.'slide'.'"';
					}
					if(!empty($atts['md_auto'])){
						$hefe_sudo_slider_shortcode_md_auto = $atts['md_auto'];
					}elseif(!empty($atts['auto'])){
						$hefe_sudo_slider_shortcode_md_auto = $atts['auto'];
					}else{
						$hefe_sudo_slider_shortcode_md_auto = 'true';
					}
					if(!empty($atts['md_numeric'])){
						$hefe_sudo_slider_shortcode_md_numeric = $atts['md_numeric'];
					}elseif(!empty($atts['numeric'])){
						$hefe_sudo_slider_shortcode_md_numeric = $atts['numeric'];
					}else{
						$hefe_sudo_slider_shortcode_md_numeric = 'true';
					}
					if(!empty($atts['md_slide_count'])){
						$hefe_sudo_slider_shortcode_md_slide_count = $atts['md_slide_count'];
					}elseif(!empty($atts['slide_count'])){
						$hefe_sudo_slider_shortcode_md_slide_count = $atts['slide_count'];
					}else{
						$hefe_sudo_slider_shortcode_md_slide_count = '1';
					}
					if(!empty($atts['md_move_count'])){
						$hefe_sudo_slider_shortcode_md_move_count = $atts['md_move_count'];
					}elseif(!empty($atts['move_count'])){
						$hefe_sudo_slider_shortcode_md_move_count = $atts['move_count'];
					}else{
						$hefe_sudo_slider_shortcode_md_move_count = '1';
					}
					if(!empty($atts['md_prev_next'])){
						$hefe_sudo_slider_shortcode_md_prev_next = $atts['md_prev_next'];
					}elseif(!empty($atts['prev_next'])){
						$hefe_sudo_slider_shortcode_md_prev_next = $atts['prev_next'];
					}else{
						$hefe_sudo_slider_shortcode_md_prev_next = 'false';
					}
					if(!empty($atts['md_prev_icon'])){
						$hefe_sudo_slider_shortcode_md_prev_icon = $atts['md_prev_icon'];
					}elseif(!empty($atts['prev_icon'])){
						$hefe_sudo_slider_shortcode_md_prev_icon = $atts['prev_icon'];
					}else{
						$hefe_sudo_slider_shortcode_md_prev_icon = '◀';
					}
					if(!empty($atts['md_next_icon'])){
						$hefe_sudo_slider_shortcode_md_next_icon = $atts['md_next_icon'];
					}elseif(!empty($atts['next_icon'])){
						$hefe_sudo_slider_shortcode_md_next_icon = $atts['next_icon'];
					}else{
						$hefe_sudo_slider_shortcode_md_next_icon = '▶';
					}
					echo 'else if ($(window).width() > 768) {var sudoSlider = $(".hefe-sudo-slider-parent.hefe-sudo-slider-parent-'.$hefe_sudo_slider_shortcode_md_id_number.' .hefe-sudo-slider-parent-interior").sudoSlider({ effect: '.$hefe_sudo_slider_shortcode_md_effect.',pause: '.$hefe_sudo_slider_shortcode_md_pause.',speed: '.$hefe_sudo_slider_shortcode_md_speed.',auto: '.$hefe_sudo_slider_shortcode_md_auto.',prevNext: '.$hefe_sudo_slider_shortcode_md_prev_next.',prevHtml:"<a class=prevBtn>'.$hefe_sudo_slider_shortcode_md_prev_icon.'</a>",nextHtml:"<a class=nextBtn>'.$hefe_sudo_slider_shortcode_md_next_icon.'</a>",numeric: '.$hefe_sudo_slider_shortcode_md_numeric.',continuous: true,customLink: ".hefe-sudo-slider-'.$hefe_sudo_slider_shortcode_md_id_number.'-link",slideCount: '.$hefe_sudo_slider_shortcode_md_slide_count.', touch: true, mouseTouch: true, moveCount: '.$hefe_sudo_slider_shortcode_md_move_count.'});}';
				}else{
					echo '';
				}
				if(!empty($atts['sm_pause']) || !empty($atts['sm_speed']) || !empty($atts['sm_effect']) || !empty($atts['sm_auto']) || !empty($atts['sm_numeric']) || !empty($atts['sm_slide_count']) || !empty($atts['sm_move_count']) || !empty($atts['sm_prev_next']) || !empty($atts['sm_prev_icon']) || !empty($atts['sm_next_icon'])){
					if(!empty($atts['id_number'])){
						$hefe_sudo_slider_shortcode_sm_id_number = $atts['id_number'];
					}else{
						$hefe_sudo_slider_shortcode_sm_id_number = 'default';
					}
					if(!empty($atts['sm_pause'])){
						$hefe_sudo_slider_shortcode_sm_pause = $atts['sm_pause'];
					}elseif(!empty($atts['pause'])){
						$hefe_sudo_slider_shortcode_sm_pause = $atts['pause'];
					}else{
						$hefe_sudo_slider_shortcode_sm_pause = '4500';
					}
					if(!empty($atts['sm_speed'])){
						$hefe_sudo_slider_shortcode_sm_speed = $atts['sm_speed'];
					}elseif(!empty($atts['speed'])){
						$hefe_sudo_slider_shortcode_sm_speed = $atts['speed'];
					}else{
						$hefe_sudo_slider_shortcode_sm_speed = '600';
					}
					if(!empty($atts['sm_effect'])){
						$hefe_sudo_slider_shortcode_sm_effect = '"'.$atts['sm_effect'].'"';
					}elseif(!empty($atts['effect'])){
						$hefe_sudo_slider_shortcode_sm_effect = '"'.$atts['effect'].'"';
					}else{
						$hefe_sudo_slider_shortcode_sm_effect = '"'.'slide'.'"';
					}
					if(!empty($atts['sm_auto'])){
						$hefe_sudo_slider_shortcode_sm_auto = $atts['sm_auto'];
					}elseif(!empty($atts['auto'])){
						$hefe_sudo_slider_shortcode_sm_auto = $atts['auto'];
					}else{
						$hefe_sudo_slider_shortcode_sm_auto = 'true';
					}
					if(!empty($atts['sm_numeric'])){
						$hefe_sudo_slider_shortcode_sm_numeric = $atts['sm_numeric'];
					}elseif(!empty($atts['numeric'])){
						$hefe_sudo_slider_shortcode_sm_numeric = $atts['numeric'];
					}else{
						$hefe_sudo_slider_shortcode_sm_numeric = 'true';
					}
					if(!empty($atts['sm_slide_count'])){
						$hefe_sudo_slider_shortcode_sm_slide_count = $atts['sm_slide_count'];
					}elseif(!empty($atts['slide_count'])){
						$hefe_sudo_slider_shortcode_sm_slide_count = $atts['slide_count'];
					}else{
						$hefe_sudo_slider_shortcode_sm_slide_count = '1';
					}
					if(!empty($atts['sm_move_count'])){
						$hefe_sudo_slider_shortcode_sm_move_count = $atts['sm_move_count'];
					}elseif(!empty($atts['move_count'])){
						$hefe_sudo_slider_shortcode_sm_move_count = $atts['move_count'];
					}else{
						$hefe_sudo_slider_shortcode_sm_move_count = '1';
					}
					if(!empty($atts['sm_prev_next'])){
						$hefe_sudo_slider_shortcode_sm_prev_next = $atts['sm_prev_next'];
					}elseif(!empty($atts['prev_next'])){
						$hefe_sudo_slider_shortcode_sm_prev_next = $atts['prev_next'];
					}else{
						$hefe_sudo_slider_shortcode_sm_prev_next = 'false';
					}
					if(!empty($atts['sm_prev_icon'])){
						$hefe_sudo_slider_shortcode_sm_prev_icon = $atts['sm_prev_icon'];
					}elseif(!empty($atts['prev_icon'])){
						$hefe_sudo_slider_shortcode_sm_prev_icon = $atts['prev_icon'];
					}else{
						$hefe_sudo_slider_shortcode_sm_prev_icon = '◀';
					}
					if(!empty($atts['sm_next_icon'])){
						$hefe_sudo_slider_shortcode_sm_next_icon = $atts['sm_next_icon'];
					}elseif(!empty($atts['next_icon'])){
						$hefe_sudo_slider_shortcode_sm_next_icon = $atts['next_icon'];
					}else{
						$hefe_sudo_slider_shortcode_sm_next_icon = '▶';
					}
					echo 'else if ($(window).width() > 576) {var sudoSlider = $(".hefe-sudo-slider-parent.hefe-sudo-slider-parent-'.$hefe_sudo_slider_shortcode_sm_id_number.' .hefe-sudo-slider-parent-interior").sudoSlider({ effect: '.$hefe_sudo_slider_shortcode_sm_effect.',pause: '.$hefe_sudo_slider_shortcode_sm_pause.',speed: '.$hefe_sudo_slider_shortcode_sm_speed.',auto: '.$hefe_sudo_slider_shortcode_sm_auto.',prevNext: '.$hefe_sudo_slider_shortcode_sm_prev_next.',prevHtml:"<a class=prevBtn>'.$hefe_sudo_slider_shortcode_sm_prev_icon.'</a>",nextHtml:"<a class=nextBtn>'.$hefe_sudo_slider_shortcode_sm_next_icon.'</a>",numeric: '.$hefe_sudo_slider_shortcode_sm_numeric.',continuous: true,customLink: ".hefe-sudo-slider-'.$hefe_sudo_slider_shortcode_sm_id_number.'-link",slideCount: '.$hefe_sudo_slider_shortcode_sm_slide_count.', touch: true, mouseTouch: true, moveCount: '.$hefe_sudo_slider_shortcode_sm_move_count.'});}';
				}else{
					echo '';
				}
				if(!empty($atts['xs_pause']) || !empty($atts['xs_speed']) || !empty($atts['xs_effect']) || !empty($atts['xs_auto']) || !empty($atts['xs_numeric']) || !empty($atts['xs_slide_count']) || !empty($atts['xs_move_count']) || !empty($atts['xs_prev_next']) || !empty($atts['xs_prev_icon']) || !empty($atts['xs_next_icon'])){
					if(!empty($atts['id_number'])){
						$hefe_sudo_slider_shortcode_xs_id_number = $atts['id_number'];
					}else{
						$hefe_sudo_slider_shortcode_xs_id_number = 'default';
					}
					if(!empty($atts['xs_pause'])){
						$hefe_sudo_slider_shortcode_xs_pause = $atts['xs_pause'];
					}elseif(!empty($atts['pause'])){
						$hefe_sudo_slider_shortcode_xs_pause = $atts['pause'];
					}else{
						$hefe_sudo_slider_shortcode_xs_pause = '4500';
					}
					if(!empty($atts['xs_speed'])){
						$hefe_sudo_slider_shortcode_xs_speed = $atts['xs_speed'];
					}elseif(!empty($atts['speed'])){
						$hefe_sudo_slider_shortcode_xs_speed = $atts['speed'];
					}else{
						$hefe_sudo_slider_shortcode_xs_speed = '600';
					}
					if(!empty($atts['xs_effect'])){
						$hefe_sudo_slider_shortcode_xs_effect = '"'.$atts['xs_effect'].'"';
					}elseif(!empty($atts['effect'])){
						$hefe_sudo_slider_shortcode_xs_effect = '"'.$atts['effect'].'"';
					}else{
						$hefe_sudo_slider_shortcode_xs_effect = '"'.'slide'.'"';
					}
					if(!empty($atts['xs_auto'])){
						$hefe_sudo_slider_shortcode_xs_auto = $atts['xs_auto'];
					}elseif(!empty($atts['auto'])){
						$hefe_sudo_slider_shortcode_xs_auto = $atts['auto'];
					}else{
						$hefe_sudo_slider_shortcode_xs_auto = 'true';
					}
					if(!empty($atts['xs_numeric'])){
						$hefe_sudo_slider_shortcode_xs_numeric = $atts['xs_numeric'];
					}elseif(!empty($atts['numeric'])){
						$hefe_sudo_slider_shortcode_xs_numeric = $atts['numeric'];
					}else{
						$hefe_sudo_slider_shortcode_xs_numeric = 'true';
					}
					if(!empty($atts['xs_slide_count'])){
						$hefe_sudo_slider_shortcode_xs_slide_count = $atts['xs_slide_count'];
					}elseif(!empty($atts['slide_count'])){
						$hefe_sudo_slider_shortcode_xs_slide_count = $atts['slide_count'];
					}else{
						$hefe_sudo_slider_shortcode_xs_slide_count = '1';
					}
					if(!empty($atts['xs_move_count'])){
						$hefe_sudo_slider_shortcode_xs_move_count = $atts['xs_move_count'];
					}elseif(!empty($atts['move_count'])){
						$hefe_sudo_slider_shortcode_xs_move_count = $atts['move_count'];
					}else{
						$hefe_sudo_slider_shortcode_xs_move_count = '1';
					}
					if(!empty($atts['xs_prev_next'])){
						$hefe_sudo_slider_shortcode_xs_prev_next = $atts['xs_prev_next'];
					}elseif(!empty($atts['prev_next'])){
						$hefe_sudo_slider_shortcode_xs_prev_next = $atts['prev_next'];
					}else{
						$hefe_sudo_slider_shortcode_xs_prev_next = 'false';
					}
					if(!empty($atts['xs_prev_icon'])){
						$hefe_sudo_slider_shortcode_xs_prev_icon = $atts['xs_prev_icon'];
					}elseif(!empty($atts['prev_icon'])){
						$hefe_sudo_slider_shortcode_xs_prev_icon = $atts['prev_icon'];
					}else{
						$hefe_sudo_slider_shortcode_xs_prev_icon = '◀';
					}
					if(!empty($atts['xs_next_icon'])){
						$hefe_sudo_slider_shortcode_xs_next_icon = $atts['xs_next_icon'];
					}elseif(!empty($atts['next_icon'])){
						$hefe_sudo_slider_shortcode_xs_next_icon = $atts['next_icon'];
					}else{
						$hefe_sudo_slider_shortcode_xs_next_icon = '▶';
					}
					echo 'else if ($(window).width() > 0) {var sudoSlider = $(".hefe-sudo-slider-parent.hefe-sudo-slider-parent-'.$hefe_sudo_slider_shortcode_xs_id_number.' .hefe-sudo-slider-parent-interior").sudoSlider({ effect: '.$hefe_sudo_slider_shortcode_xs_effect.',pause: '.$hefe_sudo_slider_shortcode_xs_pause.',speed: '.$hefe_sudo_slider_shortcode_xs_speed.',auto: '.$hefe_sudo_slider_shortcode_xs_auto.',prevNext: '.$hefe_sudo_slider_shortcode_xs_prev_next.',prevHtml:"<a class=prevBtn>'.$hefe_sudo_slider_shortcode_xs_prev_icon.'</a>",nextHtml:"<a class=nextBtn>'.$hefe_sudo_slider_shortcode_xs_next_icon.'</a>",numeric: '.$hefe_sudo_slider_shortcode_xs_numeric.',continuous: true,customLink: ".hefe-sudo-slider-'.$hefe_sudo_slider_shortcode_xs_id_number.'-link",slideCount: '.$hefe_sudo_slider_shortcode_xs_slide_count.', touch: true, mouseTouch: true, moveCount: '.$hefe_sudo_slider_shortcode_xs_move_count.'});}';
				}else{
					echo 'else if ($(window).width() > 0) {var sudoSlider = $(".hefe-sudo-slider-parent.hefe-sudo-slider-parent-'.$hefe_sudo_slider_shortcode_id_number.' .hefe-sudo-slider-parent-interior").sudoSlider({ effect: '.$hefe_sudo_slider_shortcode_effect.',pause: '.$hefe_sudo_slider_shortcode_pause.',speed: '.$hefe_sudo_slider_shortcode_speed.',auto: '.$hefe_sudo_slider_shortcode_auto.',prevNext: '.$hefe_sudo_slider_shortcode_prev_next.',prevHtml:"<a class=prevBtn>'.$hefe_sudo_slider_shortcode_prev_icon.'</a>",nextHtml:"<a class=nextBtn>'.$hefe_sudo_slider_shortcode_next_icon.'</a>",numeric: '.$hefe_sudo_slider_shortcode_numeric.',continuous: true,customLink: ".hefe-sudo-slider-'.$hefe_sudo_slider_shortcode_id_number.'-link",slideCount: '.$hefe_sudo_slider_shortcode_slide_count.', touch: true, mouseTouch: true, moveCount: '.$hefe_sudo_slider_shortcode_move_count.',});}';
				}

				echo '})(jQuery);</script>';
	        }, 999999);
		}else{
			$hefe_sudo_slider_id_class = 'hefe-sudo-slider-parent-default';
		}
		return '<div id="'.esc_attr($a['id']).'" class="hefe-sudo-slider-parent '.$hefe_sudo_slider_id_class.'" '.esc_attr($a['etc']).'><div class="hefe-sudo-slider-parent-interior">'.do_shortcode($content).'</div></div>';
	}
}
// Sudo Slider Child
if(!function_exists('hefe_shortcode_sudo_slider_child')){
	if(get_option('hefe_control_customizer_control_shortcode_name')){
		add_shortcode(hefe_shortcode_name.'_sudo_slider_child', 'hefe_shortcode_sudo_slider_child');
	}
	add_shortcode('hefe_sudo_slider_child', 'hefe_shortcode_sudo_slider_child');
	function hefe_shortcode_sudo_slider_child($atts, $content = null){
		wp_enqueue_style('hefe-sudo-slider-inc-style');
		wp_enqueue_script('hefe-sudo-slider-bbq-script');
		wp_enqueue_script('hefe-sudo-slider-hashchange-script');
		wp_enqueue_script('hefe-sudo-slider-properload-script');
		wp_enqueue_script('hefe-sudo-slider-script');
		wp_enqueue_script('hefe-sudo-slider-inc-script');
		$a = shortcode_atts(array(
			'id' => '',
			'class' => '',
			'etc' => '',
		), $atts);
		return '<div id="'.esc_attr($a['id']).'" class="hefe-sudo-slider-child '.esc_attr($a['class']).'" '.esc_attr($a['etc']).'>'.do_shortcode($content).'</div>';
	}
}
// Tabs Link
if(!function_exists('hefe_shortcode_tabs_link')){
	if(get_option('hefe_control_customizer_control_shortcode_name')){
		add_shortcode(hefe_shortcode_name.'_tabs_link', 'hefe_shortcode_tabs_link');
		add_shortcode(hefe_shortcode_name.'_click_fade_link', 'hefe_shortcode_tabs_link');
	}
	add_shortcode('hefe_tabs_link', 'hefe_shortcode_tabs_link');
	add_shortcode('hefe_click_fade_link', 'hefe_shortcode_tabs_link');
	function hefe_shortcode_tabs_link($atts, $content = null){
		wp_enqueue_style('hefe-tabs-style');
		wp_enqueue_script('hefe-tabs-script');
		$a = shortcode_atts(array(
			'id' => '',
			'class' => '',
			'paired_id' => '',
			'wrap' => '',
			'style' => '',
			'active' => '',
			'etc' => '',
		), $atts);
		if(esc_attr($a['paired_id'])){
			$paired_id = esc_attr($a['paired_id']);
		}else{
			$paired_id = get_the_ID();
		}
		if(esc_attr($a['wrap'])){
			$wrap = esc_attr($a['wrap']);
		}else{
			$wrap = 'div';
		}
		$style = '';
		if(esc_attr($a['style'])){
			$style = 'hefe-tabs-style-'.esc_attr($a['style']);
			wp_enqueue_style('hefe-tabs-style-'.esc_attr($a['style']));
		}
		$active = '';
		if(esc_attr($a['active']) == 'false' || esc_attr($a['active']) == ''){
			$active = '';
		}else{
			$active = 'hefe-tabs-active';
		}
		return '<'.$wrap.' id="'.esc_attr($a['id']).'" class="hefe-tabs-link '.$style.' '.$active.' '.esc_attr($a['class']).'" data-paired="'.$paired_id.'" '.esc_attr($a['etc']).'>'.do_shortcode($content).'</'.$wrap.'>';
	}
}
// Tabs Content
if(!function_exists('hefe_shortcode_tabs_content')){
	if(get_option('hefe_control_customizer_control_shortcode_name')){
		add_shortcode(hefe_shortcode_name.'_tabs_content', 'hefe_shortcode_tabs_content');
		add_shortcode(hefe_shortcode_name.'_tab_content', 'hefe_shortcode_tabs_content');
		add_shortcode(hefe_shortcode_name.'_click_fade_content', 'hefe_shortcode_tabs_content');
	}
	add_shortcode('hefe_tabs_content', 'hefe_shortcode_tabs_content');
	add_shortcode('hefe_tab_content', 'hefe_shortcode_tabs_content');
	add_shortcode('hefe_click_fade_content', 'hefe_shortcode_tabs_content');
	function hefe_shortcode_tabs_content($atts, $content = null){
		wp_enqueue_style('hefe-tabs-style');
		wp_enqueue_script('hefe-tabs-script');
		$a = shortcode_atts(array(
			'id' => '',
			'class' => '',
			'paired_id' => '',
			'style' => '',
			'active' => '',
			'etc' => '',
		), $atts);
		if(esc_attr($a['paired_id'])){
			$paired_id = esc_attr($a['paired_id']);
		}else{
			$paired_id = get_the_ID();
		}
		$style = '';
		if(esc_attr($a['style'])){
			$style = 'hefe-tabs-style-'.esc_attr($a['style']);
			wp_enqueue_style('hefe-tabs-style-'.esc_attr($a['style']));
		}
		$active = '';
		if(esc_attr($a['active']) == 'false' || esc_attr($a['active']) == ''){
			$active = '';
		}else{
			$active = 'hefe-tabs-active';
		}
		return '<div id="'.esc_attr($a['id']).'" class="hefe-tabs-content '.$style.' '.$active.' '.esc_attr($a['class']).'" data-paired="'.$paired_id.'" '.esc_attr($a['etc']).'>'.do_shortcode($content).'</div>';
	}
}
// Tag List
if(!function_exists('hefe_shortcode_tag_list')){
	if(get_option('hefe_control_customizer_control_shortcode_name')){
		add_shortcode(hefe_shortcode_name.'_tag_list', 'hefe_shortcode_tag_list');
		add_shortcode(hefe_shortcode_name.'_tags_list', 'hefe_shortcode_tag_list');
	}
	add_shortcode('hefe_tag_list', 'hefe_shortcode_tag_list');
	add_shortcode('hefe_tags_list', 'hefe_shortcode_tag_list');
	function hefe_shortcode_tag_list($atts, $content = null){
		$a = shortcode_atts(array(
			'id' => '',
			'class' => '',
			'limit' => '',
			'etc' => '',
		), $atts);
		$tags = get_tags();
		$tag_list = '';
		if($tags){
			$tag_list = '<ul id="'.esc_attr($a['id']).'" class="hefe-tag-list '.esc_attr($a['class']).'" '.esc_attr($a['etc']).'>';
			shuffle($tags);
			$i = 0;
			foreach ( $tags as $tag ) {
				if(esc_attr($a['limit'])){
					if($i == esc_attr($a['limit'])) break;
				}
				$tag_link = get_tag_link( $tag->term_id );
				$tag_list .= '<li><a href="'.$tag_link.'" title="'.$tag->name.' Tag" class="'.$tag->slug.'">';
				$tag_list .= $tag->name.'</a></li>';
				$i++;
			}
			$tag_list .= '</ul>';
		}
		return $tag_list;
	}
}
// Tooltip Parent
if(!function_exists('hefe_shortcode_tooltip_parent')){
	if(get_option('hefe_control_customizer_control_shortcode_name')){
		add_shortcode(hefe_shortcode_name.'_tooltip_parent', 'hefe_shortcode_tooltip_parent');
	}
	add_shortcode('hefe_tooltip_parent', 'hefe_shortcode_tooltip_parent');
	function hefe_shortcode_tooltip_parent($atts, $content = null){
		wp_enqueue_style('hefe-tooltip-style');
		$a = shortcode_atts(array(
			'id' => '',
			'class' => '',
			'vertical_position' => '',
			'horizontal_position' => '',
			'size' => '',
			'etc' => '',
		), $atts);
		$vertical_position = '';
		if(esc_attr($a['vertical_position'])){
			$vertical_position = '-'.esc_attr($a['vertical_position']);
		}
		$horizontal_position = '';
		if(esc_attr($a['horizontal_position'])){
			$horizontal_position = '-'.esc_attr($a['horizontal_position']);
		}
		$size = '';
		if(esc_attr($a['size'])){
			$size = '-'.esc_attr($a['size']);
		}
		return '<div id="'.esc_attr($a['id']).'" class="hefe-tooltip-parent hefe'.$vertical_position.$horizontal_position.$size.'-tooltip '.esc_attr($a['class']).'" '.esc_attr($a['etc']).'>'.do_shortcode($content).'</div>';
	}
}
// Tooltip Child
if(!function_exists('hefe_shortcode_tooltip_child')){
	if(get_option('hefe_control_customizer_control_shortcode_name')){
		add_shortcode(hefe_shortcode_name.'_tooltip_child', 'hefe_shortcode_tooltip_child');
	}
	add_shortcode('hefe_tooltip_child', 'hefe_shortcode_tooltip_child');
	function hefe_shortcode_tooltip_child($atts, $content = null){
		wp_enqueue_style('hefe-tooltip-style');
		$a = shortcode_atts(array(
			'id' => '',
			'class' => '',
			'etc' => '',
		), $atts);
		return '<span id="'.esc_attr($a['id']).'" class="hefe-tooltip-child '.esc_attr($a['class']).'" '.esc_attr($a['etc']).'>'.do_shortcode($content).'</span>';
	}
}
// TwentyTwenty Parent
if(!function_exists('hefe_twentytwenty_shortcode_parent')){
	if(get_option('hefe_control_customizer_control_shortcode_name')){
		add_shortcode(hefe_shortcode_name.'_before_after_parent', 'hefe_twentytwenty_shortcode_parent');
		add_shortcode(hefe_shortcode_name.'_twentytwenty_parent', 'hefe_twentytwenty_shortcode_parent');
	}
	add_shortcode('hefe_before_after_parent', 'hefe_twentytwenty_shortcode_parent');
	add_shortcode('hefe_twentytwenty_parent', 'hefe_twentytwenty_shortcode_parent');
	function hefe_twentytwenty_shortcode_parent($atts, $content = null){
		wp_enqueue_style('hefe-twentytwenty-style');
		wp_enqueue_script('hefe-twentytwenty-event-move-script');
		wp_enqueue_script('hefe-twentytwenty-script');
		wp_enqueue_script('hefe-twentytwenty-inc-script');
		$a = shortcode_atts(array(
			'id' => '',
			'class' => '',
			'etc' => '',
		), $atts);
		return '<div id="'.esc_attr($a['id']).'" class="hefe-twentytwenty-parent '.esc_attr($a['class']).'" '.esc_attr($a['etc']).'>'.do_shortcode($content).'</div>';
	}
}
// TwentyTwenty Child
if(!function_exists('hefe_twentytwenty_shortcode_child')){
	if(get_option('hefe_control_customizer_control_shortcode_name')){
		add_shortcode(hefe_shortcode_name.'_before_after_child', 'hefe_twentytwenty_shortcode_child');
		add_shortcode(hefe_shortcode_name.'_before_after_child_before', 'hefe_twentytwenty_shortcode_child');
		add_shortcode(hefe_shortcode_name.'_before_after_child_after', 'hefe_twentytwenty_shortcode_child');
		add_shortcode(hefe_shortcode_name.'_twentytwenty_child', 'hefe_twentytwenty_shortcode_child');
		add_shortcode(hefe_shortcode_name.'_twentytwenty_child_before', 'hefe_twentytwenty_shortcode_child');
		add_shortcode(hefe_shortcode_name.'_twentytwenty_child_after', 'hefe_twentytwenty_shortcode_child');
	}
	add_shortcode('hefe_before_after_child', 'hefe_twentytwenty_shortcode_child');
	add_shortcode('hefe_before_after_child_before', 'hefe_twentytwenty_shortcode_child');
	add_shortcode('hefe_before_after_child_after', 'hefe_twentytwenty_shortcode_child');
	add_shortcode('hefe_twentytwenty_child', 'hefe_twentytwenty_shortcode_child');
	add_shortcode('hefe_twentytwenty_child_before', 'hefe_twentytwenty_shortcode_child');
	add_shortcode('hefe_twentytwenty_child_after', 'hefe_twentytwenty_shortcode_child');
	function hefe_twentytwenty_shortcode_child($atts, $content = null){
		wp_enqueue_style('hefe-twentytwenty-style');
		wp_enqueue_script('hefe-twentytwenty-event-move-script');
		wp_enqueue_script('hefe-twentytwenty-script');
		wp_enqueue_script('hefe-twentytwenty-inc-script');
		$a = shortcode_atts(array(
			'id' => '',
			'class' => '',
			'src' => '',
			'etc' => '',
		), $atts);
		return '<img id="'.esc_attr($a['id']).'" class="hefe-twentytwenty-child '.esc_attr($a['class']).'" src="'.esc_attr($a['src']).'" '.esc_attr($a['etc']).' />';
	}
}
// UL
if(!function_exists('hefe_shortcode_ul')){
	if(get_option('hefe_control_customizer_control_shortcode_name')){
		add_shortcode(hefe_shortcode_name.'_ul', 'hefe_shortcode_ul');
	}
	add_shortcode('hefe_ul', 'hefe_shortcode_ul');
	function hefe_shortcode_ul($atts, $content = null){
		$a = shortcode_atts(array(
			'id' => '',
			'class' => '',
			'etc' => '',
		), $atts);
		return '<ul id="'.esc_attr($a['id']).'" class="hefe-ul '.esc_attr($a['class']).'" '.esc_attr($a['etc']).'>'.do_shortcode($content).'</ul>';
	}
}
// User Meta
if(!function_exists('hefe_shortcode_user_meta')){
	if(get_option('hefe_control_customizer_control_shortcode_name')){
		add_shortcode(hefe_shortcode_name.'_post_author', 'hefe_shortcode_user_meta');
		add_shortcode(hefe_shortcode_name.'_user_meta', 'hefe_shortcode_user_meta');
	}
	add_shortcode('hefe_post_author', 'hefe_shortcode_user_meta');
	add_shortcode('hefe_user_meta', 'hefe_shortcode_user_meta');
	function hefe_shortcode_user_meta($atts, $content = null){
		$current_user = wp_get_current_user();
		$a = shortcode_atts(array(
			'parameter' => '',
			'user_id' => '',
		), $atts);
		if(esc_attr($a['parameter'])){
			$parameter = esc_attr($a['parameter']);
		}else{
			$parameter = 'display_name';
		}
		if(esc_attr($a['user_id'])){
			$user_id = esc_attr($a['user_id']);
		}else{
			$user_id = $current_user->ID;
		}
		return get_the_author_meta($parameter, $user_id);
	}
}
// If User Role
if(!function_exists('hefe_shortcode_if_user_role')){
	if(get_option('hefe_control_customizer_control_shortcode_name')){
		add_shortcode(hefe_shortcode_name.'_if_user_role', 'hefe_shortcode_if_user_role');
	}
	add_shortcode('hefe_if_user_role', 'hefe_shortcode_if_user_role');
	function hefe_shortcode_if_user_role($atts, $content = null){
		$a = shortcode_atts(array(
			'role' => '',
		), $atts);
		global $current_user;
		wp_get_current_user();
		if(is_user_logged_in() && in_array(esc_attr($a['role']), (array) $current_user->roles)){
			return do_shortcode($content);
		}
	}
}
// If Not User Role
if(!function_exists('hefe_shortcode_if_not_user_role')){
	if(get_option('hefe_control_customizer_control_shortcode_name')){
		add_shortcode(hefe_shortcode_name.'_if_not_user_role', 'hefe_shortcode_if_not_user_role');
	}
	add_shortcode('hefe_if_not_user_role', 'hefe_shortcode_if_not_user_role');
	function hefe_shortcode_if_not_user_role($atts, $content = null){
		$a = shortcode_atts(array(
			'role' => '',
		), $atts);
		global $current_user;
		wp_get_current_user();
		if (is_user_logged_in() && !in_array(esc_attr($a['role']), (array) $current_user->roles)){
			return do_shortcode($content);
		}
	}
}
// Unformat
if(!function_exists('hefe_shortcode_unformat')){
	if(get_option('hefe_control_customizer_control_shortcode_name')){
		add_shortcode(hefe_shortcode_name.'_unformat', 'hefe_shortcode_unformat');
	}
	add_shortcode('hefe_unformat', 'hefe_shortcode_unformat');
	function hefe_shortcode_unformat($atts, $content = null){
		return trim(do_shortcode(shortcode_unautop($content)));
	}
}
// Video Player
if(!function_exists('hefe_shortcode_video_player')){
	if(get_option('hefe_control_customizer_control_shortcode_name')){
		add_shortcode(hefe_shortcode_name.'_video_player', 'hefe_shortcode_video_player');
	}
	add_shortcode('hefe_video_player', 'hefe_shortcode_video_player');
	function hefe_shortcode_video_player($atts, $content = null){
		wp_enqueue_style('hefe-video-player-style');
		$a = shortcode_atts(array(
			'id' => '',
			'class' => '',
			'src' => '',
			'preload' => '',
			'poster' => '',
			'autoplay' => '',
			'controls' => '',
			'loop' => '',
			'muted' => '',
			'modestbranding' => '',
			'autohide' => '',
			'showinfo' => '',
			'playsinline' => '',
			'etc' => '',
		), $atts);
		$banner_src = esc_attr($a['src']); 
		$ogv_url = '';
		$webm_url = '';
		$ogg_url = '';
		$mp4_url = '';
		$hefe_video_player_autoplay = '';
		$hefe_video_player_controls = '';
		$hefe_video_player_loop = '';
		$hefe_video_player_muted = '';
		$hefe_video_player_playsinline = '';
		if(strpos($banner_src, '.mp4') !== false || strpos($banner_src, '.ogg') !== false || strpos($banner_src, '.ogv') !== false || strpos($banner_src, '.webm') !== false){
			$banner_src = str_replace(' ', '', $banner_src); $banner_src = explode(',', $banner_src); 
			foreach($banner_src as $banner_src_url){
				if(strpos($banner_src_url, '.mp4') !== false){ 
					$mp4_url = '<source src="'.$banner_src_url.'" type="video/mp4">'; 
				}elseif(strpos($banner_src_url, '.ogg') !== false){ 
					$ogg_url = '<source src="'.$banner_src_url.'" type="video/ogg">'; 
				}elseif(strpos($banner_src_url, '.ogv') !== false){ 
					$ogv_url = '<source src="'.$banner_src_url.'" type="video/ogg">'; 
				}elseif(strpos($banner_src_url, '.webm') !== false){ 
					$webm_url = '<source src="'.$banner_src_url.'" type="video/webm">'; 
				}
			}
			if(esc_attr($a['autoplay']) == 'true'){
				$hefe_video_player_autoplay = 'autoplay';
			}
			if(esc_attr($a['controls']) == 'false'){
				$hefe_video_player_controls = '';
			}else{
				$hefe_video_player_controls = 'controls';
			}
			if(esc_attr($a['loop']) == 'true'){
				$hefe_video_player_loop = 'loop';
			}
			if(esc_attr($a['muted']) == 'true'){
				$hefe_video_player_muted = 'muted';
			}
			if(esc_attr($a['playsinline']) == 'true'){
				$hefe_video_player_playsinline = 'playsinline';
			}
			return '<div id="'.esc_attr($a['id']).'" class="hefe-video-player-html-parent  '.esc_attr($a['class']).'" '.esc_attr($a['etc']).'><video width="560" height="315" preload="'.esc_attr($a['preload']).'" poster="'.esc_attr($a['poster']).'" '.$hefe_video_player_autoplay.' '.$hefe_video_player_controls.' '.$hefe_video_player_loop.' '.$hefe_video_player_muted.' '.$hefe_video_player_playsinline.' >'.$mp4_url.$webm_url.$ogv_url.$ogg_url.'</video></div>';
		}elseif(strpos($banner_src, 'vimeo.com') !== false){ 
			if(esc_attr($a['muted']) == 'true'){
				$hefe_video_player_muted = '&automute=1';
			}else{
				$hefe_video_player_muted = '&automute=0';
			}
			if(esc_attr($a['autoplay']) == 'true'){
				$hefe_video_player_autoplay = '&autoplay=1';
			}
			if(esc_attr($a['loop']) == 'true'){
				$hefe_video_player_loop = '&loop=1';
			}
			$hefe_video_player_url = $banner_src;
			preg_match("/(https?:\/\/)?(www\.)?(player\.)?vimeo\.com\/([a-z]*\/)*([0-9]{6,11})[?]?.*/", $hefe_video_player_url, $hefe_video_player_url_id);
			$hefe_video_player_url_id = $hefe_video_player_url_id[5];
			return '<div id="'.esc_attr($a['id']).'" class="hefe-video-player-vimeo-parent '.esc_attr($a['class']).'" '.esc_attr($a['etc']).'><iframe width="560" height="315" src="https://player.vimeo.com/video/'.$hefe_video_player_url_id.$hefe_video_player_muted.$hefe_video_player_autoplay.$hefe_video_player_loop.'" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe></div>';
		}elseif(strpos($banner_src, 'youtube.com') !== false || strpos($banner_src, 'youtu.be') !== false){ 
			if(esc_attr($a['autoplay']) == 'true'){
				$hefe_video_player_autoplay = '&autoplay=1';
			}
			if(esc_attr($a['loop']) == 'true'){
				$hefe_video_player_loop = '&loop=1';
			}
			if(esc_attr($a['modestbranding']) == 'true' || esc_attr($a['modestbranding'])){
				$hefe_video_player_modestbranding = '&modestbranding=1';
			}elseif(esc_attr($a['modestbranding']) == 'false' || esc_attr($a['modestbranding']) == '0'){
				$hefe_video_player_modestbranding = '&modestbranding=0';
			}
			if(esc_attr($a['autohide']) == 'true' || esc_attr($a['autohide'])){
				$hefe_video_player_autohide = '&autohide=1';
			}elseif(esc_attr($a['autohide']) == 'flase' || esc_attr($a['autohide']) == '0'){
				$hefe_video_player_autohide = '&autohide=0';
			}
			if(esc_attr($a['showinfo']) == 'true' || esc_attr($a['showinfo'])){
				$hefe_video_player_showinfo = '&showinfo=1';
			}elseif(esc_attr($a['showinfo']) == 'false' || esc_attr($a['showinfo']) == '0'){
				$hefe_video_player_showinfo = '&showinfo=0';
			}
			$hefe_video_player_url = $banner_src;
			parse_str(parse_url($hefe_video_player_url, PHP_URL_QUERY), $my_array_of_vars);
			$hefe_video_player_url_id =  $my_array_of_vars['v'];
			return '<div id="'.esc_attr($a['id']).'" class="hefe-video-player-youtube-parent  '.esc_attr($a['class']).'" '.esc_attr($a['etc']).'><iframe width="560" height="315" src="https://www.youtube.com/embed/'.$hefe_video_player_url_id.'?rel=0'.$hefe_video_player_autoplay.$hefe_video_player_loop.$hefe_video_player_modestbranding.$hefe_video_player_autohide.$hefe_video_player_showinfo.'" frameborder="0" allowfullscreen></iframe></div>';
		}
	}
}
// WP Query
if(!function_exists('hefe_shortcode_wp_query')){
	if(get_option('hefe_control_customizer_control_shortcode_name')){
		add_shortcode(hefe_shortcode_name.'_wp_query', 'hefe_shortcode_wp_query');
		add_shortcode(hefe_shortcode_name.'_query', 'hefe_shortcode_wp_query');
	}
	add_shortcode('hefe_wp_query', 'hefe_shortcode_wp_query');
	add_shortcode('hefe_query', 'hefe_shortcode_wp_query');
	function hefe_shortcode_wp_query($atts, $content = null){
		$a = shortcode_atts(array(
			'author' =>'',
			'author_name' =>'',
			'author__in' =>'',
			'author__not_in' =>'',
			'cat' =>'',
			'category_name' =>'',
			'category__and' =>'',
			'category__in' =>'',
			'category__not_in' =>'',
			'tag' =>'',
			'tag_id' =>'',
			'tag__and' =>'',
			'tag__in' =>'',
			'tag__not_in' =>'',
			'tag_slug__and' =>'',
			'tag_slug__in' =>'',
			'tax_query_relation' =>'',
			'tax_query_one_taxonomy' =>'',
			'tax_query_one_field' =>'',
			'tax_query_one_terms' =>'',
			'tax_query_one_include_children' =>'',
			'tax_query_one_operator' =>'',
			'tax_query_two_taxonomy' =>'',
			'tax_query_two_field' =>'',
			'tax_query_two_terms' =>'',
			'tax_query_two_include_children' =>'',
			'tax_query_two_operator' =>'',
			'p' =>'',
			'name' =>'',
			'page_id' =>'',
			'pagename' =>'',
			'post_parent' =>'',
			'post_parent__in' =>'',
			'post_parent__not_in' =>'',
			'post__in' =>'',
			'post__not_in' =>'',
			'has_password' =>'',
			'post_password' =>'',
			'post_type' =>'any',
			'post_status' =>'',
			'posts_per_page' =>'-1',
			'posts_per_archive_page' =>'',
			'pagination' =>'',
			'offset' =>'',
			'order' =>'',
			'orderby' =>'',
			'year' =>'',
			'monthnum' =>'',
			'w' =>'',
			'day' =>'',
			'hour' =>'',
			'minute' =>'',
			'second' =>'',
			'm' =>'',
			'date_query_year' =>'',
			'date_query_month' =>'',
			'date_query_week' =>'',
			'date_query_day' =>'',
			'date_query_hour' =>'',
			'date_query_minute' =>'',
			'date_query_second' =>'',
			'date_query_after' =>'',
			'date_query_after_year' =>'',
			'date_query_after_month' =>'',
			'date_query_after_day' =>'',
			'date_query_before' =>'',
			'date_query_before_year' =>'',
			'date_query_before_month' =>'',
			'date_query_before_day' =>'',
			'date_query_inclusive' =>'',
			'date_query_compare' =>'',
			'date_query_column' =>'',
			'date_query_relation' =>'',
			'meta_key' =>'',
			'meta_value' =>'',
			'meta_value_num' =>'',
			'meta_compare' =>'',
			'meta_query_relation' =>'',
			'meta_query_one_key' =>'',
			'meta_query_one_value' =>'',
			'meta_query_one_type' =>'',
			'meta_query_one_compare' =>'',
			'meta_query_two_key' =>'',
			'meta_query_two_value' =>'',
			'meta_query_two_type' =>'',
			'meta_query_two_compare' =>'',
			'perm' =>'',
			's' =>'',
		), $atts);
		$args = array();
		if(esc_attr($a['author'])){
			$args['author'] = esc_attr($a['author']);
		}
		if(esc_attr($a['author_name'])){
			$args['author_name'] = esc_attr($a['author_name']);
		}
		if(esc_attr($a['author__in'])){
			$args['author__in'] = explode(',', esc_attr($a['author__in']));
		}
		if(esc_attr($a['author__not_in'])){
			$args['author__not_in'] = explode(',', esc_attr($a['author__not_in']));
		}
		if(esc_attr($a['cat'])){
			$args['cat'] = esc_attr($a['cat']);
		}
		if(esc_attr($a['category_name'])){
			$args['category_name'] = esc_attr($a['category_name']);
		}
		if(esc_attr($a['category__and'])){
			$args['category__and'] = explode(',', esc_attr($a['category__and']));
		}
		if(esc_attr($a['category__in'])){
			$args['category__in'] = explode(',', esc_attr($a['category__in']));
		}
		if(esc_attr($a['category__not_in'])){
			$args['category__not_in'] = explode(',', esc_attr($a['category__not_in']));
		}
		if(esc_attr($a['tag'])){
			$args['tag'] = esc_attr($a['tag']);
		}
		if(esc_attr($a['tag_id'])){
			$args['tag_id'] = esc_attr($a['tag_id']);
		}
		if(esc_attr($a['tag__and'])){
			$args['tag__and'] = explode(',', esc_attr($a['tag__and']));
		}
		if(esc_attr($a['tag__in'])){
			$args['tag__in'] = explode(',', esc_attr($a['tag__in']));
		}
		if(esc_attr($a['tag__not_in'])){
			$args['tag__not_in'] = explode(',', esc_attr($a['tag__not_in']));
		}
		if(esc_attr($a['tag_slug__and'])){
			$args['tag_slug__and'] = explode(',', esc_attr($a['tag_slug__and']));
		}
		if(esc_attr($a['tag_slug__in'])){
			$args['tag_slug__in'] = explode(',', esc_attr($a['tag_slug__in']));
		}
		$hefe_query_tax_query = array();
		$hefe_query_tax_query_array_one = array();
		$hefe_query_tax_query_array_two = array();
		if(esc_attr($a['tax_query_relation'])){
			$hefe_query_tax_query['relation'] = esc_attr($a['tax_query_relation']);
		}
		if(esc_attr($a['tax_query_one_taxonomy'])){
			$hefe_query_tax_query_array_one['taxonomy'] = esc_attr($a['tax_query_one_taxonomy']);
		}
		if(esc_attr($a['tax_query_one_field'])){
			$hefe_query_tax_query_array_one['field'] = esc_attr($a['tax_query_one_field']);
		}
		if(esc_attr($a['tax_query_one_terms'])){
			$hefe_query_tax_query_array_one['terms'] = explode(',', esc_attr($a['tax_query_one_terms']));
		}
		if(esc_attr($a['tax_query_one_include_children'])){
			$hefe_query_tax_query_array_one['include_children'] = esc_attr($a['tax_query_one_include_children']);
		}
		if(esc_attr($a['tax_query_one_operator'])){
			$hefe_query_tax_query_array_one['operator'] = esc_attr($a['tax_query_one_operator']);
		}
		if(esc_attr($a['tax_query_two_taxonomy']) != ''){
			$hefe_query_tax_query_array_two['taxonomy'] = esc_attr($a['tax_query_two_taxonomy']);
		}
		if(esc_attr($a['tax_query_two_field'])){
			$hefe_query_tax_query_array_two['field'] = esc_attr($a['tax_query_two_field']);
		}
		if(esc_attr($a['tax_query_two_terms'])){
			$hefe_query_tax_query_array_two['terms'] = explode(',', esc_attr($a['tax_query_two_terms']));
		}
		if(esc_attr($a['tax_query_two_include_children'])){
			$hefe_query_tax_query_array_two['include_children'] = esc_attr($a['tax_query_two_include_children']);
		}
		if(esc_attr($a['tax_query_two_operator'])){
			$hefe_query_tax_query_array_two['operator'] = esc_attr($a['tax_query_two_operator']);
		}
		if(esc_attr($a['tax_query_one_taxonomy']) && esc_attr($a['tax_query_two_taxonomy'])){
			array_push($hefe_query_tax_query, $hefe_query_tax_query_array_one, $hefe_query_tax_query_array_two);
			$args['tax_query'] = $hefe_query_tax_query;
		}elseif(esc_attr($a['tax_query_one_taxonomy'])){
			array_push($hefe_query_tax_query, $hefe_query_tax_query_array_one);
			$args['tax_query'] = $hefe_query_tax_query;
		}elseif(esc_attr($a['tax_query_two_taxonomy'])){
			array_push($hefe_query_tax_query, $hefe_query_tax_query_array_two);
			$args['tax_query'] = $hefe_query_tax_query;
		}
		if(esc_attr($a['p'])){
			$args['p'] = esc_attr($a['p']);
		}
		if(esc_attr($a['name'])){
			$args['name'] = esc_attr($a['name']);
		}
		if(esc_attr($a['page_id'])){
			$args['page_id'] = esc_attr($a['page_id']);
		}
		if(esc_attr($a['pagename'])){
			$args['pagename'] = esc_attr($a['pagename']);
		}
		if(esc_attr($a['post_parent'])){
			$args['post_parent'] = esc_attr($a['post_parent']);
		}
		if(esc_attr($a['post_parent__in'])){
			$args['post_parent__in'] = explode(',', esc_attr($a['post_parent__in']));
		}
		if(esc_attr($a['post_parent__not_in'])){
			$args['post_parent__not_in'] = explode(',', esc_attr($a['post_parent__not_in']));
		}
		if(esc_attr($a['post__in'])){
			$args['post__in'] = explode(',', esc_attr($a['post__in']));
		}
		if(esc_attr($a['post__not_in'])){
			$args['post__not_in'] = explode(',', esc_attr($a['post__not_in']));
		}
		if(esc_attr($a['has_password'])){
			$args['has_password'] = esc_attr($a['has_password']);
		}
		if(esc_attr($a['post_password'])){
			$args['post_password'] = esc_attr($a['post_password']);
		}
		if(esc_attr($a['post_type'])){
			$args['post_type'] = explode(',', esc_attr($a['post_type']));
		}
		if(esc_attr($a['post_status'])){
			$args['post_status'] = explode(',', esc_attr($a['post_status']));
		}
		if(esc_attr($a['posts_per_page'])){
			$args['posts_per_page'] = esc_attr($a['posts_per_page']);
		}
		if(esc_attr($a['posts_per_archive_page'])){
			$args['posts_per_archive_page'] = esc_attr($a['posts_per_archive_page']);
		}
		if(esc_attr($a['pagination'])){
			$args['paged'] = (get_query_var('paged')) ? absint(get_query_var('paged')): 1;
		}
		if(esc_attr($a['offset'])){
			$args['offset'] = esc_attr($a['offset']);
		}
		if(esc_attr($a['order'])){
			$args['order'] = esc_attr($a['order']);
		}
		if(esc_attr($a['orderby'])){
			$args['orderby'] = esc_attr($a['orderby']);
		}
		if(esc_attr($a['year'])){
			$args['year'] = esc_attr($a['year']);
		}
		if(esc_attr($a['monthnum'])){
			$args['monthnum'] = esc_attr($a['monthnum']);
		}
		if(esc_attr($a['w'])){
			$args['w'] = esc_attr($a['w']);
		}
		if(esc_attr($a['day'])){
			$args['day'] = esc_attr($a['day']);
		}
		if(esc_attr($a['hour'])){
			$args['hour'] = esc_attr($a['hour']);
		}
		if(esc_attr($a['minute'])){
			$args['minute'] = esc_attr($a['minute']);
		}
		if(esc_attr($a['second'])){
			$args['second'] = esc_attr($a['second']);
		}
		if(esc_attr($a['m'])){
			$args['m'] = esc_attr($a['m']);
		}
		$hefe_query_date_query = array();
		$hefe_query_date_query_array_after = array();
		$hefe_query_date_query_array_before = array();
		if(esc_attr($a['date_query_year'])){
			$hefe_query_date_query['year'] = esc_attr($a['date_query_year']);
		}
		if(esc_attr($a['date_query_month'])){
			$hefe_query_date_query['month'] = esc_attr($a['date_query_month']);
		}
		if(esc_attr($a['date_query_week'])){
			$hefe_query_date_query['week'] = esc_attr($a['date_query_week']);
		}
		if(esc_attr($a['date_query_day'])){
			$hefe_query_date_query['day'] = esc_attr($a['date_query_day']);
		}
		if(esc_attr($a['date_query_hour'])){
			$hefe_query_date_query['hour'] = esc_attr($a['date_query_hour']);
		}
		if(esc_attr($a['date_query_minute'])){
			$hefe_query_date_query['minute'] = esc_attr($a['date_query_minute']);
		}
		if(esc_attr($a['date_query_second'])){
			$hefe_query_date_query['second'] = esc_attr($a['date_query_second']);
		}
		if(esc_attr($a['date_query_after_year'])){
			$hefe_query_date_query_array_after['year'] = esc_attr($a['date_query_after_year']);
		}
		if(esc_attr($a['date_query_after_month'])){
			$hefe_query_date_query_array_after['month'] = esc_attr($a['date_query_after_month']);
		}
		if(esc_attr($a['date_query_after_day'])){
			$hefe_query_date_query_array_after['day'] = esc_attr($a['date_query_after_day']);
		}
		if(esc_attr($a['date_query_before_year'])){
			$hefe_query_date_query_array_before['year'] = esc_attr($a['date_query_before_year']);
		}
		if(esc_attr($a['date_query_before_month'])){
			$hefe_query_date_query_array_before['month'] = esc_attr($a['date_query_before_month']);
		}
		if(esc_attr($a['date_query_before_day'])){
			$hefe_query_date_query_array_before['day'] = esc_attr($a['date_query_before_day']);
		}
		if(esc_attr($a['date_query_inclusive'])){
			$hefe_query_date_query['inclusive'] = esc_attr($a['date_query_inclusive']);
		}
		if(esc_attr($a['date_query_compare'])){
			$hefe_query_date_query['compare'] = esc_attr($a['date_query_compare']);
		}
		if(esc_attr($a['date_query_column'])){
			$hefe_query_date_query['column'] = esc_attr($a['date_query_column']);
		}
		if(esc_attr($a['date_query_relation'])){
			$hefe_query_date_query['relation'] = esc_attr($a['date_query_relation']);
		}
		if(!empty($hefe_query_date_query_array_after) && !empty($hefe_query_date_query_array_before)){
			$hefe_query_date_query['after'] = $hefe_query_date_query_array_after;
			$hefe_query_date_query['before'] = $hefe_query_date_query_array_before;
			$args['date_query'] = array($hefe_query_date_query);
		}elseif(!empty($hefe_query_date_query_array_after)){
			$hefe_query_date_query['after'] = $hefe_query_date_query_array_after;
			$args['date_query'] = array($hefe_query_date_query);
		}elseif(!empty($hefe_query_date_query_array_before)){
			$hefe_query_date_query['before'] = $hefe_query_date_query_array_before;
			$args['date_query'] = array($hefe_query_date_query);
		}elseif(!empty($hefe_query_date_query)){
			$args['date_query'] = array($hefe_query_date_query);
		}
		if(esc_attr($a['meta_key'])){
			$args['meta_key'] = esc_attr($a['meta_key']);
		}
		if(esc_attr($a['meta_value'])){
			$args['meta_value'] = esc_attr($a['meta_value']);
		}
		if(esc_attr($a['meta_value_num'])){
			$args['meta_value_num'] = esc_attr($a['meta_value_num']);
		}
		if(esc_attr($a['meta_compare'])){
			$args['meta_compare'] = esc_attr($a['meta_compare']);
		}
		$hefe_query_meta_query = array();
		$hefe_query_meta_query_array_one = array();
		$hefe_query_meta_query_array_two = array();
		if(esc_attr($a['meta_query_relation'])){
			$hefe_query_meta_query['relation'] = esc_attr($a['meta_query_relation']);
		}
		if(esc_attr($a['meta_query_one_key'])){
			$hefe_query_meta_query_array_one['key'] = esc_attr($a['meta_query_one_key']);
		}
		if(esc_attr($a['meta_query_one_value'])){
			$hefe_query_meta_query_array_one['value'] = esc_attr($a['meta_query_one_value']);
		}
		if(esc_attr($a['meta_query_one_type'])){
			$hefe_query_meta_query_array_one['type'] = esc_attr($a['meta_query_one_type']);
		}
		if(esc_attr($a['meta_query_one_compare'])){
			$hefe_query_meta_query_array_one['compare'] = esc_attr($a['meta_query_one_compare']);
		}
		if(esc_attr($a['meta_query_two_key'])){
			$hefe_query_meta_query_array_two['key'] = esc_attr($a['meta_query_two_key']);
		}
		if(esc_attr($a['meta_query_two_value'])){
			$hefe_query_meta_query_array_two['value'] = esc_attr($a['meta_query_two_value']);
		}
		if(esc_attr($a['meta_query_two_type'])){
			$hefe_query_meta_query_array_two['type'] = esc_attr($a['meta_query_two_type']);
		}
		if(esc_attr($a['meta_query_two_compare'])){
			$hefe_query_meta_query_array_two['compare'] = esc_attr($a['meta_query_two_compare']);
		}
		if(esc_attr($a['meta_query_one_key']) && esc_attr($a['meta_query_two_key'])){
			array_push($hefe_query_meta_query, $hefe_query_meta_query_array_one, $hefe_query_meta_query_array_two);
			$args['meta_query'] = $hefe_query_meta_query;
		}elseif(esc_attr($a['meta_query_one_key'])){
			array_push($hefe_query_meta_query, $hefe_query_meta_query_array_one);
			$args['meta_query'] = $hefe_query_meta_query;
		}elseif(esc_attr($a['meta_query_two_key'])){
			array_push($hefe_query_meta_query, $hefe_query_meta_query_array_two);
			$args['meta_query'] = $hefe_query_meta_query;
		}
		if(esc_attr($a['perm'])){
			$args['perm'] = esc_attr($a['perm']);
		}
		if(esc_attr($a['s'])){
			$args['s'] = esc_attr($a['s']);
		}
		$hefe_query = new WP_Query($args);
		$hefe_query_return = '';
		while($hefe_query->have_posts()){ $hefe_query->the_post();
			$hefe_query_return.= do_shortcode($content);
		}
		$pagination_content = '';
		if(esc_attr($a['pagination'])){
			$big = 999999999;
			$pagination_content.= '<div class="hefe-pagination">';
			$pagination_content.=  
				paginate_links(array(
					'base' => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
					'format' =>'?paged=%#%',
					'current' => max(1, get_query_var('paged')),
					'total' => $hefe_query->max_num_pages
				));	
			$pagination_content.= '</div>';
		}
		$hefe_query_return.= $pagination_content;
		$hefe_query_return.= '';
		wp_reset_postdata();
		return $hefe_query_return;
	}
}

/* Widgets
------------------------------ */

// Accordion
if(get_option('hefe_widget_customizer_control_accordion')){
	class hefe_accordion_function_widget extends WP_Widget {
		public function __construct() {
			parent::__construct(
				'hefe_accordion_widget',
				__( 'Accordion ('.hefe_shortcode_name.')', 'hefe' ),
				array(
					'description' => __( 'Display an accordion.', 'hefe' ),
					'classname'   => 'widget_hefe_accordion_widget',
				)
			);
		}
		public function widget( $args, $instance ) {
			$hefe_accordion_widget_link  = ( ! empty( $instance['hefe_accordion_widget_link']  ) ) ? $instance['hefe_accordion_widget_link'] : __( '' );
			$hefe_accordion_widget_content  = ( ! empty( $instance['hefe_accordion_widget_content']  ) ) ? $instance['hefe_accordion_widget_content'] : __( '' );
			$hefe_accordion_widget_active  = ( ! empty( $instance['hefe_accordion_widget_active']  ) ) ? $instance['hefe_accordion_widget_active'] : __( '' );
			$hefe_accordion_widget_style  = ( ! empty( $instance['hefe_accordion_widget_style']  ) ) ? $instance['hefe_accordion_widget_style'] : __( '' );
			// Widget Before
			echo $args['before_widget'];
				// Accordion
				$paired_id = mt_rand(0,999999);
				echo do_shortcode('[hefe_accordion_link class="" paired_id="'.$paired_id.'" active="'.$hefe_accordion_widget_active.'" style="'.$hefe_accordion_widget_style.'"]'.$hefe_accordion_widget_link.'[/hefe_accordion_link][hefe_accordion_content class="" paired_id="'.$paired_id.'" active="'.$hefe_accordion_widget_active.'" style="'.$hefe_accordion_widget_style.'"]'.$hefe_accordion_widget_content.'[/hefe_accordion_content]');
			// Widget After
			echo $args['after_widget'];
		}
		public function form( $instance ) {
			// Set default values
			$instance = wp_parse_args( (array) $instance, array( 
				'hefe_accordion_widget_link' => '',
				'hefe_accordion_widget_content' => '',
				'hefe_accordion_widget_active' => '',
				'hefe_accordion_widget_style' => '',
			) );
			// Retrieve an existing value from the database
			$hefe_accordion_widget_link = !empty( $instance['hefe_accordion_widget_link'] ) ? $instance['hefe_accordion_widget_link'] : '';
			$hefe_accordion_widget_content = !empty( $instance['hefe_accordion_widget_content'] ) ? $instance['hefe_accordion_widget_content'] : '';
			$hefe_accordion_widget_active = !empty( $instance['hefe_accordion_widget_active'] ) ? $instance['hefe_accordion_widget_active'] : '';
			$hefe_accordion_widget_style = !empty( $instance['hefe_accordion_widget_style'] ) ? $instance['hefe_accordion_widget_style'] : '';
			// Link
			echo '<p>';
				echo '<label for="' . $this->get_field_id( 'hefe_accordion_widget_link' ) . '" class="hefe_accordion_widget_link_label">' . __( 'Link', 'hefe' ) . '</label>';
				echo '<textarea rows="4" id="' . $this->get_field_id( 'hefe_accordion_widget_link' ) . '" name="' . $this->get_field_name( 'hefe_accordion_widget_link' ) . '" placeholder="EX: Accordion Link" class="widefat" placeholder="' . esc_attr__( '', 'hefe' ) . '">' . esc_attr( $hefe_accordion_widget_link ) . '</textarea>';
			echo '</p>';
			// Content
			echo '<p>';
				echo '<label for="' . $this->get_field_id( 'hefe_accordion_widget_content' ) . '" class="hefe_accordion_widget_content_label">' . __( 'Content', 'hefe' ) . '</label>';
				echo '<textarea rows="8" id="' . $this->get_field_id( 'hefe_accordion_widget_content' ) . '" name="' . $this->get_field_name( 'hefe_accordion_widget_content' ) . '" placeholder="EX: Accordion Content" class="widefat" placeholder="' . esc_attr__( '', 'hefe' ) . '">' . esc_attr( $hefe_accordion_widget_content ) . '</textarea>';
			echo '</p>';
			// Active
			echo '<p>';
				echo '<label for="' . $this->get_field_id( 'hefe_accordion_widget_active' ) . '" class="hefe_accordion_widget_active_label">' . __( 'Active', 'hefe' ) . '</label>';
				echo '<select id="' . $this->get_field_id( 'hefe_accordion_widget_active' ) . '" name="' . $this->get_field_name( 'hefe_accordion_widget_active' ) . '" class="widefat">';
					echo '<option value="" ' . selected( $hefe_accordion_widget_active, '', false ) . '> ' . __( 'False', 'hefe' ) . '</option>';
					echo '<option value="true" ' . selected( $hefe_accordion_widget_active, 'true', false ) . '> ' . __( 'True', 'hefe' ) . '</option>';
				echo '</select>';
			echo '</p>';
			// Style
			echo '<p>';
				echo '<label for="' . $this->get_field_id( 'hefe_accordion_widget_style' ) . '" class="hefe_accordion_widget_style_label">' . __( 'Style', 'hefe' ) . '</label>';
				echo '<select id="' . $this->get_field_id( 'hefe_accordion_widget_style' ) . '" name="' . $this->get_field_name( 'hefe_accordion_widget_style' ) . '" class="widefat">';
					echo '<option value="" ' . selected( $hefe_accordion_widget_style, '', false ) . '> ' . __( 'None', 'hefe' ) . '</option>';
					echo '<option value="01" ' . selected( $hefe_accordion_widget_style, '01', false ) . '> ' . __( '01', 'hefe' ) . '</option>';
					echo '<option value="02" ' . selected( $hefe_accordion_widget_style, '02', false ) . '> ' . __( '02', 'hefe' ) . '</option>';
				echo '	</select>';
			echo '</p>';
		}
		public function update( $new_instance, $old_instance ) {
			$instance = $old_instance;
			$instance['hefe_accordion_widget_link'] = !empty( $new_instance['hefe_accordion_widget_link'] ) ? $new_instance['hefe_accordion_widget_link'] : '';
			$instance['hefe_accordion_widget_content'] = !empty( $new_instance['hefe_accordion_widget_content'] ) ? $new_instance['hefe_accordion_widget_content'] : '';
			$instance['hefe_accordion_widget_active'] = !empty( $new_instance['hefe_accordion_widget_active'] ) ? strip_tags( $new_instance['hefe_accordion_widget_active'] ) : '';
			$instance['hefe_accordion_widget_style'] = !empty( $new_instance['hefe_accordion_widget_style'] ) ? strip_tags( $new_instance['hefe_accordion_widget_style'] ) : '';
			return $instance;
		}
	}
	add_action('widgets_init', 'register_hefe_accordion_widget');
	function register_hefe_accordion_widget() {
		register_widget('hefe_accordion_function_widget');
	}
}
// Accordion Content
if(get_option('hefe_widget_customizer_control_accordion_content')){
	class hefe_accordion_content_function_widget extends WP_Widget {
		public function __construct() {
			parent::__construct(
				'hefe_accordion_content_widget',
				__( 'Accordion Content ('.hefe_shortcode_name.')', 'hefe' ),
				array(
					'description' => __( 'Display an accordion content.', 'hefe' ),
					'classname'   => 'widget_hefe_accordion_content_widget',
				)
			);
		}
		public function widget( $args, $instance ) {
			$hefe_accordion_content_widget_content  = ( ! empty( $instance['hefe_accordion_content_widget_content']  ) ) ? $instance['hefe_accordion_content_widget_content'] : __( '' );
			$hefe_accordion_content_widget_paired_id  = ( ! empty( $instance['hefe_accordion_content_widget_paired_id']  ) ) ? $instance['hefe_accordion_content_widget_paired_id'] : __( '' );
			$hefe_accordion_content_widget_active  = ( ! empty( $instance['hefe_accordion_content_widget_active']  ) ) ? $instance['hefe_accordion_content_widget_active'] : __( '' );
			$hefe_accordion_content_widget_style  = ( ! empty( $instance['hefe_accordion_content_widget_style']  ) ) ? $instance['hefe_accordion_content_widget_style'] : __( '' );
			// Widget Before
			echo $args['before_widget'];
				// Accordion
				echo do_shortcode('[hefe_accordion_content class="" paired_id="'.$hefe_accordion_content_widget_paired_id.'" active="'.$hefe_accordion_content_widget_active.'" style="'.$hefe_accordion_content_widget_style.'"]'.$hefe_accordion_content_widget_content.'[/hefe_accordion_content]');
			// Widget After
			echo $args['after_widget'];
		}
		public function form( $instance ) {
			// Set default values
			$instance = wp_parse_args( (array) $instance, array( 
				'hefe_accordion_content_widget_content' => '',
				'hefe_accordion_content_widget_paired_id' => '',
				'hefe_accordion_content_widget_active' => '',
				'hefe_accordion_content_widget_style' => '',
			) );
			// Retrieve an existing value from the database
			$hefe_accordion_content_widget_content = !empty( $instance['hefe_accordion_content_widget_content'] ) ? $instance['hefe_accordion_content_widget_content'] : '';
			$hefe_accordion_content_widget_paired_id = !empty( $instance['hefe_accordion_content_widget_paired_id'] ) ? $instance['hefe_accordion_content_widget_paired_id'] : '';
			$hefe_accordion_content_widget_active = !empty( $instance['hefe_accordion_content_widget_active'] ) ? $instance['hefe_accordion_content_widget_active'] : '';
			$hefe_accordion_content_widget_style = !empty( $instance['hefe_accordion_content_widget_style'] ) ? $instance['hefe_accordion_content_widget_style'] : '';
			// Content
			echo '<p>';
				echo '<label for="' . $this->get_field_id( 'hefe_accordion_content_widget_content' ) . '" class="hefe_accordion_content_widget_content_label">' . __( 'Content', 'hefe' ) . '</label>';
				echo '<textarea rows="8" id="' . $this->get_field_id( 'hefe_accordion_content_widget_content' ) . '" name="' . $this->get_field_name( 'hefe_accordion_content_widget_content' ) . '" placeholder="EX: Accordion Content" class="widefat" placeholder="' . esc_attr__( '', 'hefe' ) . '">' . esc_attr( $hefe_accordion_content_widget_content ) . '</textarea>';
			echo '</p>';
			// Paired ID
			echo '<p>';
				echo '<label for="' . $this->get_field_id( 'hefe_accordion_content_widget_paired_id' ) . '" class="hefe_accordion_content_widget_paired_id_label">' . __( 'Paired ID', 'hefe' ) . '</label>';
				echo '<input type="text" id="' . $this->get_field_id( 'hefe_accordion_content_widget_paired_id' ) . '" name="' . $this->get_field_name( 'hefe_accordion_content_widget_paired_id' ) . '" class="widefat" placeholder="' . esc_attr__( 'EX: 1', 'hefe' ) . '" value="' . esc_attr( $hefe_accordion_content_widget_paired_id ) . '">';
			echo '</p>';
			// Active
			echo '<p>';
			echo '<label for="' . $this->get_field_id( 'hefe_accordion_content_widget_active' ) . '" class="hefe_accordion_content_widget_active_label">' . __( 'Active', 'hefe' ) . '</label>';
				echo '<select id="' . $this->get_field_id( 'hefe_accordion_content_widget_active' ) . '" name="' . $this->get_field_name( 'hefe_accordion_content_widget_active' ) . '" class="widefat">';
					echo '<option value="" ' . selected( $hefe_accordion_content_widget_active, '', false ) . '> ' . __( 'False', 'hefe' ) . '</option>';
					echo '<option value="true" ' . selected( $hefe_accordion_content_widget_active, 'true', false ) . '> ' . __( 'True', 'hefe' ) . '</option>';
				echo '</select>';
			echo '</p>';
			// Style
			echo '<p>';
			echo '<label for="' . $this->get_field_id( 'hefe_accordion_content_widget_style' ) . '" class="hefe_accordion_content_widget_style_label">' . __( 'Style', 'hefe' ) . '</label>';
				echo '<select id="' . $this->get_field_id( 'hefe_accordion_content_widget_style' ) . '" name="' . $this->get_field_name( 'hefe_accordion_content_widget_style' ) . '" class="widefat">';
					echo '<option value="" ' . selected( $hefe_accordion_content_widget_style, '', false ) . '> ' . __( 'None', 'hefe' ) . '</option>';
					echo '<option value="01" ' . selected( $hefe_accordion_content_widget_style, '01', false ) . '> ' . __( '01', 'hefe' ) . '</option>';
					echo '<option value="02" ' . selected( $hefe_accordion_content_widget_style, '02', false ) . '> ' . __( '02', 'hefe' ) . '</option>';
				echo '</select>';
			echo '</p>';
		}
		public function update( $new_instance, $old_instance ) {
			$instance = $old_instance;
			$instance['hefe_accordion_content_widget_content'] = !empty( $new_instance['hefe_accordion_content_widget_content'] ) ? $new_instance['hefe_accordion_content_widget_content'] : '';
			$instance['hefe_accordion_content_widget_paired_id'] = !empty( $new_instance['hefe_accordion_content_widget_paired_id'] ) ? $new_instance['hefe_accordion_content_widget_paired_id'] : '';
			$instance['hefe_accordion_content_widget_active'] = !empty( $new_instance['hefe_accordion_content_widget_active'] ) ? strip_tags( $new_instance['hefe_accordion_content_widget_active'] ) : '';
			$instance['hefe_accordion_content_widget_style'] = !empty( $new_instance['hefe_accordion_content_widget_style'] ) ? strip_tags( $new_instance['hefe_accordion_content_widget_style'] ) : '';
			return $instance;
		}
	}
	add_action('widgets_init', 'register_hefe_accordion_content_widget');
	function register_hefe_accordion_content_widget() {
		register_widget('hefe_accordion_content_function_widget');
	}
}
// Accordion Link
if(get_option('hefe_widget_customizer_control_accordion_link')){
	class hefe_accordion_link_function_widget extends WP_Widget {
		public function __construct() {
			parent::__construct(
				'hefe_accordion_link_widget',
				__( 'Accordion Link ('.hefe_shortcode_name.')', 'hefe' ),
				array(
					'description' => __( 'Display an accordion link.', 'hefe' ),
					'classname'   => 'widget_hefe_accordion_link_widget',
				)
			);
		}
		public function widget( $args, $instance ) {
			$hefe_accordion_link_widget_content  = ( ! empty( $instance['hefe_accordion_link_widget_content']  ) ) ? $instance['hefe_accordion_link_widget_content'] : __( '' );
			$hefe_accordion_link_widget_paired_id  = ( ! empty( $instance['hefe_accordion_link_widget_paired_id']  ) ) ? $instance['hefe_accordion_link_widget_paired_id'] : __( '' );
			$hefe_accordion_link_widget_active  = ( ! empty( $instance['hefe_accordion_link_widget_active']  ) ) ? $instance['hefe_accordion_link_widget_active'] : __( '' );
			$hefe_accordion_link_widget_style  = ( ! empty( $instance['hefe_accordion_link_widget_style']  ) ) ? $instance['hefe_accordion_link_widget_style'] : __( '' );
			// Widget Before
			echo $args['before_widget'];
				// Accordion
				echo do_shortcode('[hefe_accordion_link class="" paired_id="'.$hefe_accordion_link_widget_paired_id.'" active="'.$hefe_accordion_link_widget_active.'" style="'.$hefe_accordion_link_widget_style.'"]'.$hefe_accordion_link_widget_content.'[/hefe_accordion_link]');
			// Widget After
			echo $args['after_widget'];
		}
		public function form( $instance ) {
			// Set default values
			$instance = wp_parse_args( (array) $instance, array( 
				'hefe_accordion_link_widget_content' => '',
				'hefe_accordion_link_widget_paired_id' => '',
				'hefe_accordion_link_widget_active' => '',
				'hefe_accordion_link_widget_style' => '',
			) );
			// Retrieve an existing value from the database
			$hefe_accordion_link_widget_content = !empty( $instance['hefe_accordion_link_widget_content'] ) ? $instance['hefe_accordion_link_widget_content'] : '';
			$hefe_accordion_link_widget_paired_id = !empty( $instance['hefe_accordion_link_widget_paired_id'] ) ? $instance['hefe_accordion_link_widget_paired_id'] : '';
			$hefe_accordion_link_widget_active = !empty( $instance['hefe_accordion_link_widget_active'] ) ? $instance['hefe_accordion_link_widget_active'] : '';
			$hefe_accordion_link_widget_style = !empty( $instance['hefe_accordion_link_widget_style'] ) ? $instance['hefe_accordion_link_widget_style'] : '';
			// Link
			echo '<p>';
				echo '<label for="' . $this->get_field_id( 'hefe_accordion_link_widget_content' ) . '" class="hefe_accordion_link_widget_content_label">' . __( 'Content', 'hefe' ) . '</label>';
				echo '<textarea rows="8" id="' . $this->get_field_id( 'hefe_accordion_link_widget_content' ) . '" name="' . $this->get_field_name( 'hefe_accordion_link_widget_content' ) . '" placeholder="EX: Accordion Title" class="widefat" placeholder="' . esc_attr__( '', 'hefe' ) . '">' . esc_attr( $hefe_accordion_link_widget_content ) . '</textarea>';
			echo '</p>';
			// Paired ID
			echo '<p>';
				echo '<label for="' . $this->get_field_id( 'hefe_accordion_link_widget_paired_id' ) . '" class="hefe_accordion_link_widget_paired_id_label">' . __( 'Paired ID', 'hefe' ) . '</label>';
				echo '<input type="text" id="' . $this->get_field_id( 'hefe_accordion_link_widget_paired_id' ) . '" name="' . $this->get_field_name( 'hefe_accordion_link_widget_paired_id' ) . '" class="widefat" placeholder="' . esc_attr__( 'EX: 1', 'hefe' ) . '" value="' . esc_attr( $hefe_accordion_link_widget_paired_id ) . '">';
			echo '</p>';
			// Active
			echo '<p>';
				echo '<label for="' . $this->get_field_id( 'hefe_accordion_link_widget_active' ) . '" class="hefe_accordion_link_widget_active_label">' . __( 'Active', 'hefe' ) . '</label>';
				echo '<select id="' . $this->get_field_id( 'hefe_accordion_link_widget_active' ) . '" name="' . $this->get_field_name( 'hefe_accordion_link_widget_active' ) . '" class="widefat">';
					echo '<option value="" ' . selected( $hefe_accordion_link_widget_active, '', false ) . '> ' . __( 'False', 'hefe' ) . '</option>';
					echo '<option value="true" ' . selected( $hefe_accordion_link_widget_active, 'true', false ) . '> ' . __( 'True', 'hefe' ) . '</option>';
				echo '</select>';
			echo '</p>';
			// Style
			echo '<p>';
				echo '<label for="' . $this->get_field_id( 'hefe_accordion_link_widget_style' ) . '" class="hefe_accordion_link_widget_style_label">' . __( 'Style', 'hefe' ) . '</label>';
				echo '<select id="' . $this->get_field_id( 'hefe_accordion_link_widget_style' ) . '" name="' . $this->get_field_name( 'hefe_accordion_link_widget_style' ) . '" class="widefat">';
					echo '<option value="" ' . selected( $hefe_accordion_link_widget_style, '', false ) . '> ' . __( 'None', 'hefe' ) . '</option>';
					echo '<option value="01" ' . selected( $hefe_accordion_link_widget_style, '01', false ) . '> ' . __( '01', 'hefe' ) . '</option>';
					echo '<option value="02" ' . selected( $hefe_accordion_link_widget_style, '02', false ) . '> ' . __( '02', 'hefe' ) . '</option>';
				echo '</select>';
			echo '</p>';
		}
		public function update( $new_instance, $old_instance ) {
			$instance = $old_instance;
			$instance['hefe_accordion_link_widget_content'] = !empty( $new_instance['hefe_accordion_link_widget_content'] ) ? $new_instance['hefe_accordion_link_widget_content'] : '';
			$instance['hefe_accordion_link_widget_paired_id'] = !empty( $new_instance['hefe_accordion_link_widget_paired_id'] ) ? $new_instance['hefe_accordion_link_widget_paired_id'] : '';
			$instance['hefe_accordion_link_widget_active'] = !empty( $new_instance['hefe_accordion_link_widget_active'] ) ? strip_tags( $new_instance['hefe_accordion_link_widget_active'] ) : '';
			$instance['hefe_accordion_link_widget_style'] = !empty( $new_instance['hefe_accordion_link_widget_style'] ) ? strip_tags( $new_instance['hefe_accordion_link_widget_style'] ) : '';
			return $instance;
		}
	}
	add_action('widgets_init', 'register_hefe_accordion_link_widget');
	function register_hefe_accordion_link_widget() {
		register_widget('hefe_accordion_link_function_widget');
	}
}
// Banner
if(get_option('hefe_widget_customizer_control_banner')){
	class hefe_banner_function_widget extends WP_Widget {
		public function __construct() {
			parent::__construct(
				'hefe_banner_widget',
				__( 'Banner ('.hefe_shortcode_name.')', 'hefe' ),
				array(
					'description' => __( 'Display banner.', 'hefe' ),
					'classname'   => 'widget_hefe_banner_widget',
				)
			);
		}
		public function widget( $args, $instance ) {
			$hefe_banner_widget_content  = ( ! empty( $instance['hefe_banner_widget_content']  ) ) ? $instance['hefe_banner_widget_content'] : __( '' );
			$hefe_banner_widget_src  = ( ! empty( $instance['hefe_banner_widget_src']  ) ) ? $instance['hefe_banner_widget_src'] : __( '' );
			$hefe_banner_widget_height  = ( ! empty( $instance['hefe_banner_widget_height']  ) ) ? $instance['hefe_banner_widget_height'] : __( '' );
			// Widget Before
			echo $args['before_widget'];
				// Banner
				echo do_shortcode('[hefe_banner src="'.$hefe_banner_widget_src.'" height="'.$hefe_banner_widget_height.'"]'.$hefe_banner_widget_content.'[/hefe_banner]');
			// Widget After
			echo $args['after_widget'];
		}
		public function form( $instance ) {
			// Set default values
			$instance = wp_parse_args( (array) $instance, array( 
				'hefe_banner_widget_content' => '',
				'hefe_banner_widget_src' => '',
				'hefe_banner_widget_height' => '',
			) );
			// Retrieve an existing value from the database
			$hefe_banner_widget_content = !empty( $instance['hefe_banner_widget_content'] ) ? $instance['hefe_banner_widget_content'] : '';
			$hefe_banner_widget_src = !empty( $instance['hefe_banner_widget_src'] ) ? $instance['hefe_banner_widget_src'] : '';
			$hefe_banner_widget_height = !empty( $instance['hefe_banner_widget_height'] ) ? $instance['hefe_banner_widget_height'] : '';
			// Content
			echo '<p>';
				echo '<label for="' . $this->get_field_id( 'hefe_banner_widget_content' ) . '" class="hefe_banner_widget_content_label">' . __( 'Banner Content', 'hefe' ) . '</label>';
				echo '<textarea rows="8" id="' . $this->get_field_id( 'hefe_banner_widget_content' ) . '" name="' . $this->get_field_name( 'hefe_banner_widget_content' ) . '" placeholder="EX: Page Title" class="widefat" placeholder="' . esc_attr__( '', 'hefe' ) . '">' . esc_attr( $hefe_banner_widget_content ) . '</textarea>';
			echo '</p>';
			// src
			echo '<p>';
				echo '<label for="' . $this->get_field_id( 'hefe_banner_widget_src' ) . '" class="hefe_banner_widget_src_label">' . __( 'Banner URL', 'hefe' ) . '</label>';
				echo '<input type="text" id="' . $this->get_field_id( 'hefe_banner_widget_src' ) . '" name="' . $this->get_field_name( 'hefe_banner_widget_src' ) . '" class="widefat" placeholder="' . esc_attr__( 'EX: image.jpg', 'hefe' ) . '" value="' . esc_attr( $hefe_banner_widget_src ) . '">';
			echo '</p>';
			// height
			echo '<p>';
				echo '<label for="' . $this->get_field_id( 'hefe_banner_widget_height' ) . '" class="hefe_banner_widget_height_label">' . __( 'Banner Height (px)', 'hefe' ) . '</label>';
				echo '<input type="text" id="' . $this->get_field_id( 'hefe_banner_widget_height' ) . '" name="' . $this->get_field_name( 'hefe_banner_widget_height' ) . '" class="widefat" placeholder="' . esc_attr__( 'EX: 100', 'hefe' ) . '" value="' . esc_attr( $hefe_banner_widget_height ) . '">';
			echo '</p>';
		}
		public function update( $new_instance, $old_instance ) {
			$instance = $old_instance;
			$instance['hefe_banner_widget_content'] = !empty( $new_instance['hefe_banner_widget_content'] ) ? $new_instance['hefe_banner_widget_content'] : '';
			$instance['hefe_banner_widget_src'] = !empty( $new_instance['hefe_banner_widget_src'] ) ? strip_tags( $new_instance['hefe_banner_widget_src'] ) : '';
			$instance['hefe_banner_widget_height'] = !empty( $new_instance['hefe_banner_widget_height'] ) ? strip_tags( $new_instance['hefe_banner_widget_height'] ) : '';
			return $instance;
		}
	}
	add_action('widgets_init', 'register_hefe_banner_widget');
	function register_hefe_banner_widget() {
		register_widget('hefe_banner_function_widget');
	}
}
// Banner Per Page
if(get_option('hefe_widget_customizer_control_banner_per_page')){
	class hefe_banner_per_page_function_widget extends WP_Widget {
		public function __construct() {
			parent::__construct(
				'hefe_banner_per_page_widget',
				__( 'Banner Per Page ('.hefe_shortcode_name.')', 'hefe' ),
				array(
					'description' => __( 'Display banner per page custom fields.', 'hefe' ),
					'classname'   => 'widget_hefe_banner_per_page_widget',
				)
			);
		}
		public function widget( $args, $instance ) {
			// Widget Before
			echo $args['before_widget'];
				// Banner
				echo do_shortcode('[hefe_banner_per_page]');
			// Widget After
			echo $args['after_widget'];
		}
		public function form( $instance ) {
			// Set default values
			$instance = wp_parse_args( (array) $instance, array( 

			) );
		}
		public function update( $new_instance, $old_instance ) {
			$instance = $old_instance;
			return $instance;
		}
	}
	add_action('widgets_init', 'register_hefe_banner_per_page_widget');
	function register_hefe_banner_per_page_widget() {
		register_widget('hefe_banner_per_page_function_widget');
	}
}
// Breadcrumbs
if(get_option('hefe_widget_customizer_control_breadcrumbs')){
	class hefe_breadcrumbs_function_widget extends WP_Widget {
		public function __construct() {
			parent::__construct(
				'hefe_breadcrumbs_widget',
				__( 'Breadcrumbs ('.hefe_shortcode_name.')', 'hefe' ),
				array(
					'description' => __( 'Display breadcrumbs.', 'hefe' ),
					'classname'   => 'widget_hefe_breadcrumbs_widget',
				)
			);
		}
		public function widget( $args, $instance ) {
			// Widget Before
			echo $args['before_widget'];
				// Breadcrumbs
				echo do_shortcode('[hefe_breadcrumbs]');
			// Widget After
			echo $args['after_widget'];
		}
		public function form( $instance ) {
			// Set default values
			$instance = wp_parse_args( (array) $instance, array( 

			) );
		}
		public function update( $new_instance, $old_instance ) {
			$instance = $old_instance;
			return $instance;
		}
	}
	add_action('widgets_init', 'register_hefe_breadcrumbs_widget');
	function register_hefe_breadcrumbs_widget() {
		register_widget('hefe_breadcrumbs_function_widget');
	}
}
// Button
if(get_option('hefe_widget_customizer_control_button')){
	class hefe_button_function_widget extends WP_Widget {
		public function __construct() {
			parent::__construct(
				'hefe_button_widget',
				__( 'Button ('.hefe_shortcode_name.')', 'hefe' ),
				array(
					'description' => __( 'Display a button.', 'hefe' ),
					'classname'   => 'widget_hefe_button_widget',
				)
			);
		}
		public function widget( $args, $instance ) {
			$hefe_button_widget_content  = ( ! empty( $instance['hefe_button_widget_content']  ) ) ? $instance['hefe_button_widget_content'] : __( '' );
			$hefe_button_widget_href  = ( ! empty( $instance['hefe_button_widget_href']  ) ) ? $instance['hefe_button_widget_href'] : __( '' );
			$hefe_button_widget_style  = ( ! empty( $instance['hefe_button_widget_style']  ) ) ? $instance['hefe_button_widget_style'] : __( '' );
			// Widget Before
			echo $args['before_widget'];
				// Button
				echo do_shortcode('[hefe_button href="'.$hefe_button_widget_href.'" style="'.$hefe_button_widget_style.'"]'.$hefe_button_widget_content.'[/hefe_button]');
			// Widget After
			echo $args['after_widget'];
		}
		public function form( $instance ) {
			// Set default values
			$instance = wp_parse_args( (array) $instance, array( 
				'hefe_button_widget_content' => '',
				'hefe_button_widget_href' => '',
				'hefe_button_widget_style' => '',
			) );
			// Retrieve an existing value from the database
			$hefe_button_widget_content = !empty( $instance['hefe_button_widget_content'] ) ? $instance['hefe_button_widget_content'] : '';
			$hefe_button_widget_href = !empty( $instance['hefe_button_widget_href'] ) ? $instance['hefe_button_widget_href'] : '';
			$hefe_button_widget_style = !empty( $instance['hefe_button_widget_style'] ) ? $instance['hefe_button_widget_style'] : '';
			// Content
			echo '<p>';
				echo '<label for="' . $this->get_field_id( 'hefe_button_widget_content' ) . '" class="hefe_button_widget_content_label">' . __( 'Content', 'hefe' ) . '</label>';
				echo '<textarea rows="8" id="' . $this->get_field_id( 'hefe_button_widget_content' ) . '" name="' . $this->get_field_name( 'hefe_button_widget_content' ) . '" class="widefat" placeholder="' . esc_attr__( 'EX: Button Text', 'hefe' ) . '">' . esc_attr( $hefe_button_widget_content ) . '</textarea>';
			echo '</p>';
			// HREF
			echo '<p>';
				echo '<label for="' . $this->get_field_id( 'hefe_button_widget_href' ) . '" class="hefe_button_widget_href_label">' . __( 'Link URL', 'hefe' ) . '</label>';
				echo '<input type="text" id="' . $this->get_field_id( 'hefe_button_widget_href' ) . '" name="' . $this->get_field_name( 'hefe_button_widget_href' ) . '" class="widefat" placeholder="' . esc_attr__( 'EX: http://website.com/..', 'hefe' ) . '" value="' . esc_attr( $hefe_button_widget_href ) . '">';
			echo '</p>';
			// Style
			echo '<p>';
			echo '<label for="' . $this->get_field_id( 'hefe_button_widget_style' ) . '" class="hefe_button_widget_style_label">' . __( 'Style', 'hefe' ) . '</label>';
				echo '<select id="' . $this->get_field_id( 'hefe_button_widget_style' ) . '" name="' . $this->get_field_name( 'hefe_button_widget_style' ) . '" class="widefat">';
					echo '<option value="" ' . selected( $hefe_button_widget_style, '', false ) . '> ' . __( 'None', 'hefe' ) . '</option>';
					echo '<option value="01" ' . selected( $hefe_button_widget_style, '01', false ) . '> ' . __( '01', 'hefe' ) . '</option>';
				echo '</select>';
			echo '</p>';
		}
		public function update( $new_instance, $old_instance ) {
			$instance = $old_instance;
			$instance['hefe_button_widget_content'] = !empty( $new_instance['hefe_button_widget_content'] ) ? $new_instance['hefe_button_widget_content'] : '';
			$instance['hefe_button_widget_href'] = !empty( $new_instance['hefe_button_widget_href'] ) ? $new_instance['hefe_button_widget_href'] : '';
			$instance['hefe_button_widget_style'] = !empty( $new_instance['hefe_button_widget_style'] ) ? strip_tags( $new_instance['hefe_button_widget_style'] ) : '';
			return $instance;
		}
	}
	add_action('widgets_init', 'register_hefe_button_widget');
	function register_hefe_button_widget() {
		register_widget('hefe_button_function_widget');
	}
}
// Copyright
if(get_option('hefe_widget_customizer_control_copyright')){
	class hefe_copyright_function_widget extends WP_Widget {
		public function __construct() {
			parent::__construct(
				'hefe_copyright_widget',
				__( 'Copyright ('.hefe_shortcode_name.')', 'hefe' ),
				array(
					'description' => __( 'Display copyright.', 'hefe' ),
					'classname'   => 'widget_hefe_copyright_widget',
				)
			);
		}
		public function widget( $args, $instance ) {
			$hefe_copyright_widget_text  = ( ! empty( $instance['hefe_copyright_widget_text']  ) ) ? $instance['hefe_copyright_widget_text'] : __( '' );
			$hefe_copyright_widget_separator  = ( ! empty( $instance['hefe_copyright_widget_separator']  ) ) ? $instance['hefe_copyright_widget_separator'] : __( '' );
			$hefe_copyright_widget_responsive  = ( ! empty( $instance['hefe_copyright_widget_responsive']  ) ) ? $instance['hefe_copyright_widget_responsive'] : __( '' );
			// Widget Before
			echo $args['before_widget'];
				// Copyright
				if($hefe_copyright_widget_text != ''){
					echo do_shortcode('[hefe_horizontal_list_parent separator="'.$hefe_copyright_widget_separator.'" responsive="'.$hefe_copyright_widget_responsive.'"]'.$hefe_copyright_widget_text.'[/hefe_horizontal_list_parent]');
				}else{
					echo do_shortcode('[hefe_horizontal_list_parent separator="'.$hefe_copyright_widget_separator.'" responsive="'.$hefe_copyright_widget_responsive.'"][hefe_horizontal_list_child]&copy; '.get_bloginfo('name').' '.date('Y').'[/hefe_horizontal_list_child][hefe_horizontal_list_child]All Rights Reserved[/hefe_horizontal_list_child][/hefe_horizontal_list_parent]');
				}
			// Widget After
			echo $args['after_widget'];
		}
		public function form( $instance ) {
			// Set default values
			$instance = wp_parse_args( (array) $instance, array( 
				'hefe_copyright_widget_text' => '',
				'hefe_copyright_widget_separator' => '',
				'hefe_copyright_widget_responsive' => '',
			) );
			// Retrieve an existing value from the database
			$hefe_copyright_widget_text = !empty( $instance['hefe_copyright_widget_text'] ) ? $instance['hefe_copyright_widget_text'] : '';
			$hefe_copyright_widget_separator = !empty( $instance['hefe_copyright_widget_separator'] ) ? $instance['hefe_copyright_widget_separator'] : '';
			$hefe_copyright_widget_responsive = !empty( $instance['hefe_copyright_widget_responsive'] ) ? $instance['hefe_copyright_widget_responsive'] : '';
			// Text
			echo '<p>';
				echo '<label for="' . $this->get_field_id( 'hefe_copyright_widget_text' ) . '" class="hefe_copyright_widget_text_label">' . __( 'Custom Text', 'hefe' ) . '</label>';
				echo '<textarea rows="8" id="' . $this->get_field_id( 'hefe_copyright_widget_text' ) . '" name="' . $this->get_field_name( 'hefe_copyright_widget_text' ) . '" class="widefat" placeholder="' . esc_attr__( 'EX: ['.hefe_shortcode_name.'_horizontal_list_child]All Rights Reserved[/'.hefe_shortcode_name.'_horizontal_list_child]', 'hefe' ) . '">' . esc_attr( $hefe_copyright_widget_text ) . '</textarea>';
			echo '</p>';
			// Separator
			echo '<p>';
			echo '<label for="' . $this->get_field_id( 'hefe_copyright_widget_separator' ) . '" class="hefe_copyright_widget_separator_label">' . __( 'Separator', 'hefe' ) . '</label>';
				echo '<select id="' . $this->get_field_id( 'hefe_copyright_widget_separator' ) . '" name="' . $this->get_field_name( 'hefe_copyright_widget_separator' ) . '" class="widefat">';
					echo '<option value="" ' . selected( $hefe_copyright_widget_separator, '', false ) . '> ' . __( 'None', 'hefe' ) . '</option>';
					echo '<option value="line" ' . selected( $hefe_copyright_widget_separator, 'line', false ) . '> ' . __( 'Line', 'hefe' ) . '</option>';
					echo '<option value="dot" ' . selected( $hefe_copyright_widget_separator, 'dot', false ) . '> ' . __( 'Dot', 'hefe' ) . '</option>';
				echo '</select>';
			echo '</p>';
			// Responsive
			echo '<p>';
			echo '<label for="' . $this->get_field_id( 'hefe_copyright_widget_responsive' ) . '" class="hefe_copyright_widget_responsive_label">' . __( 'Responsive', 'hefe' ) . '</label>';
				echo '<select id="' . $this->get_field_id( 'hefe_copyright_widget_responsive' ) . '" name="' . $this->get_field_name( 'hefe_copyright_widget_responsive' ) . '" class="widefat">';
					echo '<option value="false" ' . selected( $hefe_copyright_widget_responsive, 'false', false ) . '> ' . __( 'False', 'hefe' ) . '</option>';
					echo '<option value="true" ' . selected( $hefe_copyright_widget_responsive, 'true', false ) . '> ' . __( 'True', 'hefe' ) . '</option>';
				echo '</select>';
			echo '</p>';
		}
		public function update( $new_instance, $old_instance ) {
			$instance = $old_instance;
			$instance['hefe_copyright_widget_text'] = !empty( $new_instance['hefe_copyright_widget_text'] ) ? $new_instance['hefe_copyright_widget_text'] : '';
			$instance['hefe_copyright_widget_separator'] = !empty( $new_instance['hefe_copyright_widget_separator'] ) ? strip_tags( $new_instance['hefe_copyright_widget_separator'] ) : '';
			$instance['hefe_copyright_widget_responsive'] = !empty( $new_instance['hefe_copyright_widget_responsive'] ) ? strip_tags( $new_instance['hefe_copyright_widget_responsive'] ) : '';
			return $instance;
		}
	}
	add_action('widgets_init', 'register_hefe_copyright_widget');
	function register_hefe_copyright_widget() {
		register_widget('hefe_copyright_function_widget');
	}
}
// fancyBox Inline
if(get_option('hefe_widget_customizer_control_fancybox_inline')){
	class hefe_fancybox_inline_function_widget extends WP_Widget {
		public function __construct() {
			parent::__construct(
				'hefe_fancybox_inline_widget',
				__( 'fancyBox Inline ('.hefe_shortcode_name.')', 'hefe' ),
				array(
					'description' => __( 'Display a fancyBox inline.', 'hefe' ),
					'classname'   => 'widget_hefe_fancybox_inline_widget',
				)
			);
		}
		public function widget( $args, $instance ) {
			$hefe_fancybox_inline_widget_link  = ( ! empty( $instance['hefe_fancybox_inline_widget_link']  ) ) ? $instance['hefe_fancybox_inline_widget_link'] : __( '' );
			$hefe_fancybox_inline_widget_content  = ( ! empty( $instance['hefe_fancybox_inline_widget_content']  ) ) ? $instance['hefe_fancybox_inline_widget_content'] : __( '' );
			$hefe_fancybox_inline_widget_group  = ( ! empty( $instance['hefe_fancybox_inline_widget_group']  ) ) ? $instance['hefe_fancybox_inline_widget_group'] : __( '' );
			// Widget Before
			echo $args['before_widget'];
				// fancyBox Inline
				$paired_id = mt_rand(0,999999);
				echo do_shortcode('[hefe_fancybox_inline_link group="'.$hefe_fancybox_inline_widget_group.'" paired_id="'.$paired_id.'"]'.$hefe_fancybox_inline_widget_link.'[/hefe_fancybox_inline_link][hefe_fancybox_inline_content class="" paired_id="'.$paired_id.'"]'.$hefe_fancybox_inline_widget_content.'[/hefe_fancybox_inline_content]');
			// Widget After
			echo $args['after_widget'];
		}
		public function form( $instance ) {
			// Set default values
			$instance = wp_parse_args( (array) $instance, array( 
				'hefe_fancybox_inline_widget_link' => '',
				'hefe_fancybox_inline_widget_content' => '',
				'hefe_fancybox_inline_widget_group' => '',
			) );
			// Retrieve an existing value from the database
			$hefe_fancybox_inline_widget_link = !empty( $instance['hefe_fancybox_inline_widget_link'] ) ? $instance['hefe_fancybox_inline_widget_link'] : '';
			$hefe_fancybox_inline_widget_content = !empty( $instance['hefe_fancybox_inline_widget_content'] ) ? $instance['hefe_fancybox_inline_widget_content'] : '';
			$hefe_fancybox_inline_widget_group = !empty( $instance['hefe_fancybox_inline_widget_group'] ) ? $instance['hefe_fancybox_inline_widget_group'] : '';
			// Link
			echo '<p>';
				echo '<label for="' . $this->get_field_id( 'hefe_fancybox_inline_widget_link' ) . '" class="hefe_fancybox_inline_widget_link_label">' . __( 'Link', 'hefe' ) . '</label>';
				echo '<textarea rows="8" id="' . $this->get_field_id( 'hefe_fancybox_inline_widget_link' ) . '" name="' . $this->get_field_name( 'hefe_fancybox_inline_widget_link' ) . '" placeholder="EX: fancyBox Inline Link" class="widefat" placeholder="' . esc_attr__( '', 'hefe' ) . '">' . esc_attr( $hefe_fancybox_inline_widget_link ) . '</textarea>';
			echo '</p>';
			// Content
			echo '<p>';
				echo '<label for="' . $this->get_field_id( 'hefe_fancybox_inline_widget_content' ) . '" class="hefe_fancybox_inline_widget_content_label">' . __( 'Content', 'hefe' ) . '</label>';
				echo '<textarea rows="8" id="' . $this->get_field_id( 'hefe_fancybox_inline_widget_content' ) . '" name="' . $this->get_field_name( 'hefe_fancybox_inline_widget_content' ) . '" placeholder="EX: fancyBox Inline Content" class="widefat" placeholder="' . esc_attr__( '', 'hefe' ) . '">' . esc_attr( $hefe_fancybox_inline_widget_content ) . '</textarea>';
			echo '</p>';
			// Group
			echo '<p>';
				echo '<label for="' . $this->get_field_id( 'hefe_fancybox_inline_widget_group' ) . '" class="hefe_fancybox_inline_widget_group_label">' . __( 'Group', 'hefe' ) . '</label>';
				echo '<input type="text" id="' . $this->get_field_id( 'hefe_fancybox_inline_widget_group' ) . '" name="' . $this->get_field_name( 'hefe_fancybox_inline_widget_group' ) . '" class="widefat" placeholder="' . esc_attr__( 'EX: 1', 'hefe' ) . '" value="' . esc_attr( $hefe_fancybox_inline_widget_group ) . '">';
			echo '</p>';
		}
		public function update( $new_instance, $old_instance ) {
			$instance = $old_instance;
			$instance['hefe_fancybox_inline_widget_link'] = !empty( $new_instance['hefe_fancybox_inline_widget_link'] ) ? $new_instance['hefe_fancybox_inline_widget_link'] : '';
			$instance['hefe_fancybox_inline_widget_content'] = !empty( $new_instance['hefe_fancybox_inline_widget_content'] ) ? $new_instance['hefe_fancybox_inline_widget_content'] : '';
			$instance['hefe_fancybox_inline_widget_group'] = !empty( $new_instance['hefe_fancybox_inline_widget_group'] ) ? strip_tags( $new_instance['hefe_fancybox_inline_widget_group'] ) : '';
			return $instance;
		}
	}
	add_action('widgets_init', 'register_hefe_fancybox_inline_widget');
	function register_hefe_fancybox_inline_widget() {
		register_widget('hefe_fancybox_inline_function_widget');
	}
}
// Horizontal List
if(get_option('hefe_widget_customizer_control_horizontal_list')){
	class hefe_horizontal_list_function_widget extends WP_Widget {
		public function __construct() {
			parent::__construct(
				'hefe_horizontal_list_widget',
				__( 'Horizontal List ('.hefe_shortcode_name.')', 'hefe' ),
				array(
					'description' => __( 'Display horizontal list.', 'hefe' ),
					'classname'   => 'widget_hefe_horizontal_list_widget',
				)
			);
		}
		public function widget( $args, $instance ) {
			$hefe_horizontal_list_widget_link_01  = ( ! empty( $instance['hefe_horizontal_list_widget_link_01']  ) ) ? $instance['hefe_horizontal_list_widget_link_01'] : __( '' );
			$hefe_horizontal_list_widget_new_tab_01  = ( ! empty( $instance['hefe_horizontal_list_widget_new_tab_01']  ) ) ? $instance['hefe_horizontal_list_widget_new_tab_01'] : __( '' );
			$hefe_horizontal_list_widget_content_01  = ( ! empty( $instance['hefe_horizontal_list_widget_content_01']  ) ) ? $instance['hefe_horizontal_list_widget_content_01'] : __( '' );
			$hefe_horizontal_list_widget_link_02  = ( ! empty( $instance['hefe_horizontal_list_widget_link_02']  ) ) ? $instance['hefe_horizontal_list_widget_link_02'] : __( '' );
			$hefe_horizontal_list_widget_new_tab_02  = ( ! empty( $instance['hefe_horizontal_list_widget_new_tab_02']  ) ) ? $instance['hefe_horizontal_list_widget_new_tab_02'] : __( '' );
			$hefe_horizontal_list_widget_content_02  = ( ! empty( $instance['hefe_horizontal_list_widget_content_02']  ) ) ? $instance['hefe_horizontal_list_widget_content_02'] : __( '' );
			$hefe_horizontal_list_widget_link_03  = ( ! empty( $instance['hefe_horizontal_list_widget_link_03']  ) ) ? $instance['hefe_horizontal_list_widget_link_03'] : __( '' );
			$hefe_horizontal_list_widget_new_tab_03  = ( ! empty( $instance['hefe_horizontal_list_widget_new_tab_03']  ) ) ? $instance['hefe_horizontal_list_widget_new_tab_03'] : __( '' );
			$hefe_horizontal_list_widget_content_03  = ( ! empty( $instance['hefe_horizontal_list_widget_content_03']  ) ) ? $instance['hefe_horizontal_list_widget_content_03'] : __( '' );
			$hefe_horizontal_list_widget_link_04  = ( ! empty( $instance['hefe_horizontal_list_widget_link_04']  ) ) ? $instance['hefe_horizontal_list_widget_link_04'] : __( '' );
			$hefe_horizontal_list_widget_new_tab_04  = ( ! empty( $instance['hefe_horizontal_list_widget_new_tab_04']  ) ) ? $instance['hefe_horizontal_list_widget_new_tab_04'] : __( '' );
			$hefe_horizontal_list_widget_content_04  = ( ! empty( $instance['hefe_horizontal_list_widget_content_04']  ) ) ? $instance['hefe_horizontal_list_widget_content_04'] : __( '' );
			$hefe_horizontal_list_widget_link_05  = ( ! empty( $instance['hefe_horizontal_list_widget_link_05']  ) ) ? $instance['hefe_horizontal_list_widget_link_05'] : __( '' );
			$hefe_horizontal_list_widget_new_tab_05  = ( ! empty( $instance['hefe_horizontal_list_widget_new_tab_05']  ) ) ? $instance['hefe_horizontal_list_widget_new_tab_05'] : __( '' );
			$hefe_horizontal_list_widget_content_05  = ( ! empty( $instance['hefe_horizontal_list_widget_content_05']  ) ) ? $instance['hefe_horizontal_list_widget_content_05'] : __( '' );
			$hefe_horizontal_list_widget_link_06  = ( ! empty( $instance['hefe_horizontal_list_widget_link_06']  ) ) ? $instance['hefe_horizontal_list_widget_link_06'] : __( '' );
			$hefe_horizontal_list_widget_new_tab_06  = ( ! empty( $instance['hefe_horizontal_list_widget_new_tab_06']  ) ) ? $instance['hefe_horizontal_list_widget_new_tab_06'] : __( '' );
			$hefe_horizontal_list_widget_content_06  = ( ! empty( $instance['hefe_horizontal_list_widget_content_06']  ) ) ? $instance['hefe_horizontal_list_widget_content_06'] : __( '' );
			$hefe_horizontal_list_widget_separator  = ( ! empty( $instance['hefe_horizontal_list_widget_separator']  ) ) ? $instance['hefe_horizontal_list_widget_separator'] : __( '' );
			$hefe_horizontal_list_widget_responsive  = ( ! empty( $instance['hefe_horizontal_list_widget_responsive']  ) ) ? $instance['hefe_horizontal_list_widget_responsive'] : __( '' );
			// Widget Before
			echo $args['before_widget'];

				$horizontal_list_children = '';
				
				// Horizontal List 01
				if($hefe_horizontal_list_widget_new_tab_01){
					$new_tab_01 = '_blank';
				}
				$horizontal_list_01_link_begin = '';
				$horizontal_list_01_link_end = '';
				if($hefe_horizontal_list_widget_link_01){
					$horizontal_list_01_link_begin .= '<a href="'.do_shortcode($hefe_horizontal_list_widget_link_01).'" target="'.$new_tab_01.'">';
					$horizontal_list_01_link_end .= '</a>';
				}
				if($hefe_horizontal_list_widget_content_01){
					$horizontal_list_children .= do_shortcode('[hefe_horizontal_list_child]'.$horizontal_list_01_link_begin.$hefe_horizontal_list_widget_content_01.$horizontal_list_01_link_end.'[/hefe_horizontal_list_child]');
				}
				// Horizontal List 02
				if($hefe_horizontal_list_widget_new_tab_02){
					$new_tab_02 = '_blank';
				}
				$horizontal_list_02_link_begin = '';
				$horizontal_list_02_link_end = '';
				if($hefe_horizontal_list_widget_link_02){
					$horizontal_list_02_link_begin .= '<a href="'.do_shortcode($hefe_horizontal_list_widget_link_02).'" target="'.$new_tab_02.'">';
					$horizontal_list_02_link_end .= '</a>';
				}
				if($hefe_horizontal_list_widget_content_02){
					$horizontal_list_children .= do_shortcode('[hefe_horizontal_list_child]'.$horizontal_list_02_link_begin.$hefe_horizontal_list_widget_content_02.$horizontal_list_02_link_end.'[/hefe_horizontal_list_child]');
				}
				// Horizontal List 03
				if($hefe_horizontal_list_widget_new_tab_03){
					$new_tab_03 = '_blank';
				}
				$horizontal_list_03_link_begin = '';
				$horizontal_list_03_link_end = '';
				if($hefe_horizontal_list_widget_link_03){
					$horizontal_list_03_link_begin .= '<a href="'.do_shortcode($hefe_horizontal_list_widget_link_03).'" target="'.$new_tab_03.'">';
					$horizontal_list_03_link_end .= '</a>';
				}
				if($hefe_horizontal_list_widget_content_03){
					$horizontal_list_children .= do_shortcode('[hefe_horizontal_list_child]'.$horizontal_list_03_link_begin.$hefe_horizontal_list_widget_content_03.$horizontal_list_03_link_end.'[/hefe_horizontal_list_child]');
				}
				// Horizontal List 04
				if($hefe_horizontal_list_widget_new_tab_04){
					$new_tab_04 = '_blank';
				}
				$horizontal_list_04_link_begin = '';
				$horizontal_list_04_link_end = '';
				if($hefe_horizontal_list_widget_link_04){
					$horizontal_list_04_link_begin .= '<a href="'.do_shortcode($hefe_horizontal_list_widget_link_04).'" target="'.$new_tab_04.'">';
					$horizontal_list_04_link_end .= '</a>';
				}
				if($hefe_horizontal_list_widget_content_04){
					$horizontal_list_children .= do_shortcode('[hefe_horizontal_list_child]'.$horizontal_list_04_link_begin.$hefe_horizontal_list_widget_content_04.$horizontal_list_04_link_end.'[/hefe_horizontal_list_child]');
				}
				// Horizontal List 05
				if($hefe_horizontal_list_widget_new_tab_05){
					$new_tab_05 = '_blank';
				}
				$horizontal_list_05_link_begin = '';
				$horizontal_list_05_link_end = '';
				if($hefe_horizontal_list_widget_link_05){
					$horizontal_list_05_link_begin .= '<a href="'.do_shortcode($hefe_horizontal_list_widget_link_05).'" target="'.$new_tab_05.'">';
					$horizontal_list_05_link_end .= '</a>';
				}
				if($hefe_horizontal_list_widget_content_05){
					$horizontal_list_children .= do_shortcode('[hefe_horizontal_list_child]'.$horizontal_list_05_link_begin.$hefe_horizontal_list_widget_content_05.$horizontal_list_05_link_end.'[/hefe_horizontal_list_child]');
				}
				// Horizontal List 06
				if($hefe_horizontal_list_widget_new_tab_06){
					$new_tab_06 = '_blank';
				}
				$horizontal_list_06_link_begin = '';
				$horizontal_list_06_link_end = '';
				if($hefe_horizontal_list_widget_link_06){
					$horizontal_list_06_link_begin .= '<a href="'.do_shortcode($hefe_horizontal_list_widget_link_06).'" target="'.$new_tab_06.'">';
					$horizontal_list_06_link_end .= '</a>';
				}
				if($hefe_horizontal_list_widget_content_06){
					$horizontal_list_children .= do_shortcode('[hefe_horizontal_list_child]'.$horizontal_list_06_link_begin.$hefe_horizontal_list_widget_content_06.$horizontal_list_06_link_end.'[/hefe_horizontal_list_child]');
				}
				// Horizontal List Wrap
				echo do_shortcode('[hefe_horizontal_list_parent responsive="'.$hefe_horizontal_list_widget_responsive.'" separator="'.$hefe_horizontal_list_widget_separator.'"]'.do_shortcode($horizontal_list_children).'[/hefe_horizontal_list_parent]');
			// Widget After
			echo $args['after_widget'];
		}
		public function form( $instance ) {
			// Set default values
			$instance = wp_parse_args( (array) $instance, array( 
				'hefe_horizontal_list_widget_link_01' => '',
				'hefe_horizontal_list_widget_new_tab_01' => '',
				'hefe_horizontal_list_widget_content_01' => '',
				'hefe_horizontal_list_widget_link_02' => '',
				'hefe_horizontal_list_widget_new_tab_02' => '',
				'hefe_horizontal_list_widget_content_02' => '',
				'hefe_horizontal_list_widget_link_03' => '',
				'hefe_horizontal_list_widget_new_tab_03' => '',
				'hefe_horizontal_list_widget_content_03' => '',
				'hefe_horizontal_list_widget_link_04' => '',
				'hefe_horizontal_list_widget_new_tab_04' => '',
				'hefe_horizontal_list_widget_content_04' => '',
				'hefe_horizontal_list_widget_link_05' => '',
				'hefe_horizontal_list_widget_new_tab_05' => '',
				'hefe_horizontal_list_widget_content_05' => '',
				'hefe_horizontal_list_widget_link_06' => '',
				'hefe_horizontal_list_widget_new_tab_06' => '',
				'hefe_horizontal_list_widget_content_06' => '',
				'hefe_horizontal_list_widget_separator' => '',
				'hefe_horizontal_list_widget_responsive' => '',
			) );
			// Retrieve an existing value from the database
			$hefe_horizontal_list_widget_link_01 = !empty( $instance['hefe_horizontal_list_widget_link_01'] ) ? $instance['hefe_horizontal_list_widget_link_01'] : '';
			$hefe_horizontal_list_widget_new_tab_01 = !empty( $instance['hefe_horizontal_list_widget_new_tab_01'] ) ? $instance['hefe_horizontal_list_widget_new_tab_01'] : '';
			$hefe_horizontal_list_widget_content_01 = !empty( $instance['hefe_horizontal_list_widget_content_01'] ) ? $instance['hefe_horizontal_list_widget_content_01'] : '';
			$hefe_horizontal_list_widget_link_02 = !empty( $instance['hefe_horizontal_list_widget_link_02'] ) ? $instance['hefe_horizontal_list_widget_link_02'] : '';
			$hefe_horizontal_list_widget_new_tab_02 = !empty( $instance['hefe_horizontal_list_widget_new_tab_02'] ) ? $instance['hefe_horizontal_list_widget_new_tab_02'] : '';
			$hefe_horizontal_list_widget_content_02 = !empty( $instance['hefe_horizontal_list_widget_content_02'] ) ? $instance['hefe_horizontal_list_widget_content_02'] : '';
			$hefe_horizontal_list_widget_link_03 = !empty( $instance['hefe_horizontal_list_widget_link_03'] ) ? $instance['hefe_horizontal_list_widget_link_03'] : '';
			$hefe_horizontal_list_widget_new_tab_03 = !empty( $instance['hefe_horizontal_list_widget_new_tab_03'] ) ? $instance['hefe_horizontal_list_widget_new_tab_03'] : '';
			$hefe_horizontal_list_widget_content_03 = !empty( $instance['hefe_horizontal_list_widget_content_03'] ) ? $instance['hefe_horizontal_list_widget_content_03'] : '';
			$hefe_horizontal_list_widget_link_04 = !empty( $instance['hefe_horizontal_list_widget_link_04'] ) ? $instance['hefe_horizontal_list_widget_link_04'] : '';
			$hefe_horizontal_list_widget_new_tab_04 = !empty( $instance['hefe_horizontal_list_widget_new_tab_04'] ) ? $instance['hefe_horizontal_list_widget_new_tab_04'] : '';
			$hefe_horizontal_list_widget_content_04 = !empty( $instance['hefe_horizontal_list_widget_content_04'] ) ? $instance['hefe_horizontal_list_widget_content_04'] : '';
			$hefe_horizontal_list_widget_link_05 = !empty( $instance['hefe_horizontal_list_widget_link_05'] ) ? $instance['hefe_horizontal_list_widget_link_05'] : '';
			$hefe_horizontal_list_widget_new_tab_05 = !empty( $instance['hefe_horizontal_list_widget_new_tab_05'] ) ? $instance['hefe_horizontal_list_widget_new_tab_05'] : '';
			$hefe_horizontal_list_widget_content_05 = !empty( $instance['hefe_horizontal_list_widget_content_05'] ) ? $instance['hefe_horizontal_list_widget_content_05'] : '';
			$hefe_horizontal_list_widget_link_06 = !empty( $instance['hefe_horizontal_list_widget_link_06'] ) ? $instance['hefe_horizontal_list_widget_link_06'] : '';
			$hefe_horizontal_list_widget_new_tab_06 = !empty( $instance['hefe_horizontal_list_widget_new_tab_06'] ) ? $instance['hefe_horizontal_list_widget_new_tab_06'] : '';
			$hefe_horizontal_list_widget_content_06 = !empty( $instance['hefe_horizontal_list_widget_content_06'] ) ? $instance['hefe_horizontal_list_widget_content_06'] : '';
			$hefe_horizontal_list_widget_separator = !empty( $instance['hefe_horizontal_list_widget_separator'] ) ? $instance['hefe_horizontal_list_widget_separator'] : '';
			$hefe_horizontal_list_widget_responsive = !empty( $instance['hefe_horizontal_list_widget_responsive'] ) ? $instance['hefe_horizontal_list_widget_responsive'] : '';
			// Link 01
			echo '<p>';
				echo '<label for="' . $this->get_field_id( 'hefe_horizontal_list_widget_link_01' ) . '" class="hefe_horizontal_list_widget_link_01_label">' . __( 'Link 01', 'hefe' ) . '</label>';
				echo '<textarea rows="4" id="' . $this->get_field_id( 'hefe_horizontal_list_widget_link_01' ) . '" name="' . $this->get_field_name( 'hefe_horizontal_list_widget_link_01' ) . '" placeholder="EX: Horizontal List Link" class="widefat" placeholder="' . esc_attr__( '', 'hefe' ) . '">' . esc_attr( $hefe_horizontal_list_widget_link_01 ) . '</textarea>';
			echo '</p>';
			// New Tab 01
			echo '<p>';
				echo '<label for="' . $this->get_field_id( 'hefe_horizontal_list_widget_new_tab_01' ) . '" class="hefe_horizontal_list_widget_new_tab_01_label">' . __( 'Open Link In New Tab', 'hefe' ) . '</label>';
				echo '<select id="' . $this->get_field_id( 'hefe_horizontal_list_widget_new_tab_01' ) . '" name="' . $this->get_field_name( 'hefe_horizontal_list_widget_new_tab_01' ) . '" class="widefat">';
					echo '<option value="" ' . selected( $hefe_horizontal_list_widget_new_tab_01, '', false ) . '> ' . __( 'False', 'hefe' ) . '</option>';
					echo '<option value="true" ' . selected( $hefe_horizontal_list_widget_new_tab_01, 'true', false ) . '> ' . __( 'True', 'hefe' ) . '</option>';
				echo '	</select>';
			echo '</p>';
			// Content 01
			echo '<p>';
				echo '<label for="' . $this->get_field_id( 'hefe_horizontal_list_widget_content_01' ) . '" class="hefe_horizontal_list_widget_content_01_label">' . __( 'Content 01', 'hefe' ) . '</label>';
				echo '<textarea rows="8" id="' . $this->get_field_id( 'hefe_horizontal_list_widget_content_01' ) . '" name="' . $this->get_field_name( 'hefe_horizontal_list_widget_content_01' ) . '" placeholder="EX: Horizontal List Content" class="widefat" placeholder="' . esc_attr__( '', 'hefe' ) . '">' . esc_attr( $hefe_horizontal_list_widget_content_01 ) . '</textarea>';
			echo '</p>';
			// Link 02
			echo '<p>';
				echo '<label for="' . $this->get_field_id( 'hefe_horizontal_list_widget_link_02' ) . '" class="hefe_horizontal_list_widget_link_02_label">' . __( 'Link 02', 'hefe' ) . '</label>';
				echo '<textarea rows="4" id="' . $this->get_field_id( 'hefe_horizontal_list_widget_link_02' ) . '" name="' . $this->get_field_name( 'hefe_horizontal_list_widget_link_02' ) . '" placeholder="EX: Horizontal List Link" class="widefat" placeholder="' . esc_attr__( '', 'hefe' ) . '">' . esc_attr( $hefe_horizontal_list_widget_link_02 ) . '</textarea>';
			echo '</p>';
			// New Tab 02
			echo '<p>';
				echo '<label for="' . $this->get_field_id( 'hefe_horizontal_list_widget_new_tab_02' ) . '" class="hefe_horizontal_list_widget_new_tab_02_label">' . __( 'Open Link In New Tab', 'hefe' ) . '</label>';
				echo '<select id="' . $this->get_field_id( 'hefe_horizontal_list_widget_new_tab_02' ) . '" name="' . $this->get_field_name( 'hefe_horizontal_list_widget_new_tab_02' ) . '" class="widefat">';
					echo '<option value="" ' . selected( $hefe_horizontal_list_widget_new_tab_02, '', false ) . '> ' . __( 'False', 'hefe' ) . '</option>';
					echo '<option value="true" ' . selected( $hefe_horizontal_list_widget_new_tab_02, 'true', false ) . '> ' . __( 'True', 'hefe' ) . '</option>';
				echo '	</select>';
			echo '</p>';
			// Content 02
			echo '<p>';
				echo '<label for="' . $this->get_field_id( 'hefe_horizontal_list_widget_content_02' ) . '" class="hefe_horizontal_list_widget_content_02_label">' . __( 'Content 02', 'hefe' ) . '</label>';
				echo '<textarea rows="8" id="' . $this->get_field_id( 'hefe_horizontal_list_widget_content_02' ) . '" name="' . $this->get_field_name( 'hefe_horizontal_list_widget_content_02' ) . '" placeholder="EX: Horizontal List Content" class="widefat" placeholder="' . esc_attr__( '', 'hefe' ) . '">' . esc_attr( $hefe_horizontal_list_widget_content_02 ) . '</textarea>';
			echo '</p>';
			// Link 03
			echo '<p>';
				echo '<label for="' . $this->get_field_id( 'hefe_horizontal_list_widget_link_03' ) . '" class="hefe_horizontal_list_widget_link_03_label">' . __( 'Link 03', 'hefe' ) . '</label>';
				echo '<textarea rows="4" id="' . $this->get_field_id( 'hefe_horizontal_list_widget_link_03' ) . '" name="' . $this->get_field_name( 'hefe_horizontal_list_widget_link_03' ) . '" placeholder="EX: Horizontal List Link" class="widefat" placeholder="' . esc_attr__( '', 'hefe' ) . '">' . esc_attr( $hefe_horizontal_list_widget_link_03 ) . '</textarea>';
			echo '</p>';
			// New Tab 03
			echo '<p>';
				echo '<label for="' . $this->get_field_id( 'hefe_horizontal_list_widget_new_tab_03' ) . '" class="hefe_horizontal_list_widget_new_tab_03_label">' . __( 'Open Link In New Tab', 'hefe' ) . '</label>';
				echo '<select id="' . $this->get_field_id( 'hefe_horizontal_list_widget_new_tab_03' ) . '" name="' . $this->get_field_name( 'hefe_horizontal_list_widget_new_tab_03' ) . '" class="widefat">';
					echo '<option value="" ' . selected( $hefe_horizontal_list_widget_new_tab_03, '', false ) . '> ' . __( 'False', 'hefe' ) . '</option>';
					echo '<option value="true" ' . selected( $hefe_horizontal_list_widget_new_tab_03, 'true', false ) . '> ' . __( 'True', 'hefe' ) . '</option>';
				echo '	</select>';
			echo '</p>';
			// Content 03
			echo '<p>';
				echo '<label for="' . $this->get_field_id( 'hefe_horizontal_list_widget_content_03' ) . '" class="hefe_horizontal_list_widget_content_03_label">' . __( 'Content 03', 'hefe' ) . '</label>';
				echo '<textarea rows="8" id="' . $this->get_field_id( 'hefe_horizontal_list_widget_content_03' ) . '" name="' . $this->get_field_name( 'hefe_horizontal_list_widget_content_03' ) . '" placeholder="EX: Horizontal List Content" class="widefat" placeholder="' . esc_attr__( '', 'hefe' ) . '">' . esc_attr( $hefe_horizontal_list_widget_content_03 ) . '</textarea>';
			echo '</p>';
			// Link 04
			echo '<p>';
				echo '<label for="' . $this->get_field_id( 'hefe_horizontal_list_widget_link_04' ) . '" class="hefe_horizontal_list_widget_link_04_label">' . __( 'Link 04', 'hefe' ) . '</label>';
				echo '<textarea rows="4" id="' . $this->get_field_id( 'hefe_horizontal_list_widget_link_04' ) . '" name="' . $this->get_field_name( 'hefe_horizontal_list_widget_link_04' ) . '" placeholder="EX: Horizontal List Link" class="widefat" placeholder="' . esc_attr__( '', 'hefe' ) . '">' . esc_attr( $hefe_horizontal_list_widget_link_04 ) . '</textarea>';
			echo '</p>';
			// New Tab 04
			echo '<p>';
				echo '<label for="' . $this->get_field_id( 'hefe_horizontal_list_widget_new_tab_04' ) . '" class="hefe_horizontal_list_widget_new_tab_04_label">' . __( 'Open Link In New Tab', 'hefe' ) . '</label>';
				echo '<select id="' . $this->get_field_id( 'hefe_horizontal_list_widget_new_tab_04' ) . '" name="' . $this->get_field_name( 'hefe_horizontal_list_widget_new_tab_04' ) . '" class="widefat">';
					echo '<option value="" ' . selected( $hefe_horizontal_list_widget_new_tab_04, '', false ) . '> ' . __( 'False', 'hefe' ) . '</option>';
					echo '<option value="true" ' . selected( $hefe_horizontal_list_widget_new_tab_04, 'true', false ) . '> ' . __( 'True', 'hefe' ) . '</option>';
				echo '	</select>';
			echo '</p>';
			// Content 04
			echo '<p>';
				echo '<label for="' . $this->get_field_id( 'hefe_horizontal_list_widget_content_04' ) . '" class="hefe_horizontal_list_widget_content_04_label">' . __( 'Content 04', 'hefe' ) . '</label>';
				echo '<textarea rows="8" id="' . $this->get_field_id( 'hefe_horizontal_list_widget_content_04' ) . '" name="' . $this->get_field_name( 'hefe_horizontal_list_widget_content_04' ) . '" placeholder="EX: Horizontal List Content" class="widefat" placeholder="' . esc_attr__( '', 'hefe' ) . '">' . esc_attr( $hefe_horizontal_list_widget_content_04 ) . '</textarea>';
			echo '</p>';
			// Link 05
			echo '<p>';
				echo '<label for="' . $this->get_field_id( 'hefe_horizontal_list_widget_link_05' ) . '" class="hefe_horizontal_list_widget_link_05_label">' . __( 'Link 05', 'hefe' ) . '</label>';
				echo '<textarea rows="4" id="' . $this->get_field_id( 'hefe_horizontal_list_widget_link_05' ) . '" name="' . $this->get_field_name( 'hefe_horizontal_list_widget_link_05' ) . '" placeholder="EX: Horizontal List Link" class="widefat" placeholder="' . esc_attr__( '', 'hefe' ) . '">' . esc_attr( $hefe_horizontal_list_widget_link_05 ) . '</textarea>';
			echo '</p>';
			// New Tab 05
			echo '<p>';
				echo '<label for="' . $this->get_field_id( 'hefe_horizontal_list_widget_new_tab_05' ) . '" class="hefe_horizontal_list_widget_new_tab_05_label">' . __( 'Open Link In New Tab', 'hefe' ) . '</label>';
				echo '<select id="' . $this->get_field_id( 'hefe_horizontal_list_widget_new_tab_05' ) . '" name="' . $this->get_field_name( 'hefe_horizontal_list_widget_new_tab_05' ) . '" class="widefat">';
					echo '<option value="" ' . selected( $hefe_horizontal_list_widget_new_tab_05, '', false ) . '> ' . __( 'False', 'hefe' ) . '</option>';
					echo '<option value="true" ' . selected( $hefe_horizontal_list_widget_new_tab_05, 'true', false ) . '> ' . __( 'True', 'hefe' ) . '</option>';
				echo '	</select>';
			echo '</p>';
			// Content 05
			echo '<p>';
				echo '<label for="' . $this->get_field_id( 'hefe_horizontal_list_widget_content_05' ) . '" class="hefe_horizontal_list_widget_content_05_label">' . __( 'Content 05', 'hefe' ) . '</label>';
				echo '<textarea rows="8" id="' . $this->get_field_id( 'hefe_horizontal_list_widget_content_05' ) . '" name="' . $this->get_field_name( 'hefe_horizontal_list_widget_content_05' ) . '" placeholder="EX: Horizontal List Content" class="widefat" placeholder="' . esc_attr__( '', 'hefe' ) . '">' . esc_attr( $hefe_horizontal_list_widget_content_05 ) . '</textarea>';
			echo '</p>';
			// Link 06
			echo '<p>';
				echo '<label for="' . $this->get_field_id( 'hefe_horizontal_list_widget_link_06' ) . '" class="hefe_horizontal_list_widget_link_06_label">' . __( 'Link 06', 'hefe' ) . '</label>';
				echo '<textarea rows="4" id="' . $this->get_field_id( 'hefe_horizontal_list_widget_link_06' ) . '" name="' . $this->get_field_name( 'hefe_horizontal_list_widget_link_06' ) . '" placeholder="EX: Horizontal List Link" class="widefat" placeholder="' . esc_attr__( '', 'hefe' ) . '">' . esc_attr( $hefe_horizontal_list_widget_link_06 ) . '</textarea>';
			echo '</p>';
			// New Tab 06
			echo '<p>';
				echo '<label for="' . $this->get_field_id( 'hefe_horizontal_list_widget_new_tab_06' ) . '" class="hefe_horizontal_list_widget_new_tab_06_label">' . __( 'Open Link In New Tab', 'hefe' ) . '</label>';
				echo '<select id="' . $this->get_field_id( 'hefe_horizontal_list_widget_new_tab_06' ) . '" name="' . $this->get_field_name( 'hefe_horizontal_list_widget_new_tab_06' ) . '" class="widefat">';
					echo '<option value="" ' . selected( $hefe_horizontal_list_widget_new_tab_06, '', false ) . '> ' . __( 'False', 'hefe' ) . '</option>';
					echo '<option value="true" ' . selected( $hefe_horizontal_list_widget_new_tab_06, 'true', false ) . '> ' . __( 'True', 'hefe' ) . '</option>';
				echo '	</select>';
			echo '</p>';
			// Content 06
			echo '<p>';
				echo '<label for="' . $this->get_field_id( 'hefe_horizontal_list_widget_content_06' ) . '" class="hefe_horizontal_list_widget_content_06_label">' . __( 'Content 06', 'hefe' ) . '</label>';
				echo '<textarea rows="8" id="' . $this->get_field_id( 'hefe_horizontal_list_widget_content_06' ) . '" name="' . $this->get_field_name( 'hefe_horizontal_list_widget_content_06' ) . '" placeholder="EX: Horizontal List Content" class="widefat" placeholder="' . esc_attr__( '', 'hefe' ) . '">' . esc_attr( $hefe_horizontal_list_widget_content_06 ) . '</textarea>';
			echo '</p>';
			// Separator
			echo '<p>';
				echo '<label for="' . $this->get_field_id( 'hefe_horizontal_list_widget_separator' ) . '" class="hefe_horizontal_list_widget_separator_label">' . __( 'Separator', 'hefe' ) . '</label>';
				echo '<select id="' . $this->get_field_id( 'hefe_horizontal_list_widget_separator' ) . '" name="' . $this->get_field_name( 'hefe_horizontal_list_widget_separator' ) . '" class="widefat">';
					echo '<option value="" ' . selected( $hefe_horizontal_list_widget_separator, '', false ) . '> ' . __( 'None', 'hefe' ) . '</option>';
					echo '<option value="line" ' . selected( $hefe_horizontal_list_widget_separator, 'line', false ) . '> ' . __( 'Line', 'hefe' ) . '</option>';
					echo '<option value="dot" ' . selected( $hefe_horizontal_list_widget_separator, 'dot', false ) . '> ' . __( 'Dot', 'hefe' ) . '</option>';
				echo '	</select>';
			echo '</p>';
			// Responsive
			echo '<p>';
				echo '<label for="' . $this->get_field_id( 'hefe_horizontal_list_widget_responsive' ) . '" class="hefe_horizontal_list_widget_responsive_label">' . __( 'Responsive', 'hefe' ) . '</label>';
				echo '<select id="' . $this->get_field_id( 'hefe_horizontal_list_widget_responsive' ) . '" name="' . $this->get_field_name( 'hefe_horizontal_list_widget_responsive' ) . '" class="widefat">';
					echo '<option value="" ' . selected( $hefe_horizontal_list_widget_responsive, '', false ) . '> ' . __( 'False', 'hefe' ) . '</option>';
					echo '<option value="true" ' . selected( $hefe_horizontal_list_widget_responsive, 'true', false ) . '> ' . __( 'True', 'hefe' ) . '</option>';
				echo '	</select>';
			echo '</p>';
		}
		public function update( $new_instance, $old_instance ) {
			$instance = $old_instance;
			$instance['hefe_horizontal_list_widget_link_01'] = !empty( $new_instance['hefe_horizontal_list_widget_link_01'] ) ? $new_instance['hefe_horizontal_list_widget_link_01'] : '';
			$instance['hefe_horizontal_list_widget_new_tab_01'] = !empty( $new_instance['hefe_horizontal_list_widget_new_tab_01'] ) ? $new_instance['hefe_horizontal_list_widget_new_tab_01'] : '';
			$instance['hefe_horizontal_list_widget_content_01'] = !empty( $new_instance['hefe_horizontal_list_widget_content_01'] ) ? $new_instance['hefe_horizontal_list_widget_content_01'] : '';
			$instance['hefe_horizontal_list_widget_link_02'] = !empty( $new_instance['hefe_horizontal_list_widget_link_02'] ) ? $new_instance['hefe_horizontal_list_widget_link_02'] : '';
			$instance['hefe_horizontal_list_widget_new_tab_02'] = !empty( $new_instance['hefe_horizontal_list_widget_new_tab_02'] ) ? $new_instance['hefe_horizontal_list_widget_new_tab_02'] : '';
			$instance['hefe_horizontal_list_widget_content_02'] = !empty( $new_instance['hefe_horizontal_list_widget_content_02'] ) ? $new_instance['hefe_horizontal_list_widget_content_02'] : '';
			$instance['hefe_horizontal_list_widget_link_03'] = !empty( $new_instance['hefe_horizontal_list_widget_link_03'] ) ? $new_instance['hefe_horizontal_list_widget_link_03'] : '';
			$instance['hefe_horizontal_list_widget_new_tab_03'] = !empty( $new_instance['hefe_horizontal_list_widget_new_tab_03'] ) ? $new_instance['hefe_horizontal_list_widget_new_tab_03'] : '';
			$instance['hefe_horizontal_list_widget_content_03'] = !empty( $new_instance['hefe_horizontal_list_widget_content_03'] ) ? $new_instance['hefe_horizontal_list_widget_content_03'] : '';
			$instance['hefe_horizontal_list_widget_link_04'] = !empty( $new_instance['hefe_horizontal_list_widget_link_04'] ) ? $new_instance['hefe_horizontal_list_widget_link_04'] : '';
			$instance['hefe_horizontal_list_widget_new_tab_04'] = !empty( $new_instance['hefe_horizontal_list_widget_new_tab_04'] ) ? $new_instance['hefe_horizontal_list_widget_new_tab_04'] : '';
			$instance['hefe_horizontal_list_widget_content_04'] = !empty( $new_instance['hefe_horizontal_list_widget_content_04'] ) ? $new_instance['hefe_horizontal_list_widget_content_04'] : '';
			$instance['hefe_horizontal_list_widget_link_05'] = !empty( $new_instance['hefe_horizontal_list_widget_link_05'] ) ? $new_instance['hefe_horizontal_list_widget_link_05'] : '';
			$instance['hefe_horizontal_list_widget_new_tab_05'] = !empty( $new_instance['hefe_horizontal_list_widget_new_tab_05'] ) ? $new_instance['hefe_horizontal_list_widget_new_tab_05'] : '';
			$instance['hefe_horizontal_list_widget_content_05'] = !empty( $new_instance['hefe_horizontal_list_widget_content_05'] ) ? $new_instance['hefe_horizontal_list_widget_content_05'] : '';
			$instance['hefe_horizontal_list_widget_link_06'] = !empty( $new_instance['hefe_horizontal_list_widget_link_06'] ) ? $new_instance['hefe_horizontal_list_widget_link_06'] : '';
			$instance['hefe_horizontal_list_widget_new_tab_06'] = !empty( $new_instance['hefe_horizontal_list_widget_new_tab_06'] ) ? $new_instance['hefe_horizontal_list_widget_new_tab_06'] : '';
			$instance['hefe_horizontal_list_widget_content_06'] = !empty( $new_instance['hefe_horizontal_list_widget_content_06'] ) ? $new_instance['hefe_horizontal_list_widget_content_06'] : '';
			$instance['hefe_horizontal_list_widget_separator'] = !empty( $new_instance['hefe_horizontal_list_widget_separator'] ) ? strip_tags( $new_instance['hefe_horizontal_list_widget_separator'] ) : '';
			$instance['hefe_horizontal_list_widget_responsive'] = !empty( $new_instance['hefe_horizontal_list_widget_responsive'] ) ? strip_tags( $new_instance['hefe_horizontal_list_widget_responsive'] ) : '';
			return $instance;
		}
	}
	add_action('widgets_init', 'register_hefe_horizontal_list_widget');
	function register_hefe_horizontal_list_widget() {
		register_widget('hefe_horizontal_list_function_widget');
	}
}
// Related Article
if(get_option('hefe_widget_customizer_control_related_article')){
	class hefe_related_article_function_widget extends WP_Widget {
		public function __construct() {
			parent::__construct(
				'hefe_related_article_widget',
				__( 'Related Article ('.hefe_shortcode_name.')', 'hefe' ),
				array(
					'description' => __( 'Display a related article.', 'hefe' ),
					'classname'   => 'widget_hefe_related_article_widget',
				)
			);
		}
		public function widget( $args, $instance ) {
			$hefe_related_article_widget_post_type  = ( ! empty( $instance['hefe_related_article_widget_post_type']  ) ) ? $instance['hefe_related_article_widget_post_type'] : __( '' );
			$hefe_related_article_widget_page_id  = ( ! empty( $instance['hefe_related_article_widget_page_id']  ) ) ? $instance['hefe_related_article_widget_page_id'] : __( '' );
			$hefe_related_article_widget_cat  = ( ! empty( $instance['hefe_related_article_widget_cat']  ) ) ? $instance['hefe_related_article_widget_cat'] : __( '' );
			$hefe_related_article_widget_posts_per_page  = ( ! empty( $instance['hefe_related_article_widget_posts_per_page']  ) ) ? $instance['hefe_related_article_widget_posts_per_page'] : __( '' );
			$hefe_related_article_widget_order  = ( ! empty( $instance['hefe_related_article_widget_order']  ) ) ? $instance['hefe_related_article_widget_order'] : __( '' );
			$hefe_related_article_widget_orderby  = ( ! empty( $instance['hefe_related_article_widget_orderby']  ) ) ? $instance['hefe_related_article_widget_orderby'] : __( '' );
			$hefe_related_article_widget_post_not_in  = ( ! empty( $instance['hefe_related_article_widget_post_not_in']  ) ) ? $instance['hefe_related_article_widget_post_not_in'] : __( '' );
			$hefe_related_article_widget_style  = ( ! empty( $instance['hefe_related_article_widget_style']  ) ) ? $instance['hefe_related_article_widget_style'] : __( '' );
			// Widget Before
			echo $args['before_widget'];
				// Related Article
				echo do_shortcode('[hefe_related_article post_type="'.$hefe_related_article_widget_post_type.'" page_id="'.$hefe_related_article_widget_page_id.'" cat="'.$hefe_related_article_widget_cat.'" posts_per_page="'.$hefe_related_article_widget_posts_per_page.'" order="'.$hefe_related_article_widget_order.'" orderby="'.$hefe_related_article_widget_orderby.'" post__not_in="'.$hefe_related_article_widget_post_not_in.'" style="'.$hefe_related_article_widget_style.'"]');
			// Widget After
			echo $args['after_widget'];
		}
		public function form( $instance ) {
			// Set default values
			$instance = wp_parse_args( (array) $instance, array( 
				'hefe_related_article_widget_post_type' => '',
				'hefe_related_article_widget_page_id' => '',
				'hefe_related_article_widget_cat' => '',
				'hefe_related_article_widget_posts_per_page' => '',
				'hefe_related_article_widget_order' => '',
				'hefe_related_article_widget_orderby' => '',
				'hefe_related_article_widget_post_not_in' => '',
				'hefe_related_article_widget_style' => '',
			) );
			// Retrieve an existing value from the database
			$hefe_related_article_widget_post_type = !empty( $instance['hefe_related_article_widget_post_type'] ) ? $instance['hefe_related_article_widget_post_type'] : '';
			$hefe_related_article_widget_page_id = !empty( $instance['hefe_related_article_widget_page_id'] ) ? $instance['hefe_related_article_widget_page_id'] : '';
			$hefe_related_article_widget_cat = !empty( $instance['hefe_related_article_widget_cat'] ) ? $instance['hefe_related_article_widget_cat'] : '';
			$hefe_related_article_widget_posts_per_page = !empty( $instance['hefe_related_article_widget_posts_per_page'] ) ? $instance['hefe_related_article_widget_posts_per_page'] : '';
			$hefe_related_article_widget_order = !empty( $instance['hefe_related_article_widget_order'] ) ? $instance['hefe_related_article_widget_order'] : '';
			$hefe_related_article_widget_orderby = !empty( $instance['hefe_related_article_widget_orderby'] ) ? $instance['hefe_related_article_widget_orderby'] : '';
			$hefe_related_article_widget_post_not_in = !empty( $instance['hefe_related_article_widget_post_not_in'] ) ? $instance['hefe_related_article_widget_post_not_in'] : '';
			$hefe_related_article_widget_style = !empty( $instance['hefe_related_article_widget_style'] ) ? $instance['hefe_related_article_widget_style'] : '';
			// Post Type
			echo '<p>';
				echo '<label for="' . $this->get_field_id( 'hefe_related_article_widget_post_type' ) . '" class="hefe_related_article_widget_post_type_label">' . __( 'Post Type', 'hefe' ) . '</label>';
				echo '<input type="text" id="' . $this->get_field_id( 'hefe_related_article_widget_post_type' ) . '" name="' . $this->get_field_name( 'hefe_related_article_widget_post_type' ) . '" class="widefat" placeholder="' . esc_attr__( 'EX: post', 'hefe' ) . '" value="' . esc_attr( $hefe_related_article_widget_post_type ) . '">';
			echo '</p>';
			// Page ID
			echo '<p>';
				echo '<label for="' . $this->get_field_id( 'hefe_related_article_widget_page_id' ) . '" class="hefe_related_article_widget_page_id_label">' . __( 'Page ID', 'hefe' ) . '</label>';
				echo '<input type="text" id="' . $this->get_field_id( 'hefe_related_article_widget_page_id' ) . '" name="' . $this->get_field_name( 'hefe_related_article_widget_page_id' ) . '" class="widefat" placeholder="' . esc_attr__( 'EX: 1', 'hefe' ) . '" value="' . esc_attr( $hefe_related_article_widget_page_id ) . '">';
			echo '</p>';
			// Category ID
			echo '<p>';
				echo '<label for="' . $this->get_field_id( 'hefe_related_article_widget_cat' ) . '" class="hefe_related_article_widget_cat_label">' . __( 'Category ID', 'hefe' ) . '</label>';
				echo '<input type="text" id="' . $this->get_field_id( 'hefe_related_article_widget_cat' ) . '" name="' . $this->get_field_name( 'hefe_related_article_widget_cat' ) . '" class="widefat" placeholder="' . esc_attr__( 'EX: 1', 'hefe' ) . '" value="' . esc_attr( $hefe_related_article_widget_cat ) . '">';
			echo '</p>';
			// Posts Per Page
			echo '<p>';
				echo '<label for="' . $this->get_field_id( 'hefe_related_article_widget_posts_per_page' ) . '" class="hefe_related_article_widget_posts_per_page_label">' . __( 'Posts Per Page', 'hefe' ) . '</label>';
				echo '<input type="text" id="' . $this->get_field_id( 'hefe_related_article_widget_posts_per_page' ) . '" name="' . $this->get_field_name( 'hefe_related_article_widget_posts_per_page' ) . '" class="widefat" placeholder="' . esc_attr__( 'EX: 1', 'hefe' ) . '" value="' . esc_attr( $hefe_related_article_widget_posts_per_page ) . '">';
			echo '</p>';
			// Order
			echo '<p>';
				echo '<label for="' . $this->get_field_id( 'hefe_related_article_widget_order' ) . '" class="hefe_related_article_widget_order_label">' . __( 'Order', 'hefe' ) . '</label>';
				echo '<input type="text" id="' . $this->get_field_id( 'hefe_related_article_widget_order' ) . '" name="' . $this->get_field_name( 'hefe_related_article_widget_order' ) . '" class="widefat" placeholder="' . esc_attr__( 'EX: ASC', 'hefe' ) . '" value="' . esc_attr( $hefe_related_article_widget_order ) . '">';
			echo '</p>';
			// Orderby
			echo '<p>';
				echo '<label for="' . $this->get_field_id( 'hefe_related_article_widget_orderby' ) . '" class="hefe_related_article_widget_orderby_label">' . __( 'Orderby', 'hefe' ) . '</label>';
				echo '<input type="text" id="' . $this->get_field_id( 'hefe_related_article_widget_orderby' ) . '" name="' . $this->get_field_name( 'hefe_related_article_widget_orderby' ) . '" class="widefat" placeholder="' . esc_attr__( 'EX: date', 'hefe' ) . '" value="' . esc_attr( $hefe_related_article_widget_orderby ) . '">';
			echo '</p>';
			// Post Not In
			echo '<p>';
				echo '<label for="' . $this->get_field_id( 'hefe_related_article_widget_post_not_in' ) . '" class="hefe_related_article_widget_post_not_in_label">' . __( 'Ignore Post IDs', 'hefe' ) . '</label>';
				echo '<input type="text" id="' . $this->get_field_id( 'hefe_related_article_widget_post_not_in' ) . '" name="' . $this->get_field_name( 'hefe_related_article_widget_post_not_in' ) . '" class="widefat" placeholder="' . esc_attr__( 'EX: 1', 'hefe' ) . '" value="' . esc_attr( $hefe_related_article_widget_post_not_in ) . '">';
			echo '</p>';
			// Style
			echo '<p>';
			echo '<label for="' . $this->get_field_id( 'hefe_related_article_widget_style' ) . '" class="hefe_related_article_widget_style_label">' . __( 'Style', 'hefe' ) . '</label>';
				echo '<select id="' . $this->get_field_id( 'hefe_related_article_widget_style' ) . '" name="' . $this->get_field_name( 'hefe_related_article_widget_style' ) . '" class="widefat">';
					echo '<option value="" ' . selected( $hefe_related_article_widget_style, '', false ) . '> ' . __( 'None', 'hefe' ) . '</option>';
					echo '<option value="01" ' . selected( $hefe_related_article_widget_style, '01', false ) . '> ' . __( '01', 'hefe' ) . '</option>';
				echo '</select>';
			echo '</p>';
		}
		public function update( $new_instance, $old_instance ) {
			$instance = $old_instance;
			$instance['hefe_related_article_widget_post_type'] = !empty( $new_instance['hefe_related_article_widget_post_type'] ) ? strip_tags( $new_instance['hefe_related_article_widget_post_type'] ) : '';
			$instance['hefe_related_article_widget_page_id'] = !empty( $new_instance['hefe_related_article_widget_page_id'] ) ? strip_tags( $new_instance['hefe_related_article_widget_page_id'] ) : '';
			$instance['hefe_related_article_widget_cat'] = !empty( $new_instance['hefe_related_article_widget_cat'] ) ? strip_tags( $new_instance['hefe_related_article_widget_cat'] ) : '';
			$instance['hefe_related_article_widget_posts_per_page'] = !empty( $new_instance['hefe_related_article_widget_posts_per_page'] ) ? strip_tags( $new_instance['hefe_related_article_widget_posts_per_page'] ) : '';
			$instance['hefe_related_article_widget_order'] = !empty( $new_instance['hefe_related_article_widget_order'] ) ? strip_tags( $new_instance['hefe_related_article_widget_order'] ) : '';
			$instance['hefe_related_article_widget_orderby'] = !empty( $new_instance['hefe_related_article_widget_orderby'] ) ? strip_tags( $new_instance['hefe_related_article_widget_orderby'] ) : '';
			$instance['hefe_related_article_widget_post_not_in'] = !empty( $new_instance['hefe_related_article_widget_post_not_in'] ) ? strip_tags( $new_instance['hefe_related_article_widget_post_not_in'] ) : '';
			$instance['hefe_related_article_widget_style'] = !empty( $new_instance['hefe_related_article_widget_style'] ) ? strip_tags( $new_instance['hefe_related_article_widget_style'] ) : '';
			return $instance;
		}
	}
	add_action('widgets_init', 'register_hefe_related_article_widget');
	function register_hefe_related_article_widget() {
		register_widget('hefe_related_article_function_widget');
	}
}
// Reveal
if(get_option('hefe_widget_customizer_control_reveal')){
	class hefe_reveal_function_widget extends WP_Widget {
		public function __construct() {
			parent::__construct(
				'hefe_reveal_widget',
				__( 'Reveal ('.hefe_shortcode_name.')', 'hefe' ),
				array(
					'description' => __( 'Display reveal.', 'hefe' ),
					'classname'   => 'widget_hefe_reveal_widget',
				)
			);
		}
		public function widget( $args, $instance ) {
			$hefe_reveal_widget_over_content  = ( ! empty( $instance['hefe_reveal_widget_over_content']  ) ) ? $instance['hefe_reveal_widget_over_content'] : __( '' );
			$hefe_reveal_widget_under_content  = ( ! empty( $instance['hefe_reveal_widget_under_content']  ) ) ? $instance['hefe_reveal_widget_under_content'] : __( '' );
			// Widget Before
			echo $args['before_widget'];
				// Reveal
				echo do_shortcode('[hefe_reveal_parent][hefe_reveal_child position="over"]'.$hefe_reveal_widget_over_content.'[/hefe_reveal_child][hefe_reveal_child position="under"]'.$hefe_reveal_widget_under_content.'[/hefe_reveal_child][/hefe_reveal_parent]');
			// Widget After
			echo $args['after_widget'];
		}
		public function form( $instance ) {
			// Set default values
			$instance = wp_parse_args( (array) $instance, array( 
				'hefe_reveal_widget_over_content' => '',
				'hefe_reveal_widget_under_content' => '',
			) );
			// Retrieve an existing value from the database
			$hefe_reveal_widget_over_content = !empty( $instance['hefe_reveal_widget_over_content'] ) ? $instance['hefe_reveal_widget_over_content'] : '';
			$hefe_reveal_widget_under_content = !empty( $instance['hefe_reveal_widget_under_content'] ) ? $instance['hefe_reveal_widget_under_content'] : '';
			// Over Content
			echo '<p>';
				echo '<label for="' . $this->get_field_id( 'hefe_reveal_widget_over_content' ) . '" class="hefe_reveal_widget_over_content_label">' . __( 'Over Content', 'hefe' ) . '</label>';
				echo '<textarea rows="8" id="' . $this->get_field_id( 'hefe_reveal_widget_over_content' ) . '" name="' . $this->get_field_name( 'hefe_reveal_widget_over_content' ) . '" placeholder="EX: Over Content" class="widefat" placeholder="' . esc_attr__( '', 'hefe' ) . '">' . esc_attr( $hefe_reveal_widget_over_content ) . '</textarea>';
			echo '</p>';
			// Under Content
			echo '<p>';
				echo '<label for="' . $this->get_field_id( 'hefe_reveal_widget_under_content' ) . '" class="hefe_reveal_widget_under_content_label">' . __( 'Under Content', 'hefe' ) . '</label>';
				echo '<textarea rows="8" id="' . $this->get_field_id( 'hefe_reveal_widget_under_content' ) . '" name="' . $this->get_field_name( 'hefe_reveal_widget_under_content' ) . '" placeholder="EX: Under Content" class="widefat" placeholder="' . esc_attr__( '', 'hefe' ) . '">' . esc_attr( $hefe_reveal_widget_under_content ) . '</textarea>';
			echo '</p>';
		}
		public function update( $new_instance, $old_instance ) {
			$instance = $old_instance;
			$instance['hefe_reveal_widget_over_content'] = !empty( $new_instance['hefe_reveal_widget_over_content'] ) ? $new_instance['hefe_reveal_widget_over_content'] : '';
			$instance['hefe_reveal_widget_under_content'] = !empty( $new_instance['hefe_reveal_widget_under_content'] ) ? $new_instance['hefe_reveal_widget_under_content'] : '';
			return $instance;
		}
	}
	add_action('widgets_init', 'register_hefe_reveal_widget');
	function register_hefe_reveal_widget() {
		register_widget('hefe_reveal_function_widget');
	}
}
// Sidebar
if(get_option('hefe_widget_customizer_control_sidebar')){
	class hefe_sidebar_function_widget extends WP_Widget {
		public function __construct() {
			parent::__construct(
				'hefe_sidebar_widget',
				__( 'Sidebar ('.hefe_shortcode_name.')', 'hefe' ),
				array(
					'description' => __( 'Display sidebar.', 'hefe' ),
					'classname'   => 'widget_hefe_sidebar_widget',
				)
			);
		}
		public function widget( $args, $instance ) {
			$hefe_sidebar_widget_number  = ( ! empty( $instance['hefe_sidebar_widget_number']  ) ) ? $instance['hefe_sidebar_widget_number'] : __( '' );
			// Widget Before
			echo $args['before_widget'];
				// Sidebar
				echo do_shortcode('[hefe_sidebar_'.$hefe_sidebar_widget_number.']');
			// Widget After
			echo $args['after_widget'];
		}
		public function form( $instance ) {
			// Set default values
			$instance = wp_parse_args( (array) $instance, array( 
				'hefe_sidebar_widget_number' => '',
			) );
			// Retrieve an existing value from the database
			$hefe_sidebar_widget_number = !empty( $instance['hefe_sidebar_widget_number'] ) ? $instance['hefe_sidebar_widget_number'] : '';
			// Number
			echo '<p>';
				echo '<label for="' . $this->get_field_id( 'hefe_sidebar_widget_number' ) . '" class="hefe_sidebar_widget_number_label">' . __( 'Sidebar Number', 'hefe' ) . '</label>';
				echo '<select id="' . $this->get_field_id( 'hefe_sidebar_widget_number' ) . '" name="' . $this->get_field_name( 'hefe_sidebar_widget_number' ) . '" class="widefat">';
					echo '<option value="01" ' . selected( $hefe_sidebar_widget_number, '01', false ) . '> ' . __( '01', 'hefe' ) . '</option>';
					echo '<option value="02" ' . selected( $hefe_sidebar_widget_number, '02', false ) . '> ' . __( '02', 'hefe' ) . '</option>';
					echo '<option value="03" ' . selected( $hefe_sidebar_widget_number, '03', false ) . '> ' . __( '03', 'hefe' ) . '</option>';
					echo '<option value="04" ' . selected( $hefe_sidebar_widget_number, '04', false ) . '> ' . __( '04', 'hefe' ) . '</option>';
					echo '<option value="05" ' . selected( $hefe_sidebar_widget_number, '05', false ) . '> ' . __( '05', 'hefe' ) . '</option>';
					echo '<option value="06" ' . selected( $hefe_sidebar_widget_number, '06', false ) . '> ' . __( '06', 'hefe' ) . '</option>';
				echo '	</select>';
			echo '</p>';
		}
		public function update( $new_instance, $old_instance ) {
			$instance = $old_instance;
			$instance['hefe_sidebar_widget_number'] = !empty( $new_instance['hefe_sidebar_widget_number'] ) ? strip_tags( $new_instance['hefe_sidebar_widget_number'] ) : '';
			return $instance;
		}
	}
	add_action('widgets_init', 'register_hefe_sidebar_widget');
	function register_hefe_sidebar_widget() {
		register_widget('hefe_sidebar_function_widget');
	}
}
// Site Identity
if(get_option('hefe_widget_customizer_control_site_identity')){
	class hefe_site_identity_function_widget extends WP_Widget {
		public function __construct() {
			parent::__construct(
				'hefe_site_identity_widget',
				__( 'Site Identity ('.hefe_shortcode_name.')', 'hefe' ),
				array(
					'description' => __( 'Display site identity.', 'hefe' ),
					'classname'   => 'widget_hefe_site_identity_widget',
				)
			);
		}
		public function widget( $args, $instance ) {
			$hefe_site_identity_widget_display  = ( ! empty( $instance['hefe_site_identity_widget_display']  ) ) ? $instance['hefe_site_identity_widget_display'] : __( '' );
			// Widget Before
			echo $args['before_widget'];
				// Site Identity
				if($hefe_site_identity_widget_display == 'image'){
					echo '<div class="hefe-widget-siteidentity">';
						echo '<a href="'.site_url().'">';
							echo do_shortcode('[hefe_site_logo]');
						echo '</a>';
					echo '</div>';
				}elseif($hefe_site_identity_widget_display == 'title-description'){
					echo '<div class="hefe-widget-siteidentity">';
						echo '<h1>';
							echo '<a href="'.site_url().'">';
								echo do_shortcode('[hefe_site_title]');
							echo '</a>';
						echo '</h1>';
						echo '<p>';
							echo '<a href="'.site_url().'">';
								echo do_shortcode('[hefe_site_description]');
							echo '</a>';
						echo '</p>';
						
					echo '</div>';
				}else{
					echo '<div class="hefe-widget-siteidentity">';
						echo '<h1>';
							echo '<a href="'.site_url().'">';
								echo do_shortcode('[hefe_site_title]');
							echo '</a>';
						echo '</h1>';
					echo '</div>';
				}
			// Widget After
			echo $args['after_widget'];
		}
		public function form( $instance ) {
			// Set default values
			$instance = wp_parse_args( (array) $instance, array( 
				'hefe_site_identity_widget_display' => '',
			) );
			// Retrieve an existing value from the database
			$hefe_site_identity_widget_display = !empty( $instance['hefe_site_identity_widget_display'] ) ? $instance['hefe_site_identity_widget_display'] : '';
			// Display
			echo '<p>';
				echo '<label for="' . $this->get_field_id( 'hefe_site_identity_widget_display' ) . '" class="hefe_site_identity_widget_display_label">' . __( 'Display', 'hefe' ) . '</label>';
				echo '<select id="' . $this->get_field_id( 'hefe_site_identity_widget_display' ) . '" name="' . $this->get_field_name( 'hefe_site_identity_widget_display' ) . '" class="widefat">';
					echo '<option value="title" ' . selected( $hefe_site_identity_widget_display, 'title', false ) . '> ' . __( 'Title', 'hefe' ) . '</option>';
					echo '<option value="title-description" ' . selected( $hefe_site_identity_widget_display, 'title-description', false ) . '> ' . __( 'Title & Description', 'hefe' ) . '</option>';
					echo '<option value="image" ' . selected( $hefe_site_identity_widget_display, 'image', false ) . '> ' . __( 'Image', 'hefe' ) . '</option>';
				echo '	</select>';
			echo '</p>';
		}
		public function update( $new_instance, $old_instance ) {
			$instance = $old_instance;
			$instance['hefe_site_identity_widget_display'] = !empty( $new_instance['hefe_site_identity_widget_display'] ) ? strip_tags( $new_instance['hefe_site_identity_widget_display'] ) : '';
			return $instance;
		}
	}
	add_action('widgets_init', 'register_hefe_site_identity_widget');
	function register_hefe_site_identity_widget() {
		register_widget('hefe_site_identity_function_widget');
	}
}
// Tabs
if(get_option('hefe_widget_customizer_control_tabs')){
	class hefe_tabs_function_widget extends WP_Widget {
		public function __construct() {
			parent::__construct(
				'hefe_tabs_widget',
				__( 'Tabs ('.hefe_shortcode_name.')', 'hefe' ),
				array(
					'description' => __( 'Display tabs.', 'hefe' ),
					'classname'   => 'widget_hefe_tabs_widget',
				)
			);
		}
		public function widget( $args, $instance ) {
			$hefe_tabs_widget_link_01  = ( ! empty( $instance['hefe_tabs_widget_link_01']  ) ) ? $instance['hefe_tabs_widget_link_01'] : __( '' );
			$hefe_tabs_widget_content_01  = ( ! empty( $instance['hefe_tabs_widget_content_01']  ) ) ? $instance['hefe_tabs_widget_content_01'] : __( '' );
			$hefe_tabs_widget_link_02  = ( ! empty( $instance['hefe_tabs_widget_link_02']  ) ) ? $instance['hefe_tabs_widget_link_02'] : __( '' );
			$hefe_tabs_widget_content_02  = ( ! empty( $instance['hefe_tabs_widget_content_02']  ) ) ? $instance['hefe_tabs_widget_content_02'] : __( '' );
			$hefe_tabs_widget_link_03  = ( ! empty( $instance['hefe_tabs_widget_link_03']  ) ) ? $instance['hefe_tabs_widget_link_03'] : __( '' );
			$hefe_tabs_widget_content_03  = ( ! empty( $instance['hefe_tabs_widget_content_03']  ) ) ? $instance['hefe_tabs_widget_content_03'] : __( '' );
			$hefe_tabs_widget_link_04  = ( ! empty( $instance['hefe_tabs_widget_link_04']  ) ) ? $instance['hefe_tabs_widget_link_04'] : __( '' );
			$hefe_tabs_widget_content_04  = ( ! empty( $instance['hefe_tabs_widget_content_04']  ) ) ? $instance['hefe_tabs_widget_content_04'] : __( '' );
			$hefe_tabs_widget_link_05  = ( ! empty( $instance['hefe_tabs_widget_link_05']  ) ) ? $instance['hefe_tabs_widget_link_05'] : __( '' );
			$hefe_tabs_widget_content_05  = ( ! empty( $instance['hefe_tabs_widget_content_05']  ) ) ? $instance['hefe_tabs_widget_content_05'] : __( '' );
			$hefe_tabs_widget_link_06  = ( ! empty( $instance['hefe_tabs_widget_link_06']  ) ) ? $instance['hefe_tabs_widget_link_06'] : __( '' );
			$hefe_tabs_widget_content_06  = ( ! empty( $instance['hefe_tabs_widget_content_06']  ) ) ? $instance['hefe_tabs_widget_content_06'] : __( '' );
			$hefe_tabs_widget_active  = ( ! empty( $instance['hefe_tabs_widget_active']  ) ) ? $instance['hefe_tabs_widget_active'] : __( '' );
			$hefe_tabs_widget_style  = ( ! empty( $instance['hefe_tabs_widget_style']  ) ) ? $instance['hefe_tabs_widget_style'] : __( '' );
			// Widget Before
			echo $args['before_widget'];
				// Tabs
				$paired_id_01 = mt_rand(0,999999);
				$paired_id_02 = mt_rand(0,999999);
				$paired_id_03 = mt_rand(0,999999);
				$paired_id_04 = mt_rand(0,999999);
				$paired_id_05 = mt_rand(0,999999);
				$paired_id_06 = mt_rand(0,999999);
				$hefe_tabs_widget_active_01 = '';
				$hefe_tabs_widget_active_02 = '';
				$hefe_tabs_widget_active_03 = '';
				$hefe_tabs_widget_active_04 = '';
				$hefe_tabs_widget_active_05 = '';
				$hefe_tabs_widget_active_06 = '';
				if($hefe_tabs_widget_active == '01'){
					$hefe_tabs_widget_active_01 = 'true';
				}elseif($hefe_tabs_widget_active == '02'){
					$hefe_tabs_widget_active_02 = 'true';
				}elseif($hefe_tabs_widget_active == '03'){
					$hefe_tabs_widget_active_03 = 'true';
				}elseif($hefe_tabs_widget_active == '04'){
					$hefe_tabs_widget_active_04 = 'true';
				}elseif($hefe_tabs_widget_active == '05'){
					$hefe_tabs_widget_active_05 = 'true';
				}elseif($hefe_tabs_widget_active == '06'){
					$hefe_tabs_widget_active_06 = 'true';
				}
				if($hefe_tabs_widget_link_01){
					echo do_shortcode('[hefe_tabs_link class="" paired_id="'.$paired_id_01.'" active="'.$hefe_tabs_widget_active_01.'" style="'.$hefe_tabs_widget_style.'"]'.$hefe_tabs_widget_link_01.'[/hefe_tabs_link]');
				}
				if($hefe_tabs_widget_link_02){
					echo do_shortcode('[hefe_tabs_link class="" paired_id="'.$paired_id_02.'" active="'.$hefe_tabs_widget_active_02.'" style="'.$hefe_tabs_widget_style.'"]'.$hefe_tabs_widget_link_02.'[/hefe_tabs_link]');
				}
				if($hefe_tabs_widget_link_03){
					echo do_shortcode('[hefe_tabs_link class="" paired_id="'.$paired_id_03.'" active="'.$hefe_tabs_widget_active_03.'" style="'.$hefe_tabs_widget_style.'"]'.$hefe_tabs_widget_link_03.'[/hefe_tabs_link]');
				}
				if($hefe_tabs_widget_link_04){
					echo do_shortcode('[hefe_tabs_link class="" paired_id="'.$paired_id_04.'" active="'.$hefe_tabs_widget_active_04.'" style="'.$hefe_tabs_widget_style.'"]'.$hefe_tabs_widget_link_04.'[/hefe_tabs_link]');
				}
				if($hefe_tabs_widget_link_05){
					echo do_shortcode('[hefe_tabs_link class="" paired_id="'.$paired_id_05.'" active="'.$hefe_tabs_widget_active_05.'" style="'.$hefe_tabs_widget_style.'"]'.$hefe_tabs_widget_link_05.'[/hefe_tabs_link]');
				}
				if($hefe_tabs_widget_link_06){
					echo do_shortcode('[hefe_tabs_link class="" paired_id="'.$paired_id_06.'" active="'.$hefe_tabs_widget_active_06.'" style="'.$hefe_tabs_widget_style.'"]'.$hefe_tabs_widget_link_06.'[/hefe_tabs_link]');
				}
				if($hefe_tabs_widget_content_01){
					echo do_shortcode('[hefe_tabs_content class="" paired_id="'.$paired_id_01.'" active="'.$hefe_tabs_widget_active_01.'" style="'.$hefe_tabs_widget_style.'"]'.$hefe_tabs_widget_content_01.'[/hefe_tabs_content]');
				}
				if($hefe_tabs_widget_content_02){
					echo do_shortcode('[hefe_tabs_content class="" paired_id="'.$paired_id_02.'" active="'.$hefe_tabs_widget_active_02.'" style="'.$hefe_tabs_widget_style.'"]'.$hefe_tabs_widget_content_02.'[/hefe_tabs_content]');
				}
				if($hefe_tabs_widget_content_03){
					echo do_shortcode('[hefe_tabs_content class="" paired_id="'.$paired_id_03.'" active="'.$hefe_tabs_widget_active_03.'" style="'.$hefe_tabs_widget_style.'"]'.$hefe_tabs_widget_content_03.'[/hefe_tabs_content]');
				}
				if($hefe_tabs_widget_content_04){
					echo do_shortcode('[hefe_tabs_content class="" paired_id="'.$paired_id_04.'" active="'.$hefe_tabs_widget_active_04.'" style="'.$hefe_tabs_widget_style.'"]'.$hefe_tabs_widget_content_04.'[/hefe_tabs_content]');
				}
				if($hefe_tabs_widget_content_05){
					echo do_shortcode('[hefe_tabs_content class="" paired_id="'.$paired_id_05.'" active="'.$hefe_tabs_widget_active_05.'" style="'.$hefe_tabs_widget_style.'"]'.$hefe_tabs_widget_content_05.'[/hefe_tabs_content]');
				}
				if($hefe_tabs_widget_content_06){
					echo do_shortcode('[hefe_tabs_content class="" paired_id="'.$paired_id_06.'" active="'.$hefe_tabs_widget_active_06.'" style="'.$hefe_tabs_widget_style.'"]'.$hefe_tabs_widget_content_06.'[/hefe_tabs_content]');
				}
			// Widget After
			echo $args['after_widget'];
		}
		public function form( $instance ) {
			// Set default values
			$instance = wp_parse_args( (array) $instance, array( 
				'hefe_tabs_widget_link_01' => '',
				'hefe_tabs_widget_content_01' => '',
				'hefe_tabs_widget_link_02' => '',
				'hefe_tabs_widget_content_02' => '',
				'hefe_tabs_widget_link_03' => '',
				'hefe_tabs_widget_content_03' => '',
				'hefe_tabs_widget_link_04' => '',
				'hefe_tabs_widget_content_04' => '',
				'hefe_tabs_widget_link_05' => '',
				'hefe_tabs_widget_content_05' => '',
				'hefe_tabs_widget_link_06' => '',
				'hefe_tabs_widget_content_06' => '',
				'hefe_tabs_widget_active' => '',
				'hefe_tabs_widget_style' => '',
			) );
			// Retrieve an existing value from the database
			$hefe_tabs_widget_link_01 = !empty( $instance['hefe_tabs_widget_link_01'] ) ? $instance['hefe_tabs_widget_link_01'] : '';
			$hefe_tabs_widget_content_01 = !empty( $instance['hefe_tabs_widget_content_01'] ) ? $instance['hefe_tabs_widget_content_01'] : '';
			$hefe_tabs_widget_link_02 = !empty( $instance['hefe_tabs_widget_link_02'] ) ? $instance['hefe_tabs_widget_link_02'] : '';
			$hefe_tabs_widget_content_02 = !empty( $instance['hefe_tabs_widget_content_02'] ) ? $instance['hefe_tabs_widget_content_02'] : '';
			$hefe_tabs_widget_link_03 = !empty( $instance['hefe_tabs_widget_link_03'] ) ? $instance['hefe_tabs_widget_link_03'] : '';
			$hefe_tabs_widget_content_03 = !empty( $instance['hefe_tabs_widget_content_03'] ) ? $instance['hefe_tabs_widget_content_03'] : '';
			$hefe_tabs_widget_link_04 = !empty( $instance['hefe_tabs_widget_link_04'] ) ? $instance['hefe_tabs_widget_link_04'] : '';
			$hefe_tabs_widget_content_04 = !empty( $instance['hefe_tabs_widget_content_04'] ) ? $instance['hefe_tabs_widget_content_04'] : '';
			$hefe_tabs_widget_link_05 = !empty( $instance['hefe_tabs_widget_link_05'] ) ? $instance['hefe_tabs_widget_link_05'] : '';
			$hefe_tabs_widget_content_05 = !empty( $instance['hefe_tabs_widget_content_05'] ) ? $instance['hefe_tabs_widget_content_05'] : '';
			$hefe_tabs_widget_link_06 = !empty( $instance['hefe_tabs_widget_link_06'] ) ? $instance['hefe_tabs_widget_link_06'] : '';
			$hefe_tabs_widget_content_06 = !empty( $instance['hefe_tabs_widget_content_06'] ) ? $instance['hefe_tabs_widget_content_06'] : '';
			$hefe_tabs_widget_active = !empty( $instance['hefe_tabs_widget_active'] ) ? $instance['hefe_tabs_widget_active'] : '';
			$hefe_tabs_widget_style = !empty( $instance['hefe_tabs_widget_style'] ) ? $instance['hefe_tabs_widget_style'] : '';
			// Link 01
			echo '<p>';
				echo '<label for="' . $this->get_field_id( 'hefe_tabs_widget_link_01' ) . '" class="hefe_tabs_widget_link_01_label">' . __( 'Link 01', 'hefe' ) . '</label>';
				echo '<textarea rows="4" id="' . $this->get_field_id( 'hefe_tabs_widget_link_01' ) . '" name="' . $this->get_field_name( 'hefe_tabs_widget_link_01' ) . '" placeholder="EX: Tabs Link" class="widefat" placeholder="' . esc_attr__( '', 'hefe' ) . '">' . esc_attr( $hefe_tabs_widget_link_01 ) . '</textarea>';
			echo '</p>';
			// Content 01
			echo '<p>';
				echo '<label for="' . $this->get_field_id( 'hefe_tabs_widget_content_01' ) . '" class="hefe_tabs_widget_content_01_label">' . __( 'Content 01', 'hefe' ) . '</label>';
				echo '<textarea rows="8" id="' . $this->get_field_id( 'hefe_tabs_widget_content_01' ) . '" name="' . $this->get_field_name( 'hefe_tabs_widget_content_01' ) . '" placeholder="EX: Tabs Content" class="widefat" placeholder="' . esc_attr__( '', 'hefe' ) . '">' . esc_attr( $hefe_tabs_widget_content_01 ) . '</textarea>';
			echo '</p>';
			// Link 02
			echo '<p>';
				echo '<label for="' . $this->get_field_id( 'hefe_tabs_widget_link_02' ) . '" class="hefe_tabs_widget_link_02_label">' . __( 'Link 02', 'hefe' ) . '</label>';
				echo '<textarea rows="4" id="' . $this->get_field_id( 'hefe_tabs_widget_link_02' ) . '" name="' . $this->get_field_name( 'hefe_tabs_widget_link_02' ) . '" placeholder="EX: Tabs Link" class="widefat" placeholder="' . esc_attr__( '', 'hefe' ) . '">' . esc_attr( $hefe_tabs_widget_link_02 ) . '</textarea>';
			echo '</p>';
			// Content 02
			echo '<p>';
				echo '<label for="' . $this->get_field_id( 'hefe_tabs_widget_content_02' ) . '" class="hefe_tabs_widget_content_02_label">' . __( 'Content 02', 'hefe' ) . '</label>';
				echo '<textarea rows="8" id="' . $this->get_field_id( 'hefe_tabs_widget_content_02' ) . '" name="' . $this->get_field_name( 'hefe_tabs_widget_content_02' ) . '" placeholder="EX: Tabs Content" class="widefat" placeholder="' . esc_attr__( '', 'hefe' ) . '">' . esc_attr( $hefe_tabs_widget_content_02 ) . '</textarea>';
			echo '</p>';
			// Link 03
			echo '<p>';
				echo '<label for="' . $this->get_field_id( 'hefe_tabs_widget_link_03' ) . '" class="hefe_tabs_widget_link_03_label">' . __( 'Link 03', 'hefe' ) . '</label>';
				echo '<textarea rows="4" id="' . $this->get_field_id( 'hefe_tabs_widget_link_03' ) . '" name="' . $this->get_field_name( 'hefe_tabs_widget_link_03' ) . '" placeholder="EX: Tabs Link" class="widefat" placeholder="' . esc_attr__( '', 'hefe' ) . '">' . esc_attr( $hefe_tabs_widget_link_03 ) . '</textarea>';
			echo '</p>';
			// Content 03
			echo '<p>';
				echo '<label for="' . $this->get_field_id( 'hefe_tabs_widget_content_03' ) . '" class="hefe_tabs_widget_content_03_label">' . __( 'Content 03', 'hefe' ) . '</label>';
				echo '<textarea rows="8" id="' . $this->get_field_id( 'hefe_tabs_widget_content_03' ) . '" name="' . $this->get_field_name( 'hefe_tabs_widget_content_03' ) . '" placeholder="EX: Tabs Content" class="widefat" placeholder="' . esc_attr__( '', 'hefe' ) . '">' . esc_attr( $hefe_tabs_widget_content_03 ) . '</textarea>';
			echo '</p>';
			// Link 04
			echo '<p>';
				echo '<label for="' . $this->get_field_id( 'hefe_tabs_widget_link_04' ) . '" class="hefe_tabs_widget_link_04_label">' . __( 'Link 04', 'hefe' ) . '</label>';
				echo '<textarea rows="4" id="' . $this->get_field_id( 'hefe_tabs_widget_link_04' ) . '" name="' . $this->get_field_name( 'hefe_tabs_widget_link_04' ) . '" placeholder="EX: Tabs Link" class="widefat" placeholder="' . esc_attr__( '', 'hefe' ) . '">' . esc_attr( $hefe_tabs_widget_link_04 ) . '</textarea>';
			echo '</p>';
			// Content 04
			echo '<p>';
				echo '<label for="' . $this->get_field_id( 'hefe_tabs_widget_content_04' ) . '" class="hefe_tabs_widget_content_04_label">' . __( 'Content 04', 'hefe' ) . '</label>';
				echo '<textarea rows="8" id="' . $this->get_field_id( 'hefe_tabs_widget_content_04' ) . '" name="' . $this->get_field_name( 'hefe_tabs_widget_content_04' ) . '" placeholder="EX: Tabs Content" class="widefat" placeholder="' . esc_attr__( '', 'hefe' ) . '">' . esc_attr( $hefe_tabs_widget_content_04 ) . '</textarea>';
			echo '</p>';
			// Link 05
			echo '<p>';
				echo '<label for="' . $this->get_field_id( 'hefe_tabs_widget_link_05' ) . '" class="hefe_tabs_widget_link_05_label">' . __( 'Link 05', 'hefe' ) . '</label>';
				echo '<textarea rows="4" id="' . $this->get_field_id( 'hefe_tabs_widget_link_05' ) . '" name="' . $this->get_field_name( 'hefe_tabs_widget_link_05' ) . '" placeholder="EX: Tabs Link" class="widefat" placeholder="' . esc_attr__( '', 'hefe' ) . '">' . esc_attr( $hefe_tabs_widget_link_05 ) . '</textarea>';
			echo '</p>';
			// Content 05
			echo '<p>';
				echo '<label for="' . $this->get_field_id( 'hefe_tabs_widget_content_05' ) . '" class="hefe_tabs_widget_content_05_label">' . __( 'Content 05', 'hefe' ) . '</label>';
				echo '<textarea rows="8" id="' . $this->get_field_id( 'hefe_tabs_widget_content_05' ) . '" name="' . $this->get_field_name( 'hefe_tabs_widget_content_05' ) . '" placeholder="EX: Tabs Content" class="widefat" placeholder="' . esc_attr__( '', 'hefe' ) . '">' . esc_attr( $hefe_tabs_widget_content_05 ) . '</textarea>';
			echo '</p>';
			// Link 06
			echo '<p>';
				echo '<label for="' . $this->get_field_id( 'hefe_tabs_widget_link_06' ) . '" class="hefe_tabs_widget_link_06_label">' . __( 'Link 06', 'hefe' ) . '</label>';
				echo '<textarea rows="4" id="' . $this->get_field_id( 'hefe_tabs_widget_link_06' ) . '" name="' . $this->get_field_name( 'hefe_tabs_widget_link_06' ) . '" placeholder="EX: Tabs Link" class="widefat" placeholder="' . esc_attr__( '', 'hefe' ) . '">' . esc_attr( $hefe_tabs_widget_link_06 ) . '</textarea>';
			echo '</p>';
			// Content 06
			echo '<p>';
				echo '<label for="' . $this->get_field_id( 'hefe_tabs_widget_content_06' ) . '" class="hefe_tabs_widget_content_06_label">' . __( 'Content 06', 'hefe' ) . '</label>';
				echo '<textarea rows="8" id="' . $this->get_field_id( 'hefe_tabs_widget_content_06' ) . '" name="' . $this->get_field_name( 'hefe_tabs_widget_content_06' ) . '" placeholder="EX: Tabs Content" class="widefat" placeholder="' . esc_attr__( '', 'hefe' ) . '">' . esc_attr( $hefe_tabs_widget_content_06 ) . '</textarea>';
			echo '</p>';
			// Active
			echo '<p>';
				echo '<label for="' . $this->get_field_id( 'hefe_tabs_widget_active' ) . '" class="hefe_tabs_widget_active_label">' . __( 'Active', 'hefe' ) . '</label>';
				echo '<select id="' . $this->get_field_id( 'hefe_tabs_widget_active' ) . '" name="' . $this->get_field_name( 'hefe_tabs_widget_active' ) . '" class="widefat">';
					echo '<option value="01" ' . selected( $hefe_tabs_widget_active, '01', false ) . '> ' . __( 'Tab 01', 'hefe' ) . '</option>';
					echo '<option value="02" ' . selected( $hefe_tabs_widget_active, '02', false ) . '> ' . __( 'Tab 02', 'hefe' ) . '</option>';
					echo '<option value="03" ' . selected( $hefe_tabs_widget_active, '03', false ) . '> ' . __( 'Tab 03', 'hefe' ) . '</option>';
					echo '<option value="04" ' . selected( $hefe_tabs_widget_active, '024', false ) . '> ' . __( 'Tab 04', 'hefe' ) . '</option>';
					echo '<option value="05" ' . selected( $hefe_tabs_widget_active, '05', false ) . '> ' . __( 'Tab 05', 'hefe' ) . '</option>';
					echo '<option value="06" ' . selected( $hefe_tabs_widget_active, '06', false ) . '> ' . __( 'Tab 06', 'hefe' ) . '</option>';
				echo '</select>';
			echo '</p>';
			// Style
			echo '<p>';
				echo '<label for="' . $this->get_field_id( 'hefe_tabs_widget_style' ) . '" class="hefe_tabs_widget_style_label">' . __( 'Style', 'hefe' ) . '</label>';
				echo '<select id="' . $this->get_field_id( 'hefe_tabs_widget_style' ) . '" name="' . $this->get_field_name( 'hefe_tabs_widget_style' ) . '" class="widefat">';
					echo '<option value="" ' . selected( $hefe_tabs_widget_style, '', false ) . '> ' . __( 'None', 'hefe' ) . '</option>';
					echo '<option value="01" ' . selected( $hefe_tabs_widget_style, '01', false ) . '> ' . __( '01', 'hefe' ) . '</option>';
					echo '<option value="02" ' . selected( $hefe_tabs_widget_style, '02', false ) . '> ' . __( '02', 'hefe' ) . '</option>';
				echo '	</select>';
			echo '</p>';
		}
		public function update( $new_instance, $old_instance ) {
			$instance = $old_instance;
			$instance['hefe_tabs_widget_link_01'] = !empty( $new_instance['hefe_tabs_widget_link_01'] ) ? $new_instance['hefe_tabs_widget_link_01'] : '';
			$instance['hefe_tabs_widget_content_01'] = !empty( $new_instance['hefe_tabs_widget_content_01'] ) ? $new_instance['hefe_tabs_widget_content_01'] : '';
			$instance['hefe_tabs_widget_link_02'] = !empty( $new_instance['hefe_tabs_widget_link_02'] ) ? $new_instance['hefe_tabs_widget_link_02'] : '';
			$instance['hefe_tabs_widget_content_02'] = !empty( $new_instance['hefe_tabs_widget_content_02'] ) ? $new_instance['hefe_tabs_widget_content_02'] : '';
			$instance['hefe_tabs_widget_link_03'] = !empty( $new_instance['hefe_tabs_widget_link_03'] ) ? $new_instance['hefe_tabs_widget_link_03'] : '';
			$instance['hefe_tabs_widget_content_03'] = !empty( $new_instance['hefe_tabs_widget_content_03'] ) ? $new_instance['hefe_tabs_widget_content_03'] : '';
			$instance['hefe_tabs_widget_link_04'] = !empty( $new_instance['hefe_tabs_widget_link_04'] ) ? $new_instance['hefe_tabs_widget_link_04'] : '';
			$instance['hefe_tabs_widget_content_04'] = !empty( $new_instance['hefe_tabs_widget_content_04'] ) ? $new_instance['hefe_tabs_widget_content_04'] : '';
			$instance['hefe_tabs_widget_link_05'] = !empty( $new_instance['hefe_tabs_widget_link_05'] ) ? $new_instance['hefe_tabs_widget_link_05'] : '';
			$instance['hefe_tabs_widget_content_05'] = !empty( $new_instance['hefe_tabs_widget_content_05'] ) ? $new_instance['hefe_tabs_widget_content_05'] : '';
			$instance['hefe_tabs_widget_link_06'] = !empty( $new_instance['hefe_tabs_widget_link_06'] ) ? $new_instance['hefe_tabs_widget_link_06'] : '';
			$instance['hefe_tabs_widget_content_06'] = !empty( $new_instance['hefe_tabs_widget_content_06'] ) ? $new_instance['hefe_tabs_widget_content_06'] : '';
			$instance['hefe_tabs_widget_active'] = !empty( $new_instance['hefe_tabs_widget_active'] ) ? strip_tags( $new_instance['hefe_tabs_widget_active'] ) : '';
			$instance['hefe_tabs_widget_style'] = !empty( $new_instance['hefe_tabs_widget_style'] ) ? strip_tags( $new_instance['hefe_tabs_widget_style'] ) : '';
			return $instance;
		}
	}
	add_action('widgets_init', 'register_hefe_tabs_widget');
	function register_hefe_tabs_widget() {
		register_widget('hefe_tabs_function_widget');
	}
}
// Tabs Content
if(get_option('hefe_widget_customizer_control_tabs_content')){
	class hefe_tabs_content_function_widget extends WP_Widget {
		public function __construct() {
			parent::__construct(
				'hefe_tabs_content_widget',
				__( 'Tabs Content ('.hefe_shortcode_name.')', 'hefe' ),
				array(
					'description' => __( 'Display a tabs content.', 'hefe' ),
					'classname'   => 'widget_hefe_tabs_content_widget',
				)
			);
		}
		public function widget( $args, $instance ) {
			$hefe_tabs_content_widget_content  = ( ! empty( $instance['hefe_tabs_content_widget_content']  ) ) ? $instance['hefe_tabs_content_widget_content'] : __( '' );
			$hefe_tabs_content_widget_paired_id  = ( ! empty( $instance['hefe_tabs_content_widget_paired_id']  ) ) ? $instance['hefe_tabs_content_widget_paired_id'] : __( '' );
			$hefe_tabs_content_widget_active  = ( ! empty( $instance['hefe_tabs_content_widget_active']  ) ) ? $instance['hefe_tabs_content_widget_active'] : __( '' );
			$hefe_tabs_content_widget_style  = ( ! empty( $instance['hefe_tabs_content_widget_style']  ) ) ? $instance['hefe_tabs_content_widget_style'] : __( '' );
			// Widget Before
			echo $args['before_widget'];
				// Tabs
				echo do_shortcode('[hefe_tabs_content class="" paired_id="'.$hefe_tabs_content_widget_paired_id.'" active="'.$hefe_tabs_content_widget_active.'" style="'.$hefe_tabs_content_widget_style.'"]'.$hefe_tabs_content_widget_content.'[/hefe_tabs_content]');
			// Widget After
			echo $args['after_widget'];
		}
		public function form( $instance ) {
			// Set default values
			$instance = wp_parse_args( (array) $instance, array( 
				'hefe_tabs_content_widget_content' => '',
				'hefe_tabs_content_widget_paired_id' => '',
				'hefe_tabs_content_widget_active' => '',
				'hefe_tabs_content_widget_style' => '',
			) );
			// Retrieve an existing value from the database
			$hefe_tabs_content_widget_content = !empty( $instance['hefe_tabs_content_widget_content'] ) ? $instance['hefe_tabs_content_widget_content'] : '';
			$hefe_tabs_content_widget_paired_id = !empty( $instance['hefe_tabs_content_widget_paired_id'] ) ? $instance['hefe_tabs_content_widget_paired_id'] : '';
			$hefe_tabs_content_widget_active = !empty( $instance['hefe_tabs_content_widget_active'] ) ? $instance['hefe_tabs_content_widget_active'] : '';
			$hefe_tabs_content_widget_style = !empty( $instance['hefe_tabs_content_widget_style'] ) ? $instance['hefe_tabs_content_widget_style'] : '';
			// Content
			echo '<p>';
				echo '<label for="' . $this->get_field_id( 'hefe_tabs_content_widget_content' ) . '" class="hefe_tabs_content_widget_content_label">' . __( 'Content', 'hefe' ) . '</label>';
				echo '<textarea rows="8" id="' . $this->get_field_id( 'hefe_tabs_content_widget_content' ) . '" name="' . $this->get_field_name( 'hefe_tabs_content_widget_content' ) . '" placeholder="EX: Tabs Content" class="widefat" placeholder="' . esc_attr__( '', 'hefe' ) . '">' . esc_attr( $hefe_tabs_content_widget_content ) . '</textarea>';
			echo '</p>';
			// Paired ID
			echo '<p>';
				echo '<label for="' . $this->get_field_id( 'hefe_tabs_content_widget_paired_id' ) . '" class="hefe_tabs_content_widget_paired_id_label">' . __( 'Paired ID', 'hefe' ) . '</label>';
				echo '<input type="text" id="' . $this->get_field_id( 'hefe_tabs_content_widget_paired_id' ) . '" name="' . $this->get_field_name( 'hefe_tabs_content_widget_paired_id' ) . '" class="widefat" placeholder="' . esc_attr__( 'EX: 1', 'hefe' ) . '" value="' . esc_attr( $hefe_tabs_content_widget_paired_id ) . '">';
			echo '</p>';
			// Active
			echo '<p>';
			echo '<label for="' . $this->get_field_id( 'hefe_tabs_content_widget_active' ) . '" class="hefe_tabs_content_widget_active_label">' . __( 'Active', 'hefe' ) . '</label>';
				echo '<select id="' . $this->get_field_id( 'hefe_tabs_content_widget_active' ) . '" name="' . $this->get_field_name( 'hefe_tabs_content_widget_active' ) . '" class="widefat">';
					echo '<option value="" ' . selected( $hefe_tabs_content_widget_active, '', false ) . '> ' . __( 'False', 'hefe' ) . '</option>';
					echo '<option value="true" ' . selected( $hefe_tabs_content_widget_active, 'true', false ) . '> ' . __( 'True', 'hefe' ) . '</option>';
				echo '</select>';
			echo '</p>';
			// Style
			echo '<p>';
			echo '<label for="' . $this->get_field_id( 'hefe_tabs_content_widget_style' ) . '" class="hefe_tabs_content_widget_style_label">' . __( 'Style', 'hefe' ) . '</label>';
				echo '<select id="' . $this->get_field_id( 'hefe_tabs_content_widget_style' ) . '" name="' . $this->get_field_name( 'hefe_tabs_content_widget_style' ) . '" class="widefat">';
					echo '<option value="" ' . selected( $hefe_tabs_content_widget_style, '', false ) . '> ' . __( 'None', 'hefe' ) . '</option>';
					echo '<option value="01" ' . selected( $hefe_tabs_content_widget_style, '01', false ) . '> ' . __( '01', 'hefe' ) . '</option>';
				echo '</select>';
			echo '</p>';
		}
		public function update( $new_instance, $old_instance ) {
			$instance = $old_instance;
			$instance['hefe_tabs_content_widget_content'] = !empty( $new_instance['hefe_tabs_content_widget_content'] ) ? $new_instance['hefe_tabs_content_widget_content'] : '';
			$instance['hefe_tabs_content_widget_paired_id'] = !empty( $new_instance['hefe_tabs_content_widget_paired_id'] ) ? $new_instance['hefe_tabs_content_widget_paired_id'] : '';
			$instance['hefe_tabs_content_widget_active'] = !empty( $new_instance['hefe_tabs_content_widget_active'] ) ? strip_tags( $new_instance['hefe_tabs_content_widget_active'] ) : '';
			$instance['hefe_tabs_content_widget_style'] = !empty( $new_instance['hefe_tabs_content_widget_style'] ) ? strip_tags( $new_instance['hefe_tabs_content_widget_style'] ) : '';
			return $instance;
		}
	}
	add_action('widgets_init', 'register_hefe_tabs_content_widget');
	function register_hefe_tabs_content_widget() {
		register_widget('hefe_tabs_content_function_widget');
	}
}
// Tabs Link
if(get_option('hefe_widget_customizer_control_tabs_link')){
	class hefe_tabs_link_function_widget extends WP_Widget {
		public function __construct() {
			parent::__construct(
				'hefe_tabs_link_widget',
				__( 'Tabs Link ('.hefe_shortcode_name.')', 'hefe' ),
				array(
					'description' => __( 'Display a tabs link.', 'hefe' ),
					'classname'   => 'widget_hefe_tabs_link_widget',
				)
			);
		}
		public function widget( $args, $instance ) {
			$hefe_tabs_link_widget_content  = ( ! empty( $instance['hefe_tabs_link_widget_content']  ) ) ? $instance['hefe_tabs_link_widget_content'] : __( '' );
			$hefe_tabs_link_widget_paired_id  = ( ! empty( $instance['hefe_tabs_link_widget_paired_id']  ) ) ? $instance['hefe_tabs_link_widget_paired_id'] : __( '' );
			$hefe_tabs_link_widget_active  = ( ! empty( $instance['hefe_tabs_link_widget_active']  ) ) ? $instance['hefe_tabs_link_widget_active'] : __( '' );
			$hefe_tabs_link_widget_style  = ( ! empty( $instance['hefe_tabs_link_widget_style']  ) ) ? $instance['hefe_tabs_link_widget_style'] : __( '' );
			// Widget Before
			echo $args['before_widget'];
				// Tabs
				echo do_shortcode('[hefe_tabs_link class="" paired_id="'.$hefe_tabs_link_widget_paired_id.'" active="'.$hefe_tabs_link_widget_active.'" style="'.$hefe_tabs_link_widget_style.'"]'.$hefe_tabs_link_widget_content.'[/hefe_tabs_link]');
			// Widget After
			echo $args['after_widget'];
		}
		public function form( $instance ) {
			// Set default values
			$instance = wp_parse_args( (array) $instance, array( 
				'hefe_tabs_link_widget_content' => '',
				'hefe_tabs_link_widget_paired_id' => '',
				'hefe_tabs_link_widget_active' => '',
				'hefe_tabs_link_widget_style' => '',
			) );
			// Retrieve an existing value from the database
			$hefe_tabs_link_widget_content = !empty( $instance['hefe_tabs_link_widget_content'] ) ? $instance['hefe_tabs_link_widget_content'] : '';
			$hefe_tabs_link_widget_paired_id = !empty( $instance['hefe_tabs_link_widget_paired_id'] ) ? $instance['hefe_tabs_link_widget_paired_id'] : '';
			$hefe_tabs_link_widget_active = !empty( $instance['hefe_tabs_link_widget_active'] ) ? $instance['hefe_tabs_link_widget_active'] : '';
			$hefe_tabs_link_widget_style = !empty( $instance['hefe_tabs_link_widget_style'] ) ? $instance['hefe_tabs_link_widget_style'] : '';
			// Link
			echo '<p>';
				echo '<label for="' . $this->get_field_id( 'hefe_tabs_link_widget_content' ) . '" class="hefe_tabs_link_widget_content_label">' . __( 'Content', 'hefe' ) . '</label>';
				echo '<textarea rows="8" id="' . $this->get_field_id( 'hefe_tabs_link_widget_content' ) . '" name="' . $this->get_field_name( 'hefe_tabs_link_widget_content' ) . '" placeholder="EX: Tabs Title" class="widefat" placeholder="' . esc_attr__( '', 'hefe' ) . '">' . esc_attr( $hefe_tabs_link_widget_content ) . '</textarea>';
			echo '</p>';
			// Paired ID
			echo '<p>';
				echo '<label for="' . $this->get_field_id( 'hefe_tabs_link_widget_paired_id' ) . '" class="hefe_tabs_link_widget_paired_id_label">' . __( 'Paired ID', 'hefe' ) . '</label>';
				echo '<input type="text" id="' . $this->get_field_id( 'hefe_tabs_link_widget_paired_id' ) . '" name="' . $this->get_field_name( 'hefe_tabs_link_widget_paired_id' ) . '" class="widefat" placeholder="' . esc_attr__( 'EX: 1', 'hefe' ) . '" value="' . esc_attr( $hefe_tabs_link_widget_paired_id ) . '">';
			echo '</p>';
			// Active
			echo '<p>';
				echo '<label for="' . $this->get_field_id( 'hefe_tabs_link_widget_active' ) . '" class="hefe_tabs_link_widget_active_label">' . __( 'Active', 'hefe' ) . '</label>';
				echo '<select id="' . $this->get_field_id( 'hefe_tabs_link_widget_active' ) . '" name="' . $this->get_field_name( 'hefe_tabs_link_widget_active' ) . '" class="widefat">';
					echo '<option value="" ' . selected( $hefe_tabs_link_widget_active, '', false ) . '> ' . __( 'False', 'hefe' ) . '</option>';
					echo '<option value="true" ' . selected( $hefe_tabs_link_widget_active, 'true', false ) . '> ' . __( 'True', 'hefe' ) . '</option>';
				echo '</select>';
			echo '</p>';
			// Style
			echo '<p>';
				echo '<label for="' . $this->get_field_id( 'hefe_tabs_link_widget_style' ) . '" class="hefe_tabs_link_widget_style_label">' . __( 'Style', 'hefe' ) . '</label>';
				echo '<select id="' . $this->get_field_id( 'hefe_tabs_link_widget_style' ) . '" name="' . $this->get_field_name( 'hefe_tabs_link_widget_style' ) . '" class="widefat">';
					echo '<option value="" ' . selected( $hefe_tabs_link_widget_style, '', false ) . '> ' . __( 'None', 'hefe' ) . '</option>';
					echo '<option value="01" ' . selected( $hefe_tabs_link_widget_style, '01', false ) . '> ' . __( '01', 'hefe' ) . '</option>';
				echo '</select>';
			echo '</p>';
		}
		public function update( $new_instance, $old_instance ) {
			$instance = $old_instance;
			$instance['hefe_tabs_link_widget_content'] = !empty( $new_instance['hefe_tabs_link_widget_content'] ) ? $new_instance['hefe_tabs_link_widget_content'] : '';
			$instance['hefe_tabs_link_widget_paired_id'] = !empty( $new_instance['hefe_tabs_link_widget_paired_id'] ) ? $new_instance['hefe_tabs_link_widget_paired_id'] : '';
			$instance['hefe_tabs_link_widget_active'] = !empty( $new_instance['hefe_tabs_link_widget_active'] ) ? strip_tags( $new_instance['hefe_tabs_link_widget_active'] ) : '';
			$instance['hefe_tabs_link_widget_style'] = !empty( $new_instance['hefe_tabs_link_widget_style'] ) ? strip_tags( $new_instance['hefe_tabs_link_widget_style'] ) : '';
			return $instance;
		}
	}
	add_action('widgets_init', 'register_hefe_tabs_link_widget');
	function register_hefe_tabs_link_widget() {
		register_widget('hefe_tabs_link_function_widget');
	}
}
// TwentyTwenty
if(get_option('hefe_widget_customizer_control_twentytwenty')){
	class hefe_twentytwenty_function_widget extends WP_Widget {
		public function __construct() {
			parent::__construct(
				'hefe_twentytwenty_widget',
				__( 'TwentyTwenty ('.hefe_shortcode_name.')', 'hefe' ),
				array(
					'description' => __( 'Display twentytwenty.', 'hefe' ),
					'classname'   => 'widget_hefe_twentytwenty_widget',
				)
			);
		}
		public function widget( $args, $instance ) {
			$hefe_twentytwenty_widget_before_image  = ( ! empty( $instance['hefe_twentytwenty_widget_before_image']  ) ) ? $instance['hefe_twentytwenty_widget_before_image'] : __( '' );
			$hefe_twentytwenty_widget_after_image  = ( ! empty( $instance['hefe_twentytwenty_widget_after_image']  ) ) ? $instance['hefe_twentytwenty_widget_after_image'] : __( '' );
			// Widget Before
			echo $args['before_widget'];
				// TwentyTwenty
				echo do_shortcode('[hefe_twentytwenty_parent][hefe_twentytwenty_child src="'.$hefe_twentytwenty_widget_before_image.'"][hefe_twentytwenty_child src="'.$hefe_twentytwenty_widget_after_image.'"][/hefe_twentytwenty_parent]');
			// Widget After
			echo $args['after_widget'];
		}
		public function form( $instance ) {
			// Set default values
			$instance = wp_parse_args( (array) $instance, array( 
				'hefe_twentytwenty_widget_before_image' => '',
				'hefe_twentytwenty_widget_after_image' => '',
			) );
			// Retrieve an existing value from the database
			$hefe_twentytwenty_widget_before_image = !empty( $instance['hefe_twentytwenty_widget_before_image'] ) ? $instance['hefe_twentytwenty_widget_before_image'] : '';
			$hefe_twentytwenty_widget_after_image = !empty( $instance['hefe_twentytwenty_widget_after_image'] ) ? $instance['hefe_twentytwenty_widget_after_image'] : '';
			// Before Image
			echo '<p>';
				echo '<label for="' . $this->get_field_id( 'hefe_twentytwenty_widget_before_image' ) . '" class="hefe_twentytwenty_widget_before_image_label">' . __( 'Before Image', 'hefe' ) . '</label>';
				echo '<input type="text" id="' . $this->get_field_id( 'hefe_twentytwenty_widget_before_image' ) . '" name="' . $this->get_field_name( 'hefe_twentytwenty_widget_before_image' ) . '" class="widefat" placeholder="' . esc_attr__( 'EX: image.jpg', 'hefe' ) . '" value="' . esc_attr( $hefe_twentytwenty_widget_before_image ) . '">';
			echo '</p>';
			// After Image
			echo '<p>';
				echo '<label for="' . $this->get_field_id( 'hefe_twentytwenty_widget_after_image' ) . '" class="hefe_twentytwenty_widget_after_image_label">' . __( 'After Image', 'hefe' ) . '</label>';
				echo '<input type="text" id="' . $this->get_field_id( 'hefe_twentytwenty_widget_after_image' ) . '" name="' . $this->get_field_name( 'hefe_twentytwenty_widget_after_image' ) . '" class="widefat" placeholder="' . esc_attr__( 'EX: image.jpg', 'hefe' ) . '" value="' . esc_attr( $hefe_twentytwenty_widget_after_image ) . '">';
			echo '</p>';
		}
		public function update( $new_instance, $old_instance ) {
			$instance = $old_instance;
			$instance['hefe_twentytwenty_widget_before_image'] = !empty( $new_instance['hefe_twentytwenty_widget_before_image'] ) ? strip_tags( $new_instance['hefe_twentytwenty_widget_before_image'] ) : '';
			$instance['hefe_twentytwenty_widget_after_image'] = !empty( $new_instance['hefe_twentytwenty_widget_after_image'] ) ? strip_tags( $new_instance['hefe_twentytwenty_widget_after_image'] ) : '';
			return $instance;
		}
	}
	add_action('widgets_init', 'register_hefe_twentytwenty_widget');
	function register_hefe_twentytwenty_widget() {
		register_widget('hefe_twentytwenty_function_widget');
	}
}

/* reCaptcha
------------------------------ */

if(get_option('hefe_control_customizer_control_rcapk') && get_option('hefe_control_customizer_control_rcappk')){
	wp_enqueue_script('recaptcha', 'https://www.google.com/recaptcha/api.js');
	function display_recaptcha(){
		echo '<div class="g-recaptcha" data-sitekey="'.get_option('hefe_control_customizer_control_rcapk').'" data-callback="recaptcha_callback"></div>';
	}
	add_action('login_form', 'display_recaptcha');
	add_filter('wp_authenticate_user', 'verify_captcha');
	function verify_captcha( $parameter = true ){
		if(isset($_POST['g-recaptcha-response'])){
			$secret_key = get_option('hefe_control_customizer_control_rcappk');
			$response = json_decode(wp_remote_retrieve_body( wp_remote_get('https://www.google.com/recaptcha/api/siteverify?secret='.$secret_key.'&response='.$_POST['g-recaptcha-response'] ) ), true );
			if($response["success"]){
				return $parameter;
			}else{
				return new WP_Error( 'empty_captcha', '<strong>ERROR:</strong> CAPTCHA returned incorrect.');
			}
		}
		return false;
	}
	add_action('register_form', 'display_recaptcha');
	add_filter('registration_errors', 'verify_registration_captcha', 10, 3);
	function verify_registration_captcha($errors, $sanitized_user_login, $user_email) {
		if(isset($_POST['g-recaptcha-response'])){
			$secret_key = get_option('hefe_control_customizer_control_rcappk');
			$response = json_decode(wp_remote_retrieve_body( wp_remote_get('https://www.google.com/recaptcha/api/siteverify?secret='.$secret_key.'&response='.$_POST['g-recaptcha-response'] ) ), true );
			if($response["success"]){
				return $errors;
			}else{
				return new WP_Error( 'failed_verification', '<strong>ERROR:</strong> CAPTCHA returned incorrect.');
			}
		}
		return $errors;
	}
	add_action('lostpassword_form', 'display_recaptcha');
	add_filter('allow_password_reset', 'verify_lostpassword_captcha', 10, 3);
	function verify_lostpassword_captcha($allow, $user_id) {
		if(isset($_POST['g-recaptcha-response'])){
			$secret_key = get_option('hefe_control_customizer_control_rcappk');
			$response = json_decode(wp_remote_retrieve_body( wp_remote_get('https://www.google.com/recaptcha/api/siteverify?secret='.$secret_key.'&response='.$_POST['g-recaptcha-response'] ) ), true );
			if($response["success"]){
				return $allow;
			}else{
				return new WP_Error( 'failed_verification', '<strong>ERROR:</strong> CAPTCHA returned incorrect.');
			}
		}
		return $allow;    
	}	
}

/* WP Head
------------------------------ */

// ACF Header addy
if(!function_exists('hefe_acf_form_header_addy')){
	function hefe_acf_form_header_addy(){
		acf_form_head();
	}
}
// Google Analytics
if(get_option('hefe_control_customizer_control_google_analytics_ua_code')){
	if(!function_exists('hefe_google_analytics_header_include_ua')){
		add_action('wp_head', 'hefe_google_analytics_header_include_ua');
		function hefe_google_analytics_header_include_ua(){
			echo "
			<script async src='https://www.googletagmanager.com/gtag/js?id=".get_option('hefe_control_customizer_control_google_analytics_ua_code')."'></script>
			<script>
			  window.dataLayer = window.dataLayer || [];
			  function gtag(){dataLayer.push(arguments);}
			  gtag('js', new Date());

			  gtag('config', '".get_option('hefe_control_customizer_control_google_analytics_ua_code')."');
			</script>
			";
		}
	}
}
// Injection
if(get_option('hefe_control_customizer_control_injection')){
	if(!function_exists('hefe_injection_universal_header_inc')){
		add_action('wp_head', 'hefe_injection_universal_header_inc');
		function hefe_injection_universal_header_inc(){ 
			$args = array(
			    'posts_per_page' => -1,
			    'post_type' => 'injection',
			    'meta_query'=> array(
					'relation' => 'AND',
					array(
						'key' => 'hefe_injection_meta_box_header',
						'compare' => '=',
						'value' => 'checked',
					),
					array(
						'key' => 'hefe_injection_meta_box_ids',
						'compare' => '=',
						'value' => 'none',
					),
			   ),
			);
			$hefe_injection_query = new WP_Query($args);
			while($hefe_injection_query->have_posts()): $hefe_injection_query->the_post();
			    echo do_shortcode(get_the_content());
			endwhile;
			wp_reset_postdata();
		}
	}
	if(!function_exists('hefe_injection_specific_header_inc')){
		add_action('wp_head', 'hefe_injection_specific_header_inc');
		function hefe_injection_specific_header_inc(){ 
			$args = array(
			    'posts_per_page' => -1,
			    'post_type' => 'injection',
			    'meta_query'=> array(
					'relation' => 'AND',
					array(
						'key' => 'hefe_injection_meta_box_header',
						'compare' => '=',
						'value' => 'checked',
					),
					array(
						'key' => 'hefe_injection_meta_box_ids',
						'compare' => 'LIKE',
						'value' => get_the_ID(),
					),
			   ),
			);
			$hefe_injection_query = new WP_Query($args);
			while($hefe_injection_query->have_posts()): $hefe_injection_query->the_post();
			    echo do_shortcode(get_the_content());
			endwhile;
			wp_reset_postdata();
		}
	}
}
// SEO
if(get_option('hefe_control_customizer_control_seo')){
	if(!function_exists('hefe_function_header_inc_seo')){
		add_action('wp_head', 'hefe_function_header_inc_seo', 1);
		function hefe_function_header_inc_seo(){ 
			$ID = get_the_ID();
			$hefe_seo_title = get_post_meta($ID, 'hefe_seo_title', true);
			if($hefe_seo_title != ''){
				$hefe_seo_title_display = '<title>'.$hefe_seo_title.'</title>';
				$hefe_seo_title_display .= '<meta property="og:title" content="'.$hefe_seo_title.'">';
				$hefe_seo_title_display .= '<meta name="twitter:title" content="'.$hefe_seo_title.'">';
				echo $hefe_seo_title_display;
			}else{
				$hefe_seo_title = get_post($ID);
				$hefe_seo_title = $hefe_seo_title->post_title.' - '.get_bloginfo('name');
				$hefe_seo_title_display = '<title>'.$hefe_seo_title.'</title>';
				$hefe_seo_title_display .= '<meta property="og:title" content="'.$hefe_seo_title.'">';
				$hefe_seo_title_display .= '<meta name="twitter:title" content="'.$hefe_seo_title.'">';
				echo $hefe_seo_title_display;
			}
			$hefe_seo_description = get_post_meta($ID, 'hefe_seo_description', true);
			if($hefe_seo_description != ''){
				$hefe_seo_description_display = '<meta name="description" content="'.$hefe_seo_description.'" />';
				$hefe_seo_description_display .= '<meta property="og:description" content="'.$hefe_seo_description .'">';
				$hefe_seo_description_display .= '<meta name="twitter:description" content="'.$hefe_seo_description.'">';
				echo $hefe_seo_description_display;
			}
			$hefe_seo_keywords = get_post_meta($ID, 'hefe_seo_keywords', true);
			if($hefe_seo_keywords != ''){
				$hefe_seo_keywords_display = '<meta name="keywords" content="'.$hefe_seo_keywords.'" />';
				echo $hefe_seo_keywords_display;
			}
			$hefe_seo_robots = get_post_meta($ID, 'hefe_seo_robots', true);
			if($hefe_seo_robots != ''){
				$hefe_seo_robots_display = '<meta name="robots" content="'.$hefe_seo_robots.'" />';
				echo $hefe_seo_robots_display;
			}
			$hefe_seo_schema = get_post_meta($ID, 'hefe_seo_schema', true);
			if($hefe_seo_schema != ''){
				$hefe_seo_schema_display = '<noscript>'.$hefe_seo_schema.'</noscript>';
				echo $hefe_seo_schema_display;
			}
		}
	}
}

/* WP Footer
------------------------------ */

// ACF Footer addy
if(!function_exists('hefe_acf_form_footer_addy')){
	function hefe_acf_form_footer_addy(){
		acf_enqueue_uploader();
	}
}
// Front-End Media
if(get_option('hefe_enqueue_customizer_control_front_end_media')){
	class Front_End_Media {
		function __construct() {
			add_action( 'init', array( $this, 'init' ) );
		}
		function init() {
			load_plugin_textdomain( 'front-end-media', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
			add_filter( 'ajax_query_attachments_args', array( $this, 'filter_media' ) );
		}
		function enqueue_scripts() {
			wp_enqueue_media();
		}
		function filter_media( $query ) {
			if ( ! current_user_can( 'manage_options' ) ){
				$query['author'] = get_current_user_id();
			}
			return $query;
		}
	}
	new Front_End_Media();
}
// Injection
if(get_option('hefe_control_customizer_control_injection')){
	if(!function_exists('hefe_injection_universal_footer_inc')){
		add_action('wp_footer', 'hefe_injection_universal_footer_inc');
		function hefe_injection_universal_footer_inc(){ 
			$args = array(
			    'posts_per_page' => -1,
			    'post_type' => 'injection',
			    'meta_query'=> array(
					'relation' => 'AND',
					array(
						'key' => 'hefe_injection_meta_box_footer',
						'compare' => '=',
						'value' => 'checked',
					),
					array(
						'key' => 'hefe_injection_meta_box_ids',
						'compare' => '=',
						'value' => 'none',
					),
			   ),
			);
			$hefe_injection_query = new WP_Query($args);
			while($hefe_injection_query->have_posts()): $hefe_injection_query->the_post();
			    echo do_shortcode(get_the_content());
			endwhile;
			wp_reset_postdata();
		}
	}
	if(!function_exists('hefe_injection_specific_footer_inc')){
		add_action('wp_footer', 'hefe_injection_specific_footer_inc');
		function hefe_injection_specific_footer_inc(){ 
			$args = array(
			    'posts_per_page' => -1,
			    'post_type' => 'injection',
			    'meta_query'=> array(
					'relation' => 'AND',
					array(
						'key' => 'hefe_injection_meta_box_footer',
						'compare' => '=',
						'value' => 'checked',
					),
					array(
						'key' => 'hefe_injection_meta_box_ids',
						'compare' => 'LIKE',
						'value' => get_the_ID(),
					),
			   ),
			);
			$hefe_injection_query = new WP_Query($args);
			while($hefe_injection_query->have_posts()): $hefe_injection_query->the_post();
			    echo do_shortcode(get_the_content());
			endwhile;
			wp_reset_postdata();
		}
	}
}
// Pop Out Sidebar
if(get_option('hefe_control_customizer_control_pop_out_sidebar')){
	if(!function_exists('hefe_pop_out_sidebar_footer_inc')){
		function hefe_pop_out_sidebar_footer_inc(){ 
			$hefe_pop_out_sidebar_return = '<div class="hefe-pop-out-sidebar-widgets">';
				ob_start();
				include(hefe_dir_path.'php/widgets-pop-out-sidebar.php');
				$hefe_pop_out_sidebar_return .= ob_get_contents();
				ob_end_clean();
			$hefe_pop_out_sidebar_return .= '</div><div class="hefe-pop-out-sidebar-body-bg">&nbsp;</div>';
			echo $hefe_pop_out_sidebar_return;
		}
	}
}
// Pop Out Sidebar w/ Enqueue
if(get_option('hefe_enqueue_customizer_control_pop_out_sidebar')){
	add_action('wp_footer', 'hefe_pop_out_sidebar_footer_inc');
}
// Scroll Up Box
if(get_option('hefe_control_customizer_control_scroll_up_box')){
	if(!function_exists('hefe_scroll_up_box_footer_inc')){
		add_action('wp_footer', 'hefe_scroll_up_box_footer_inc');
		function hefe_scroll_up_box_footer_inc(){ 
			wp_enqueue_style('hefe-scroll-up-box-style');
			echo do_shortcode('[hefe_scroll_up_box automatic="true"][/hefe_scroll_up_box]');
		}
	}
}
// Search Modal
if(!function_exists('hefe_search_modal_footer_inc')){
	if(get_option('hefe_enqueue_customizer_control_search_modal')){
		add_action('wp_footer', 'hefe_search_modal_footer_inc');
	}
	function hefe_search_modal_footer_inc(){ 
		echo '<div class="hefe-search-modal-footer"><div class="hefe-search-modal-table"><div class="hefe-search-modal-table-cell"><form method="get" action="'.esc_url(home_url('/')).'" class="hefe-search-modal-search-form"><div class="hefe-search-modal-search-form-input-group"><input type="text" name="s" placeholder="Search" value="" /><span class="hefe-search-modal-search-form-input-group-btn"><button class="hefe-search-modal-search-form-btn" type="submit"><i class="hefe-search-modal-search-form-search-icon"></i></button></span></div></form><div class="hefe-search-modal-toggle-out">x</div></div></div></div>';
	}
}

/* Pre Get Posts
------------------------------ */

// Exclude Page IDs From Search
if(get_option('hefe_control_customizer_control_page_ids_excluded_from_search')){
	if(!function_exists('hefe_control_init_exclude_from_search_page_ids')){
		add_action('pre_get_posts', 'hefe_control_init_exclude_from_search_page_ids');
		function hefe_control_init_exclude_from_search_page_ids($query){
			$hefe_exclude_from_search_page_ids_variables = explode(',', str_replace(' ', '', get_option('hefe_control_customizer_control_page_ids_excluded_from_search')));
			$hefe_exclude_from_search_page_ids_array = array();
			foreach($hefe_exclude_from_search_page_ids_variables as $hefe_exclude_from_search_page_ids_variable):
				$hefe_exclude_from_search_page_ids_array[] = $hefe_exclude_from_search_page_ids_variable;
			endforeach;
			if($query->is_search && $query->is_main_query()){
				$query->set('post__not_in', $hefe_exclude_from_search_page_ids_array);
			}
		}
	}
}
// Home Post Type / Post Types
if(get_option('hefe_control_customizer_control_post_types_on_home_page')){
	if(!function_exists('hefe_control_pre_get_posts_home_post_type_filter')){
		add_action('pre_get_posts', 'hefe_control_pre_get_posts_home_post_type_filter');
		function hefe_control_pre_get_posts_home_post_type_filter($wp_query){
			$hefe_home_post_types =  explode(',', str_replace(' ', '', get_option('hefe_control_customizer_control_post_types_on_home_page')));
			if(get_option('hefe_control_customizer_control_post_type_status_on_home_page')){
				$hefe_home_post_status = explode(',', str_replace(' ', '', get_option('hefe_control_customizer_control_post_type_status_on_home_page')));
			}else{
				$hefe_home_post_status = 'any';
			}
			if ( !is_admin() && $wp_query->is_main_query() && is_home() ) {
				$wp_query->set('post_type', $hefe_home_post_types );
				$wp_query->set('post_status', $hefe_home_post_status );
			}
		}
	}
}

/* Init
------------------------------ */

// Exclude Post Types From Search
if(get_option('hefe_control_customizer_control_post_types_excludes_from_search')){
	if(!function_exists('hefe_control_init_exclude_from_search_post_types')){
		add_action('init', 'hefe_control_init_exclude_from_search_post_types');
		function hefe_control_init_exclude_from_search_post_types(){
			global $wp_post_types;
			$hefe_exclude_from_search_post_types_variables = explode(',', str_replace(' ', '', get_option('hefe_control_customizer_control_post_types_excludes_from_search')));
			foreach($hefe_exclude_from_search_post_types_variables as $hefe_exclude_from_search_post_types_variable):
				$wp_post_types[$hefe_exclude_from_search_post_types_variable]->exclude_from_search = true;
			endforeach;
		}
	}
}
// Custom Author Base
if(get_option('hefe_control_customizer_control_custom_author_base')){
	if(!function_exists('hefe_control_init_custom_author_base')){
		add_action('init', 'hefe_control_init_custom_author_base');
		function hefe_control_init_custom_author_base() {
		    global $wp_rewrite;
		    $author_slug = sanitize_text_field(get_option('hefe_control_customizer_control_custom_author_base'));
		    $wp_rewrite->author_base = $author_slug;
		}
	}
}

/* After Setup Theme
------------------------------ */

// Disable Admin Bar
if(get_option('hefe_control_customizer_control_disable_admin_bar_by_user_role')){
	if(!function_exists('hefe_after_setup_theme_disable_admin_bar')){
		add_action('after_setup_theme', 'hefe_after_setup_theme_disable_admin_bar');
		function hefe_after_setup_theme_disable_admin_bar(){
			// Get current user role
			$hefe_disable_admin_bar_get_roles = implode(get_userdata(get_current_user_id())->roles);
			// Get user roles to disable admin bar
			$hefe_disable_admin_bar_user_role_variables = explode(',', str_replace(' ', '', strtolower(get_option('hefe_control_customizer_control_disable_admin_bar_by_user_role'))));
			// Disable admin bar
			foreach($hefe_disable_admin_bar_user_role_variables as $hefe_disable_admin_bar_user_role_variable){
				if($hefe_disable_admin_bar_user_role_variable == $hefe_disable_admin_bar_get_roles){
					show_admin_bar(false);
				}
			}
		}
	}
}
// Enable Admin Bar
if(get_option('hefe_control_customizer_control_enable_admin_bar_by_user_role')){
	if(!function_exists('hefe_control_after_setup_theme_enable_admin_bar')){
		add_action('after_setup_theme', 'hefe_control_after_setup_theme_enable_admin_bar');
		function hefe_control_after_setup_theme_enable_admin_bar(){
			if(get_userdata(get_current_user_id())->roles){
				$user_roles = get_userdata(get_current_user_id())->roles;
			}else{
				$user_roles = array();
			}
			// Get current user role
			$hefe_enable_admin_bar_get_roles = implode($user_roles);
			// Get user roles to disable admin bar
			$hefe_enable_admin_bar_user_role_variables =  explode(',', str_replace(' ', '', strtolower(get_option('hefe_control_customizer_control_enable_admin_bar_by_user_role'))));
			// Disable admin bar
			foreach($hefe_enable_admin_bar_user_role_variables as $hefe_enable_admin_bar_user_role_variable){
				if($hefe_enable_admin_bar_user_role_variable == $hefe_enable_admin_bar_get_roles){
					show_admin_bar(true);
				}else {
					show_admin_bar(false);
				}
			}
		}
	}
}

/* Posts Orderby
------------------------------ */

// Home Page Orderby
if(get_option('hefe_control_customizer_control_home_page_orderby')){
	if(strtolower(get_option('hefe_control_customizer_control_home_page_orderby')) == 'random' || strtolower(get_option('hefe_control_customizer_control_home_page_orderby')) == 'rand'){
		session_start();
		add_filter( 'posts_orderby', 'randomise_with_pagination' );
		function randomise_with_pagination( $orderby ) {
			if( is_front_page() ) {
			  	// Reset seed on load of initial archive page
				if( ! get_query_var( 'paged' ) || get_query_var( 'paged' ) == 0 || get_query_var( 'paged' ) == 1 ) {
					if( isset( $_SESSION['seed'] ) ) {
						unset( $_SESSION['seed'] );
					}
				}
			
				// Get seed from session variable if it exists
				$seed = false;
				if( isset( $_SESSION['seed'] ) ) {
					$seed = $_SESSION['seed'];
				}
			
			    	// Set new seed if none exists
			    	if ( ! $seed ) {
			      		$seed = rand();
			      		$_SESSION['seed'] = $seed;
			    	}
			
			    	// Update ORDER BY clause to use seed
			    	$orderby = 'RAND(' . $seed . ')';
			}
			return $orderby;
		}
	}else{
		add_action( 'pre_get_posts', 'home_query_order' );
		function home_query_order( $query ) {
		    if ( $query->is_home() && $query->is_main_query() ) {
		        $query->set( 'orderby', strtolower(get_option('hefe_control_customizer_control_home_page_orderby')) );
		        $query->set( 'order', strtoupper(get_option('hefe_control_customizer_control_home_page_order')) );
		    }
		}
	}
}

/* INIT
------------------------------ */

// Injection
if(get_option('hefe_control_customizer_control_injection')){
	if(!function_exists('hefe_excludeinjectionfromsearch')){
		add_action('init', 'hefe_excludeinjectionfromsearch');
		function hefe_excludeinjectionfromsearch(){
			global $wp_post_types;
			$wp_post_types['injection']->exclude_from_search = true;
		}
	}
}
// SEO
if(get_option('hefe_control_customizer_control_seo')){
	if(!function_exists('hefe_seo_remove_title')){
		add_action('init', 'hefe_seo_remove_title');
		function hefe_seo_remove_title(){ 
			remove_theme_support('title-tag');
		}
	}
}

/* Post Types
------------------------------ */

// Injection
if(get_option('hefe_control_customizer_control_injection')){
	if(!function_exists('hefe_injection')){
		function hefe_injection(){
			$labels = array(
				'name' => _x('Injections', 'Post Type General Name', 'hefe_injection'),
				'singular_name' => _x('Injection', 'Post Type Singular Name', 'hefe_injection'),
				'menu_name' => __('Injections', 'hefe_injection'),
				'name_admin_bar' => __('Injection', 'hefe_injection'),
				'archives' => __('Injection Archives', 'hefe_injection'),
				'parent_item_colon' => __('Parent Injection:', 'hefe_injection'),
				'all_items' => __('All Injections', 'hefe_injection'),
				'add_new_item' => __('Add New Injection', 'hefe_injection'),
				'add_new' => __('Add New', 'hefe_injection'),
				'new_item' => __('New Injection', 'hefe_injection'),
				'edit_item' => __('Edit Injection', 'hefe_injection'),
				'update_item' => __('Update Injection', 'hefe_injection'),
				'view_item' => __('View Injection', 'hefe_injection'),
				'search_items' => __('Search Injections', 'hefe_injection'),
				'not_found' => __('Not found', 'hefe_injection'),
				'not_found_in_trash' => __('Not found in Trash', 'hefe_injection'),
				'featured_image' => __('Featured Image', 'hefe_injection'),
				'set_featured_image' => __('Set featured image', 'hefe_injection'),
				'remove_featured_image' => __('Remove featured image', 'hefe_injection'),
				'use_featured_image' => __('Use as featured image', 'hefe_injection'),
				'insert_into_item' => __('Insert into injection', 'hefe_injection'),
				'uploaded_to_this_item' => __('Uploaded to this injection', 'hefe_injection'),
				'items_list' => __('Injections list', 'hefe_injection'),
				'items_list_navigation' => __('Injections list navigation', 'hefe_injection'),
				'filter_items_list' => __('Filter injections list', 'hefe_injection'),
			);
			$rewrite = array(
				'slug' => 'injection',
				'with_front' => true,
				'pages' => true,
				'feeds' => true,
			);
			$args = array(
				'label' => __('Injection', 'hefe_injection'),
				'description' => __('Injection', 'hefe_injection'),
				'labels' => $labels,
				'supports' => array('title', 'editor', 'author', 'revisions',),
				'taxonomies' => array('injection_filter'),
				'hierarchical' => false,
				'public' => true,
				'show_ui' => true,
				'show_in_menu' => true,
				'menu_position' => 5,
				'show_in_admin_bar' => true,
				'show_in_nav_menus' => true,
				'can_export' => true,
				'has_archive' => true,		
				'exclude_from_search' => false,
				'publicly_queryable' => true,
				'rewrite' => $rewrite,
				'capability_type' => 'page',
				'capabilities' => array(
					'publish_posts' => 'manage_options',
					'edit_posts' => 'manage_options',
					'edit_others_posts' => 'manage_options',
					'delete_posts' => 'manage_options',
					'delete_others_posts' => 'manage_options',
					'read_private_posts' => 'manage_options',
					'edit_post' => 'manage_options',
					'delete_post' => 'manage_options',
					'read_post' => 'manage_options',
				),
			);
			register_post_type('injection', $args);
		}
		add_action('init', 'hefe_injection', 0);
	}
}

/* Taxonomies
------------------------------ */

// Injection
if(get_option('hefe_control_customizer_control_injection')){
	if(!function_exists('hefe_injection_filter')){
		function hefe_injection_filter(){
			$labels = array(
				'name' => _x('Injection Filters', 'Taxonomy General Name', 'injection_filter'),
				'singular_name' => _x('Injection Filter', 'Taxonomy Singular Name', 'injection_filter'),
				'menu_name' => __('Injection Filter', 'injection_filter'),
				'all_items' => __('All Injection Filters', 'injection_filter'),
				'parent_item' => __('Parent Injection Filter', 'injection_filter'),
				'parent_item_colon' => __('Parent Injection Filter:', 'injection_filter'),
				'new_item_name' => __('New Injection Filter Name', 'injection_filter'),
				'add_new_item' => __('Add New Injection Filter', 'injection_filter'),
				'edit_item' => __('Edit Injection Filter', 'injection_filter'),
				'update_item' => __('Update Injection Filter', 'injection_filter'),
				'view_item' => __('View Injection Filter', 'injection_filter'),
				'separate_items_with_commas' => __('Separate injection filters with commas', 'injection_filter'),
				'add_or_remove_items' => __('Add or remove injection filters', 'injection_filter'),
				'choose_from_most_used' => __('Choose from the most used', 'injection_filter'),
				'popular_items' => __('Popular Injection Filters', 'injection_filter'),
				'search_items' => __('Search Injection Filters', 'injection_filter'),
				'not_found' => __('Not Found', 'injection_filter'),
				'no_terms' => __('No injection filters', 'injection_filter'),
				'items_list' => __('Injection Filters list', 'injection_filter'),
				'items_list_navigation' => __('Injection Filters list navigation', 'injection_filter'),
			);
			$rewrite = array(
				'slug' => 'injection-filter',
				'with_front' => true,
				'hierarchical' => false,
			);
			$args = array(
				'labels' => $labels,
				'hierarchical' => false,
				'public' => true,
				'show_ui' => true,
				'show_admin_column' => true,
				'show_in_nav_menus' => true,
				'show_tagcloud' => true,
				'rewrite' => $rewrite,
			);
			register_taxonomy('injection_filter', array('injection'), $args);
		}
		add_action('init', 'hefe_injection_filter', 0);
	}
}

/* Meta Boxes
------------------------------ */

// Banner Per Page
if(get_option('hefe_control_customizer_control_banner_per_page')){
	class hefe_banner_per_page_meta_box{
		public function __construct(){
			if(is_admin()):
				add_action('load-post.php',     array($this, 'init_metabox'));
				add_action('load-post-new.php', array($this, 'init_metabox'));
			endif;
		}
		public function init_metabox(){
			add_action('add_meta_boxes',        array($this, 'add_metabox')        );
			add_action('save_post',             array($this, 'save_metabox'), 10, 2);
		}
		public function add_metabox(){
			add_meta_box(
				'',
				__('Banner Per Page ('.hefe_shortcode_name.')', 'text_domain'),
				array($this, 'render_metabox'),
				explode(',', get_option('hefe_control_customizer_control_banner_per_page')),
				'normal',
				'default'
			);
		}
		public function render_metabox($post){
			// Add nonce for security and authentication.
			wp_nonce_field('hefe_banner_per_page_meta_box_nonce_action', 'hefe_banner_per_page_meta_box_nonce');
			// Retrieve an existing value from the database.
			$hefe_banner_per_page_meta_box_banner_content = get_post_meta($post->ID, 'hefe_banner_per_page_meta_box_banner_content', true);
			$hefe_banner_per_page_meta_box_banner_src = get_post_meta($post->ID, 'hefe_banner_per_page_meta_box_banner_src', true);
			$hefe_banner_per_page_meta_box_banner_height = get_post_meta($post->ID, 'hefe_banner_per_page_meta_box_banner_height', true);
			// Set default values.
			// if(empty($hefe_banner_per_page_meta_box_banner_url)) $hefe_banner_per_page_meta_box_banner_url = 'none';
			echo '<table class="form-table">';
				echo '<tr>';
					echo '<th><label for="hefe_banner_per_page_meta_box_banner_content" class="hefe_banner_per_page_meta_box_banner_content_label">'.__('Banner Content', 'hefe').'</label></th>';
					echo '<td>';
						echo '<textarea rows="8" id="hefe_banner_per_page_meta_box_banner_content" name="hefe_banner_per_page_meta_box_banner_content" class="hefe_banner_per_page_meta_box_banner_content_field" placeholder="'.esc_attr__('EX: Page Title', 'hefe').'">'.esc_attr__($hefe_banner_per_page_meta_box_banner_content).'</textarea>';
					echo '</td>';
				echo '</tr>';
				echo '<tr>';
					echo '<th><label for="hefe_banner_per_page_meta_box_banner_src" class="hefe_banner_per_page_meta_box_banner_src_label">'.__('Banner URL', 'hefe').'</label></th>';
					echo '<td>';
						echo '<input type="text" id="hefe_banner_per_page_meta_box_banner_src" name="hefe_banner_per_page_meta_box_banner_src" class="hefe_banner_per_page_meta_box_banner_src_field" placeholder="'.esc_attr__('EX: image.jpg', 'hefe').'" value="'.esc_attr__($hefe_banner_per_page_meta_box_banner_src).'">';
					echo '</td>';
				echo '</tr>';
				echo '<tr>';
					echo '<th><label for="hefe_banner_per_page_meta_box_banner_height" class="hefe_banner_per_page_meta_box_banner_height_label">'.__('Banner Height (px)', 'hefe').'</label></th>';
					echo '<td>';
						echo '<input type="text" id="hefe_banner_per_page_meta_box_banner_height" name="hefe_banner_per_page_meta_box_banner_height" class="hefe_banner_per_page_meta_box_banner_height_field" placeholder="'.esc_attr__('EX: 100', 'hefe').'" value="'.esc_attr__($hefe_banner_per_page_meta_box_banner_height).'">';
					echo '</td>';
				echo '</tr>';
			echo '</table>';
		}
		public function save_metabox($post_id, $post){
			// Add nonce for security and authentication.
			$nonce_name = isset($_POST['hefe_banner_per_page_meta_box_nonce']) ? $_POST['hefe_banner_per_page_meta_box_nonce']: '';
			$nonce_action = 'hefe_banner_per_page_meta_box_nonce_action';
			// Check if a nonce is set.
			if(!isset($nonce_name))
				return;
			// Check if a nonce is valid.
			if(!wp_verify_nonce($nonce_name, $nonce_action))
				return;
			// Sanitize user input.
			$hefe_banner_per_page_meta_box_new_banner_content = isset($_POST[ 'hefe_banner_per_page_meta_box_banner_content' ]) ? $_POST[ 'hefe_banner_per_page_meta_box_banner_content' ]: '';
			$hefe_banner_per_page_meta_box_new_banner_src = isset($_POST[ 'hefe_banner_per_page_meta_box_banner_src' ]) ? sanitize_text_field($_POST[ 'hefe_banner_per_page_meta_box_banner_src' ]): '';
			$hefe_banner_per_page_meta_box_new_banner_height = isset($_POST[ 'hefe_banner_per_page_meta_box_banner_height' ]) ? sanitize_text_field($_POST[ 'hefe_banner_per_page_meta_box_banner_height' ]): '';
			// Update the meta field in the database.
			update_post_meta($post_id, 'hefe_banner_per_page_meta_box_banner_content', $hefe_banner_per_page_meta_box_new_banner_content);
			update_post_meta($post_id, 'hefe_banner_per_page_meta_box_banner_src', $hefe_banner_per_page_meta_box_new_banner_src);
			update_post_meta($post_id, 'hefe_banner_per_page_meta_box_banner_height', $hefe_banner_per_page_meta_box_new_banner_height);
		}
	}
	new hefe_banner_per_page_meta_box;
}
// Injection
if(get_option('hefe_control_customizer_control_injection')){
	class hefe_injection_meta_box{
		public function __construct(){
			if(is_admin()):
				add_action('load-post.php',     array($this, 'init_metabox'));
				add_action('load-post-new.php', array($this, 'init_metabox'));
			endif;
		}
		public function init_metabox(){
			add_action('add_meta_boxes',        array($this, 'add_metabox')        );
			add_action('save_post',             array($this, 'save_metabox'), 10, 2);
		}
		public function add_metabox(){
			add_meta_box(
				'',
				__('Injection Locations', 'text_domain'),
				array($this, 'render_metabox'),
				'injection',
				'normal',
				'default'
			);
		}
		public function render_metabox($post){
			// Add nonce for security and authentication.
			wp_nonce_field('hefe_injection_meta_box_nonce_action', 'hefe_injection_meta_box_nonce');
			// Retrieve an existing value from the database.
			$hefe_injection_meta_box_header = get_post_meta($post->ID, 'hefe_injection_meta_box_header', true);
			$hefe_injection_meta_box_footer = get_post_meta($post->ID, 'hefe_injection_meta_box_footer', true);
			$hefe_injection_meta_box_ids = get_post_meta($post->ID, 'hefe_injection_meta_box_ids', true);
			// Set default values.
			if(empty($hefe_injection_meta_box_ids)) $hefe_injection_meta_box_ids = 'none';
			// Form fields.
			echo '<table class="form-table">';
			echo '	<tr>';
			echo '		<th><label for="hefe_injection_meta_box_placement" class="hefe_injection_meta_box_placement_label">'.__('Placement', 'text_domain').'</label></th>';
			echo '		<td>';
			echo '			<label><input type="checkbox" name="hefe_injection_meta_box_header" class="hefe_injection_meta_box_placement_field" value="'.$hefe_injection_meta_box_header.'" '.checked($hefe_injection_meta_box_header, 'checked', false).'>'.__('Header', 'text_domain').'</label><br>';
			echo '			<label><input type="checkbox" name="hefe_injection_meta_box_footer" class="hefe_injection_meta_box_placement_field" value="'.$hefe_injection_meta_box_footer.'" '.checked($hefe_injection_meta_box_footer, 'checked', false).'>'.__('Footer', 'text_domain').'</label><br>';
			echo '			<p class="description">'.__('Need this injection in the header / footer?', 'text_domain').'</p>';
			echo '		</td>';
			echo '	</tr>';
			echo '	<tr>';
			echo '		<th><label for="hefe_injection_meta_box_ids" class="hefe_injection_meta_box_ids_label">'.__('Specific Page IDs', 'text_domain').'</label></th>';
			echo '		<td>';
			echo '			<input type="text" id="hefe_injection_meta_box_ids" name="hefe_injection_meta_box_ids" class="hefe_injection_meta_box_ids_field" placeholder="'.esc_attr__('1,2,3 or none', 'text_domain').'" value="'.esc_attr__($hefe_injection_meta_box_ids).'">';
			echo '			<p class="description">'.__('Need this injection on a specific page?', 'text_domain').'</p>';
			echo '		</td>';
			echo '	</tr>';
			echo '</table>';
		}
		public function save_metabox($post_id, $post){
			// Add nonce for security and authentication.
			$nonce_name   = isset($_POST['hefe_injection_meta_box_nonce']) ? $_POST['hefe_injection_meta_box_nonce']: '';
			$nonce_action = 'hefe_injection_meta_box_nonce_action';
			// Check if a nonce is set.
			if(!isset($nonce_name))
				return;
			// Check if a nonce is valid.
			if(!wp_verify_nonce($nonce_name, $nonce_action))
				return;
			// Sanitize user input.
			$hefe_injection_meta_box_new_header = isset($_POST[ 'hefe_injection_meta_box_header' ]) ? 'checked': '';
			$hefe_injection_meta_box_new_footer = isset($_POST[ 'hefe_injection_meta_box_footer' ]) ? 'checked': '';
			$hefe_injection_meta_box_new_ids = isset($_POST[ 'hefe_injection_meta_box_ids' ]) ? sanitize_text_field($_POST[ 'hefe_injection_meta_box_ids' ]): '';
			// Update the meta field in the database.
			update_post_meta($post_id, 'hefe_injection_meta_box_header', $hefe_injection_meta_box_new_header);
			update_post_meta($post_id, 'hefe_injection_meta_box_footer', $hefe_injection_meta_box_new_footer);
			update_post_meta($post_id, 'hefe_injection_meta_box_ids', $hefe_injection_meta_box_new_ids);
		}
	}
	new hefe_injection_meta_box;
}
// Default Hidden Injection
if(get_option('hefe_control_customizer_control_injection')){
	if(!function_exists('hefe_injection_hide_meta_boxes')){
		add_action('default_hidden_meta_boxes', 'hefe_injection_hide_meta_boxes', 10, 2);
		function hefe_injection_hide_meta_boxes($hefe_injection_hidden, $hefe_injection_screen){
			$hefe_injection_post_type = $hefe_injection_screen->post_type;
			if($hefe_injection_post_type == 'injection'):
				$hefe_injection_hidden = array('slugdiv', 'authordiv');
			endif;
			return $hefe_injection_hidden;
		}
	}
}
// SEO
if(get_option('hefe_control_customizer_control_seo')){
	class hefe_seo_custom_fields{
		public function __construct(){
			if(is_admin()){
				add_action('load-post.php',     array($this, 'init_metabox'));
				add_action('load-post-new.php', array($this, 'init_metabox'));
			}
		}
		public function init_metabox(){
			add_action('add_meta_boxes', array($this, 'add_metabox')       );
			add_action('save_post',      array($this, 'save_metabox'), 10, 2);
		}
		public function add_metabox(){
			$post_types = get_post_types();
			foreach($post_types as $post_type):
				add_meta_box(
					'hefe_seo_options',
					__('SEO ('.hefe_shortcode_name.')', 'text_domain'),
					array($this, 'render_metabox'),
					'',
					'normal',
					'default'
				);
			endforeach;
		}
		public function render_metabox($post){
			// Add nonce for security and authentication.
			wp_nonce_field('hefe_seo_nonce_action', 'hefe_seo_nonce');
			// Retrieve an existing value from the database.
			$hefe_seo_title = get_post_meta($post->ID, 'hefe_seo_title', true);
			$hefe_seo_description = get_post_meta($post->ID, 'hefe_seo_description', true);
			$hefe_seo_keywords = get_post_meta($post->ID, 'hefe_seo_keywords', true);
			// Set default values.
			if(empty($hefe_seo_title)) $hefe_seo_title = '';
			if(empty($hefe_seo_description)) $hefe_seo_description = '';
			if(empty($hefe_seo_keywords)) $hefe_seo_keywords = '';
			// Form fields.
			echo '<table class="form-table">';
			echo '	<tr>';
			echo '		<th><label for="hefe_seo_title" class="hefe_seo_title_label">'.__('Title', 'text_domain').'</label></th>';
			echo '		<td>';
			echo '			<input type="text" id="hefe_seo_title" name="hefe_seo_title" class="hefe_seo_title_field" placeholder="'.esc_attr__('', 'text_domain').'" value="'.esc_attr($hefe_seo_title).'" style="width: 100%;">';
			echo '			<p class="description">'.__('Place the title of this page here.', 'text_domain').'</p>';
			echo '		</td>';
			echo '	</tr>';
			echo '	<tr>';
			echo '		<th><label for="hefe_seo_description" class="hefe_seo_description_label">'.__('Description', 'text_domain').'</label></th>';
			echo '		<td>';
			echo '			<input type="text" id="hefe_seo_description" name="hefe_seo_description" class="hefe_seo_description_field" placeholder="'.esc_attr__('', 'text_domain').'" value="'.esc_attr($hefe_seo_description).'" style="width: 100%;">';
			echo '			<p class="description">'.__('Place the description of this page here.', 'text_domain').'</p>';
			echo '		</td>';
			echo '	</tr>';
			echo '	<tr>';
			echo '		<th><label for="hefe_seo_keywords" class="hefe_seo_keywords_label">'.__('Keywords', 'text_domain').'</label></th>';
			echo '		<td>';
			echo '			<input type="text" id="hefe_seo_keywords" name="hefe_seo_keywords" class="hefe_seo_keywords_field" placeholder="'.esc_attr__('', 'text_domain').'" value="'.esc_attr($hefe_seo_keywords).'" style="width: 100%;">';
			echo '			<p class="description">'.__('Place the keywords of this page here.', 'text_domain').'</p>';
			echo '		</td>';
			echo '	</tr>';
			echo '</table>';
		}
		public function save_metabox($post_id, $post){
			// Add nonce for security and authentication.
			$nonce_name   = isset($_POST['hefe_seo_nonce']) ? $_POST['hefe_seo_nonce']: '';
			$nonce_action = 'hefe_seo_nonce_action';
			// Check if a nonce is set.
			if(!isset($nonce_name))
				return;
			// Check if a nonce is valid.
			if(!wp_verify_nonce($nonce_name, $nonce_action))
				return;
			// Sanitize user input.
			$hefe_seo_title_new = isset($_POST[ 'hefe_seo_title' ]) ? sanitize_text_field($_POST[ 'hefe_seo_title' ]): '';
			$hefe_seo_description_new = isset($_POST[ 'hefe_seo_description' ]) ? sanitize_text_field($_POST[ 'hefe_seo_description' ]): '';
			$hefe_seo_keywords_new = isset($_POST[ 'hefe_seo_keywords' ]) ? sanitize_text_field($_POST[ 'hefe_seo_keywords' ]): '';
			// Update the meta field in the database.
			update_post_meta($post_id, 'hefe_seo_title', $hefe_seo_title_new);
			update_post_meta($post_id, 'hefe_seo_description', $hefe_seo_description_new);
			update_post_meta($post_id, 'hefe_seo_keywords', $hefe_seo_keywords_new);
		}
	}
	new hefe_seo_custom_fields;
}

/* Register Sidebars
------------------------------ */

// Pop Out Sidebar
if(get_option('hefe_control_customizer_control_pop_out_sidebar')){
	if(!function_exists('hefe_pop_out_sidebar_wp_loaded_register_sidebar')){
		add_action('wp_loaded', 'hefe_pop_out_sidebar_wp_loaded_register_sidebar');
		function hefe_pop_out_sidebar_wp_loaded_register_sidebar(){
			register_sidebar(
				array(
					'name'=> 'Pop Out Sidebar',
					'id' => 'pop-out-sidebar',
					'before_widget' => '<div id="%1$s" class="hefe-pop-out-sidebar-widget %2$s">',
					'after_widget' => '</div>',
					'before_title' => '<h3 class="hefe-pop-out-sidebar-widget-title">',
					'after_title' => '</h3>',
				)
			);
		}
	}
}
// Sidebar 01
if(get_option('hefe_control_customizer_control_sidebar_01')){
	if(!function_exists('hefe_sidebars_wp_loaded_01_register_sidebar')){
		add_action('wp_loaded', 'hefe_sidebars_wp_loaded_01_register_sidebar');
		function hefe_sidebars_wp_loaded_01_register_sidebar(){
			$hefe_sidebars_01_title = get_option('hefe_control_customizer_control_sidebar_01');
			$hefe_sidebars_01_heading = get_option('hefe_control_customizer_control_sidebar_01_header');
			if($hefe_sidebars_01_heading == ''){
				$hefe_sidebars_01_heading = 'h3';
			}
			register_sidebar(
				array(
					'name' => $hefe_sidebars_01_title,
					'id' =>'sidebar-01',
					'before_widget' =>'<div id="%1$s" class="hefe-sidebar-01-widget %2$s">',
					'after_widget' =>'</div>',
					'before_title' =>'<'.$hefe_sidebars_01_heading.' class="hefe-sidebar-01-widget-title">',
					'after_title' =>'</'.$hefe_sidebars_01_heading.'>',
				)
			);
		}
	}
}
// Sidebar 02
if(get_option('hefe_control_customizer_control_sidebar_02')){
	if(!function_exists('hefe_sidebars_wp_loaded_02_register_sidebar')){
		add_action('wp_loaded', 'hefe_sidebars_wp_loaded_02_register_sidebar');
		function hefe_sidebars_wp_loaded_02_register_sidebar(){
			$hefe_sidebars_02_title = get_option('hefe_control_customizer_control_sidebar_02');
			$hefe_sidebars_02_heading = get_option('hefe_control_customizer_control_sidebar_02_header');
			if($hefe_sidebars_02_heading == ''){
				$hefe_sidebars_02_heading = 'h3';
			}
			register_sidebar(
				array(
					'name' => $hefe_sidebars_02_title,
					'id' =>'sidebar-02',
					'before_widget' =>'<div id="%1$s" class="hefe-sidebar-02-widget %2$s">',
					'after_widget' =>'</div>',
					'before_title' =>'<'.$hefe_sidebars_02_heading.' class="hefe-sidebar-02-widget-title">',
					'after_title' =>'</'.$hefe_sidebars_02_heading.'>',
				)
			);
		}
	}
}
// Sidebar 03
if(get_option('hefe_control_customizer_control_sidebar_03')){
	if(!function_exists('hefe_sidebars_wp_loaded_03_register_sidebar')){
		add_action('wp_loaded', 'hefe_sidebars_wp_loaded_03_register_sidebar');
		function hefe_sidebars_wp_loaded_03_register_sidebar(){
			$hefe_sidebars_03_title = get_option('hefe_control_customizer_control_sidebar_03');
			$hefe_sidebars_03_heading = get_option('hefe_control_customizer_control_sidebar_03_header');
			if($hefe_sidebars_03_heading == ''){
				$hefe_sidebars_03_heading = 'h3';
			}
			register_sidebar(
				array(
					'name' => $hefe_sidebars_03_title,
					'id' =>'sidebar-03',
					'before_widget' =>'<div id="%1$s" class="hefe-sidebar-03-widget %2$s">',
					'after_widget' =>'</div>',
					'before_title' =>'<'.$hefe_sidebars_03_heading.' class="hefe-sidebar-03-widget-title">',
					'after_title' =>'</'.$hefe_sidebars_03_heading.'>',
				)
			);
		}
	}
}
// Sidebar 04
if(get_option('hefe_control_customizer_control_sidebar_04')){
	if(!function_exists('hefe_sidebars_wp_loaded_04_register_sidebar')){
		add_action('wp_loaded', 'hefe_sidebars_wp_loaded_04_register_sidebar');
		function hefe_sidebars_wp_loaded_04_register_sidebar(){
			$hefe_sidebars_04_title = get_option('hefe_control_customizer_control_sidebar_04');
			$hefe_sidebars_04_heading = get_option('hefe_control_customizer_control_sidebar_04_header');
			if($hefe_sidebars_04_heading == ''){
				$hefe_sidebars_04_heading = 'h3';
			}
			register_sidebar(
				array(
					'name' => $hefe_sidebars_04_title,
					'id' =>'sidebar-04',
					'before_widget' =>'<div id="%1$s" class="hefe-sidebar-04-widget %2$s">',
					'after_widget' =>'</div>',
					'before_title' =>'<'.$hefe_sidebars_04_heading.' class="hefe-sidebar-04-widget-title">',
					'after_title' =>'</'.$hefe_sidebars_04_heading.'>',
				)
			);
		}
	}
}
// Sidebar 05
if(get_option('hefe_control_customizer_control_sidebar_05')){
	if(!function_exists('hefe_sidebars_wp_loaded_05_register_sidebar')){
		add_action('wp_loaded', 'hefe_sidebars_wp_loaded_05_register_sidebar');
		function hefe_sidebars_wp_loaded_05_register_sidebar(){
			$hefe_sidebars_05_title = get_option('hefe_control_customizer_control_sidebar_05');
			$hefe_sidebars_05_heading = get_option('hefe_control_customizer_control_sidebar_05_header');
			if($hefe_sidebars_05_heading == ''){
				$hefe_sidebars_05_heading = 'h3';
			}
			register_sidebar(
				array(
					'name' => $hefe_sidebars_05_title,
					'id' =>'sidebar-05',
					'before_widget' =>'<div id="%1$s" class="hefe-sidebar-05-widget %2$s">',
					'after_widget' =>'</div>',
					'before_title' =>'<'.$hefe_sidebars_05_heading.' class="hefe-sidebar-05-widget-title">',
					'after_title' =>'</'.$hefe_sidebars_05_heading.'>',
				)
			);
		}
	}
}
// Sidebar 06
if(get_option('hefe_control_customizer_control_sidebar_06')){
	if(!function_exists('hefe_sidebars_wp_loaded_06_register_sidebar')){
		add_action('wp_loaded', 'hefe_sidebars_wp_loaded_06_register_sidebar');
		function hefe_sidebars_wp_loaded_06_register_sidebar(){
			$hefe_sidebars_06_title = get_option('hefe_control_customizer_control_sidebar_06');
			$hefe_sidebars_06_heading = get_option('hefe_control_customizer_control_sidebar_06_header');
			if($hefe_sidebars_06_heading == ''){
				$hefe_sidebars_06_heading = 'h3';
			}
			register_sidebar(
				array(
					'name' => $hefe_sidebars_06_title,
					'id' =>'sidebar-06',
					'before_widget' =>'<div id="%1$s" class="hefe-sidebar-06-widget %2$s">',
					'after_widget' =>'</div>',
					'before_title' =>'<'.$hefe_sidebars_06_heading.' class="hefe-sidebar-06-widget-title">',
					'after_title' =>'</'.$hefe_sidebars_06_heading.'>',
				)
			);
		}
	}
}

/* Forced Login
------------------------------ */

if(get_option('hefe_control_customizer_control_enable_forced_login')){
	add_action('template_redirect', 'hefe_forced_login');
	function hefe_forced_login(){
		// Exceptions for AJAX, Cron, or WP-CLI requests
		if((defined('DOING_AJAX') && DOING_AJAX) || (defined('DOING_CRON') && DOING_CRON) || (defined('WP_CLI') && WP_CLI)){
			return;
		}
		// Redirect unauthorized visitors
		if(!is_user_logged_in()){
			// Get URL
			$url = isset($_SERVER['HTTPS']) && 'on' === $_SERVER['HTTPS'] ? 'https' : 'http';
			$url .= '://' . $_SERVER['HTTP_HOST'];
			// port is prepopulated here sometimes
			if(strpos($_SERVER['HTTP_HOST'], ':') === FALSE){
				$url .= in_array($_SERVER['SERVER_PORT'], array('80', '443')) ? '' : ':' . $_SERVER['SERVER_PORT'];
			}
			$url .= $_SERVER['REQUEST_URI'];
			// Apply filters
			$bypass = apply_filters('hefe_forced_login_bypass', false);
			$whitelist = apply_filters('hefe_forced_login_whitelist', array());
			$redirect_url = apply_filters('hefe_forced_login_redirect', $url);
			// Redirect
			if(preg_replace('/\?.*/', '', $url) != preg_replace('/\?.*/', '', wp_login_url()) && !in_array($url, $whitelist) && !$bypass) {
				wp_safe_redirect(wp_login_url($redirect_url), 302); exit();
			}
		}else{
			// Only allow Multisite users access to their assigned sites
			if(function_exists('is_multisite') && is_multisite()){
				$current_user = wp_get_current_user();
				if(!is_user_member_of_blog($current_user->ID) && !is_super_admin()){
					wp_die( __( "You're not authorized to access this site.", 'hefe' ), get_option('blogname') . ' &rsaquo; ' . __("Error", 'hefe'));
				}
			}
		}
	}
	/* Restrict REST API */
	add_filter('rest_authentication_errors', 'hefe_forced_login_rest_access', 99);
	function hefe_forced_login_rest_access($result){
		if(null === $result && !is_user_logged_in()){
			return new WP_Error('rest_unauthorized', __("Only authenticated users can access the REST API.", 'hefe'), array('status' => rest_authorization_required_code()));
		}
		return $result;
	}
	/* Localization */
	add_action('plugins_loaded', 'hefe_forced_login_load_textdomain');
	function hefe_forced_login_load_textdomain(){
		load_plugin_textdomain('hefe', false, dirname(plugin_basename( __FILE__ )) . '/languages/');
	}
}

/* Etc
------------------------------ */

// Font Awesome Defer
if(!function_exists('make_script_defer')){
	add_filter( 'script_loader_tag', 'make_script_defer', 10, 3 );
	function make_script_defer( $tag, $handle, $src ){
	    if ( 'hefe-font-awesome-script' != $handle ) {
	        return $tag;
	    }
	    return str_replace( '<script', '<script defer', $tag );
	}
}
// Do Shortcodes
add_filter('wp_nav_menu_items', 'do_shortcode');
add_filter('widget_text', 'do_shortcode');
// Theme Support
add_theme_support('post-thumbnails');
add_theme_support('menus');
add_theme_support('custom-logo');
// Remove Empty Menu Links
if(!function_exists('wpse_remove_empty_links')){
	add_filter('wp_nav_menu_items', 'wpse_remove_empty_links');
	function wpse_remove_empty_links($menu){
		return str_replace('<a>', '', $menu);
	}
}
// Add is_post_type()
if(!function_exists('is_post_type')){
	function is_post_type($type){
		global $wp_query;
		if($type == get_post_type($wp_query->post->ID)) return true;
		return false;
	}
}
// Excerpts in Pages
if(!function_exists('excerpt_to_pages')){
	add_action('init', 'excerpt_to_pages');
	function excerpt_to_pages(){
		add_post_type_support('page', 'excerpt');
	}
}
// ACF Add Options page
if(function_exists('acf_add_options_page')){
    acf_add_options_page();
}
// Gravity Forms Tabbing Issue
if(!function_exists('gform_tabindexer')){
	add_filter( 'gform_tabindex', 'gform_tabindexer', 10, 2 );
	function gform_tabindexer( $tab_index, $form = false ) {
		$starting_index = 1000; // if you need a higher tabindex, update this number
		if( $form )
			add_filter( 'gform_tabindex_' . $form['id'], 'gform_tabindexer' );
		return GFCommon::$tab_index >= $starting_index ? GFCommon::$tab_index : $starting_index;
	}
}

?>