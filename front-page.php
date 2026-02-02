<?php
/*
Template Name: Front Page Template
*/

get_header(); ?>

	<style>
		body {
			font-family: var(--custom-font), sans-serif;
		}
	</style>
    <main>
		
		
		
		
	

		<?php if( have_rows('items') ): ?>
			<?php while( have_rows('items') ): the_row(); ?>
				<?php if( get_row_layout() == 'text_block' ): ?>
				<section class="text section" <?php echo (get_sub_field('block_id') ? 'id=' . '"' . get_sub_field('block_id') . '"' : '') ?>>
					<div class="container">
						<?php the_sub_field('text'); ?>
					</div>
				</section>

				<?php elseif( get_row_layout() == 'slider_block' ): ?>

				<section class="section block_slider" <?php echo (get_sub_field('block_id') ? 'id=' . '"' . get_sub_field('block_id') . '"' : '') ?>>
					<div class="container text">
						<?php the_sub_field('content'); ?>
						<div class="splide">
							<div class="splide__track">
								<div class="block_slider_wrap splide__list">
									<?php if( have_rows('slider_items') ): ?>
										<?php while( have_rows('slider_items') ) : the_row(); ?>
										<div class="block_slider_item splide__slide">
											<img src="<?php the_sub_field('image'); ?>">
										</div>
										<?php endwhile; ?>
									<?php endif;?>
								</div>
							</div>
						</div>
					</div>
				</section>
		
				<?php elseif( get_row_layout() == 'spin_wheel' ): ?>

				<section class="fortune_block section">
					<script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.umd.js"></script>
					<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.css" />
					
					<div class="modal" id="spin_popup">
						<div class="spin_popup_title" style="color: <?php the_sub_field('text_1_color'); ?>;"><?php the_sub_field('text_1_modal'); ?></div>
						<div class="spin_popup_text" style="color: <?php the_sub_field('text_2_color'); ?>;"><?php the_sub_field('text_2_modal'); ?></div>
						<div class="spin_popup_win_text" style="color: <?php the_sub_field('text_3_color'); ?>;"><?php the_sub_field('text_3_modal'); ?></div>
						<a href="<?php echo get_sub_field('button')['link']; ?>" style="background-color: <?php echo the_sub_field('button_background_color'); ?> !important; color: <?php echo the_sub_field('button_color'); ?> !important;" class="spin_popup_button button button-secondary"><?php echo get_sub_field('button')['title']; ?></a>
					</div>

					<style>
						.fortune_block canvas {
							display: block;
							margin: 0 auto;
						}
						.modal {
							display: none;
						}
						.spin_popup_win_text {
							font-size: 46px;
    						text-align: center;
							color: #ff1919;
							margin-bottom: 15px;
							font-weight: bold;
						}
						.fortune_block_wrapper {
							display: grid;
							margin-left: auto;
							margin-right: auto;
							grid-template-columns: 1fr;
							align-items: center;
						}
						.spin_popup_text {
							font-size: 25px;
							text-align: center;
							margin-bottom: 15px;
							font-weight: 500;
						}
						@keyframes pulse {
						  0% {
							transform: scale(1);
						  }
						  70% {
							transform: scale(1.05);
						  }
						  100% {
							transform: scale(1);
						  }
						}
						#spin {
							position: absolute;
							top: 35%;
							transition: all .3s linear;
							font-size: 50px !important;
							left: 35%;
							animation: pulse 1.5s infinite;
							border-radius: 100%;
							height: 210px;
							width: 210px;
						}
						#spin.go {
							color: <?php the_sub_field('spin_button_color'); ?> !important;
							background: <?php the_sub_field('spin_button_background'); ?> !important;
						}
						#spin:not(.go) {
							background: <?php the_sub_field('spin_button_background_disabled'); ?> !important;
						}
						.fortune_block_item {
							max-width: 700px;
							margin: 0 auto;
							position: relative;
						}
						.spin_popup_title {
							font-size: 35px;
							margin-bottom: 15px;
							font-weight: 500;
							text-align: center;
						}
						#spin:not(.go) {
							animation: none;
							cursor: inherit;
						}
						.spin_popup_button {
							text-decoration: none;
							outline: none;
						}
						.fancybox__content {
							padding: 30px 20px;
							border-radius: 10px;
							background: <?php the_sub_field('background_color_popup'); ?> !important;
							max-width: 420px;
							width: 100%;
						}
						@media(max-width: 768px) {
							.fortune_block canvas {
								width: 100% !important;
								max-width: 360px !important;
								height: 100% !important;
								max-height: 360px;
							}
							#spin {
								font-size: 29px !important;
								width: 95px;
								left: 37% !important;
								top: 37% !important;
								height: 95px;
							}
						}
					</style>

					<?php
					$acf_data = get_sub_field('items_spin'); // это твой массив из ACF
					$bgcolors = array_column($acf_data, 'color');
					
					$slices = [];

					foreach ($acf_data as $item) {
						$slice = [
							'label' => $item['title'],
							'chance' => (int) $item['chance_%']
						];

						// Если есть изображение или цвет — добавляем style
						if (!empty($item['image'])) {

							if (!empty($item['image'])) {
								$slice['style']['text']['image'] = $item['image'];
								$slice['style']['text']['image_width'] = 20;
								$slice['style']['text']['image_height'] = 20;
								$slice['style']['text']['color'] = $item['color_text'];
							}
						}

						$slices[] = $slice;
					}
					?>
					<script>
					$(function () {
						const slices = <?= json_encode($slices, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE); ?>;
						console.log(slices);
						if (localStorage.getItem('wheel_spinned') === 'true') {
							$('#spin').removeClass('go'); // Скрываем кнопку
						}
					  SpinWheelSkins['myskin'] = $.extend({}, SpinWheelSkins.default);
					  SpinWheelSkins['myskin'].innerCircle[1]['color'] = '<?php echo get_sub_field('inner_circle_color'); ?>';
					  SpinWheelSkins['myskin'].innerCircle[1]['image'] = '<?php echo get_sub_field('inner_circle_image'); ?>';
					  SpinWheelSkins['myskin']['slice']['bgcolors'] = <?= json_encode($bgcolors); ?>;
					  SpinWheelSkins['myskin']['slice']['text']['color'] = '#ffffff';
					  SpinWheelSkins['myskin']['slice']['text']['font_size'] = '<?php echo get_sub_field('font_size'); ?>';
					  SpinWheelSkins['myskin'].outerCircle['color'] = '<?php echo get_sub_field('background_color'); ?>';
					  SpinWheelSkins['myskin']['arrow']['color'][0] = '<?php echo get_sub_field('arrow_color_1'); ?>';
					  SpinWheelSkins['myskin']['arrow']['color'][1] = '<?php echo get_sub_field('arrow_color_2'); ?>';

					  $("#spinwheel").spinWheel({
						slices: slices,
						skin: 'myskin',
						radius: 350,
						tick_sound: '<?php echo get_sub_field('tick_sound'); ?>',
						duration: <?php echo get_sub_field('duration'); ?>,
						speed: <?php echo get_sub_field('speed'); ?>,
						onStop: function (idx, slice) {
						  Fancybox.show([{ src: "#spin_popup", type: "inline" }]);
							$('#spin').fadeOut(300, function () {
								$(this).removeClass('go');
							});
							localStorage.setItem('wheel_spinned', 'true');
						},
					  	onTick: function(ids, we) {
							console.log(ids, we);
						}
					  });
						$(document).on('click', '#spin.go', function(){
							$("#spinwheel").spinWheel().spin();
							$(this).removeClass('go');
						})
					});
					</script>

					<div class="container">
						<?php the_sub_field('content_before_spinwheel'); ?>
						<div class="fortune_block_wrapper">
							<div class="fortune_block_item">
								<div id="spinwheel"></div>
								<span class="button button-secondary go" id="spin">Spin</span>
							</div>
						</div>

					</div>
				</section>

				<?php elseif( get_row_layout() == 'list_image_text_link' ): ?>

				<section class="section block_with_links_logo" <?php echo (get_sub_field('block_id') ? 'id=' . '"' . get_sub_field('block_id') . '"' : '') ?>>
					<div class="container">
						<?php the_sub_field('content'); ?>
						<div class="block_with_links_logo_wrap">
							<?php if( have_rows('list') ): ?>
								<?php while( have_rows('list') ) : the_row(); ?>
								<a href="<?php the_sub_field('link'); ?>" target="_blank" class="block_with_links_logo_item">
									<img src="<?php the_sub_field('image'); ?>">
									<div class="block_with_links_logo_item_title">
										<?php the_sub_field('title'); ?>
									</div> 
								</a>
								<?php endwhile; ?>
							<?php endif;?>
						</div>
					</div>
				</section>
		
				<?php elseif( get_row_layout() == 'hero_block_v2' ): ?>
					<style>
						.app-bonus-inner__app {
							background: <?php the_sub_field('background_color_1'); ?>;
						}
						html body .app-bonus-inner__app table {
							background-color: <?php the_sub_field('background_color_2'); ?>;
						}
						.app-bonus-inner__app-content .btn {
							background-color: <?php the_sub_field('buttons_color'); ?>;
							color: <?php the_sub_field('buttons_text_color'); ?> !important;
						}
						.app-bonus-inner__app-descr p {
							color: <?php the_sub_field("text_color"); ?> !important;
						}
						.app-bonus-inner__app-content .text table td {
							color: <?php the_sub_field('text_2_color'); ?>;
						}
						
					</style>
					<section class="section">
						<div class="container">
							<div class="app-bonus-inner__app">
								<img decoding="async" width="173" height="345" src="<?php the_sub_field("image"); ?>" class="attachment-full size-full">
								<div class="app-bonus-inner__app-content">
									<div class="app-bonus-inner__app-descr">
										<?php the_sub_field("text"); ?>
									</div>
									<table>
										<tbody>
											<tr class="app-bonus-inner__app-list">
												<?php if( have_rows('items_hero_v2') ): ?>
													<?php while( have_rows('items_hero_v2') ) : the_row(); ?>
													<td class="app-bonus-inner__app-item">
														<span><?php the_sub_field('title'); ?></span><?php the_sub_field('text'); ?>
													</td>
													<?php endwhile; ?>
												<?php endif;?>
											</tr>
										</tbody>
									</table>
									<div class="app-bonus-inner__app-links">
										<div class="buttons_container">
											<?php if(get_sub_field('adnroid_link')) { ?>
										<a href="<?php echo get_sub_field('adnroid_link'); ?>" class="btn accent google_play fn_link_href"> <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" viewBox="0 0 25 25" fill="currentColor">
											<path fill-rule="evenodd" clip-rule="evenodd" d="M6.56661 8.96631H17.933V17.9436C17.933 18.4763 17.487 18.9094 16.9374 18.9094H15.7988V21.6431C15.8014 21.9738 15.6684 22.292 15.4291 22.5276C15.1898 22.7633 14.8637 22.8973 14.5225 22.9C14.3535 22.8989 14.1863 22.8655 14.0305 22.8017C13.8748 22.7379 13.7335 22.645 13.6148 22.5282C13.4961 22.4115 13.4023 22.2733 13.3388 22.1214C13.2753 21.9695 13.2432 21.807 13.2445 21.6431V18.9094H11.256V21.6431C11.2573 21.807 11.2252 21.9695 11.1617 22.1214C11.0982 22.2733 11.0044 22.4115 10.8857 22.5282C10.767 22.645 10.6257 22.7379 10.47 22.8017C10.3142 22.8655 10.147 22.8989 9.97796 22.9C9.80895 22.8988 9.64185 22.8653 9.4862 22.8014C9.33055 22.7376 9.1894 22.6447 9.07081 22.5279C8.95221 22.4112 8.8585 22.273 8.79502 22.1212C8.73153 21.9694 8.69953 21.807 8.70082 21.6431V18.9094H7.56311C7.01347 18.9094 6.56661 18.4763 6.56661 17.9436V8.96631ZM4.33945 8.89268C3.62715 8.89268 3.0498 9.46091 3.0498 10.1625V15.1233C3.0498 15.8231 3.62715 16.3922 4.33945 16.3922C5.05175 16.3922 5.62909 15.8231 5.62909 15.1233V10.1625C5.62909 9.46091 5.05175 8.89268 4.33945 8.89268ZM17.933 8.06113H6.56661C6.70693 6.4933 7.75795 5.1429 9.27013 4.36072L8.18872 2.82148C8.15189 2.77219 8.12565 2.71623 8.11154 2.65693C8.09743 2.59763 8.09575 2.53619 8.10659 2.47624C8.11743 2.4163 8.14058 2.35907 8.17466 2.30796C8.20873 2.25684 8.25305 2.21288 8.30498 2.17866C8.35691 2.14445 8.4154 2.12069 8.47698 2.10879C8.53856 2.09689 8.60198 2.09708 8.66348 2.10937C8.72498 2.12166 8.78331 2.14578 8.83501 2.18032C8.88671 2.21485 8.93074 2.2591 8.96447 2.31042L10.1424 3.98739C11.5078 3.52886 12.9927 3.52886 14.3581 3.98739L15.536 2.31042C15.5707 2.26111 15.615 2.21889 15.6664 2.18618C15.7179 2.15346 15.7755 2.13089 15.8359 2.11975C15.8964 2.10861 15.9585 2.10912 16.0188 2.12125C16.079 2.13338 16.1362 2.1569 16.1871 2.19045C16.238 2.22401 16.2815 2.26695 16.3153 2.31682C16.3491 2.3667 16.3723 2.42252 16.3838 2.48112C16.3953 2.53972 16.3948 2.59993 16.3823 2.65833C16.3698 2.71673 16.3455 2.77217 16.3109 2.82148L15.2313 4.35985C16.7435 5.14117 17.7927 6.49071 17.933 8.06113ZM10.4811 5.92941C10.4812 5.76791 10.4152 5.61298 10.2974 5.4987C10.1797 5.38442 10.0199 5.32015 9.85328 5.32004C9.68665 5.31992 9.5268 5.38397 9.40888 5.49808C9.29097 5.6122 9.22467 5.76704 9.22455 5.92854C9.22455 6.09004 9.29074 6.24493 9.40857 6.35913C9.5264 6.47332 9.6862 6.53748 9.85284 6.53748C10.0195 6.53748 10.1793 6.47332 10.2971 6.35913C10.4149 6.24493 10.4811 6.09091 10.4811 5.92941ZM15.3555 5.92941C15.3555 5.84933 15.3392 5.77003 15.3076 5.69605C15.276 5.62206 15.2296 5.55484 15.1712 5.49821C15.1128 5.44158 15.0434 5.39667 14.9671 5.36602C14.8908 5.33538 14.8089 5.3196 14.7263 5.3196C14.6437 5.3196 14.5619 5.33538 14.4855 5.36602C14.4092 5.39667 14.3398 5.44158 14.2814 5.49821C14.223 5.55484 14.1766 5.62206 14.145 5.69605C14.1134 5.77003 14.0971 5.84933 14.0971 5.92941C14.0971 6.09114 14.1634 6.24624 14.2814 6.3606C14.3994 6.47497 14.5594 6.53921 14.7263 6.53921C14.8932 6.53921 15.0532 6.47497 15.1712 6.3606C15.2892 6.24624 15.3555 6.09114 15.3555 5.92941ZM20.1619 8.89181C19.4496 8.89181 18.8714 9.46004 18.8714 10.1617V15.1241C18.8714 15.8257 19.4496 16.394 20.1619 16.394C20.8742 16.394 21.4498 15.8249 21.4498 15.1241V10.1608C21.4498 9.46004 20.8742 8.89181 20.1619 8.89181Z"></path>
											</svg> <span> <span>Download</span> <span>For Android</span> </span> 
										</a> 
										<?php } ?>
										<?php if(get_sub_field('ios_link')) { ?>
										<a href="<?php the_sub_field("get_sub_field('ios_link')"); ?>" class="btn accent app_store fn_link_href"> <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" viewBox="0 0 25 25" fill="currentColor">
												<path d="M18.3477 22.0655C17.2622 23.1631 16.077 22.9898 14.9361 22.4699C13.7288 21.9384 12.6211 21.9153 11.3473 22.4699C9.75225 23.1862 8.91043 22.9783 7.95784 22.0655C2.55245 16.2538 3.34997 7.40334 9.48641 7.07982C10.9818 7.1607 12.023 7.93482 12.898 8.00415C14.2051 7.72685 15.4567 6.92962 16.8524 7.03361C18.5249 7.17225 19.7877 7.8655 20.6184 9.11334C17.1625 11.274 17.9822 16.0227 21.1501 17.3514C20.5187 19.0845 19.6991 20.8061 18.3366 22.077L18.3477 22.0655ZM12.7872 7.0105C12.6211 4.43393 14.626 2.30798 16.9299 2.10001C17.2511 5.08096 14.338 7.29935 12.7872 7.0105Z"></path>
											</svg> <span> <span>Download</span> <span>For iOS</span> </span> 
										</a>
										<?php } ?>
									</div>

									</div>
								</div>
							</div>
						</div>
					</section>

				<?php elseif( get_row_layout() == 'mobile_application_list_with_image' ): ?>

				<section class="section image_card_block" <?php echo (get_sub_field('block_id') ? 'id=' . '"' . get_sub_field('block_id') . '"' : '') ?>>
					<div class="container text">
						<?php the_sub_field('content'); ?>
						<div class="image_card_block_wrap">
							<div class="image_card_block_i">
								<?php if( have_rows('list') ): ?>
								<?php while( have_rows('list') ) : the_row(); ?>
									<a href="<?php the_sub_field('link'); ?>">
										<img src="<?php the_sub_field('image'); ?>">
									</a>
								<?php endwhile; ?>
								<?php endif;?>
							</div>
						</div>
					</div>
				</section>

				<?php elseif( get_row_layout() == 'mobile_application_block' ): ?>

				<section class="section banner_app" <?php echo (get_sub_field('block_id') ? 'id=' . '"' . get_sub_field('block_id') . '"' : '') ?>>
					<div class="container">
					<div class="apps_banner">
						<div class="title"><?php the_sub_field('title'); ?></div>
						<div class="description"><?php the_sub_field('description'); ?></div>
						<div class="buttons_container"> 
							<a href="<?php the_sub_field('android_download_link'); ?>" class="btn accent google_play fn_link_href"> <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" viewBox="0 0 25 25" fill="currentColor">
								<path fill-rule="evenodd" clip-rule="evenodd" d="M6.56661 8.96631H17.933V17.9436C17.933 18.4763 17.487 18.9094 16.9374 18.9094H15.7988V21.6431C15.8014 21.9738 15.6684 22.292 15.4291 22.5276C15.1898 22.7633 14.8637 22.8973 14.5225 22.9C14.3535 22.8989 14.1863 22.8655 14.0305 22.8017C13.8748 22.7379 13.7335 22.645 13.6148 22.5282C13.4961 22.4115 13.4023 22.2733 13.3388 22.1214C13.2753 21.9695 13.2432 21.807 13.2445 21.6431V18.9094H11.256V21.6431C11.2573 21.807 11.2252 21.9695 11.1617 22.1214C11.0982 22.2733 11.0044 22.4115 10.8857 22.5282C10.767 22.645 10.6257 22.7379 10.47 22.8017C10.3142 22.8655 10.147 22.8989 9.97796 22.9C9.80895 22.8988 9.64185 22.8653 9.4862 22.8014C9.33055 22.7376 9.1894 22.6447 9.07081 22.5279C8.95221 22.4112 8.8585 22.273 8.79502 22.1212C8.73153 21.9694 8.69953 21.807 8.70082 21.6431V18.9094H7.56311C7.01347 18.9094 6.56661 18.4763 6.56661 17.9436V8.96631ZM4.33945 8.89268C3.62715 8.89268 3.0498 9.46091 3.0498 10.1625V15.1233C3.0498 15.8231 3.62715 16.3922 4.33945 16.3922C5.05175 16.3922 5.62909 15.8231 5.62909 15.1233V10.1625C5.62909 9.46091 5.05175 8.89268 4.33945 8.89268ZM17.933 8.06113H6.56661C6.70693 6.4933 7.75795 5.1429 9.27013 4.36072L8.18872 2.82148C8.15189 2.77219 8.12565 2.71623 8.11154 2.65693C8.09743 2.59763 8.09575 2.53619 8.10659 2.47624C8.11743 2.4163 8.14058 2.35907 8.17466 2.30796C8.20873 2.25684 8.25305 2.21288 8.30498 2.17866C8.35691 2.14445 8.4154 2.12069 8.47698 2.10879C8.53856 2.09689 8.60198 2.09708 8.66348 2.10937C8.72498 2.12166 8.78331 2.14578 8.83501 2.18032C8.88671 2.21485 8.93074 2.2591 8.96447 2.31042L10.1424 3.98739C11.5078 3.52886 12.9927 3.52886 14.3581 3.98739L15.536 2.31042C15.5707 2.26111 15.615 2.21889 15.6664 2.18618C15.7179 2.15346 15.7755 2.13089 15.8359 2.11975C15.8964 2.10861 15.9585 2.10912 16.0188 2.12125C16.079 2.13338 16.1362 2.1569 16.1871 2.19045C16.238 2.22401 16.2815 2.26695 16.3153 2.31682C16.3491 2.3667 16.3723 2.42252 16.3838 2.48112C16.3953 2.53972 16.3948 2.59993 16.3823 2.65833C16.3698 2.71673 16.3455 2.77217 16.3109 2.82148L15.2313 4.35985C16.7435 5.14117 17.7927 6.49071 17.933 8.06113ZM10.4811 5.92941C10.4812 5.76791 10.4152 5.61298 10.2974 5.4987C10.1797 5.38442 10.0199 5.32015 9.85328 5.32004C9.68665 5.31992 9.5268 5.38397 9.40888 5.49808C9.29097 5.6122 9.22467 5.76704 9.22455 5.92854C9.22455 6.09004 9.29074 6.24493 9.40857 6.35913C9.5264 6.47332 9.6862 6.53748 9.85284 6.53748C10.0195 6.53748 10.1793 6.47332 10.2971 6.35913C10.4149 6.24493 10.4811 6.09091 10.4811 5.92941ZM15.3555 5.92941C15.3555 5.84933 15.3392 5.77003 15.3076 5.69605C15.276 5.62206 15.2296 5.55484 15.1712 5.49821C15.1128 5.44158 15.0434 5.39667 14.9671 5.36602C14.8908 5.33538 14.8089 5.3196 14.7263 5.3196C14.6437 5.3196 14.5619 5.33538 14.4855 5.36602C14.4092 5.39667 14.3398 5.44158 14.2814 5.49821C14.223 5.55484 14.1766 5.62206 14.145 5.69605C14.1134 5.77003 14.0971 5.84933 14.0971 5.92941C14.0971 6.09114 14.1634 6.24624 14.2814 6.3606C14.3994 6.47497 14.5594 6.53921 14.7263 6.53921C14.8932 6.53921 15.0532 6.47497 15.1712 6.3606C15.2892 6.24624 15.3555 6.09114 15.3555 5.92941ZM20.1619 8.89181C19.4496 8.89181 18.8714 9.46004 18.8714 10.1617V15.1241C18.8714 15.8257 19.4496 16.394 20.1619 16.394C20.8742 16.394 21.4498 15.8249 21.4498 15.1241V10.1608C21.4498 9.46004 20.8742 8.89181 20.1619 8.89181Z"></path>
								</svg> <span> <span>Download</span> <span>For Android</span> </span> 
							</a> 
							<a href="<?php the_sub_field('ios_download_link'); ?>" class="btn accent app_store fn_link_href"> <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" viewBox="0 0 25 25" fill="currentColor">
									<path d="M18.3477 22.0655C17.2622 23.1631 16.077 22.9898 14.9361 22.4699C13.7288 21.9384 12.6211 21.9153 11.3473 22.4699C9.75225 23.1862 8.91043 22.9783 7.95784 22.0655C2.55245 16.2538 3.34997 7.40334 9.48641 7.07982C10.9818 7.1607 12.023 7.93482 12.898 8.00415C14.2051 7.72685 15.4567 6.92962 16.8524 7.03361C18.5249 7.17225 19.7877 7.8655 20.6184 9.11334C17.1625 11.274 17.9822 16.0227 21.1501 17.3514C20.5187 19.0845 19.6991 20.8061 18.3366 22.077L18.3477 22.0655ZM12.7872 7.0105C12.6211 4.43393 14.626 2.30798 16.9299 2.10001C17.2511 5.08096 14.338 7.29935 12.7872 7.0105Z"></path>
								</svg> <span> <span>Download</span> <span>For iOS</span> </span> 
							</a> 
						</div>
						<div class="image">
							<div itemtype="https://schema.org/ImageObject" class="get-image schema-image-object-thumbnail">
								<img width="246" height="450" src="<?php the_sub_field('image'); ?>" class="attachment-medium size-medium" loading="lazy" decoding="async">
							</div>
						</div>
					</div>
					</div>
				</section>

				<style>
					.apps_banner {
						background: <?php the_sub_field('background_color'); ?>;
					}
					.apps_banner .btn {
						background: <?php the_sub_field('buttons_color'); ?>;
					}
				</style>

				<?php elseif( get_row_layout() == 'advantages_block' ): ?>

				<section class="section advantages" <?php echo (get_sub_field('block_id') ? 'id=' . '"' . get_sub_field('block_id') . '"' : '') ?>>
					<div class="container">
						<?php the_sub_field('text_adv'); ?>
						<div class="advantages_items">
							<?php if( have_rows('items_adv') ): ?>
							<?php while( have_rows('items_adv') ) : the_row(); ?>
								<div class="advantages_item">
									<img src="<?php the_sub_field('image_adv'); ?>">
									<?php the_sub_field('content_adv'); ?>
								</div>
							<?php endwhile; ?>
							<?php endif;?>
						</div>
					</div>
				</section>

				<?php elseif( get_row_layout() == 'сarousel_with_categories' ): ?>

				<section class="section mini_padding" <?php echo (get_sub_field('block_id') ? 'id=' . '"' . get_sub_field('block_id') . '"' : '') ?>>
					<div class="container">
					<ul class="categories__container">
						<?php if( have_rows('items_carousel') ): ?>
						<?php while( have_rows('items_carousel') ) : the_row(); ?>
							<li>
								<a href="<?php the_sub_field('link'); ?>" class="link" rel="nofollow">
									<div><div><?php the_sub_field('title'); ?></div></div>
									<img src="<?php the_sub_field('image'); ?>" loading="lazy" decoding="async">
								</a>
							</li>
						<?php endwhile; ?>
						<?php endif;?>
					</ul>
					</div>
				</section>

				<style>
					.categories__container .link {
						background: <?php the_sub_field('item_background'); ?>;
					}
				</style>

				<?php elseif( get_row_layout() == 'banner_block_with_text' ): ?>

				<section class="section bonus_block" <?php echo (get_sub_field('block_id') ? 'id=' . '"' . get_sub_field('block_id') . '"' : '') ?>>
					<div class="container">
						<div class="bonus_block_wrap">
							<div class="bonus_block_item text">
								<?php the_sub_field('content'); ?>
							</div>
							<div class="bonus_block_item">
								<div class="bonus_block_item_w" style="background-image: url(<?php the_sub_field('background_image'); ?>);">
									<div class="bonus_block_item_w_title"><?php the_sub_field('title_1'); ?></div>
									<div class="bonus_block_item_w_subtitle"><?php the_sub_field('title_2'); ?></div>
									<?php the_sub_field('banner_text'); ?>
								</div>
							</div>
						</div>
					</div>
				</section>

				<style>
					.bonus_block_item_w {
						background-color: <?php the_sub_field('background_color'); ?>;
					}
					.bonus_block_item_w_subtitle {
						color: <?php the_sub_field('title_2_color'); ?>;
					}
				</style>

				<?php elseif( get_row_layout() == 'hero_block' ): ?>

				<section class="hero" <?php echo (get_sub_field('block_id') ? 'id=' . '"' . get_sub_field('block_id') . '"' : '') ?>>
					<div class="container">
						<div class="hero__text">
							<h1><?php the_sub_field('title'); ?></h1>
							<?php the_sub_field('text'); ?>
							<span><?php the_sub_field('subtitle'); ?></span>
						</div>
						<div class="hero__img">
							<img src="<?php the_sub_field('image'); ?>">
						</div>
					</div>
				</section>
				
				<?php elseif( get_row_layout() == 'card_items' ): ?>
					<section class="bonuses section" <?php echo (get_sub_field('block_id') ? 'id=' . '"' . get_sub_field('block_id') . '"' : '') ?>>
						<div class="container">
							<?php if( have_rows('items_w') ): ?>
								<ul class="bonuses-list">
								<?php while( have_rows('items_w') ) : the_row(); ?>
									<li class="bonuses-list__item">
										<div class="bonus">
											<div class="bonus__img">
												<img src="<?php the_sub_field('image'); ?>" alt="Welcome bonus">
											</div>
											<div class="bonus__description">
												<a href="<?php the_field('global_site_link', 'option'); ?>" class="button button-primary" target="_blank" title="Play"><?php the_field('link_title', 'option'); ?></a>
												<h3><?php the_sub_field('title'); ?></h3>
												<p><?php the_sub_field('text'); ?></p>
											</div>
										</div>
									</li>
								<?php endwhile; ?>
								</ul>
							<?php endif;?>
						</div>
					</section>
		
				<?php elseif( get_row_layout() == 'faq' ): ?>
					<?php $titleFAQ = get_sub_field('title'); ?>
					<?php if( have_rows('faq_items') ): ?>
						<section class="faq section" <?php echo (get_sub_field('block_id') ? 'id=' . '"' . get_sub_field('block_id') . '"' : '') ?>>
						<div class="container">
							<h2 class="faq__title title"><?php echo $titleFAQ; ?></h2>
							<div class="faq__accordion">
								<ul class="accordion">
									<?php while( have_rows('faq_items') ) : the_row(); ?>
									<li class="accordion__item">
										<div class="accordion__top">
											<h3 class="accordion__item-title"><?php the_sub_field('title'); ?></h3>
											<button class="accordion__item-button" type="button">
											</button>
										</div>
										<div class="accordion__item-content text"><?php the_sub_field('description'); ?></div>
									</li>
									<?php endwhile; ?>
								</ul>
							</div>
						</div>
						</section>
					<?php endif;?>
							
					
				<?php elseif( get_row_layout() == 'grid_block' ): ?>
					<?php if( have_rows('grid_items') ): ?>
						<section class="grid_block section" <?php echo (get_sub_field('block_id') ? 'id=' . '"' . get_sub_field('block_id') . '"' : '') ?>>
							<div class="container">
								<?php while( have_rows('grid_items') ) : the_row(); ?>
								<div class="grid_block_wrapper">
										<img style="<?php echo (get_sub_field('image')) ? '' : 'visibility: hidden; height:0;' ?>" src="<?php the_sub_field('image'); ?>" />
									<div>
										<?php the_sub_field('content'); ?>
										
									</div>
								</div>
								<?php endwhile; ?>
							</div>
						</section>
					<?php endif;?>
							
					
				<?php endif; ?>
		
		
		
			<?php endwhile; ?>
		<?php endif; ?>

		<?php
		 the_content(); 
		 ?>
		
		
		
        
    </main>

<?php get_footer(); ?>