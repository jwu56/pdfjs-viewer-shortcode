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

$replacements = array(
	'locale/locale.json"' => 'locale/locale.json?v=' . $version . '"',
	'../build/pdf.mjs"'   => '../build/pdf.js?v=' . $version . '"',
	'viewer.css"'         => 'viewer.css?v=' . $version . '"',
	'viewer.mjs"'         => 'viewer.js?v=' . $version . '"',
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
