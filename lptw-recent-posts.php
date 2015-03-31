<?php
/*
Plugin Name: Advanced Recent Posts
Plugin URI: http://lp-tricks.com/
Description: Plugin that shows the recent posts with thumbnails in the widget and in other parts of the your blog or theme with shortcodes.
Tags: widget, posts, plugin, recent, recent posts, shortcode, thumbnail, thumbnails, categories, content, featured image, Taxonomy
Version: 0.3
Author: Eugene Holin
Author URI: http://lp-tricks.com/
License: GPLv2 or later
Text Domain: lptw_recent_posts_domain
*/

/* load js and css styles */
function lptw_recent_posts_register_scripts() {
	wp_register_style( 'lptw-style', plugins_url( 'lptw-recent-posts.css', __FILE__ ) );
	wp_enqueue_style( 'lptw-style' );
}

add_action( 'wp_enqueue_scripts', 'lptw_recent_posts_register_scripts' );

/**
-------------------------------------- Fluid Images Widget --------------------------------------
**/

// Creating the widget with fluid images
class lptw_recent_posts_fluid_images_widget extends WP_Widget {

    function __construct() {

		$widget_ops = array('classname' => 'lptw_recent_posts_fluid_images_widget', 'description' => __( "Your site&#8217;s most recent Posts. Displays big fluid images, post date ant title.", 'lptw_recent_posts_domain') );
		parent::__construct('lptw-fluid-images-recent-posts', __('Recent Posts Widget (Fluid Images)', 'lptw_recent_posts_domain'), $widget_ops);
		$this->alt_option_name = 'lptw_widget_fluid_images_recent_entries';

		add_action( 'save_post', array($this, 'flush_widget_cache') );
		add_action( 'deleted_post', array($this, 'flush_widget_cache') );
		add_action( 'switch_theme', array($this, 'flush_widget_cache') );

    }

