<?php
/**
 * Theme functions and definitions
 *
 * @package Blogtimes
 */

if ( ! function_exists( 'blogtimes_enqueue_styles' ) ) :
	/**
	 * @since 0.1
	 */
	function blogtimes_enqueue_styles() {
		wp_enqueue_style('bootstrap', get_template_directory_uri() . '/css/bootstrap.css');
		wp_enqueue_style( 'blogus-style-parent', get_template_directory_uri() . '/style.css' );
		wp_enqueue_style( 'blogtimes-style', get_stylesheet_directory_uri() . '/style.css', array( 'blogus-style-parent' ), '1.0' );
		// wp_dequeue_style( 'blogus-default',get_template_directory_uri() .'/css/colors/default.css');
		wp_enqueue_style( 'blogtimes-default-css', get_stylesheet_directory_uri()."/css/colors/default.css" );
		wp_enqueue_style( 'blogtimes-dark', get_stylesheet_directory_uri() . '/css/colors/dark.css');

		if(is_rtl()){
			wp_enqueue_style( 'blogus_style_rtl', trailingslashit( get_template_directory_uri() ) . 'style-rtl.css' );
	    }
		
	}

endif;
add_action( 'wp_enqueue_scripts', 'blogtimes_enqueue_styles', 9999 );

if ( ! function_exists( 'blogtimes_enqueue_scripts' ) ) :
	/**
	 * @since 0.1
	 */
	function blogtimes_enqueue_scripts() {
	
		wp_enqueue_script( 'blogtimes-ticker-js', get_stylesheet_directory_uri() .'/js/jquery.marquee.min.js', array('jquery'), '1.0', true); 
		wp_enqueue_script( 'blogtimes-custom', get_stylesheet_directory_uri() .'/js/custom.js', array('jquery'), '1.0', true); 
	
	}

endif;
add_action( 'wp_enqueue_scripts', 'blogtimes_enqueue_scripts', 99999 ); 


function blogtimes_customizer_rid_values($wp_customize) {
}

add_action( 'customize_register', 'blogtimes_customizer_rid_values', 1000 );

function blogtimes_theme_setup() {
	$args = array(
		'default-color' => '#f3f4f6',
		'default-image' => '',
		'wp-head-callback' => '_custom_background_cb',
		'admin-head-callback' => '',
		'admin-preview-callback' => ''
	);
	add_theme_support( 'custom-background', $args );

	//Load text domain for translation-ready
	load_theme_textdomain('blogtimes', get_stylesheet_directory() . '/languages');

	require( get_stylesheet_directory() . '/hooks/hook-featured-slide.php' );
	require( get_stylesheet_directory() . '/hooks/hook-front-page-ticker-section.php' );
	require( get_stylesheet_directory() . '/frontpage-options.php' );
	require( get_stylesheet_directory() . '/font.php' );
	add_theme_support( 'title-tag' );
	add_theme_support( 'automatic-feed-links' );
} 
add_action( 'after_setup_theme', 'blogtimes_theme_setup' );


if ( ! function_exists( 'blogtimes_blog_content' ) ) :
    function blogtimes_blog_content() { 
      $blogus_blog_content  = get_theme_mod('blogus_blog_content','excerpt');

      	if ( 'excerpt' == $blogus_blog_content ){
		$blogus_excerpt = blogus_the_excerpt( absint( 20 ) );
			if ( !empty( $blogus_excerpt ) ) :                   
				echo wp_kses_post( wpautop( $blogus_excerpt ) );
			endif; 
		} else{ 
       		the_content( __('Read More','blogtimes') );
        }
	}
endif;

function blogtimes_customizer_styles() { ?>
	<style>
		body #accordion-section-blogus_pro_upsell h3.accordion-section-title {
			background-image: linear-gradient(-200deg, #f67b56 0%, #c19be9 100%) !important
		}
	</style>
	<?php
}
add_action('customize_controls_enqueue_scripts', 'blogtimes_customizer_styles');


