<?php
/**
* @package fanpage
*/
/*
Plugin Name: Fan Page
Plugin URI: https://delower.me/fan-page/
Description: Fan Page helps you easily embed and promote any public facebook page on your wordpres widget, post or even in a page.
Version:1.0.1
Author: Delower
Author URI: https://delower.me
License: GPLv2 or later
Text Domain: fanpage
*/
/*
Fan Page is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

Fan Page is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with Fan Page; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.

Copyright (C) 2021  delower.

*/
defined('ABSPATH') or die('Hey, What are you doing here? You Silly Man!');

//activate plugin
function activate_fanpage(){
    flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'activate_fanpage' );

//deactivate plugin
function deactivate_fanpage(){
    flush_rewrite_rules();
}
register_deactivation_hook( __FILE__, 'deactivate_fanpage' );

// Check if class already exist
if ( !class_exists( 'FanPagePlugin' ) ) {

	// Main plugin class
	class FanPagePlugin {
		
		public $pluginPath;
		public $pluginUrl;
		public $pluginName;
		public $optionName;

		public $fbLocalesUrl = "http://www.facebook.com/translations/fbLocales.xml";
		public $locales;

		/**
		* fanpage Plugin Constructor
		*
		* Gets things started
		*/
		public function __construct( ) {
			$this->pluginPath 	= plugin_dir_path(__FILE__);
			$this->pluginUrl 	= plugin_dir_url(__FILE__);

			$this->optionName	= "fan_page_options";
			
			$this->locales 		= $this->parseLocales($this->fbLocalesUrl);

			$this->loadFiles();
			$this->addActions();
			$this->addShortcodes();
		}
		
		/**
		 * Load all the required files.
		 */
		protected function loadFiles() {
			// Include social plugins files
			require_once( $this->pluginPath . 'base/FanPageWidget.php' );

			// Allow addons load files
			do_action('fanpage_load_files');
		}
		
		/**
		* Add all the required actions.
		*/
		protected function addActions() {
			
			//add_action( 'admin_menu', 	array( $this, 'pluginMenu') );
			add_action( 'admin_notices',    array( $this, 'adminNotice') );
			add_action( 'widgets_init', 	array( $this, 'addWidgets') );
			//add_action( 'wp_footer',		array( $this, 'addJavaScriptSDK') );
			add_action( 'admin_init', 		array( $this, 'saveOptions' ) );
			add_action( 'admin_init', 		array( $this, 'ignoreNotices' ) );
			add_action( 'admin_enqueue_scripts',	array( $this, 'enqueueScriptsAdmin') );

			// Add settings link on Plugins page
			$plugin = "fan-page/fan-page.php";
			add_filter( "plugin_action_links_$plugin", array( $this, 'pluginSettingsLink') );

			// Allow addons add actions
			do_action( 'fanpage_add_actions', $this );
		}
		
		/**
		* Register all widgets
		*/
		public function addWidgets() {
			
			register_widget('fanpageWidget');
		
			// Allow addons add widgets
			do_action('fanpage_add_widgets');
		}
		
		/**
		* Register all shortcodes
		*/
		public function addShortcodes() {
		
			add_shortcode('fanpage', 'fanpageplugin_shortcode');
			
			// Allow addons add shortcodes
			do_action('fanpage_add_shortcodes');
		}

		/**
		 * Get remote XML file by URL
		 * 
		 * @param  string $url 
		 * @return array
		 * 
		 */
		public function parseLocales ( $url = "" ) {

			if ( file_exists( $url ) && function_exists( "simplexml_load_file" ) ) {
				
				$locales 	= array();
				$xml 		= simplexml_load_file( $url );
				
				foreach ( $xml as $key => $locale ) {
					
					$name = (array) $locale->englishName;
					$name = $name[0];

					$code = (array) $locale->codes->code->standard->representation;
					$code = $code[0];

					$locales[$code] = $name; 
				};
			}
			else 
				$locales = array( 
					"af_ZA"=>   "Afrikaans",
					"ar_AR"=>   "Arabic",
					"az_AZ"=>	"Azerbaijani",
					"be_BY"=>   "Belarusian",
					"bg_BG"=>   "Bulgarian",
					"bn_IN"=>   "Bengali",
					"bs_BA"=>   "Bosnian",
					"ca_ES"=>   "Catalan",
					"cs_CZ"=>   "Czech",
					"cy_GB"=>   "Welsh",
					"da_DK"=>   "Danish",
					"de_DE"=>   "German",
					"el_GR"=>   "Greek",
					"en_GB"=>   "English (UK)",
					"en_PI"=>   "English (Pirate)",
					"en_UD"=>   "English (Upside Down)",
					"en_US"=>   "English (US)",
					"eo_EO"=>   "Esperanto",
					"es_ES"=>   "Spanish (Spain)",
					"es_LA"=>   "Spanish",
					"et_EE"=>   "Estonian",
					"eu_ES"=>   "Basque",
					"fa_IR"=>   "Persian",
					"fb_LT"=>   "Leet Speak",
					"fi_FI"=>   "Finnish",
					"fo_FO"=>   "Faroese",
					"fr_CA"=>   "French (Canada)",
					"fr_FR"=>   "French (France)",
					"fy_NL"=>   "Frisian",
					"ga_IE"=>   "Irish",
					"gl_ES"=>   "Galician",
					"he_IL"=>   "Hebrew",
					"hi_IN"=>   "Hindi",
					"hr_HR"=>   "Croatian",
					"hu_HU"=>   "Hungarian",
					"hy_AM"=>   "Armenian",
					"id_ID"=>   "Indonesian",
					"is_IS"=>   "Icelandic",
					"it_IT"=>   "Italian",
					"ja_JP"=>   "Japanese",
					"ka_GE"=>   "Georgian",
					"km_KH"=>   "Khmer",
					"ko_KR"=>   "Korean",
					"ku_TR"=>   "Kurdish",
					"la_VA"=>   "Latin",
					"lt_LT"=>   "Lithuanian",
					"lv_LV"=>   "Latvian",
					"mk_MK"=>   "Macedonian",
					"ml_IN"=>   "Malayalam",
					"ms_MY"=>   "Malay",
					"nb_NO"=>   "Norwegian (bokmal)",
					"ne_NP"=>   "Nepali",
					"nl_NL"=>   "Dutch",
					"nn_NO"=>   "Norwegian (nynorsk)",
					"pa_IN"=>   "Punjabi",
					"pl_PL"=>   "Polish",
					"ps_AF"=>   "Pashto",
					"pt_BR"=>   "Portuguese (Brazil)",
					"pt_PT"=>   "Portuguese (Portugal)",
					"ro_RO"=>   "Romanian",
					"ru_RU"=>   "Russian",
					"sk_SK"=>   "Slovak",
					"sl_SI"=>   "Slovenian",
					"sq_AL"=>   "Albanian",
					"sr_RS"=>   "Serbian",
					"sv_SE"=>   "Swedish",
					"sw_KE"=>   "Swahili",
					"ta_IN"=>   "Tamil",
					"te_IN"=>   "Telugu",
					"th_TH"=>   "Thai",
					"tl_PH"=>   "Filipino",
					"tr_TR"=>   "Turkish",
					"uk_UA"=>   "Ukrainian",
					"vi_VN"=>   "Vietnamese",
					"zh_CN"=>   "Simplified Chinese (China)",
					"zh_HK"=>   "Traditional Chinese (Hong Kong)",
					"zh_TW"=>   "Traditional Chinese (Taiwan)" 
				);

			return $locales;
		}

		/**
		 * Load styles for dashboard
		 *
		 * 
		 */

		static function enqueueScriptsAdmin() {
			
			// add custom css
			wp_register_style( 'fanpage-admin-style', plugin_dir_url(__FILE__) . '/assets/css/fanpage-admin-style.css' );
			wp_enqueue_style( 'fanpage-admin-style' );
		}

		/**
		 * Load fb JavaScript SDK
		 *
		 * 
		 */
		
		public function addJavaScriptSDK() { 

			$options 	= $this->getPluginOptions();
			$locale 	= $options['locale'];

			?>

		<div id="fb-root"></div>
		<script>
			(function(d){
				var js, id = 'fb-jssdk';
				if (d.getElementById(id)) {return;}
				js = d.createElement('script');
				js.id = id;
				js.async = true;
				js.src = "//connect.fb.net/<?php echo $locale; ?>/all.js#xfbml=1";
				d.getElementsByTagName('head')[0].appendChild(js);
			}(document));
		</script>

		<?php }

		/**
		 * Add Dashboard > Plugins Menu Page
		 *
		 * 
		 */

		public function pluginMenu() {
			
			add_plugins_page('Fan Page Menu', 'Fan Page', 'read', 'fan_page', array( $this, "pluginMenuView" ) );
		}

		/**
		 * Show Menu Page View
		 *
		 * 
		 */

		public function pluginMenuView() {

			$options = $this->getPluginOptions();
			
			// include Like Box view
			include( esc_url($wpfbplugin->pluginPath . 'views/view-menu.php') );

		}

		/**
		* Show admin notice
		* 
		* 
		*/

		public function adminNotice() {

			global $current_user;
			$user_id = $current_user->ID;

			/* Check that the user hasn't already clicked to ignore the message */
			if ( ! get_user_meta( $user_id, 'fanpage_ignore_notice_3') ) {

				echo '<div class="updated"><p>';

				//printf( __('Thanks for using our <strong>Simple fb Plugin</strong>! We have some other great WordPress plugins <a href="http://codecanyon.net/user/topdevs/portfolio?ref=topdevs">View Portfolio</a> | <a href="%1$s">Hide this</a>'), '?fanpage_ignore_2=0');

				printf( __('We recommend to try <a href="'.esc_url('https://wordpress.org/plugins/wp-todo/').'">WP To Do</a>. WP To Do is full featured To Do list plugin. | <a href="%1$s">Hide this</a>'), '?fanpage_ignore_3=0');

				echo "</p></div>";

			}
		}

		public function ignoreNotices() {
			
			global $current_user;
			$user_id = $current_user->ID;
			
			/* If user clicks to ignore the notice, add that to their user meta */
			if ( isset( $_GET['fanpage_ignore_3'] ) && '0' == $_GET['fanpage_ignore_3'] ) {
				add_user_meta( $user_id, sanitize_text_field('fanpage_ignore_notice_3'), 'true', true);
			}
		}

		/**
		* Add status link on plugins page
		*
		* 
		*/

		public function pluginSettingsLink ( $links ) {

			$settings_link = '<a href="' . esc_url(site_url( "/wp-admin/widgets.php", 'http' )) . '">Settings</a>'; 

			array_unshift( $links, $settings_link );

			return $links; 
		}

		/**
		* Get plugin options
		* 
		* 
		*/

		public function getPluginOptions() {

			$defaults = array( 'locale' => sanitize_text_field("en_US") );

			$defaults = apply_filters( "fanpage_default_options", $defaults );

			$options = get_option( $this->optionName, $defaults );

			return $options;
		}

		/**
		* Save plugin options
		* 
		* 
		*/

		public function savePluginOptions( $options = array() ) {

			update_option( $this->optionName, $options );
		}

		/**
		* Trigger when settings page form submitted
		*
		* 
		*/

		public function saveOptions() {

			//delete_option( $this->optionName );

			// If submit button pressed
			if ( isset( $_POST['fanpage_options_saved'] ) ) {

				$options = $this->getPluginOptions();

				if ( isset( $_POST['locale'] ) && !empty( $_POST['locale'] ) ) {

					$options['locale'] = sanitize_text_field($_POST['locale']);
				}

				$this->savePluginOptions( $options );
			}
		}
		
	} // end WPFBPlugin class

} // end if !class_exists

// Create new FanPagePlugin instance
$GLOBALS["fanpageplugin"] = new FanPagePlugin();