    // Creating widget front-end
    // This is where the action happens
	public function widget($args, $instance) {
		$cache = array();
		if ( ! $this->is_preview() ) {
			$cache = wp_cache_get( 'lptw_recent_posts_fluid_images_widget', 'widget' );
		}

		if ( ! is_array( $cache ) ) {
			$cache = array();
		}

		if ( ! isset( $args['widget_id'] ) ) {
			$args['widget_id'] = $this->id;
		}

		if ( isset( $cache[ $args['widget_id'] ] ) ) {
			echo $cache[ $args['widget_id'] ];
			return;
		}

		ob_start();

        /* months localization */
        $months_ru = Array(1 => "Янв", 2 => "Фев", 3 => "Мар", 4 => "Апр", 5 => "Май", 6 => "Июн", 7 => "Июл", 8 => "Авг", 9 => "Сен", 10 => "Окт", 11 => "Ноя", 12 => "Дек");
        $months_full_ru = Array(1 => "Январь", 2 => "Февраль", 3 => "Март", 4 => "Апрель", 5 => "Май", 6 => "Июнь", 7 => "Июль", 8 => "Август", 9 => "Сентябрь", 10 => "Октябрь", 11 => "Ноябрь", 12 => "Декабрь");
        $months_en = Array(1 => "Jan", 2 => "Feb", 3 => "Mar", 4 => "Apr", 5 => "May", 6 => "Jun", 7 => "Jul", 8 => "Aug", 9 => "Sep", 10 => "Oct", 11 => "Nov", 12 => "Dec");
        $months_full_en = Array(1 => "January", 2 => "February", 3 => "March", 4 => "April", 5 => "May", 6 => "June", 7 => "July", 8 => "August", 9 => "September", 10 => "October", 11 => "November", 12 => "December");

		$show_widget_title = isset( $instance['show_widget_title'] ) ? $instance['show_widget_title'] : true;
		$exclude_current_post = isset( $instance['exclude_current_post'] ) ? $instance['exclude_current_post'] : true;

		$title = ( ! empty( $instance['title'] ) ) ? $instance['title'] : __( 'Recent Posts', 'lptw_recent_posts_domain' );

		$title = apply_filters( 'widget_title', $title, $instance, $this->id_base );

		$number = ( ! empty( $instance['number'] ) ) ? absint( $instance['number'] ) : 5;
		if ( ! $number ) {$number = 5;}

		$show_date = isset( $instance['show_date'] ) ? $instance['show_date'] : true;

		$show_post_title = isset( $instance['show_post_title'] ) ? $instance['show_post_title'] : true;

		$color_scheme = isset( $instance['color_scheme'] ) ? $instance['color_scheme'] : 'light';

		$locale = isset( $instance['locale'] ) ? $instance['locale'] : 'en';

        /* choose months names in case of locale setting */
        switch($locale) {
            case 'ru':
                $months = $months_ru;
                $months_full = $months_full_ru;
            break;
            case 'en':
                $months = $months_en;
                $months_full = $months_full_en;
            break;
            default:
                $months = $months_en;
                $months_full = $months_full_en;
            break;
        }

        /* don't show post in recent if it shows in page */
        global $post;
        if (!empty($post) && $exclude_current_post == true) { $exclude_post = array( $post->ID ); }

        /* @TODO */
        /* exclude some post IDs, defined in admin area */

		$r = new WP_Query( apply_filters( 'widget_posts_args', array(
			'posts_per_page'      => $number,
			'no_found_rows'       => true,
			'post_status'         => 'publish',
			'ignore_sticky_posts' => true,
            'post__not_in'        => $exclude_post
		) ) );

		if ($r->have_posts()) :
?>
		<?php echo $args['before_widget']; ?>
		<?php if ( $title && $show_widget_title == true) {
			echo $args['before_title'] . $title . $args['after_title'];
		} ?>
		<ul class="lptw-recent-posts-fluid-images-widget">
		<?php while ( $r->have_posts() ) : $r->the_post(); ?>
        <?php
            $post_date = get_the_date();
            $month_dig = date("n", strtotime($post_date));
            $month_word = $months_full[$month_dig];
        ?>


			<li>
                <?php if ( has_post_thumbnail() ) :
                    $thumb = wp_get_attachment_image_src( get_post_thumbnail_id($r->post_ID), 'large' );
                    $url = $thumb['0'];
                ?>
				<div class="lptw-post-thumbnail">
                    <a href="<?php the_permalink(); ?>" class="lptw-post-thumbnail-link"><div class="overlay overlay-<?php echo $color_scheme; ?>"><img src="<?php echo $url; ?>" alt="<?php get_the_title() ? the_title() : the_ID(); ?>" /></div>
                    <div class="lptw-post-header">
        		    	<?php if ( $show_date ) : ?>
    	    			<span class="lptw-post-date date-<?php echo $color_scheme; ?>"><?php echo $month_word.' '.date("d, Y", strtotime($post_date)); ?></span>
        			    <?php endif; ?>
        		    	<?php if ( $show_post_title ) : ?>
    		    		<span class="lptw-post-title title-<?php echo $color_scheme; ?>"><?php get_the_title() ? the_title() : the_ID(); ?></span>
        			    <?php endif; ?>
                    </div>
                    </a>
                </div>
                <?php else : ?>
    			<?php if ( $show_date ) : ?>
    				<span class="lptw-post-date"><?php echo $month_word.' '.date("d, Y", strtotime($post_date)); ?></span>
    			<?php endif; ?>
				<a href="<?php the_permalink(); ?>" class="lptw-post-title-link"><?php get_the_title() ? the_title() : the_ID(); ?></a>
                <?php endif; ?>
			</li>
		<?php endwhile; ?>
		</ul>
		<?php echo $args['after_widget']; ?>
<?php
		// Reset the global $the_post as this query will have stomped on it
		wp_reset_postdata();

		endif;

		if ( ! $this->is_preview() ) {
			$cache[ $args['widget_id'] ] = ob_get_flush();
			wp_cache_set( 'lptw_recent_posts_fluid_images_widget', $cache, 'widget' );
		} else {
			ob_end_flush();
		}
	}

