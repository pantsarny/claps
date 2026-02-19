<?php
/*
 * The header for our theme
 */

?>
<!DOCTYPE html>
<html lang="<?php the_field('lang', 'option'); ?>">

<head>
    <meta charset="<?php get_bloginfo('charset'); ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#3A2B62">
    <meta name="msapplication-navbutton-color" content="#3A2B62">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
	<link href="
	https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/css/splide.min.css
	" rel="stylesheet">
	
    <?php wp_head(); ?>

	<style>
		:root {
			--header-bg-color: <?php the_field('header_background_color', 'option'); ?>;
			--footer-bg-color: <?php the_field('footer_background_color', 'option'); ?>;
			--banner-bg: <?php the_field('banner_background', 'option'); ?>;
			--buttons-color: <?php the_field('buttons_color', 'option'); ?>;
			--buttons-color-v2: <?php the_field('buttons_color_v2', 'option'); ?>;
            --background-color-body: <?= str_replace('##', '#', get_field('background_color_body', 'option')); ?>;
			--paragraph-color: <?php the_field('paragraph_color', 'option'); ?>;
			--h2h3h4-color: <?php the_field('h2h3h4_color', 'option'); ?>;
			--hero-bg: <?php the_field('hero_block_bg', 'option'); ?>;
			--faq-item-bg-color: <?php the_field('faq_item_bg_color', 'option'); ?>;
			--links-color: <?php the_field('links_color', 'option'); ?>;
			--links-color-content: <?php echo get_field('links_color_content', 'option') ?: get_field('links_color', 'option'); ?>;
			--table-background: <?php the_field('table_background', 'option'); ?>;
			--table-background-body: <?php echo get_field('table_background_body', 'option') ?: 'initial'; ?>;
		}
		.header {
			background: var(--header-bg-color);
		}
		a {
			color: var(--links-color) !important;
		}
        main a {
            color: var(--links-color-content) !important;
        }
		.accordion__item {
			background-color: var(--faq-item-bg-color) !important;
		}
		.hero {
			background-color: var(--hero-bg);
		}
		p {
			color: var(--paragraph-color) !important;
		}
		h2,h3,h4,h5 {
			color: var(--h2h3h4-color) !important;
		}
		.footer {
			background: var(--footer-bg-color);
		}
		.sticky-cta {
			background-color: var(--banner-bg);
		}
		.button--bright {
			background-color: var(--buttons-color-v2) !important;
		}
		.button {
			background: var(--buttons-color);
		}
		body {
			background-color: var(--background-color-body) !important;
		}
 		.text table thead {
			background-color: var(--table-background) !important;
		}
        .text table tbody {
            background-color: var(--table-background-body) !important;
        }
        body {
            background-color: var(--background-color-body) !important;
            display: block !important;
        }
        .hero .container, main {
            padding-top: 129px !important;
        }
        main {
            overflow: hidden;
        }
	</style>
</head>

<body <?php body_class(); ?>>
    <header class="header">
        <div class="container">
			<?php the_custom_logo(); ?>
            <nav class="header__menu">
				<?php 
					wp_nav_menu(
						array(
							'theme_location' => 'primary'
						)
					); 
				?>
            </nav>
            <div class="header__cta">
                <!-- <div class="header__bonus">
                    <button class="header__bonus-button" type="button">
                        <span>6</span>
                    </button>
                </div> -->
				<?php if(get_field('header_button_v', 'option')) { ?>
                	<a href="<?php echo get_field('header_button_v', 'option')['url']; ?>" class="button button-secondary" target="_blank"><?php echo get_field('header_button_v', 'option')['title']; ?></a>
				<?php } else { ?>
					<a href="<?php echo get_field('global_link', 'option')['url']; ?>" class="button button-secondary" target="_blank"><?php echo get_field('global_link', 'option')['title']; ?></a>
				<?php } ?>
				<?php if(get_field('header_button_2', 'option')) { ?>
                	<a href="<?php echo get_field('header_button_2', 'option')['url']; ?>" class="button button-secondary" target="_blank"><?php echo get_field('header_button_2', 'option')['title']; ?></a>
				<?php } else { ?>
					<a href="<?php echo get_field('global_link', 'option')['url']; ?>" class="button button-secondary" target="_blank"><?php echo get_field('global_link', 'option')['title']; ?></a>
				<?php } ?>
            </div>
        </div>
    </header>