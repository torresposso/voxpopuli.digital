<?php
/**
 * Partial Template for Author Box on single pages
 */

$enabled = is_single() && Bunyad::options()->author_box;

// Pages can have author box enabled.
if (is_page() && Bunyad::posts()->meta('author_box')) {
	$enabled = true;
}

?>

<?php if ($enabled) : // author box? ?>

	<?php
	// If we have co-authors!
	if (function_exists('get_coauthors')):
		global $authordata;
		$coauthors = get_coauthors();
	?>
		<?php foreach ($coauthors as $coauthor): ?>
		<div class="author-box">
			<?php 
			// Set global $authordata to current co-author
			$authordata = $coauthor;
			get_template_part('partials/author'); 
			?>
		</div>
		<?php endforeach; ?>
	
	<?php else: // Normal single author. ?>
		<div class="author-box">
			<?php get_template_part('partials/author'); ?>
		</div>
	<?php endif; ?>

<?php endif; ?>