    /* --------------------------------- Widget Backend --------------------------------- */
    public function form( $instance ) {
        if ( isset( $instance[ 'title' ] ) ) { $title = esc_attr( $instance[ 'title' ]) ; }
        else { $title = __( 'Recent posts', 'lptw_recent_posts_domain' ); }

        if ( isset( $instance[ 'show_widget_title' ] ) ) { $show_widget_title = (bool) $instance[ 'show_widget_title' ]; }
        else { $show_widget_title = true; }

        if ( isset( $instance[ 'exclude_current_post' ] ) ) { $exclude_current_post = (bool) $instance[ 'exclude_current_post' ]; }
        else { $exclude_current_post = true; }

        if ( isset( $instance[ 'number' ] ) ) { $number = absint( $instance[ 'number' ] ); }
        else { $number = 5; }

        if ( isset( $instance[ 'show_post_title' ] ) ) { $show_post_title = (bool) $instance[ 'show_post_title' ]; }
        else { $show_post_title = true; }

        if ( isset( $instance[ 'show_date' ] ) ) { $show_date = (bool) $instance[ 'show_date' ]; }
        else { $show_date = false; }

        if ( isset( $instance[ 'color_scheme' ] ) ) { $color_scheme = $instance[ 'color_scheme' ] ; }
        else { $color_scheme = 'light'; }

        if ( isset( $instance[ 'locale' ] ) ) { $locale = $instance[ 'locale' ] ; }
        else { $locale = 'en'; }


        // Widget admin form
        ?>
        <p>
        <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'lptw_recent_posts_domain' ); ?></label>
        <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" />

		<p><input class="checkbox" type="checkbox" <?php checked( $show_widget_title ); ?> id="<?php echo $this->get_field_id( 'show_widget_title' ); ?>" name="<?php echo $this->get_field_name( 'show_widget_title' ); ?>" />
		<label for="<?php echo $this->get_field_id( 'show_widget_title' ); ?>"><?php _e( 'Display widget title?', 'lptw_recent_posts_domain' ); ?></label></p>

		<p><input class="checkbox" type="checkbox" <?php checked( $exclude_current_post ); ?> id="<?php echo $this->get_field_id( 'exclude_current_post' ); ?>" name="<?php echo $this->get_field_name( 'exclude_current_post' ); ?>" />
		<label for="<?php echo $this->get_field_id( 'exclude_current_post' ); ?>"><?php _e( 'Exclude current post from list?', 'lptw_recent_posts_domain' ); ?></label></p>

		<p><label for="<?php echo $this->get_field_id( 'number' ); ?>"><?php _e( 'Number of posts to show:', 'lptw_recent_posts_domain' ); ?></label>
		<input id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="text" value="<?php echo $number; ?>" size="3" /></p>

		<p><input class="checkbox" type="checkbox" <?php checked( $show_date ); ?> id="<?php echo $this->get_field_id( 'show_date' ); ?>" name="<?php echo $this->get_field_name( 'show_date' ); ?>" />
		<label for="<?php echo $this->get_field_id( 'show_date' ); ?>"><?php _e( 'Display post date?', 'lptw_recent_posts_domain' ); ?></label></p>

		<p><input class="checkbox" type="checkbox" <?php checked( $show_post_title ); ?> id="<?php echo $this->get_field_id( 'show_post_title' ); ?>" name="<?php echo $this->get_field_name( 'show_post_title' ); ?>" />
		<label for="<?php echo $this->get_field_id( 'show_post_title' ); ?>"><?php _e( 'Display post title?', 'lptw_recent_posts_domain' ); ?></label></p>

		<p>
			<label for="<?php echo $this->get_field_id('color_scheme'); ?>"><?php _e( 'Color scheme:', 'lptw_recent_posts_domain' ); ?></label>
			<select name="<?php echo $this->get_field_name( 'color_scheme' ); ?>" id="<?php echo $this->get_field_id('color_scheme'); ?>" class="widefat">
				<option value="light"<?php selected( $color_scheme, 'light' ); ?>><?php _e('Light', 'lptw_recent_posts_domain'); ?></option>
				<option value="dark"<?php selected( $color_scheme, 'dark' ); ?>><?php _e('Dark', 'lptw_recent_posts_domain'); ?></option>
			</select>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id('locale'); ?>"><?php _e( 'Locale:', 'lptw_recent_posts_domain' ); ?></label>
			<select name="<?php echo $this->get_field_name( 'locale' ); ?>" id="<?php echo $this->get_field_id('locale'); ?>" class="widefat">
				<option value="en"<?php selected( $locale, 'en' ); ?>><?php _e('English', 'lptw_recent_posts_domain'); ?></option>
				<option value="ru"<?php selected( $locale, 'ru' ); ?>><?php _e('Russian', 'lptw_recent_posts_domain'); ?></option>
			</select>
		</p>

