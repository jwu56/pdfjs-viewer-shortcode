<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly.

add_action( 'init', function() {
	if ( isset( $_GET['pdfjs_id'] ) ) {
		if ( ! isset( $_REQUEST['_wpnonce'] ) ) {
			wp_die( esc_html__( 'Security Check Failed', 'pdfjs-viewer-shortcode' ) );
		}

		$nonce         = sanitize_text_field( wp_unslash( $_REQUEST['_wpnonce'] ) );
		$attachment_id = absint( wp_unslash( $_GET['pdfjs_id'] ) );

		// Nonce action must match render-viewer.php: unique per attachment or URL hash
		// When pdfjs_id=0, the nonce suffix is md5 of the URL — but we don't have the URL
		// here, so the URL-only path is not supported by the custom-page handler.
		if ( 0 === $attachment_id ) {
			wp_die( esc_html__( 'Security Check Failed', 'pdfjs-viewer-shortcode' ) );
		}
		$nonce_action = 'pdfjs_full_screen_' . $attachment_id;
		if ( '' === $nonce || ! wp_verify_nonce( $nonce, $nonce_action ) ) {
			wp_die( esc_html__( 'Security Check Failed', 'pdfjs-viewer-shortcode' ) );
		}

		if ( 0 !== $attachment_id ) {
			// Verify attachment exists and is valid
			$attachment = get_post( $attachment_id );
			if ( ! $attachment || 'attachment' !== $attachment->post_type ) {
				wp_die( esc_html__( 'Invalid attachment.', 'pdfjs-viewer-shortcode' ) );
			}

			// Check if attachment is accessible (not private unless user has permission for this specific post)
			if ( 'private' === $attachment->post_status && ! current_user_can( 'read_post', $attachment_id ) ) {
				wp_die( esc_html__( 'You do not have permission to view this attachment.', 'pdfjs-viewer-shortcode' ) );
			}
			
			// Verify the file is actually a PDF
			$mime_type = get_post_mime_type( $attachment_id );
			if ( 'application/pdf' !== $mime_type ) {
				wp_die( esc_html__( 'This attachment is not a PDF file.', 'pdfjs-viewer-shortcode' ) );
			}
			
			$pdfjs_url = wp_get_attachment_url( $attachment_id );
		} else {
			$pdfjs_url = plugin_dir_url( __FILE__ ) . '../pdf-loading-error.pdf';
		}

		if ( ! $pdfjs_url ) {
			$pdfjs_url = plugin_dir_url( __FILE__ ) . '../pdf-loading-error.pdf';
		}

		include plugin_dir_path( __FILE__ ) . '../templates/fullscreen.php';
		die();
	}
});
