<?php
/**
 * Fields for Grid Listings.
 */
$fields = [
	[
		'name'    => 'feat_grids_meta_above',
		'label'   => esc_html__('Meta: Items Above Title', 'bunyad-admin'),
		'desc'    => '',
		'value'   => ['cat'],
		'type'    => 'checkboxes',
		'options' => $_common['meta_options'],
		// Not a global style, specific to checkboxes.
		'style'   => 'sortable',
	],
	[
		'name'    => 'feat_grids_meta_below',
		'label'   => esc_html__('Meta: Items Below Title', 'bunyad-admin'),
		'desc'    => '',
		'value'   => ['author', 'date'],
		'type'    => 'checkboxes',
		'options' => $_common['meta_options'],
		// Not a global style, specific to checkboxes.
		'style'   => 'sortable',
	],
	[
		'name'  => 'feat_grids_meta_cat_style',
		'label' => esc_html__('Category Style', 'bunyad-admin'),
		'desc'  => esc_html__('Default category style. Note: This is not the category overlay.', 'bunyad-admin'),
		'value' => 'labels',
		'type'  => 'select',
		'style' => 'inline-sm',
		'options' => [
			'labels' => esc_html__('Label/Badge', 'bunyad-admin'),
			'text'   => esc_html__('Normal Text', 'bunyad-admin'),
		],
	],
	[
		'name'  => '_n_feat_grids',
		'type'  => 'message',
		'label' => '',
		'text'  => 'Many more options are available when adding the featured grid in Elementor Page Builder for your homepage.',
		'style' => 'message-info',
	],
];

return $fields;