        </p>
        <?php
    }

	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['show_widget_title'] = isset( $new_instance['show_widget_title'] ) ? (bool) $new_instance['show_widget_title'] : false;
		$instance['exclude_current_post'] = isset( $new_instance['exclude_current_post'] ) ? (bool) $new_instance['exclude_current_post'] : false;
		$instance['number'] = (int) $new_instance['number'];
		$instance['show_post_title'] = isset( $new_instance['show_post_title'] ) ? (bool) $new_instance['show_post_title'] : false;
		$instance['show_date'] = isset( $new_instance['show_date'] ) ? (bool) $new_instance['show_date'] : false;
		$instance['color_scheme'] = strip_tags($new_instance['color_scheme']);
		$instance['locale'] = strip_tags($new_instance['locale']);
		$this->flush_widget_cache();

		$alloptions = wp_cache_get( 'alloptions', 'options' );
		if ( isset($alloptions['lptw_widget_fluid_images_recent_entries']) )
			delete_option('lptw_widget_fluid_images_recent_entries');

		return $instance;
	}

	public function flush_widget_cache() {
		wp_cache_delete('lptw_recent_posts_fluid_images_widget', 'widget');
	}

} // Class wpb_widget ends here

/**
-------------------------------------- Small Thumbnails Widget --------------------------------------
**/

// Creating the widget with fluid images
class lptw_recent_posts_thumbnails_widget extends WP_Widget {

    function __construct() {

		$widget_ops = array('classname' => 'lptw_recent_posts_thumbnails_widget', 'description' => __( "Your site&#8217;s most recent Posts. Displays small thumbnails, post date and title.", 'lptw_recent_posts_domain') );
		parent::__construct('lptw-thumbnails-recent-posts', __('Recent Posts Widget (Thumbnails)', 'lptw_recent_posts_domain'), $widget_ops);
		$this->alt_option_name = 'lptw_widget_thumbnails_recent_entries';

		add_action( 'save_post', array($this, 'flush_widget_cache') );
		add_action( 'deleted_post', array($this, 'flush_widget_cache') );
		add_action( 'switch_theme', array($this, 'flush_widget_cache') );

    }

