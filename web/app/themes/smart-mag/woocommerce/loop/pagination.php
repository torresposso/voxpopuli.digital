<?php 
/**
 * Pagination - Show numbered pagination for catalog pages.
 * 
 * @version     9.3.0
 */
?>
	<div class="main-pagination pagination-numbers" aria-label="<?php esc_attr_e('Product Pagination', 'woocommerce'); ?>">
		<?php echo Bunyad::posts()->paginate(); ?>
	</div>