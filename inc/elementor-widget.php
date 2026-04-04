<?php
/**
 * PDF.js Viewer Elementor Widget
 *
 * @package PDFjs_Viewer_Shortcode
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * PDF.js Viewer Elementor Widget Class
 */
final class PDFjs_Viewer_Elementor_Widget extends \Elementor\Widget_Base {

	/**
	 * Get widget name.
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'pdfjs-viewer';
	}

	/**
	 * Get widget title.
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return esc_html__( 'PDF.js Viewer', 'pdfjs-viewer-shortcode' );
	}

	/**
	 * Get widget icon.
	 *
	 * @return string Widget icon class name.
	 */
	public function get_icon() {
		return 'eicon-document-file';
	}

	/**
	 * Get widget categories.
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return array( 'basic' );
	}

	/**
	 * Get widget keywords.
	 *
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
		return array( 'pdf', 'viewer', 'embed', 'mozilla', 'pdfjs', 'document' );
	}

	/**
	 * Register widget controls.
	 *
	 * @return void
	 */
	protected function _register_controls() {
		// Content Section
		$this->start_controls_section(
			'section_content',
			array(
				'label' => esc_html__( 'PDF Content', 'pdfjs-viewer-shortcode' ),
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'attachment_id',
			array(
				'label'       => esc_html__( 'Select PDF', 'pdfjs-viewer-shortcode' ),
				'type'        => \Elementor\Controls_Manager::MEDIA,
				'media_type'  => array( 'image', 'video', 'audio', 'application' ),
				'description' => esc_html__( 'Select a PDF file from your media library.', 'pdfjs-viewer-shortcode' ),
			)
		);

		$this->end_controls_section();

		// Display Options Section
		$this->start_controls_section(
			'section_display',
			array(
				'label' => esc_html__( 'Display Options', 'pdfjs-viewer-shortcode' ),
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_responsive_control(
			'viewer_height',
			array(
				'label'       => esc_html__( 'Viewer Height', 'pdfjs-viewer-shortcode' ),
				'type'        => \Elementor\Controls_Manager::SLIDER,
				'size_units'  => array( 'px', 'em', 'rem', 'vh' ),
				'range'       => array(
					'px' => array(
						'min' => 300,
						'max' => 2000,
					),
				),
				'default'     => array(
					'unit' => 'px',
					'size' => 800,
				),
				'description' => esc_html__( 'Set the height of the PDF viewer.', 'pdfjs-viewer-shortcode' ),
			)
		);

		$this->add_responsive_control(
			'viewer_width',
			array(
				'label'       => esc_html__( 'Viewer Width', 'pdfjs-viewer-shortcode' ),
				'type'        => \Elementor\Controls_Manager::SLIDER,
				'size_units'  => array( 'px', '%', 'em', 'rem', 'vw' ),
				'range'       => array(
					'%'  => array(
						'min' => 1,
						'max' => 100,
					),
					'px' => array(
						'min' => 300,
						'max' => 2000,
					),
				),
				'default'     => array(
					'unit' => '%',
					'size' => 100,
				),
				'description' => esc_html__( 'Set the width of the PDF viewer. Leave at 100% for full width.', 'pdfjs-viewer-shortcode' ),
			)
		);

		$this->add_control(
			'zoom_level',
			array(
				'label'   => esc_html__( 'Default Zoom Level', 'pdfjs-viewer-shortcode' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'options' => array(
					'auto'      => esc_html__( 'Auto', 'pdfjs-viewer-shortcode' ),
					'page-fit'  => esc_html__( 'Fit Page', 'pdfjs-viewer-shortcode' ),
					'page-width' => esc_html__( 'Fit Width', 'pdfjs-viewer-shortcode' ),
					'50'        => '50%',
					'75'        => '75%',
					'100'       => '100%',
					'125'       => '125%',
					'150'       => '150%',
					'200'       => '200%',
				),
				'default' => 'auto',
			)
		);

		$this->end_controls_section();

		// Toolbar Section
		$this->start_controls_section(
			'section_toolbar',
			array(
				'label' => esc_html__( 'Toolbar Options', 'pdfjs-viewer-shortcode' ),
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'show_download',
			array(
				'label'       => esc_html__( 'Show Download Button', 'pdfjs-viewer-shortcode' ),
				'type'        => \Elementor\Controls_Manager::SWITCHER,
				'label_on'    => esc_html__( 'Yes', 'pdfjs-viewer-shortcode' ),
				'label_off'   => esc_html__( 'No', 'pdfjs-viewer-shortcode' ),
				'default'     => 'yes',
				'description' => esc_html__( 'Enable or disable the download button in the toolbar.', 'pdfjs-viewer-shortcode' ),
			)
		);

		$this->add_control(
			'show_print',
			array(
				'label'       => esc_html__( 'Show Print Button', 'pdfjs-viewer-shortcode' ),
				'type'        => \Elementor\Controls_Manager::SWITCHER,
				'label_on'    => esc_html__( 'Yes', 'pdfjs-viewer-shortcode' ),
				'label_off'   => esc_html__( 'No', 'pdfjs-viewer-shortcode' ),
				'default'     => 'yes',
				'description' => esc_html__( 'Enable or disable the print button in the toolbar.', 'pdfjs-viewer-shortcode' ),
			)
		);

		$this->add_control(
			'show_search',
			array(
				'label'       => esc_html__( 'Show Search Button', 'pdfjs-viewer-shortcode' ),
				'type'        => \Elementor\Controls_Manager::SWITCHER,
				'label_on'    => esc_html__( 'Yes', 'pdfjs-viewer-shortcode' ),
				'label_off'   => esc_html__( 'No', 'pdfjs-viewer-shortcode' ),
				'default'     => 'yes',
				'description' => esc_html__( 'Enable or disable the search functionality.', 'pdfjs-viewer-shortcode' ),
			)
		);

		$this->add_control(
			'show_editing',
			array(
				'label'       => esc_html__( 'Show Editing Buttons', 'pdfjs-viewer-shortcode' ),
				'type'        => \Elementor\Controls_Manager::SWITCHER,
				'label_on'    => esc_html__( 'Yes', 'pdfjs-viewer-shortcode' ),
				'label_off'   => esc_html__( 'No', 'pdfjs-viewer-shortcode' ),
				'default'     => 'yes',
				'description' => esc_html__( 'Enable or disable editing tools in the toolbar.', 'pdfjs-viewer-shortcode' ),
			)
		);

		$this->end_controls_section();

		// Fullscreen Section
		$this->start_controls_section(
			'section_fullscreen',
			array(
				'label' => esc_html__( 'Fullscreen Link', 'pdfjs-viewer-shortcode' ),
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'show_fullscreen',
			array(
				'label'       => esc_html__( 'Show Fullscreen Link', 'pdfjs-viewer-shortcode' ),
				'type'        => \Elementor\Controls_Manager::SWITCHER,
				'label_on'    => esc_html__( 'Yes', 'pdfjs-viewer-shortcode' ),
				'label_off'   => esc_html__( 'No', 'pdfjs-viewer-shortcode' ),
				'default'     => 'yes',
				'description' => esc_html__( 'Display a link above the viewer to open it in fullscreen.', 'pdfjs-viewer-shortcode' ),
			)
		);

		$this->add_control(
			'fullscreen_text',
			array(
				'label'       => esc_html__( 'Fullscreen Link Text', 'pdfjs-viewer-shortcode' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'default'     => esc_html__( 'View Fullscreen', 'pdfjs-viewer-shortcode' ),
				'placeholder' => esc_html__( 'View Fullscreen', 'pdfjs-viewer-shortcode' ),
				'condition'   => array(
					'show_fullscreen' => 'yes',
				),
				'description' => esc_html__( 'Enter custom text for the fullscreen link.', 'pdfjs-viewer-shortcode' ),
			)
		);

		$this->add_control(
			'fullscreen_target_blank',
			array(
				'label'       => esc_html__( 'Open Fullscreen in New Tab', 'pdfjs-viewer-shortcode' ),
				'type'        => \Elementor\Controls_Manager::SWITCHER,
				'label_on'    => esc_html__( 'Yes', 'pdfjs-viewer-shortcode' ),
				'label_off'   => esc_html__( 'No', 'pdfjs-viewer-shortcode' ),
				'default'     => '',
				'condition'   => array(
					'show_fullscreen' => 'yes',
				),
				'description' => esc_html__( 'Open the fullscreen viewer in a new browser tab.', 'pdfjs-viewer-shortcode' ),
			)
		);

		$this->end_controls_section();

		// Style Section
		$this->start_controls_section(
			'section_style',
			array(
				'label' => esc_html__( 'Style', 'pdfjs-viewer-shortcode' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'container_background',
			array(
				'label'       => esc_html__( 'Background Color', 'pdfjs-viewer-shortcode' ),
				'type'        => \Elementor\Controls_Manager::COLOR,
				'description' => esc_html__( 'Set the background color of the PDF viewer container.', 'pdfjs-viewer-shortcode' ),
				'selectors'   => array(
					'{{WRAPPER}} .pdfjs-embed-container' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			array(
				'name'        => 'container_border',
				'label'       => esc_html__( 'Border', 'pdfjs-viewer-shortcode' ),
				'selector'    => '{{WRAPPER}} .pdfjs-embed-container, {{WRAPPER}} iframe[data-pdfjs-viewer]',
				'description' => esc_html__( 'Set border properties for the PDF viewer.', 'pdfjs-viewer-shortcode' ),
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			array(
				'name'        => 'container_shadow',
				'label'       => esc_html__( 'Box Shadow', 'pdfjs-viewer-shortcode' ),
				'selector'    => '{{WRAPPER}} .pdfjs-embed-container, {{WRAPPER}} iframe[data-pdfjs-viewer]',
				'description' => esc_html__( 'Add shadow effects to the PDF viewer.', 'pdfjs-viewer-shortcode' ),
			)
		);

		$this->add_responsive_control(
			'container_padding',
			array(
				'label'           => esc_html__( 'Padding', 'pdfjs-viewer-shortcode' ),
				'type'            => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units'      => array( 'px', 'em', 'rem', '%' ),
				'selectors'       => array(
					'{{WRAPPER}} .pdfjs-embed-container' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'description'     => esc_html__( 'Set padding around the PDF viewer.', 'pdfjs-viewer-shortcode' ),
			)
		);

		$this->add_responsive_control(
			'container_margin',
			array(
				'label'           => esc_html__( 'Margin', 'pdfjs-viewer-shortcode' ),
				'type'            => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units'      => array( 'px', 'em', 'rem', '%' ),
				'selectors'       => array(
					'{{WRAPPER}} .pdfjs-embed-container' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'description'     => esc_html__( 'Set margin around the PDF viewer.', 'pdfjs-viewer-shortcode' ),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Render the widget output on the frontend.
	 *
	 * @return void
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		// Get PDF from media library only.
		$pdf_url       = '';
		$attachment_id = '';

		if ( ! empty( $settings['attachment_id']['id'] ) ) {
			$attachment_id = absint( $settings['attachment_id']['id'] );
			$pdf_url       = wp_get_attachment_url( $attachment_id );
		}

		if ( empty( $pdf_url ) ) {
			echo '<div role="alert" aria-live="assertive" style="padding: 20px; border: 2px solid #dc3232; background: #f8d7da; color: #721c24; margin: 20px 0;">';
			echo '<p style="margin: 0;"><strong>' . esc_html__( 'Error:', 'pdfjs-viewer-shortcode' ) . '</strong> ' . esc_html__( 'Please select a PDF file from your media library.', 'pdfjs-viewer-shortcode' ) . '</p>';
			echo '</div>';
			return;
		}

		// Get dimension settings.
		$height = $this->get_responsive_dimension( $settings, 'viewer_height' );
		$width  = $this->get_responsive_dimension( $settings, 'viewer_width' );

		// Build arguments for pdfjs_render_viewer function.
		$viewer_args = array(
			'url'               => $pdf_url,
			'viewer_height'     => $height,
			'viewer_width'      => $width,
			'fullscreen'        => 'yes' === $settings['show_fullscreen'] ? 'true' : 'false',
			'fullscreen_text'   => sanitize_text_field( $settings['fullscreen_text'] ),
			'fullscreen_target' => 'yes' === $settings['fullscreen_target_blank'] ? 'true' : 'false',
			'download'          => 'yes' === $settings['show_download'] ? 'true' : 'false',
			'print'             => 'yes' === $settings['show_print'] ? 'true' : 'false',
			'zoom'              => sanitize_text_field( $settings['zoom_level'] ),
			'attachment_id'     => $attachment_id,
			'search'            => 'yes' === $settings['show_search'] ? 'true' : 'false',
			'editing'           => 'yes' === $settings['show_editing'] ? 'true' : 'false',
		);

		// Wrap in a container for styling.
		echo '<div class="pdfjs-embed-container">';
		echo wp_kses_post( pdfjs_render_viewer( $viewer_args ) );
		echo '</div>';
	}

	/**
	 * Get responsive dimension value.
	 *
	 * @param array  $settings Control settings.
	 * @param string $control_key Control key name.
	 * @return string Formatted dimension string.
	 */
	private function get_responsive_dimension( $settings, $control_key ) {
		$size = isset( $settings[ $control_key ]['size'] ) ? $settings[ $control_key ]['size'] : 0;
		$unit = isset( $settings[ $control_key ]['unit'] ) ? $settings[ $control_key ]['unit'] : 'px';

		if ( 0 === (int) $size || empty( $size ) ) {
			return $control_key === 'viewer_width' ? '100%' : '800px';
		}

		return absint( $size ) . $unit;
	}
}
