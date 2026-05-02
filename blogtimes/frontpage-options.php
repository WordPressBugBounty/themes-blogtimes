<?php

/**
 * Option Panel
 *
 * @package Blogtimes
 */

function blogtimes_customize_register($wp_customize) {

	$wp_customize->get_setting('show_main_news_section')->default = true;

	$wp_customize->get_setting('blogus_content_layout')->default = 'grid-right-sidebar';

	$wp_customize->get_setting('primary_menu_bg_color')->default = '';

	//=================================
	// Trending Posts Section.
	//=================================

	$wp_customize->add_section('news_ticker_section',
		array(
			'title' => esc_html__('News Ticker', 'blogtimes'),
			'priority' => 31,
			'capability' => 'edit_theme_options', 
		)
	);

	$wp_customize->add_setting('enable_news_ticker',
		array(
			'default' => false,
			'capability' => 'edit_theme_options',
			'sanitize_callback' => 'blogus_sanitize_checkbox',
		)
	);
	$wp_customize->add_control(new Blogus_Toggle_Control( $wp_customize, 'enable_news_ticker', 
		array(
			'label' => esc_html__('Enable / Disable', 'blogtimes'),
			'section' => 'news_ticker_section',
			'type' => 'toggle',
			'priority' => 22,

		)
	));

	// Setting - number_of_slides.
	$wp_customize->add_setting('news_ticker_title',
		array(
			'default' =>  __('Trending', 'blogtimes'),
			'capability' => 'edit_theme_options',
			'sanitize_callback' => 'sanitize_text_field',
			// 'transport' => $selective_refresh
		)
	);

	$wp_customize->add_control('news_ticker_title',
		array(
			'label' => esc_html__('Title', 'blogtimes'),
			'section' => 'news_ticker_section',
			'type' => 'text',
			'priority' => 23,
			// 'active_callback' => 'blogtimes_flash_posts_section_status'

		)
	);

	// Setting - drop down category for slider.
	$wp_customize->add_setting('news_ticker_category',
		array(
			'default' => 0,
			'capability' => 'edit_theme_options',
			'sanitize_callback' => 'absint',
		)
	);


	$wp_customize->add_control(new blogus_Dropdown_Taxonomies_Control($wp_customize, 'news_ticker_category',
		array(
			'label' => esc_html__('Category', 'blogtimes'),
			'description' => esc_html__('Posts to be shown on trending posts ', 'blogtimes'),
			'section' => 'news_ticker_section',
			'type' => 'dropdown-taxonomies',
			'taxonomy' => 'category',
			'priority' => 23,
			// 'active_callback' => 'blogtimes_flash_posts_section_status'
		)
	));
}
add_action('customize_register', 'blogtimes_customize_register');