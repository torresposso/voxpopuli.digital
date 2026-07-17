<?php
/**
 * Element: Scrolling/Mobile Nav
 */
$fields = [
	[
		'name'    => 'header_mob_nav_scroll_menu',
		'label'   => esc_html__('Select Menu', 'bunyad-admin'),
		'desc'    => sprintf(
			'If none selected, will fallback to Mobile Navigation. <a href="%1$s" target="_blank">Create a new menu</a> and then refresh this page.',
			admin_url('nav-menus.php?action=edit&menu=0')
		),
		'value'   => '',
		'type'    => 'select',
		'style'   => 'inline-sm',
		'classes' => 'sep-bottom',
		'options' => function() {
			return wp_list_pluck(wp_get_nav_menus(), 'name', 'term_id');
		}
	],

	[
		'name'   => 'header_mob_nav_scroll_hov_style',
		'label'  => esc_html__('Menu Hover Style', 'bunyad-admin'),
		'desc'   => '',
		'type'   => 'select',
		'value'  => 'a',
		'options' => [
			'a' => esc_html__('A: Normal Color', 'bunyad-admin'),
			'b' => esc_html__('B: Line Below', 'bunyad-admin'),
		],
		'style' => 'inline-sm'
	],

	[
		'name'    => 'css_nav_scroll_font',
		'label'   => esc_html__('Menu Font Family', 'bunyad-admin'),
		'desc'    => '',
		'type'    => 'font-family',
		'value'   => '',
		'css'     => [
			// Also applies to small menu etc.
			'.navigation-scroll' => ['props' => ['font-family' => '%s']]
		],
	],

	[
		'name'             => 'css_nav_scroll_typo',
		'label'            => esc_html__('Top-Level Typography', 'bunyad-admin'),
		'desc'             => '',
		'type'             => 'group',
		'group_type'       => 'typography',
		'style'            => 'edit',
		'css'              => '.navigation-scroll .menu > li > a',
		'devices'          => false,
		'controls'         => ['family', 'size', 'weight', 'style', 'transform', 'spacing'],
		'controls_options' => [
			'size' => [
				'css' => [
					'{selector}' => [
						'all' => ['props' => ['font-size' => '%spx']],
					
						// Scale down with min of 10px.
						// 'large' => ['props' => ['font-size' => 'calc(10px + (%spx - 10px) * .7)'], 'value_key' => 'global']
					]
				]
			],
		],
	],
	[
		'name'    => 'css_nav_scroll_height',
		'label'   => esc_html__('Navigation Height', 'bunyad-admin'),
		'desc'    => '',
		'value'   => '',
		'type'    => 'number',
		'style'   => 'inline-sm',
		'css'     => [
			'.navigation-scroll' => ['props' => ['height' => '%spx']]
		]
	],
	[
		'name'    => 'css_nav_scroll_space',
		'label'   => esc_html__('Space Between Items', 'bunyad-admin'),
		'desc'    => '',
		'value'   => '',
		'type'    => 'number',
		'style'   => 'inline-sm',
		'css'     => [
			'.smart-head .navigation-scroll' => ['props' => ['--nav-items-space' => '%spx']]
		]
	],
	[
		'name'    => 'css_nav_scroll_color',
		'value'   => '',
		'label'   => esc_html__('Links Color', 'bunyad-admin'),
		'desc'    => '',
		'type'    => 'color-alpha',
		'style'   => 'inline-sm',
		'css'     => ['.s-light .navigation-scroll' => ['props' => ['--c-nav' => '%s']]],
	],
	[
		'name'    => 'css_nav_scroll_color_sd',
		'value'   => '',
		'label'   => esc_html__('Dark: Links Color', 'bunyad-admin'),
		'desc'    => '',
		'type'    => 'color-alpha',
		'style'   => 'inline-sm',
		'css'     => ['.s-dark .navigation-scroll' => ['props' => ['--c-nav' => '%s']]],
	],
	[
		'name'    => 'css_nav_scroll_hover',
		'value'   => '',
		'label'   => esc_html__('Top-level Hover', 'bunyad-admin'),
		'desc'    => '',
		'type'    => 'color',
		'style'   => 'inline-sm',
		'css'     => ['.s-light .navigation-scroll' => ['props' => ['--c-nav-hov' => '%s']]],
	],
	[
		'name'    => 'css_nav_scroll_hover_sd',
		'value'   => '',
		'label'   => esc_html__('Dark: Top-level Hover', 'bunyad-admin'),
		'desc'    => '',
		'type'    => 'color-alpha',
		'style'   => 'inline-sm',
		'css'     => ['.s-dark .navigation-scroll' => ['props' => ['--c-nav-hov' => '%s']]],
	],

	[
		'name'  => '_n_scroll_nav',
		'type'  => 'message',
		'label' => '',
		'text'  => 'Rest of Color settings are applied from the Main Navigation.',
		'style' => 'message-info',
	],
];

return $fields;