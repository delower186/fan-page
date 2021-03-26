<?php
/**
* Main Fan Page Plugin File. Creates widget, shortcode and template tag.
*
* @package fbpage Plugin
* @author Delower
* 
*/

/**
* Fan Page Plugin Widget Class
*
* Contains the main functions for fbpage and stores variables
*
* @since Fan Page Plugin 1.0.0
* @author Delower
*/

class FanPageWidget extends WP_Widget {
	
	/**
	 * Register widget with WordPress
	 */
	function __construct() {
		$widget_ops = array( 'description' => esc_html(__('Show Fan Page','fanpage')) );
		parent::__construct( 'fanpage_widget', $name = esc_html(__('Fan Page','fanpage')),  $widget_ops);
	}

	/**
	 * Front-end
	 */
	function widget( $args, $instance ) {
		
		global $fanpageplugin;

		// Add-ons hook
		$instance = apply_filters( "fanpage_before_plugin", $instance, $this, $fanpageplugin );
		do_action( "fanpage_before_plugin", $args, $instance, $this, $fanpageplugin );

		// extract user options
		extract( $args );
		extract( $instance );
		
		// Stnadar WP output
		echo $before_widget;
		
		// check for title
		$title = apply_filters( 'widget_title', $title );
		if ( ! empty( $title ) ) echo $before_title . $title . $after_title;
		
		// include Page Plugin view
		include( $fanpageplugin->pluginPath . 'views/view-fan-page.php' );

		// Add-ons hook
		do_action("fanpage_after_plugin", $args, $instance, $this, $fanpageplugin );
		
		// Stnadar WP output
		echo $after_widget;
	}

	/**
	 * Sanitize widget form values as they are saved
	 */
	function update( $new_instance, $old_instance ) {
		
		$instance = $old_instance;
		// save new options
		$instance['title'] 			= sanitize_text_field( $new_instance['title'] );
		$instance['url'] 			= esc_url_raw( $new_instance['url'] );
		$instance['width']			= sanitize_text_field( $new_instance['width'] );
		$instance['height']			= sanitize_text_field( $new_instance['height'] );
		$instance['hide_cover']		= isset( $new_instance['hide_cover'] );
		$instance['show_facepile']	= isset( $new_instance['show_facepile'] );
		$instance['small_header']	= isset( $new_instance['small_header'] );
		$instance['timeline']		= isset( $new_instance['timeline'] );
		$instance['events']			= isset( $new_instance['events'] );
		$instance['messages']		= isset( $new_instance['messages'] );
		
		$instance['locale']			= sanitize_text_field( $new_instance['locale'] );
	
		// Add-ons hook
		apply_filters( 'fanpage_widget_update', $instance, $new_instance, $old_instance );
		
		return $instance;
	}

