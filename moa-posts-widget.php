<?php 
	/*
Plugin Name: Moa posts widget

*/
	defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
	
	
	// The widget class
class moa_posts_widget extends WP_Widget {

	// Main constructor
	public function __construct() {
		parent::__construct(
			'moa_posts_widget',
			__( 'Moa Posts Widget', 'text_domain' ),
			array(
				'customize_selective_refresh' => true,
			)
		);
	}

	// The widget form (for the backend )
public function form( $instance ) {

	// Set widget defaults
	$defaults = array(
		'title',
		'category_ids' => '',
		'before_posts' => '',
		'checkbox' => '',
		'select'   => '',
	);
	
	// Parse current settings with defaults
	extract( wp_parse_args( ( array ) $instance, $defaults ) ); ?>

	<?php // Widget Title ?>
	<p>
		<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php _e( 'Widget Title', 'text_domain' ); ?></label>
		<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
	</p>

	<?php // Categories Field ?>
	<p>
		<label for="<?php echo esc_attr( $this->get_field_id( 'category_ids' ) ); ?>"><?php _e( 'Category ID\'s (comma separated):', 'text_domain' ); ?></label>
		<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'category_ids' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'category_ids' ) ); ?>" type="text" value="<?php echo esc_attr( $category_ids ); ?>" />
		
	</p>

	<?php // Textarea Field ?>
	<p>
		<label for="<?php echo esc_attr( $this->get_field_id( 'textarea' ) ); ?>"><?php _e( 'Before posts:', 'text_domain' ); ?></label>
		<textarea class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'before_posts' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'before_posts' ) ); ?>"><?php echo wp_kses_post( $textarea ); ?></textarea>
	</p>

	<?php // Checkbox ?>
	<p>
		<input id="<?php echo esc_attr( $this->get_field_id( 'checkbox' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'checkbox' ) ); ?>" type="checkbox" value="1" <?php checked( '1', $checkbox ); ?> />
		<label for="<?php echo esc_attr( $this->get_field_id( 'checkbox' ) ); ?>"><?php _e( 'Checkbox', 'text_domain' ); ?></label>
	</p>

	<?php // Dropdown ?>
	<p>
		<label for="<?php echo $this->get_field_id( 'select' ); ?>"><?php _e( 'Select', 'text_domain' ); ?></label>
		<select name="<?php echo $this->get_field_name( 'select' ); ?>" id="<?php echo $this->get_field_id( 'select' ); ?>" class="widefat">
		<?php
		// Your options array
		$options = array(
			''        => __( 'Select', 'text_domain' ),
			'option_1' => __( 'Option 1', 'text_domain' ),
			'option_2' => __( 'Option 2', 'text_domain' ),
			'option_3' => __( 'Option 3', 'text_domain' ),
		);

		// Loop through options and add each one to the select dropdown
		foreach ( $options as $key => $name ) {
			echo '<option value="' . esc_attr( $key ) . '" id="' . esc_attr( $key ) . '" '. selected( $select, $key, false ) . '>'. $name . '</option>';

		} ?>
		</select>
	</p>

<?php }

	// Update widget settings
	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title']    = isset( $new_instance['title'] ) ? wp_strip_all_tags( $new_instance['title'] ) : '';
		$instance['category_ids']     = isset( $new_instance['category_ids'] ) ? wp_strip_all_tags( $new_instance['category_ids'] ) : '';
		$instance['before_posts'] = isset( $new_instance['before_posts'] ) ? wp_kses_post( $new_instance['before_posts'] ) : '';
		$instance['checkbox'] = isset( $new_instance['checkbox'] ) ? 1 : false;
		$instance['select']   = isset( $new_instance['select'] ) ? wp_strip_all_tags( $new_instance['select'] ) : '';
		return $instance;
	}

	// Display the widget
public function widget( $args, $instance ) {

	extract( $args );

	// Check the widget options
	$title    = isset( $instance['title'] ) ? apply_filters( 'widget_title', $instance['title'] ) : '';
	$category_ids     = isset( $instance['category_ids'] ) ? $instance['category_ids'] : '';
	$before_posts = isset( $instance['before_posts'] ) ?$instance['before_posts'] : '';
	$select   = isset( $instance['select'] ) ? $instance['select'] : '';
	$checkbox = ! empty( $instance['checkbox'] ) ? $instance['checkbox'] : false;
	
	
	$args = array(
		'cat' => $category_ids
	);
	$the_query = new WP_Query( $args );
	
	

	// WordPress core before_widget hook (always include )
	echo $before_widget;

   // Display the widget
   echo '<div class="widget-text wp_widget_plugin_box">';
	 	
	 	// Display widget title if defined
		if ( $title ) {
			echo $before_title . $title . $after_title;
		}
		
	 	
	 	if ( $the_query->have_posts() ) {
			while ( $the_query->have_posts() ) {
				$the_query->the_post();
				?>
				<div class="row">
				<div class="col-6">
					<?php the_post_thumbnail('thumbnail', ['class' => 'img-fluid img-responsive responsive--full', 'title' => 'Feature image']); ?>
				</div>
				<div class="col-6">
					<p><?php echo get_the_title(); ?></p>
				</div>
				</div>
				<?php	
			}
		}
	
	
		

		// Display text field
		if ( $category_ids ) {
			echo '<p>' . $category_ids . '</p>';
		}

		// Display textarea field
		if ( $before_posts ) {
			echo '<p>' . $before_posts . '</p>';
		}

		// Display select field
		if ( $select ) {
			echo '<p>' . $select . '</p>';
		}

		// Display something if checkbox is true
		if ( $checkbox ) {
			echo '<p>Something awesome</p>';
		}

	echo '</div>';

	// WordPress core after_widget hook (always include )
	echo $after_widget;

}

}

// Register the widget
function register_most_posts_widget() {
	register_widget( 'moa_posts_widget' );
}
add_action( 'widgets_init', 'register_most_posts_widget' );