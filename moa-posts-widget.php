<?php 
	/*
Plugin Name: Moa posts widget

*/
	defined( 'ABSPATH' ) or die( 'No script kiddies please!' );


function moa_posts_widget_scripts() {
	$plugin_url = plugin_dir_url( __FILE__ );
  wp_enqueue_style( 'style',  $plugin_url . "/moa-posts-widget.css");
    //wp_enqueue_script( 'script-name', get_template_directory_uri() . '/js/example.js', array(), '1.0.0', true );
}

  add_action( 'wp_enqueue_scripts', 'moa_posts_widget_scripts' );  
	
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
		'title' => 'The Title',
		'post_type' => 'post',
		'layout' => 'row',
		'width' => 'boxed',
		'number_of_posts' => 5,
		'category_ids' => '',
		'before_posts' => '',
		'after_posts' => '',
		'show_dates' => '',
		'carousel' => '',
		'select'   => '',
	);
	
	// Parse current settings with defaults
	extract( wp_parse_args( ( array ) $instance, $defaults ) ); ?>

	<?php // Widget Title ?>
	<p>
		<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php _e( 'Widget Title', 'text_domain' ); ?></label>
		<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
	</p>
	
	<?php // Post type ?>
	<p>
		<label for="<?php echo esc_attr( $this->get_field_id( 'post_type' ) ); ?>"><?php _e( 'Post type', 'text_domain' ); ?></label>
		<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'post_type' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'post_type' ) ); ?>" type="text" value="<?php echo esc_attr( $post_type ); ?>" />
	</p>
	
	<?php // Layout dropdown ?>
	<p>
		<label for="<?php echo $this->get_field_id( 'layout' ); ?>"><?php _e( 'Layout', 'text_domain' ); ?></label>
		<select name="<?php echo $this->get_field_name( 'layout' ); ?>" id="<?php echo $this->get_field_id( 'layout' ); ?>" class="widefat">
		<?php
		// Your options array
		$options = array(
			''        => __( 'Select', 'text_domain' ),
			'row' => __( 'Row', 'text_domain' ),
			'column' => __( 'Column', 'text_domain' ),
			
		);

		// Loop through options and add each one to the select dropdown
		foreach ( $options as $key => $name ) {
			echo '<option value="' . esc_attr( $key ) . '" id="' . esc_attr( $key ) . '" '. selected( $layout, $key, false ) . '>'. $name . '</option>';

		} ?>
		</select>
	</p>
	
	<?php // Width dropdown ?>
	<p>
		<label for="<?php echo $this->get_field_id( 'width' ); ?>"><?php _e( 'Width', 'text_domain' ); ?></label>
		<select name="<?php echo $this->get_field_name( 'width' ); ?>" id="<?php echo $this->get_field_id( 'width' ); ?>" class="widefat">
		<?php
		// Your options array
		$options = array(
			''        => __( 'Select', 'text_domain' ),
			'boxed' => __( 'Boxed', 'text_domain' ),
			'full_width' => __( 'Full width', 'text_domain' ),
			
		);

		// Loop through options and add each one to the select dropdown
		foreach ( $options as $key => $name ) {
			echo '<option value="' . esc_attr( $key ) . '" id="' . esc_attr( $key ) . '" '. selected( $width, $key, false ) . '>'. $name . '</option>';

		} ?>
		</select>
	</p>

	<?php // Number of posts ?>
	<p>
		<label for="<?php echo esc_attr( $this->get_field_id( 'number_of_posts' ) ); ?>"><?php _e( 'Number of posts to show (default 5):', 'text_domain' ); ?></label>
		<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'number_of_posts' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'number_of_posts' ) ); ?>" type="text" value="<?php echo esc_attr( $number_of_posts ); ?>" />
		
	</p>
	
	<?php // Categories Field ?>
	<p>
		<label for="<?php echo esc_attr( $this->get_field_id( 'category_ids' ) ); ?>"><?php _e( 'Category ID\'s (comma separated):', 'text_domain' ); ?></label>
		<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'category_ids' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'category_ids' ) ); ?>" type="text" value="<?php echo esc_attr( $category_ids ); ?>" />
		
	</p>

	<?php // Before Posts Textarea Field ?>
	<p>
		<label for="<?php echo esc_attr( $this->get_field_id( 'textarea-beforeposts' ) ); ?>"><?php _e( 'Before posts:', 'text_domain' ); ?></label>
		<textarea class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'before_posts' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'before_posts' ) ); ?>"><?php echo wp_kses_post( $before_posts ); ?></textarea>
	</p>
	
	<?php // After Posts Textarea Field ?>
	<p>
		<label for="<?php echo esc_attr( $this->get_field_id( 'textarea-afterposts' ) ); ?>"><?php _e( 'After posts:', 'text_domain' ); ?></label>
		<textarea class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'after_posts' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'after_posts' ) ); ?>"><?php echo wp_kses_post( $after_posts ); ?></textarea>
	</p>

	<?php // Checkbox ?>
	<p>
		<input id="<?php echo esc_attr( $this->get_field_id( 'show_dates' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_dates' ) ); ?>" type="checkbox" value="1" <?php checked( '1', $show_dates ); ?> />
		<label for="<?php echo esc_attr( $this->get_field_id( 'show_dates' ) ); ?>"><?php _e( 'Show dates', 'text_domain' ); ?></label>
	</p>
	
	<?php // Carousel ?>
	<p>
		<input id="<?php echo esc_attr( $this->get_field_id( 'carousel' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'carousel' ) ); ?>" type="checkbox" value="1" <?php checked( '1', $carousel ); ?> />
		<label for="<?php echo esc_attr( $this->get_field_id( 'carousel' ) ); ?>"><?php _e( 'Carousel', 'text_domain' ); ?></label>
	</p>


