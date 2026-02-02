<?php
 /*
 * The template for displaying the footer
 */
?>

    <footer class="footer">
        <div class="footer__top">
            <div class="container">
                <div class="footer__top-description">
                   	<?php the_custom_logo(); ?>
                    <?php the_field('footer_text', 'option'); ?>
                </div>
                <?php if( have_rows('footer_menu', 'option') ): ?>
                    <?php while( have_rows('footer_menu', 'option') ) : the_row(); ?>
                        <div class="footer__top-navigation">
                            <span><?php the_sub_field('title_menu'); ?></span>
                            <?php if( have_rows('menu_list') ): ?>
                            <ul>
                                <?php while( have_rows('menu_list') ) : the_row(); ?>
									<?php
									$url = get_sub_field('link')['url'];
									if (function_exists('pll_get_post')) {
										$post_id = url_to_postid($url);
										if ($post_id) {
											$translated = pll_get_post($post_id);
											if ($translated) {
												$url = get_permalink($translated);
											}
										}
									}
									?>
                                    <li><a href="<?php echo $url; ?>"><?php echo get_sub_field('link')['title']; ?></a></li>
                                <?php endwhile; ?>
                            </ul>
                            <?php endif;?>
                        </div>  
                    <?php endwhile; ?>
                <?php endif;?>
            </div>                
        </div>
        <div class="footer__copyright">
            <div class="container">
                <span>© 2025 HighFlyBet. All rights reserved</span>
                <?php if( have_rows('footer_logos', 'option') ): ?>
                    <ul>
                        <?php while( have_rows('footer_logos', 'option') ) : the_row(); ?>
                            <li>
                                <span>
                                    <img src="<?php the_sub_field('logo'); ?>">
                                </span>
                            </li>
                        <?php endwhile; ?>
                    </ul>
                <?php endif;?>
                </ul>
            </div>
        </div>
    </footer>
    
    <?php wp_footer(); ?>
    <script src="
    https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/js/splide.min.js
    "></script>
	<script>
		$(document).ready(function(){
			$('.sticky-cta__close').on('click', function(){
				$(this).parents('.sticky-cta').addClass('hide');
			});
            $('a[href*="#"]:not([href="#"])').on('click', function(event) {
                event.preventDefault();
                var target = $(this.hash);
                if (target.length) {
                    $('html, body').animate({
                        scrollTop: target.offset().top
                    }, 800);
                }
            });
			$('table').not('.text .overflow_table table').each(function() {
				$(this).wrap('<div class="text overflow_table"></div>');
			});
			var isRTL = document.body.classList.contains('rtl');
            var splide = new Splide('.block_slider .splide', {
                perPage  : 3,
                pagination: true,
				direction : isRTL ? 'rtl' : 'ltr',
                gap      : '16px',
                arrowPath: `M25.1009 8.80413L34.529 18.2322C35.5053 19.2085 35.5053 20.7914 34.529 21.7678L25.1009 31.1958C24.1246 32.1722 22.5417 32.1722 21.5654 31.1958C20.5891 30.2195 20.5891 28.6366 21.5654 27.6603L26.7257 22.5H7.5C6.11929 22.5 5 21.3807 5 20C5 18.6193 6.11929 17.5 7.5 17.5H26.7257L21.5654 12.3397C20.5891 11.3634 20.5891 9.78044 21.5654 8.80413C22.5417 7.82782 24.1246 7.82782 25.1009 8.80413Z`,
                breakpoints: {
                    768: { perPage: '1', pagination: true }
                }
            });
            splide.mount();
		});
	</script>


	<?php if(get_field('show_banner', 'option')) { ?>
    <div class="sticky-cta">
        <div class="container">
            <?php the_custom_logo(); ?>
            <span class="sticky-cta__text"><?php the_field('title_banner', 'option'); ?></span>
            <a href="<?php the_field('banner_link', 'option'); ?>" class="button button-secondary" target="_blank"><?php the_field('button_text_banner', 'option'); ?></a>
        </div>
        <button class="sticky-cta__close">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="#fff">
                <path
                    d="M6.28 5.22a.75.75 0 00-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 101.06 1.06L10 11.06l3.72 3.72a.75.75 0 101.06-1.06L11.06 10l3.72-3.72a.75.75 0 00-1.06-1.06L10 8.94 6.28 5.22z">
                </path>
            </svg>
        </button>
    </div>
	<?php } ?>
</body>
</html>