function blogtimes_post_image_display_type($post) {
    $url = blogus_get_freatured_image_url($post->ID, 'blogus-medium');
	$blogus_global_category_enable = get_theme_mod('blogus_global_category_enable',true);
    if($url) { 
        if ( blogus_get_option('post_image_type') == 'post_fix_height' ) { ?>
            <div class="bs-blog-thumb md back-img bs-effect-shine" style="background-image: url('<?php echo esc_url($url); ?>');">
                <a href="<?php the_permalink(); ?>" class="link-div"></a>
					<?php if($blogus_global_category_enable == true) { blogus_post_categories(); } ?> 
            </div> 
        <?php } else { ?>
            <div class="bs-post-thumb lg bs-effect-shine">
				<?php echo '<a href="'.esc_url(get_the_permalink()).'">'; the_post_thumbnail( '', array( 'class'=>'img-responsive img-fluid' ) ); echo '</a>'; ?>
				<?php if($blogus_global_category_enable == true) { blogus_post_categories(); } ?> 
            </div> 
        <?php }
    }
}


if ( ! function_exists( 'blogus_comments_content' ) ) :
    function blogus_comments_content() { 
        $blogus_global_comment_enable = get_theme_mod('blogus_global_comment_enable', false);
        if($blogus_global_comment_enable == true) {?>
        <span class="comments-link"> 
            <a href="<?php the_permalink(); ?>">
            <span>
                <?php if ( get_comments_number() == 0 ) {
                    esc_html_e(  __('No Comments', 'blogtimes') );
                } else {
                    echo get_comments_number() . ' ';
                    esc_html_e( get_comments_number() == 1 ? __('Comment', 'blogtimes') : __('Comments', 'blogtimes') );
                } ?>
            </span>
        </a> 
        </span>
    <?php } 
    }
endif;

if (!function_exists('blogus_main_content')) :
    function blogus_main_content()
    {
        $blogus_content_layout = esc_attr(get_theme_mod('blogus_content_layout','grid-right-sidebar'));
        if($blogus_content_layout == "align-content-left" || $blogus_content_layout == "grid-left-sidebar") { ?>
            <!--col-lg-4-->
            <aside class="col-lg-4 sidebar-left">
                <?php get_sidebar();?>
            </aside>
            <!--/col-lg-4-->
        <?php } ?>
        
            <!--col-lg-8-->
        <?php if($blogus_content_layout == "align-content-right" || $blogus_content_layout == "align-content-left"){ ?>
            <div class="col-lg-8 content-right">
                <?php get_template_part('template-parts/content', get_post_format()); ?>
            </div>
        <?php } elseif($blogus_content_layout == "full-width-content") { ?>
            <div class="col-lg-12 content-full">
                <?php get_template_part('template-parts/content', get_post_format()); ?>
            </div>
        <?php }  
        
        if($blogus_content_layout == "grid-left-sidebar" || $blogus_content_layout == "grid-right-sidebar"){ ?>
            <div class="col-lg-8 content-right">
                <?php get_template_part('template-parts/content','grid'); ?>
            </div>
        <?php } elseif($blogus_content_layout == "grid-fullwidth") { ?>
            <div class="col-lg-12 content-full">
                <?php get_template_part('template-parts/content','grid'); ?>
            </div>
        <?php } ?>

            <!--/col-lg-8-->
        <?php if($blogus_content_layout == "align-content-right" || $blogus_content_layout == "grid-right-sidebar") { ?>
            <!--col-lg-4-->
            <aside class="col-lg-4 sidebar-right">
                <?php get_sidebar();?>
            </aside>
            <!--/col-lg-4-->
        <?php }        
    }
endif;
add_action('blogus_action_main_content_layouts', 'blogus_main_content', 40);

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function blogtimes_widgets_init() {

	$blogus_footer_column_layout = esc_attr(get_theme_mod('blogus_footer_column_layout',3));
	
	$blogus_footer_column_layout = 12 / $blogus_footer_column_layout;
	
	register_sidebar( array(
		'name'          => esc_html__( 'Sidebar Widget Area', 'blogtimes' ),
		'id'            => 'sidebar-1',
		'description'   => '',
		'before_widget' => '<div id="%1$s" class="bs-widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<div class="bs-widget-title"><h2 class="title">',
		'after_title'   => '</h2></div>',
	) );


	
	register_sidebar( array(
		'name'          => esc_html__( 'Footer Widget Area', 'blogtimes' ),
		'id'            => 'footer_widget_area',
		'description'   => '',
		'before_widget' => '<div id="%1$s" class="col-md-'.$blogus_footer_column_layout.' rotateInDownLeft animated bs-widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<div class="bs-widget-title"><h2 class="title">',
		'after_title'   => '</h2></div>',
	) );

}
add_action( 'widgets_init', 'blogtimes_widgets_init' );