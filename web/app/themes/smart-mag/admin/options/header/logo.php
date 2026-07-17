<?php
/**
 * Element: Logo
 */
$fields = [
	[
		'name'  => '_n_header_logo',
		'type'  => 'message',
		'label' => 'Upload Logos',
		'text'  => 'To upload logos, please go to <a href="#" class="focus-link is-with-nav" data-section="bunyad-logos">Site Logo</a> on main customizer screen.',
		'style' => 'message-info',
	],

	[
		'name'    => 'header_logo_home_h1',
		'label'   => esc_html__('Use H1 on Home Logo for SEO', 'bunyad-admin'),
		'value'   => 1,
		'desc'    => '',
		'style'   => 'inline-sm',
		'type'    => 'toggle',
	],

	[
		'name'    => 'css_header_logo_padding',
		'label'   => esc_html__('Logo Padding', 'bunyad-admin'),
		'desc'    => 'Only applies to image logo on non-mobile header.',
		'value'   => [],
		'type'    => 'dimensions',
		'devices' => ['main', 'large'],
		'css'     => [
			'.smart-head-main .logo-is-image' => ['dimensions' => 'padding']
		],
	],

	[
		'name'    => 'css_header_mob_logo_padding',
		'label'   => esc_html__('Mobile Logo Padding', 'bunyad-admin'),
		'desc'    => 'Only applies to image logo on mobile and small tablets.',
		'value'   => '',
		'devices' => ['main'], // pseudo
		'type'    => 'dimensions',
		'css'     => [
			'.smart-head-mobile .logo-mobile' => [
				'dimensions' => 'padding',
				'props' => [
					'--top-pad' => '{value:top}',
					'--bot-pad' => '{value:bottom}',
					'max-height' => 'calc(var(--head-h) - 1px * (var(--top-pad, 10) + var(--bot-pad, 10)))',
				]
			]

		],
	],
];

return $fields;