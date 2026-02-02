<?php
/**
 * Claps functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Claps
 */


/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
add_filter( 'wpseo_canonical', '__return_false');
add_filter( 'get_canonical_url', function ( $canonical, $page ) {

    if ( $page->post_type !== 'page' || !function_exists( 'pll_current_language' )) {
        return  $canonical;

    }

    $cur_lang = pll_get_post_language($page->ID);
    if (!$cur_lang) {
        return $canonical;
    }

    $default_lang = pll_default_language();

    if ($cur_lang !== $default_lang) {
        return $canonical;
    }

    $langs = pll_languages_list( [ 'hide_empty' => false ] );
    $target_langs = array_diff( $langs, [ $default_lang ] );

    foreach ( $target_langs as $lang ) {
        $translated_id = pll_get_post( $page->ID, $lang );

        if ( $translated_id && get_post_status( $translated_id ) === 'publish' ) {
            return get_permalink( $translated_id );
        }
    }

    return $canonical;

}, 10, 2 );

add_filter('pll_rel_hreflang_attributes', function ($hreflangs) {
    $languages = PLL()->model->get_languages_list();

    foreach ($hreflangs as $short => $url) {
        if ($short === 'x-default') {
            continue;
        }
        
        if (str_contains($short, '-')) {
            $lowercased = mb_strtolower($short);
            if ($short !== $lowercased) {
                $hreflangs[$lowercased] = $url;
                unset($hreflangs[$short]);
            }
            continue;
        }
        
        foreach ($languages as $lang) {
            if (explode('-', $lang->get_locale('display'), 2)[0] === $short) {
                $hreflangs[mb_strtolower($lang->slug)] = $url;
                unset($hreflangs[$short]);
                continue 2;
            }
        }
    }

    foreach ($languages as $lang) {
        if (!$lang->is_default) {
            $hreflangs['x-default'] = $hreflangs[mb_strtolower($lang->slug)];
            break;
        }
    }

    return $hreflangs;
});

add_action('wp_enqueue_scripts', 'claps_styles');
function claps_styles()
{
    wp_enqueue_style('main-style', get_template_directory_uri() . '/assets/css/style.min.css', array(), rand(111,9999));
    wp_enqueue_style('style', get_stylesheet_uri());
}

add_action('wp_enqueue_scripts', 'claps_scripts');
function claps_scripts(){
	wp_enqueue_script('jquery2015', get_template_directory_uri() . '/assets/js/jquery2015.js', array(), '', false);
    wp_enqueue_script('script', get_template_directory_uri() . '/assets/js/script.min.js', array(), '', false);
	wp_enqueue_script('spin1', get_template_directory_uri() . '/assets/js/spin1.js', array(), '', false);
	wp_enqueue_script('spin2', get_template_directory_uri() . '/assets/js/spin2.js', array(), '', false);
}

add_action('after_setup_theme', 'menus');
function menus()
{
    register_nav_menus([
        'primary' => 'Primary menu',
        'error' => 'Error menu'
    ]);
}
add_theme_support('custom-logo');
if (function_exists('acf_add_options_page')) {
    acf_add_options_page(array(
        'page_title' => 'Site settings',
        'menu_title' => 'Site settings',
        'menu_slug'  => 'acf-settings',
        'capability' => 'edit_posts',
        'redirect'   => false
    ));
}

function load_custom_google_fonts() {
    if (function_exists('get_field')) {
        $font = get_field('font', 'option');
        if ($font) {
            $font = str_replace(' ', '+', $font); // Заменяем пробелы на "+"
			echo '<link rel="preconnect" href="https://fonts.googleapis.com">';
            echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>';
            echo "<link href='https://fonts.googleapis.com/css2?family={$font}:wght@100;200;300;400;500;600;700;800;900&display=swap' rel='stylesheet'>";
        }
    }
}
add_action('wp_head', 'load_custom_google_fonts');

function add_custom_font_style() {
    if (function_exists('get_field')) {
        $font = get_field('font', 'option');
        if ($font) {
            echo "<style>:root { --custom-font: '{$font}', sans-serif; }</style>";
        }
    }
}
add_theme_support('title-tag');
add_action('wp_head', 'add_custom_font_style');


function custom_link_shortcode($atts) {
    $default_link = '/';
    $default_text = 'Перейти на сайт';
    $default_fit = 'no';
    
    $atts = shortcode_atts(
        array(
            'url' => $default_link,
            'text' => $default_text,
            'fit' => $default_fit
        ),
        $atts,
        'custom_link'
    );
    
    return '<div class="single_button"><a class="'. $atts['fit'] . ' button button-primary" target="_blank" href="' . esc_url($atts['url']) . '">' . esc_html($atts['text']) . '</a></div>';
}

add_shortcode('custom_button', 'custom_link_shortcode');


add_action('template_redirect', 'redirect_404_to_homepage');
function redirect_404_to_homepage() {
    if (is_404()) {
        wp_redirect(home_url(), 301);
        exit;
    }
}

add_filter('body_class', function($classes) {
    if (get_field('rtl_mode', 'option')) {
        $classes[] = 'rtl';
    }
    return $classes;
});