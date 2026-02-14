<?php
if( function_exists('acf_add_local_field_group') ):

acf_add_local_field_group(array(
	'key' => 'group_wos_hero_tier_details',
	'title' => 'Hero Tier Details',
	'fields' => array(
		array(
			'key' => 'field_generation',
			'label' => 'Generation',
			'name' => 'generation',
			'type' => 'number',
			'instructions' => 'Enter the Hero Generation (e.g., 1, 2, ...)',
			'required' => 1,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '25',
				'class' => '',
				'id' => '',
			),
			'default_value' => '',
			'placeholder' => '',
			'prepend' => 'Gen',
			'append' => '',
			'min' => 1,
			'max' => 20,
			'step' => 1,
		),
		array(
			'key' => 'field_troop_type',
			'label' => 'Troop Type',
			'name' => 'troop_type',
			'type' => 'select',
			'instructions' => 'Select the Hero\'s troop type',
			'required' => 1,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '25',
				'class' => '',
				'id' => '',
			),
			'choices' => array(
				'Infantry' => 'Infantry',
				'Lancer' => 'Lancer',
				'Marksman' => 'Marksman',
			),
			'default_value' => array(
			),
			'allow_null' => 0,
			'multiple' => 0,
			'ui' => 0,
			'return_format' => 'value',
			'ajax' => 0,
			'placeholder' => '',
		),
		array(
			'key' => 'field_overall_tier',
			'label' => 'Overall Tier',
			'name' => 'overall_tier',
			'type' => 'select',
			'instructions' => 'Select the Hero\'s Overall Tier',
			'required' => 1,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '25',
				'class' => '',
				'id' => '',
			),
			'choices' => array(
				'S+' => 'S+',
				'S' => 'S',
				'A' => 'A',
				'B' => 'B',
				'C' => 'C',
			),
			'default_value' => array(
			),
			'allow_null' => 0,
			'multiple' => 0,
			'ui' => 0,
			'return_format' => 'value',
			'ajax' => 0,
			'placeholder' => '',
		),
		array(
			'key' => 'field_special_role',
			'label' => 'Special Role',
			'name' => 'special_role',
			'type' => 'checkbox',
			'instructions' => 'Select special roles for this hero',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '25',
				'class' => '',
				'id' => '',
			),
			'choices' => array(
				'Rally' => 'Rally', // 集結
				'Defense' => 'Defense', // 防衛
				'Arena' => 'Arena', // 競技場
			),
			'allow_custom' => 0,
			'default_value' => array(
			),
			'layout' => 'horizontal', // or vertical
			'toggle' => 0,
			'return_format' => 'value',
		),
	),
	'location' => array(
		array(
			array(
				'param' => 'post_type',
				'operator' => '==',
				'value' => 'wos_hero',
			),
		),
	),
	'menu_order' => 0,
	'position' => 'normal',
	'style' => 'default',
	'label_placement' => 'top',
	'instruction_placement' => 'label',
	'hide_on_screen' => '',
	'active' => true,
	'description' => '',
));

endif;
