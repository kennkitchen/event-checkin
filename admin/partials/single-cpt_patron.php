<?php

get_header(); ?>

	<div id="main-content" class="main-content">

		<div id="primary" class="content-area">
			<div id="content" class="site-content" role="main">
				<h1>Custom Template Found! (Patron)</h1>
				<?php
				// Start the Loop.
				while ( have_posts() ) : the_post();

					// Include the page content template.
					get_template_part( 'content', 'post' );

				endwhile;
				?>
			</div><!-- #content -->
		</div><!-- #primary -->
	</div><!-- #main-content -->

<?php
get_sidebar();
get_footer();