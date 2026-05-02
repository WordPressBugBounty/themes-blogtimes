<?php
if (!function_exists('blogtimes_featured_section')) :
    /**
     *
     * @since Blogtimes
     *
     */
    function blogtimes_featured_section(){ 
        if (is_front_page() || is_home()) {
            $number_of_posts = 5;
            $blogus_enable_main_slider = get_theme_mod('show_main_news_section',true);
            $select_vertical_slider_news_category = blogus_get_option('select_vertical_slider_news_category');
            $all_posts_vertical = blogus_get_posts($number_of_posts, $select_vertical_slider_news_category);
            if ($blogus_enable_main_slider): ?>
                <div  class="col-12 cc">
                    <div class="homemain-two bs swiper-container">
                        <div class="swiper-wrapper">
                            <?php blogus_get_block('list', 'banner'); ?>         
                        </div>
                        <div class="swiper-button-next"></div>
                        <div class="swiper-button-prev"></div>
                    </div>
                </div>
                <!--==/ Home Slider ==-->
            <?php endif; ?>
            <!-- end slider-section -->
        <?php }
    }
    endif;
            
add_action('blogtimes_action_featured_section', 'blogtimes_featured_section', 40);