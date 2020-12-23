<?php

/**
 * Registers all block assets so that they can be enqueued through the block editor
 * in the corresponding context.
 *
 * @see https://developer.wordpress.org/block-editor/tutorials/block-tutorial/applying-styles-with-stylesheets/
 */
function activecampaign_form_block_init() {
	$dir = dirname( __FILE__ );

	$script_asset_path = "$dir/build/index.asset.php";
	if ( ! file_exists( $script_asset_path ) ) {
		throw new Error(
			'You need to run `npm start` or `npm run build` for the "activecampaign-form/activecampaign-form-block" block first.'
		);
	}
	$index_js     = 'build/index.js';
	$script_asset = require( $script_asset_path );
	wp_register_script(
		'activecampaign-form-block-editor',
		plugins_url( $index_js, __FILE__ ),
		$script_asset['dependencies'],
		$script_asset['version']
	);

	$editor_css = 'build/index.css';
	wp_register_style(
		'activecampaign-form-block-editor',
		plugins_url( $editor_css, __FILE__ ),
		array(),
		filemtime( "$dir/$editor_css" )
	);

	$style_css = 'build/style-index.css';
	wp_register_style(
		'activecampaign-form-block',
		plugins_url( $style_css, __FILE__ ),
		array(),
		filemtime( "$dir/$style_css" )
	);

	$block_attributes = [
		'formId'             => [
			'type'    => 'string',
		],
		'settings_activecampaign'       => [
			'type' => 'object',
			'default' => get_option("settings_activecampaign")
		],
	];

	register_block_type( 'activecampaign-form/activecampaign-form-block', [
		'editor_script' => 'activecampaign-form-block-editor',
		'editor_style'  => 'activecampaign-form-block-editor',
		'style'         => 'activecampaign-form-block',
		'attributes'    => $block_attributes
	] );


}