    // Creating widget front-end
    // This is where the action happens
	public function widget($args, $instance) {
		$cache = array();
		if ( ! $this->is_preview() ) {
			$cache = wp_cache_get( 'lptw_recent_posts_thumbnails_widget', 'widget' );
		}

		if ( ! is_array( $cache ) ) {
			$cache = array();
		}

		if ( ! isset( $args['widget_id'] ) ) {
			$args['widget_id'] = $this->id;
		}

		if ( isset( $cache[ $args['widget_id'] ] ) ) {
			echo $cache[ $args['widget_id'] ];
			return;
		}

		ob_start();

        /* months localization */
        $months_ru = Array(1 => "Янв", 2 => "Фев", 3 => "Мар", 4 => "Апр", 5 => "Май", 6 => "Июн", 7 => "Июл", 8 => "Авг", 9 => "Сен", 10 => "Окт", 11 => "Ноя", 12 => "Дек");
        $months_full_ru = Array(1 => "Январь", 2 => "Февраль", 3 => "Март", 4 => "Апрель", 5 => "Май", 6 => "Июнь", 7 => "Июль", 8 => "Август", 9 => "Сентябрь", 10 => "Октябрь", 11 => "Ноябрь", 12 => "Декабрь");
        $months_en = Array(1 => "Jan", 2 => "Feb", 3 => "Mar", 4 => "Apr", 5 => "May", 6 => "Jun", 7 => "Jul", 8 => "Aug", 9 => "Sep", 10 => "Oct", 11 => "Nov", 12 => "Dec");
        $months_full_en = Array(1 => "January", 2 => "February", 3 => "March", 4 => "April", 5 => "May", 6 => "June", 7 => "July", 8 => "August", 9 => "September", 10 => "October", 11 => "November", 12 => "December");

		$show_widget_title = isset( $instance['show_widget_title'] ) ? $instance['show_widget_title'] : true;
		$exclude_current_post = isset( $instance['exclude_current_post'] ) ? $instance['exclude_current_post'] : true;

		$title = ( ! empty( $instance['title'] ) ) ? $instance['title'] : __( 'Recent Posts', 'lptw_recent_posts_domain' );

		$title = apply_filters( 'widget_title', $title, $instance, $this->id_base );

		$number = ( ! empty( $instance['number'] ) ) ? absint( $instance['number'] ) : 5;
		if ( ! $number ) {$number = 5;}

		$show_date = isset( $instance['show_date'] ) ? $instance['show_date'] : true;

		$show_post_title = isset( $instance['show_post_title'] ) ? $instance['show_post_title'] : true;

		$locale = isset( $instance['locale'] ) ? $instance['locale'] : 'en';

        /* choose months names in case of locale setting */
        switch($locale) {
            case 'ru':
                $months = $months_ru;
                $months_full = $months_full_ru;
            break;
            case 'en':
                $months = $months_en;
                $months_full = $months_full_en;
            break;
            default:
                $months = $months_en;
                $months_full = $months_full_en;
            break;
        }

        /* don't show post in recent if it shows in page */
        global $post;
        if (!empty($post) && $exclude_current_post == true) { $exclude_post = array( $post->ID ); }

        /* @TODO */
        /* exclude some post IDs, defined in admin area */

		$r = new WP_Query( apply_filters( 'widget_posts_args', array(
			'posts_per_page'      => $number,
			'no_found_rows'       => true,
			'post_status'         => 'publish',
			'ignore_sticky_posts' => true,
            'post__not_in'        => $exclude_post
		) ) );

		if ($r->have_posts()) :
?>
		<?php echo $args['before_widget']; ?>
		<?php if ( $title && $show_widget_title == true) {
			echo $args['before_title'] . $title . $args['after_title'];
		} ?>
		<ul class="lptw-recent-posts-thumbnails-widget">
		<?php while ( $r->have_posts() ) : $r->the_post(); ?>
        <?php
            $post_date = get_the_date();
            $month_dig = date("n", strtotime($post_date));
            $month_word = $months_full[$month_dig];
        ?>

			<li>
                <div class="lptw-post-small-thumbnail">
                    <a href="<?php the_permalink(); ?>" class="lptw-thumbnail-link"><?php if ( has_post_thumbnail() ) {the_post_thumbnail( array(100, 100) );} ?></a>
                    <div class="lptw-post-header">
        		    	<?php if ( $show_date ) : ?>
        	    		<span class="lptw-post-date"><?php echo $month_word.' '.date("d, Y", strtotime($post_date)); ?></span>
        			    <?php endif; ?>
        		    	<?php if ( $show_post_title ) : ?>
        		    	<a href="<?php the_permalink(); ?>" class="lptw-header-link"><?php get_the_title() ? the_title() : the_ID(); ?></a>
        			    <?php endif; ?>
                    </div>
                </div>
			</li>
		<?php endwhile; ?>
		</ul>
		<?php echo $args['after_widget']; ?>
<?php
		// Reset the global $the_post as this query will have stomped on it
		wp_reset_postdata();

		endif;

		if ( ! $this->is_preview() ) {
			$cache[ $args['widget_id'] ] = ob_get_flush();
			wp_cache_set( 'lptw_recent_posts_thumbnails_widget', $cache, 'widget' );
		} else {
			ob_end_flush();
		}
	}

    /* --------------------------------- Widget Backend --------------------------------- */
    public function form( $instance ) {
        if ( isset( $instance[ 'title' ] ) ) { $title = esc_attr( $instance[ 'title' ]) ; }
        else { $title = __( 'Recent posts', 'lptw_recent_posts_domain' ); }

        if ( isset( $instance[ 'show_widget_title' ] ) ) { $show_widget_title = (bool) $instance[ 'show_widget_title' ]; }
        else { $show_widget_title = true; }

        if ( isset( $instance[ 'exclude_current_post' ] ) ) { $exclude_current_post = (bool) $instance[ 'exclude_current_post' ]; }
        else { $exclude_current_post = true; }

        if ( isset( $instance[ 'number' ] ) ) { $number = absint( $instance[ 'number' ] ); }
        else { $number = 5; }

        if ( isset( $instance[ 'show_post_title' ] ) ) { $show_post_title = (bool) $instance[ 'show_post_title' ]; }
        else { $show_post_title = true; }

        if ( isset( $instance[ 'show_date' ] ) ) { $show_date = (bool) $instance[ 'show_date' ]; }
        else { $show_date = false; }

        if ( isset( $instance[ 'locale' ] ) ) { $locale = $instance[ 'locale' ] ; }
        else { $locale = 'en'; }

        // Widget admin form
        ?>
        <p>
        <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'lptw_recent_posts_domain' ); ?></label>
        <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" />