<?php }

	// Update widget settings
	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title']    = isset( $new_instance['title'] ) ? wp_strip_all_tags( $new_instance['title'] ) : '';
		$instance['post_type']    = isset( $new_instance['post_type'] ) ? wp_strip_all_tags( $new_instance['post_type'] ) : '';
		$instance['layout']   = isset( $new_instance['layout'] ) ? wp_strip_all_tags( $new_instance['layout'] ) : '';
		$instance['width']   = isset( $new_instance['width'] ) ? wp_strip_all_tags( $new_instance['width'] ) : '';
		$instance['number_of_posts']    = isset( $new_instance['number_of_posts'] ) ? wp_strip_all_tags( $new_instance['number_of_posts'] ) : '';
		$instance['category_ids']     = isset( $new_instance['category_ids'] ) ? wp_strip_all_tags( $new_instance['category_ids'] ) : '';
		$instance['before_posts'] = isset( $new_instance['before_posts'] ) ? wp_kses_post( $new_instance['before_posts'] ) : '';
		$instance['after_posts'] = isset( $new_instance['after_posts'] ) ? wp_kses_post( $new_instance['after_posts'] ) : '';

		$instance['show_dates'] = isset( $new_instance['show_dates'] ) ? 1 : false;
		$instance['carousel'] = isset( $new_instance['carousel'] ) ? 1 : false;
/*
		$instance['select']   = isset( $new_instance['select'] ) ? wp_strip_all_tags( $new_instance['select'] ) : '';
*/
		return $instance;
	}

	// Display the widget