	/**
	 * Back-end form
	 */
	function form( $instance ) {

		global $fanpageplugin;
		
		$default = array(
			// default options
			'title' 		=> sanitize_text_field(__('Delower\'s Facebook Page', 'fanpage')),
			'url'			=> esc_url('https://www.facebook.com/Delower-103206595201617'),
			'width'			=> '',
			'height'		=> '',
			'hide_cover'	=> false,
			'show_facepile'	=> true,
			'small_header'	=> false,
			'timeline'		=> false,
			'events'		=> false,
			'messages'		=> false,
			
			'locale'		=> sanitize_text_field('en_US')
		);

		// Add-ons hook
		//$instance = apply_filters( 'fanpage_form', $instance, $default, $this, $fanpageplugin );

		extract( array_merge( $default, $instance ) ); ?>

		<?php 
			// Add-ons hook
			do_action( "fanpage_widget_form_start", $instance, $this, $fanpageplugin );

			// WP To Do campaign
			printf( __('<p>Try <a href="'.esc_url('https://wordpress.org/plugins/wp-todo/').'">WP To Do</a> A full featured plugin for creating and managing a "to do" list.</p>'));
		?>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title'); ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('url'); ?>"><?php _e('Facebook Page URL:'); ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id('url'); ?>" name="<?php echo $this->get_field_name('url'); ?>" type="text" value="<?php echo $url; ?>" />
		</p>

		<label for="<?php echo $this->get_field_id('width'); ?>"><?php _e('Width:'); ?></label> 
		<input size="6" id="<?php echo $this->get_field_id('width'); ?>" name="<?php echo $this->get_field_name('width'); ?>" type="text" value="<?php echo $width; ?>" />px
		<p class="description">
			The plugin will automatically adapt to the width of its parent element on page load if parent's width is lower than plugin's. Min is 180 and Max is 500.
		</p>	
		<label for="<?php echo $this->get_field_id('height'); ?>"><?php _e('Height:'); ?></label> 
		<input size="6" id="<?php echo $this->get_field_id('height'); ?>" name="<?php echo $this->get_field_name('height'); ?>" type="text" value="<?php echo $height; ?>" />px
		<p class="description">Minimum is 70.</p>
		<?php 
			// Add-ons hook
			do_action( "fanpage_widget_form_after_inputs", $instance, $this, $fanpageplugin );
		?>
		<table>
			<tr><td>
				<br/>
				<b><?php _e('Header'); ?></b>
			</td></tr>
			<tr><td>
				<label for="<?php echo $this->get_field_id('hide_cover'); ?>"><?php _e('Hide Cover Photo'); ?></label> 
				</td><td>
				<input id="<?php echo $this->get_field_id('hide_cover'); ?>" type="checkbox" name="<?php echo $this->get_field_name('hide_cover'); ?>" <?php checked(isset($hide_cover) ? $hide_cover : 0); ?>/>
			</td></tr>
			<tr><td>
				<label for="<?php echo $this->get_field_id('show_facepile'); ?>"><?php _e('Show Friend\'s Faces'); ?></label> 
				</td><td>
				<input id="<?php echo $this->get_field_id('show_facepile'); ?>" type="checkbox" name="<?php echo $this->get_field_name('show_facepile'); ?>" <?php checked(isset($show_facepile) ? $show_facepile : 0); ?>/>
			</td></tr>
			<tr><td>
				<label for="<?php echo $this->get_field_id('small_header'); ?>"><?php _e('Small Header'); ?></label> 
				</td><td>
				<input id="<?php echo $this->get_field_id('small_header'); ?>" type="checkbox" name="<?php echo $this->get_field_name('small_header'); ?>" <?php checked(isset($small_header) ? $small_header : 0); ?>/>
			</td></tr>
			<tr><td>
				<br/>
				<b><?php _e('Tabs'); ?></b>
			</td></tr>
			<tr><td>
				<label for="<?php echo $this->get_field_id('timeline'); ?>"><?php _e('Show Timeline'); ?></label> 
				</td><td>
				<input id="<?php echo $this->get_field_id('timeline'); ?>" type="checkbox" name="<?php echo $this->get_field_name('timeline'); ?>" <?php checked(isset($timeline) ? $timeline : 0); ?>/> 
			</td></tr>
			<tr><td>
				<label for="<?php echo $this->get_field_id('events'); ?>"><?php _e('Show Events'); ?></label> 
				</td><td>
				<input id="<?php echo $this->get_field_id('events'); ?>" type="checkbox" name="<?php echo $this->get_field_name('events'); ?>" <?php checked(isset($events) ? $events : 0); ?>/> 
			</td></tr>
			<tr><td>
				<label for="<?php echo $this->get_field_id('messages'); ?>"><?php _e('Show Messages'); ?></label> 
				</td><td>
				<input id="<?php echo $this->get_field_id('messages'); ?>" type="checkbox" name="<?php echo $this->get_field_name('messages'); ?>" <?php checked(isset($messages) ? $messages : 0); ?>/> 
			</td></tr>
			<?php 
				// Add-ons hook
				do_action("fanpage_widget_form_after_checkboxes", $instance, $this, $fanpageplugin );
			?>
		</table>
		<br/>
		<p>
			<label for="<?php echo $this->get_field_id('locale'); ?>"><?php _e('Language'); ?></label> 
			<select name="<?php echo $this->get_field_name('locale'); ?>">
			<?php foreach ( $fanpageplugin->locales as $code => $name ) : ?>
				<option <?php selected(( $locale == $code) ? 1 : 0); ?> value="<?php echo $code; ?>" ><?php echo $name; ?></option>
			<?php endforeach; ?>
			</select>
		</p>
		<?php 
			do_action( "fanpage_widget_form_end", $instance, $this, $fanpageplugin );
		?>

	<?php }
	
} // class FBPageWidget

/**
 * FB Page Plugin 'Shortcode'
 *
 * @since fbpage Plugin
 * @author Delower
 */

function fanpageplugin_shortcode ( $instance ) {

	global $fanpageplugin;

	$instance = ( !$instance ) ? array() : $instance;

	// Add-ons hook
	$instance = apply_filters( "fanpage_before_plugin", $instance, $fanpageplugin );

	extract( array_merge( array(
		// default options
		'url'			=> esc_url('https://www.facebook.com/Delower-103206595201617'),
		'width'			=> '',
		'height'		=> '',
		'hide_cover'	=> false,
		'show_facepile'	=> true,
		'small_header'	=> false,
		'timeline'		=> false,
		'events'		=> false,
		'messages'		=> false,
		'locale'		=> sanitize_text_field('en_US')
	), $instance ) );

	ob_start();

	// include Page Plugin view
	include( $fanpageplugin->pluginPath . 'views/view-fan-page.php' );

	return ob_get_clean();
}


/**
* Fan Page Plugin 'Template Tag'
* 
* @since Fan Page Plugin
* @author Delower
*/

function fanpageplugin ( $instance = array() ) { 
	
	global $fanpageplugin;

	// Add-ons hook
	$instance = apply_filters( "fanpage_before_plugin", $instance, $fanpageplugin );
	
	extract( array_merge( array(
		// default options
		'url'			=> esc_url('https://www.facebook.com/Delower-103206595201617'),
		'width'			=> '',
		'height'		=> '',
		'hide_cover'	=> false,
		'show_facepile'	=> true,
		'small_header'	=> false,
		'timeline'		=> false,
		'events'		=> false,
		'messages'		=> false,
		'locale'		=> sanitize_text_field('en_US')
	), $instance ) );
	
	// include Fan Page Plugin view
	include($fanpageplugin->pluginPath . 'views/view-fan-page.php' );
}

?>