		<p><input class="checkbox" type="checkbox" <?php checked( $show_widget_title ); ?> id="<?php echo $this->get_field_id( 'show_widget_title' ); ?>" name="<?php echo $this->get_field_name( 'show_widget_title' ); ?>" />
		<label for="<?php echo $this->get_field_id( 'show_widget_title' ); ?>"><?php _e( 'Display widget title?', 'lptw_recent_posts_domain' ); ?></label></p>

		<p><input class="checkbox" type="checkbox" <?php checked( $exclude_current_post ); ?> id="<?php echo $this->get_field_id( 'exclude_current_post' ); ?>" name="<?php echo $this->get_field_name( 'exclude_current_post' ); ?>" />
		<label for="<?php echo $this->get_field_id( 'exclude_current_post' ); ?>"><?php _e( 'Exclude current post from list?', 'lptw_recent_posts_domain' ); ?></label></p>

		<p><label for="<?php echo $this->get_field_id( 'number' ); ?>"><?php _e( 'Number of posts to show:', 'lptw_recent_posts_domain' ); ?></label>
		<input id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="text" value="<?php echo $number; ?>" size="3" /></p>

		<p><input class="checkbox" type="checkbox" <?php checked( $show_date ); ?> id="<?php echo $this->get_field_id( 'show_date' ); ?>" name="<?php echo $this->get_field_name( 'show_date' ); ?>" />
		<label for="<?php echo $this->get_field_id( 'show_date' ); ?>"><?php _e( 'Display post date?', 'lptw_recent_posts_domain' ); ?></label></p>

		<p><input class="checkbox" type="checkbox" <?php checked( $show_post_title ); ?> id="<?php echo $this->get_field_id( 'show_post_title' ); ?>" name="<?php echo $this->get_field_name( 'show_post_title' ); ?>" />
		<label for="<?php echo $this->get_field_id( 'show_post_title' ); ?>"><?php _e( 'Display post title?', 'lptw_recent_posts_domain' ); ?></label></p>

		<p>
			<label for="<?php echo $this->get_field_id('locale'); ?>"><?php _e( 'Locale:', 'lptw_recent_posts_domain' ); ?></label>
			<select name="<?php echo $this->get_field_name( 'locale' ); ?>" id="<?php echo $this->get_field_id('locale'); ?>" class="widefat">
				<option value="en"<?php selected( $locale, 'en' ); ?>><?php _e('English', 'lptw_recent_posts_domain'); ?></option>
				<option value="ru"<?php selected( $locale, 'ru' ); ?>><?php _e('Russian', 'lptw_recent_posts_domain'); ?></option>
			</select>
		</p>

        </p>
        <?php
    }

	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['show_widget_title'] = isset( $new_instance['show_widget_title'] ) ? (bool) $new_instance['show_widget_title'] : false;
		$instance['exclude_current_post'] = isset( $new_instance['exclude_current_post'] ) ? (bool) $new_instance['exclude_current_post'] : false;
		$instance['number'] = (int) $new_instance['number'];
		$instance['show_post_title'] = isset( $new_instance['show_post_title'] ) ? (bool) $new_instance['show_post_title'] : false;
		$instance['show_date'] = isset( $new_instance['show_date'] ) ? (bool) $new_instance['show_date'] : false;
		$instance['locale'] = strip_tags($new_instance['locale']);
		$this->flush_widget_cache();

		$alloptions = wp_cache_get( 'alloptions', 'options' );
		if ( isset($alloptions['lptw_widget_thumbnails_recent_entries']) )
			delete_option('lptw_widget_thumbnails_recent_entries');

		return $instance;
	}

	public function flush_widget_cache() {
		wp_cache_delete('lptw_recent_posts_thumbnails_widget', 'widget');
	}

} // Class wpb_widget ends here

// Register and load the widget
function lptw_recent_posts_load_widget() {
	register_widget( 'lptw_recent_posts_fluid_images_widget' );
	register_widget( 'lptw_recent_posts_thumbnails_widget' );
}
add_action( 'widgets_init', 'lptw_recent_posts_load_widget' );


