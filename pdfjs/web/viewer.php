<?php
$asset_version = isset( $_GET['v'] ) ? preg_replace( '/[^a-zA-Z0-9._-]/', '', $_GET['v'] ) : '1.0';
$viewer_html   = __DIR__ . '/viewer.html';

if ( ! is_readable( $viewer_html ) ) {
	http_response_code( 500 );
	header( 'Content-Type: text/plain; charset=utf-8' );
	echo 'Unable to load PDF.js viewer.';
	exit;
}

$html = file_get_contents( $viewer_html );

if ( false === $html ) {
	http_response_code( 500 );
	header( 'Content-Type: text/plain; charset=utf-8' );
	echo 'Unable to load PDF.js viewer.';
	exit;
}

$version = rawurlencode( $asset_version );

// Cache busting query parameters for all critical assets.
// These ensure assets are refreshed when PDF.js is updated.
// Replacements handle both markers from update-pdfjs.sh (%PDFJS_VER%) 
// and direct file references that may appear in HTML.
$replacements = array(
	// Handle markers from update-pdfjs.sh script
	'%PDFJS_VER%'         => $version,
	// Fallback replacements for direct references (in case markers weren't applied)
	'locale/locale.json"' => 'locale/locale.json?v=' . $version . '"',
	'../build/pdf.mjs"'   => '../build/pdf.js?v=' . $version . '"',
	'../build/pdf.js"'    => '../build/pdf.js?v=' . $version . '"',
	'viewer.css"'         => 'viewer.css?v=' . $version . '"',
	'viewer.mjs"'         => 'viewer.js?v=' . $version . '"',
	'viewer.js"'          => 'viewer.js?v=' . $version . '"',
	// Cache bust worker and sandbox scripts (both in HTML and JavaScript references)
	'../build/pdf.worker.mjs' => '../build/pdf.worker.js?v=' . $version,
	'../build/pdf.worker.js'  => '../build/pdf.worker.js?v=' . $version,
	'../build/pdf.sandbox.mjs' => '../build/pdf.sandbox.js?v=' . $version,
	'../build/pdf.sandbox.js'  => '../build/pdf.sandbox.js?v=' . $version,
);

$customization_script = <<<'HTML'
<script>
(function () {
	const params = new URLSearchParams(window.location.search);
	const enabled = key => (params.get(key) || 'true') === 'true';
	const hide = id => {
		const element = document.getElementById(id);
		if (element) {
			element.style.display = 'none';
		}
	};
	const hideMany = ids => ids.forEach(hide);

	const applyToggles = () => {
		if (!enabled('sButton')) {
			hide('viewFindButton');
			hide('findbar');
		}

		if (!enabled('oButton')) {
			hide('secondaryOpenFile');
		}

		if (!enabled('pButton')) {
			hideMany(['printButton', 'secondaryPrint']);
		}

		if (!enabled('dButton')) {
			hideMany(['downloadButton', 'secondaryDownload']);
		}

		if (!enabled('editButtons')) {
			hide('editorModeButtons');
			hide('editorModeSeparator');
		}
	};

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', applyToggles, { once: true });
	} else {
		applyToggles();
	}
})();
</script>
HTML;

header( 'Content-Type: text/html; charset=utf-8' );
echo str_replace( '</head>', $customization_script . '</head>', strtr( $html, $replacements ) );