public function widget( $args, $instance ) {
	add_action( 'wp_enqueue_scripts', 'moa_posts_widget_scripts' );
	extract( $args );

	
	// Check the widget options
	$title    = isset( $instance['title'] ) ? apply_filters( 'widget_title', $instance['title'] ) : '';
	$post_type    = isset( $instance['post_type'] ) ?  $instance['post_type'] : '';
	$layout    = isset( $instance['layout'] ) ?  $instance['layout'] : '';
	$width    = isset( $instance['width'] ) ?  $instance['width'] : '';
	$number_of_posts    = isset( $instance['number_of_posts'] ) ? $instance['number_of_posts'] : '';
	$category_ids     = isset( $instance['category_ids'] ) ? $instance['category_ids'] : '';
	$before_posts = isset( $instance['before_posts'] ) ?$instance['before_posts'] : '';
	$after_posts = isset( $instance['after_posts'] ) ?$instance['after_posts'] : '';
	$show_dates = ! empty( $instance['show_dates'] ) ? $instance['show_dates'] : false;
	$carousel = ! empty( $instance['carousel'] ) ? $instance['carousel'] : false;
	
	$cur_id = get_queried_object_id(); 
	$query_args = array(
		'cat' => $category_ids,
		'post_type' => $post_type,
		'posts_per_page' => $number_of_posts,
		'post__not_in' => array($cur_id)
	);
	

	$the_query = new WP_Query( $query_args );
	
	

	// WordPress core before_widget hook (always include )
	echo $before_widget;
	
	

   // Display the widget
   echo '<div class="widget-text wp_widget_plugin_box">';
	 	
	 	// Display widget title if defined
		if ( $title ) {
			echo $before_title . $title . $after_title;
		}
		
		if($before_posts) {
			echo '<p>'  . $before_posts . '</p>';
		}
		?>
		<?php if($width == 'boxed'): ?>
		<div class="container">
		<?php endif; ?>
		<div class="moa-posts ~row <?php if($carousel): ?>owl-carousel owl-theme<?php endif; ?>">
		<?php
	 	
	 	if ( $the_query->have_posts() ) {
		 	
		 	
			while ( $the_query->have_posts() ) {
				$the_query->the_post();
				$postid = get_the_id();
				setup_postdata( $postid );
				$thumb = get_the_post_thumbnail($postid, 'thumbnail', ['class' => 'img-fluid img-responsive responsive--full', 'title' => 'Feature image']); 
				?>
			
					
					
					<?php if($layout == 'row'): ?>
						
						<?php if(get_the_post_thumbnail()): ?>
							<div class="row moa-posts-widget-row">
								<div class="container">
									<div class="row">
										<div class="col-5 image-col">
											<a href="<?php the_permalink(); ?>"><?php the_post_thumbnail('thumbnail', ['class' => 'img-fluid img-responsive responsive--full', 'title' => 'Feature image']) ?></a>
										</div>
										<div class="col-7 text-col">
											<div class="inner-wrap">
												<p><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></p>
												<?php if($show_dates): ?>
													<p class="post-date text-muted"><time datetime="<?php echo get_post_time('c', true) ?>"><?php echo get_the_date() ?></time></p>
												<?php endif; ?>
											</div>
										</div>
									</div>
								</div>
							</div>
						<?php else: ?>
							<div class="text-col">
								<div class="inner-wrap">
									<p><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></p>
									<?php if($show_dates): ?>
										<p class="post-date text-muted"><time datetime="<?php echo get_post_time('c', true) ?>"><?php echo get_the_date() ?></time></p>
									<?php endif; ?>
								</div>
							</div>
						<?php endif; ?>

					
					
					<?php elseif($layout == 'column'): ?>
					
					
						<?php if(get_the_post_thumbnail()): ?>
						<div class="moa-posts-widget-row">
							<div class="image-col">
								<a href="<?php the_permalink(); ?>"><?php the_post_thumbnail('thumbnail', ['class' => 'img-fluid img-responsive responsive--full', 'title' => 'Feature image']) ?></a>
							</div>
							<div class="text-col">
								<div class="inner-wrap">
									<p><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></p>
									<?php if($show_dates): ?>
										<p class="post-date text-muted"><time datetime="<?php echo get_post_time('c', true) ?>"><?php echo get_the_date() ?></time></p>
									<?php endif; ?>
								</div>
							</div>
						</div>
						<?php else: ?>
							<div class="text-col">
								<div class="inner-wrap">
									<p><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></p>
									<?php if($show_dates): ?>
										<p class="post-date text-muted"><time datetime="<?php echo get_post_time('c', true) ?>"><?php echo get_the_date() ?></time></p>
									<?php endif; ?>
								</div>
							</div>
						<?php endif; ?>
					
					
					<?php endif; ?>
					
					
					
					
				
				<?php	wp_reset_postdata();
			}
		}
	
		?>
		</div>
		<?php if($width == 'boxed'): ?>
			
		</div>
		<?php endif; ?>
		
		<?php if($carousel): ?>
					<script>
						jQuery(document).ready( function(){
							jQuery(".moa-posts").owlCarousel({
				        loop: true,
				        dots: true,
				        nav: true,
				        autoplay: false,
				        animateOut: 'fadeOut',
				        responsive: {
					        0 : {
						        items: 2,
					        },
					        480 : {
						        items: 3,
						      },
					        768 : {
						        items: 5,
					        },
					        1024 : {
						        items: 8,
					        }
					        
				        }
			        });
						});
						
					</script>
					<?php endif; ?>
					
		<?php
		if($after_posts) {
			echo '<p>' . $after_posts . '</p>';
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