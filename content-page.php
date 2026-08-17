<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php
	/**
	 * Functions hooked in to vonaco_page action
	 *
	 * @see vonaco_page_header          - 10
	 * @see vonaco_page_content         - 20
	 *
	 */
	do_action( 'vonaco_page' );
	?>
</article><!-- #post-## -->
