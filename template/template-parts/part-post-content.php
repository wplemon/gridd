<?php
/**
 * The content.
 *
 * @package Gridd
 * @since 1.0.3
 */

use Gridd\Theme;
?>
<div class="entry-content container">
	<?php Theme::get_template_part( 'template-parts/the-content' ); ?>
	<?php Theme::get_template_part( 'template-parts/link-pages' ); ?>
</div>