/**
-------------------------------------- Shortcode --------------------------------------
**/

// all-news-small-thumb

function lptw_display_recent_posts ( $atts ) {
    /* months localization */
    $months_ru = Array(1 => "Янв", 2 => "Фев", 3 => "Мар", 4 => "Апр", 5 => "Май", 6 => "Июн", 7 => "Июл", 8 => "Авг", 9 => "Сен", 10 => "Окт", 11 => "Ноя", 12 => "Дек");
    $months_full_ru = Array(1 => "Январь", 2 => "Февраль", 3 => "Март", 4 => "Апрель", 5 => "Май", 6 => "Июнь", 7 => "Июль", 8 => "Август", 9 => "Сентябрь", 10 => "Октябрь", 11 => "Ноябрь", 12 => "Декабрь");
    $months_en = Array(1 => "Jan", 2 => "Feb", 3 => "Mar", 4 => "Apr", 5 => "May", 6 => "Jun", 7 => "Jul", 8 => "Aug", 9 => "Sep", 10 => "Oct", 11 => "Nov", 12 => "Dec");
    $months_full_en = Array(1 => "January", 2 => "February", 3 => "March", 4 => "April", 5 => "May", 6 => "June", 7 => "July", 8 => "August", 9 => "September", 10 => "October", 11 => "November", 12 => "December");

    $default_posts_per_page =  get_option( 'posts_per_page', '10' );

    $a = shortcode_atts( array(
        'post_type'         => 'post',
        'category_id'       => '',
        'post_parent'       => '0',
        'posts_per_page'    => $default_posts_per_page,
        'thumbnail_size'    => 'thumbnail',
        'random_thumbnail'  => 'false',
        'layout'            => 'overlay',
        'color_scheme'      => 'light',
        'show_date'         => 'true',
        'fluid_images'      => 'false',
        'columns'           => '1',
        'height'            => '',
        'width'             => '300',
        'locale'            => 'en'
    ), $atts );

    /* choose months names in case of locale setting */
    switch($a['locale']) {
        case 'ru':
            $months = $months_ru;
            $months_full = $months_full_ru;
        break;
        case 'en':
            $months = $months_en;
            $months_full = $months_full_en;
        break;
        default:
            $months = $months_en;
            $months_full = $months_full_en;
        break;
    }

    if ($a['width'] != '' || $a['height'] != '') {
        $dim_style = 'style="';
        if ($a['width'] != '')  {$dim_style .= 'width:'.$a['width'].'px;';}
        if ($a['height'] != '') {$dim_style .= 'height:'.$a['height'].'px;';}
        $dim_style .= '"';
    }

    if ($a['fluid_images'] == 'true') { $dim_style = 'style="width:100%;"'; }

    if ($a['columns'] < 1) {$a['columns'] = 1;}
    elseif ($a['columns'] > 2) {$a['columns'] = 2;}
    $column_style = 'columns-'.$a['columns'];

    $args = array(
        'post_type'       => $a['post_type'],
        'cat'             => $a['category_id'],
        'post_parent'     => $a['post_parent'],
        'posts_per_page'  => $a['posts_per_page']
        );

    $allnews = new WP_Query( $args );
    if( $allnews->have_posts() ) {
        $i=0;
        $content = '';
        while( $allnews->have_posts() ) {
            $allnews->the_post();
            $thumb = wp_get_attachment_image_src( get_post_thumbnail_id($allnews->post_ID), $a['thumbnail_size'] );
            $url = $thumb['0'];
            if (!$url && $a['random_thumbnail'] == 'true') {
                $thumb_posts = get_posts(array('orderby' => 'rand', 'category' => $a['category_id'], 'numberposts' => 1, 'meta_key' => '_thumbnail_id'));
                foreach( $thumb_posts as $rand_post ) {
                    $rand_post_id = $rand_post->ID;
                    $thumb = wp_get_attachment_image_src( get_post_thumbnail_id($rand_post_id), $a['thumbnail_size'] );
                    $url = $thumb['0'];
                }
            }

            if ($a['columns'] > 1) {
                if (($i % 2) == 1) {$cell_style = 'last-cell';}
                else {$cell_style = 'inner-cell';}
            } else {$cell_style = 'last-cell';}

            /* start layouts output */
            /* basic layout - one or tho columns, fixed or adaptive width */
            if ($a['layout'] == 'basic' ) {
            $post_date = get_the_date();
            $month_dig = date("n", strtotime($post_date));
            $month_word = $months_full[$month_dig];

            $content .= '
            <article class="basic-layout '.$column_style.' '.$cell_style.'" '.$dim_style.'>
                <header>
                    <a href="'.get_the_permalink().'" class="lptw-post-thumbnail-link"><div class="overlay overlay-'.$a['color_scheme'].'"><img src="'.$url.'" alt="'.get_the_title().'" class="fluid" /></div>
                    <div class="lptw-post-header">';
        	if ( $a['show_date'] ) {$content .= '<span class="lptw-post-date date-'.$a['color_scheme'].'">'.$month_word.' '.date("d, Y", strtotime($post_date)).'</span>';}
    		$content .= '<span class="lptw-post-title title-'.$a['color_scheme'].'">'.get_the_title().'</span>
                    </div>
                    </a>
                </header>
            </article>';

            /* small thumbnails */
            } elseif ($a['layout'] == 'thumbnail' ) {
            $post_date = get_the_date();
            $month_dig = date("n", strtotime($post_date));
            $month_word = $months_full[$month_dig];
            $thumb_100 = wp_get_attachment_image_src( get_post_thumbnail_id($allnews->post_ID), array ( 100,100 ) );
            $url_100 = $thumb_100['0'];
            $content .= '<article class="thumbnail-layout '.$column_style.' '.$cell_style.'" '.$dim_style.'>
                <a href="'.get_the_permalink().'" class="lptw-thumbnail-link"><img src="'.$url_100.'" width="100" height="100" alt="'.get_the_title().'" /></a>
                <header class="lptw-post-header">';
        	if ( $a['show_date'] ) { $content .= '<span class="lptw-post-date">'.$month_word.' '.date("d, Y", strtotime($post_date)).'</span>'; }
    		    	$content .= '<a href="'.get_the_permalink().'" class="lptw-post-title">'.get_the_title().'</a>
                </header>
            </article>';

            /* recent posts without thumbnails, with date as drop cap */
            /* the months are localized */
            } elseif ($a['layout'] == 'dropcap' ) {
                $post_date = get_the_date();
                $month_dig = date("n", strtotime($post_date));
                $month_word = $months[$month_dig];
            $content .= '<article class="dropcap-layout '.$column_style.' '.$cell_style.'" '.$dim_style.'>
                <header>
                    <div class="lptw-dropcap-date">
                        <span class="lptw-dropcap-day">'.date("d", strtotime($post_date)).'</span>
                        <span class="lptw-dropcap-month">'.$month_word.'.'.date("Y", strtotime($post_date)).'</span>
                    </div>
                    <a class="lptw-dropcap-date-link" href="'.get_the_permalink().'">'.get_the_title().'</a>
                </header>
            </article>';
            }
            $i++;
        } // end while( $allnews->have_posts() )
        return $content;
    } else {
        return __( 'No recent posts', 'lptw_recent_posts_domain' );
    }
}

add_shortcode( 'lptw_recentposts', 'lptw_display_recent_posts' );

/*
 * Add Shortcode Builder
 */
function lptw_register_recent_posts_menu_page(){
    add_menu_page( 'Advanced Recent Posts', 'Advanced Recent Posts', 'manage_options', 'recent_posts', 'lptw_recent_posts_manage_shortcodes', 'dashicons-editor-code', 100 );
}
add_action( 'admin_menu', 'lptw_register_recent_posts_menu_page' );

function lptw_recent_posts_backend_scripts() {
	wp_register_style('lptw-recent-posts-backend-style', plugins_url( 'backend/lptw-recent-posts-backend.css', __FILE__ ) );
	wp_enqueue_style('lptw-recent-posts-backend-style' );

    wp_enqueue_script( 'lptw-shortcode-builder-script', plugins_url ( 'backend/lptw-recent-posts-shortcode-builder.js', __FILE__ ), array(), '0.3', true );
}
add_action( 'admin_enqueue_scripts', 'lptw_recent_posts_backend_scripts' );

/* include shortcode builder  */
include( plugin_dir_path( __FILE__ ) . 'backend/lptw-recent-posts-backend.php');

?>