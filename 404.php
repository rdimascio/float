<?php
/**
 * The 404 template file
 *
 * @package FloatWP
 */

$post = get_page_by_title( '404' ); //phpcs:ignore
setup_postdata( $post );

get_header(); ?>
	<main id="primary" class="site__main site__404">
		<div id="<?php echo esc_attr( float_post_slug() ); ?>" class="default-page">
			<div class="default-page__main-area">
				<div class="default-page__main-area-inner">
					<div class="default-page__content">
						<div class="default-page__body">
							<div class="entry-content entry-content--404">
								<?php the_content(); ?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</main>
<?php
get_footer();
