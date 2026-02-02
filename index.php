<?php
get_header(); ?>

	<style>
		body {
			font-family: var(--custom-font), sans-serif;
		}
	</style>
    <main>
        <section class="hero">
            <div class="container">
                <div class="hero__text">
                    <h1><?php the_field('title'); ?></h1>
					<?php the_field('text'); ?>
                    <span><?php the_field('subtitle'); ?></span>
                    <a href="<?php the_field('global_site_link', 'option'); ?>" class="button button-primary" target="_blank" title="Claim bonus"><?php the_field('link_title', 'option'); ?></a>
                </div>
                <div class="hero__img">
                    <img src="<?php the_field('image'); ?>" alt="Stake hero">
                </div>
            </div>
        </section>
		<?php if( have_rows('items') ): ?>
			<?php while( have_rows('items') ): the_row(); ?>
				<?php if( get_row_layout() == 'text_block' ): ?>
				<section class="text section">
					<div class="container">
						<?php the_sub_field('text'); ?>
					</div>
				</section>
				
				<?php elseif( get_row_layout() == 'card_items' ): ?>
					<section class="bonuses section">
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
						<section class="faq section">
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
										<p class="accordion__item-content"><?php the_sub_field('description'); ?></p>
									</li>
									<?php endwhile; ?>
								</ul>
							</div>
						</div>
						</section>
					<?php endif;?>
							
					
				<?php elseif( get_row_layout() == 'grid_block' ): ?>
					<?php if( have_rows('grid_items') ): ?>
						<section class="grid_block section">
							<div class="container">
								<?php while( have_rows('grid_items') ) : the_row(); ?>
								<div class="grid_block_wrapper">
									<img style="<?php echo (get_sub_field('image')) ? '' : 'visibility: hidden; height:0;' ?>" src="<?php the_sub_field('image'); ?>" />
									<div>
										<?php the_sub_field('content'); ?>
										<a href="<?php the_field('global_site_link', 'option'); ?>" class="button button-primary" target="_blank" title="Play"><?php the_field('link_title', 'option'); ?></a>
									</div>
								</div>
								<?php endwhile; ?>
							</div>
						</section>
					<?php endif;?>
							
					
				<?php endif; ?>
		
		
		
			<?php endwhile; ?>
		<?php endif; ?>
		
        
    </main>

<?php get_footer(); ?>