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


require_once ABSPATH . 'wp-admin/includes/file.php';
require_once ABSPATH . 'wp-admin/includes/media.php';
require_once ABSPATH . 'wp-admin/includes/image.php';

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
    wp_enqueue_style('main-style', get_template_directory_uri() . '/assets/css/style.min.css', array(), filemtime(get_template_directory() . '/assets/css/style.min.css'));
    wp_enqueue_style('style', get_stylesheet_uri(), [], filemtime(get_stylesheet_directory() . '/style.css'));
}

add_action('wp_enqueue_scripts', 'claps_scripts');
function claps_scripts(){
	wp_enqueue_script('jquery2015', get_template_directory_uri() . '/assets/js/jquery2015.js', array(), filemtime(get_template_directory() . '/assets/js/jquery2015.js'), false);
    wp_enqueue_script('script', get_template_directory_uri() . '/assets/js/script.min.js', array(), filemtime(get_template_directory() . '/assets/js/script.min.js'), false);
	wp_enqueue_script('spin1', get_template_directory_uri() . '/assets/js/spin1.js', array(), filemtime(get_template_directory() . '/assets/js/spin1.js'), false);
	wp_enqueue_script('spin2', get_template_directory_uri() . '/assets/js/spin2.js', array(), filemtime(get_template_directory() . '/assets/js/spin2.js'), false);
}

add_action('after_setup_theme', 'menus');
function menus()
{
    register_nav_menus([
        'primary' => 'Primary menu',
		'lang' => 'Lang menu',
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

function shortcode_chat_btn($atts) {

	$atts = shortcode_atts([
		'url'  => '#',
		'text' => 'Chat'
	], $atts);

	ob_start();
	?>
	<a href="<?php echo esc_url($atts['url']); ?>" class="button button-secondary chat-btn" target="_blank">
		<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
			<path d="M8 10.5H16" stroke="#1C274C" stroke-width="1.5" stroke-linecap="round"/>
			<path d="M8 14H13.5" stroke="#1C274C" stroke-width="1.5" stroke-linecap="round"/>
			<path d="M17 3.33782C15.5291 2.48697 13.8214 2 12 2C6.47715 2 2 6.47715 2 12C2 13.5997 2.37562 15.1116 3.04346 16.4525C3.22094 16.8088 3.28001 17.2161 3.17712 17.6006L2.58151 19.8267C2.32295 20.793 3.20701 21.677 4.17335 21.4185L6.39939 20.8229C6.78393 20.72 7.19121 20.7791 7.54753 20.9565C8.88837 21.6244 10.4003 22 12 22C17.5228 22 22 17.5228 22 12C22 10.1786 21.513 8.47087 20.6622 7"
			      stroke="#1C274C" stroke-width="1.5" stroke-linecap="round"/>
		</svg>
		<?php echo esc_html($atts['text']); ?>
	</a>
	<?php
	return ob_get_clean();
}

add_shortcode('chat_btn','shortcode_chat_btn');




function import_remote_gallery($json_url, $option_name) {

    if (get_option($option_name)) {
        return;
    }

    $response = wp_remote_get($json_url, ['timeout' => 20]);
    if (is_wp_error($response)) return;

    $data = json_decode(wp_remote_retrieve_body($response), true);
    if (!is_array($data)) return;

    require_once ABSPATH . 'wp-admin/includes/file.php';
    require_once ABSPATH . 'wp-admin/includes/media.php';
    require_once ABSPATH . 'wp-admin/includes/image.php';

    $ids = [];

    foreach ($data as $item) {

        // 🔴 ОГРАНИЧЕНИЕ — НЕ БОЛЬШЕ 6
        if (count($ids) >= 6) {
            break;
        }

        if (empty($item['img'])) continue;

        if (empty($item['titles']) || !is_array($item['titles'])) continue;
        $selected_title = trim($item['titles'][array_rand($item['titles'])]);

        $slug = sanitize_title($selected_title);

        $tmp = download_url($item['img']);
        if (is_wp_error($tmp)) continue;

        $ext = pathinfo(parse_url($item['img'], PHP_URL_PATH), PATHINFO_EXTENSION);
        if (!$ext) $ext = 'jpg';

        $file_array = [
            'name'     => $slug . '-slot' . '.' . $ext,
            'tmp_name' => $tmp,
        ];

        $image_id = media_handle_sideload($file_array, 0, $selected_title);

        if (is_wp_error($image_id)) {
            @unlink($tmp);
            continue;
        }

        wp_update_post([
            'ID'         => $image_id,
            'post_title' => $selected_title,
            'post_name'  => $slug,
        ]);

        if (!empty($item['items']) && is_array($item['items'])) {
            update_post_meta(
                $image_id,
                '_selected_item',
                trim($item['items'][array_rand($item['items'])])
            );
        }

        update_post_meta($image_id, '_selected_title', $selected_title);

        $ids[] = $image_id;
    }

    shuffle($ids);

    update_option($option_name, $ids, false);
}







add_action('init', function () {
    if (current_user_can('administrator') && isset($_GET['import_gallery'])) {
        import_remote_gallery('https://cultivatecounselingcenter.com/set1.json', 'remote_set1_ids');
        import_remote_gallery('https://cultivatecounselingcenter.com/set2.json', 'remote_set2_ids');
        exit('IMPORT DONE');
    }
});

// https://site.com/wp-admin/?import_gallery=1


add_action('init', function() {
    if (isset($_GET['reset_gallery']) && current_user_can('administrator')) {
        delete_option('remote_set1_ids');
        delete_option('remote_set2_ids');
        exit('Gallery reset done');
    }
});
