<?php
/**
 * Elementor Integration for PDF.js Viewer
 *
 * Handles registration of the PDF.js Viewer widget with Elementor.
 * Only loads when Elementor is active.
 *
 * @package PDFjs_Viewer_Shortcode
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Register PDF.js Viewer Elementor widget.
 *
 * Runs on Elementor's widgets_registered action hook.
 * Validates that Elementor is active and the widget class can be loaded.
 *
 * @return void
 */
function pdfjs_register_elementor_widget() {
	// Check that Elementor widgets manager is available.
	if ( ! did_action( 'elementor/loaded' ) ) {
		return;
	}

	// Load the widget class.
	require_once plugin_dir_path( __FILE__ ) . 'elementor-widget.php';

	// Register the widget with Elementor.
	\Elementor\Plugin::instance()->widgets_manager->register( new PDFjs_Viewer_Elementor_Widget() );
}

/**
 * Check if Elementor is active.
 *
 * @return bool True if Elementor is active, false otherwise.
 */
function pdfjs_is_elementor_active() {
	return did_action( 'elementor/loaded' ) || class_exists( '\Elementor\Plugin' );
}

// Hook into Elementor's widgets registration if Elementor is available.
if ( did_action( 'elementor/loaded' ) ) {
	// If elementor/loaded has already been fired, register immediately.
	pdfjs_register_elementor_widget();
} else {
	// Otherwise wait for the elementor/loaded hook.
	add_action( 'elementor/widgets/register', 'pdfjs_register_elementor_widget' );
}

/**
 * Add Elementor widget category for better organization.
 *
 * @param object $elements_manager The widgets manager instance.
 * @return void
 */
function pdfjs_add_elementor_widget_category( $elements_manager ) {
	$elements_manager->add_category(
		'pdfjs',
		array(
			'title' => esc_html__( 'PDF.js Viewer', 'pdfjs-viewer-shortcode' ),
			'icon'  => 'fa fa-file-pdf',
		)
	);
}

// Add custom category - Use the element_category action.
add_action( 'elementor/elements/categories_registered', 'pdfjs_add_elementor_widget_category' );
