# PDF.js Viewer – Developer Guide

This guide contains developer-focused setup, commands, and structure details. End-users should refer to `readme.md`.

## Requirements

-   Node.js 18+ (use [nvm](https://github.com/nvm-sh/nvm) to manage versions)
-   npm 10+

## Quick Start

```bash
# Clone the repository
git clone https://github.com/TwisterMc/PDF.js-Viewer-Shortcode.git
cd PDF.js-Viewer-Shortcode

# Use the correct Node version (if using nvm)
nvm use

# Install dependencies
npm install

# Build for production
npm run build

# Or start development with watch mode
npm run start
```

## Available Commands

| Command            | Description                        |
| ------------------ | ---------------------------------- |
| `npm run build`    | Build production assets            |
| `npm run start`    | Watch & rebuild during development |
| `npm run lint:js`  | Lint JavaScript files              |
| `npm run lint:css` | Lint CSS/SCSS                      |
| `npm run format`   | Format code with Prettier          |

## File Structure

```
blocks/
  src/      # Gutenberg block source (JS/SCSS)
  build/    # Generated build assets (do not edit)
inc/        # PHP includes (shortcode, options, rendering)
pdfjs/      # Vendor PDF.js viewer code
DEVELOPER.md
readme.md   # User-focused documentation
```

## Key PHP Components

-   `inc/render-viewer.php` – Shared rendering logic for block & shortcode.
-   `inc/shortcode.php` – Shortcode handler using shared renderer.
-   `inc/embed.php` – Legacy wrapper (kept for backward compatibility).
-   `inc/gutenberg-block.php` – Block registration & script localization.
-   `inc/options-page.php` – Admin settings page.

## Shared Helpers

-   `pdfjs_get_options()` – Consistent retrieval of plugin options.
-   `pdfjs_render_viewer()` – Builds iframe + fullscreen link.
-   `pdfjs_sanitize_option()` – Sanitizes stored options.

## Development Tips

-   Avoid editing anything inside `blocks/build/`; it is generated.
-   Use `attachment_id` when possible instead of raw `url` for security.
-   Keep changes atomic; avoid patching built bundles manually.
-   Run `npm run build` before committing to ensure assets are current.

## Updating Dependencies

```bash
npm outdated          # See what can be updated
npm update            # Apply safe updates
npm audit             # View security issues
npm audit fix         # Attempt fixes
```

## Updating Bundled PDF.js

When upgrading the vendored `pdfjs/` directory from Mozilla's legacy build:

1. Download and extract the legacy PDF.js package from Mozilla.
2. Run:

```bash
npm run update:pdfjs -- update/pdfjs-5.5.207-legacy-dist
```

This command will:

-   Sync `build/` and `web/` into this repo's `pdfjs/` folder.
-   Mirror key `.mjs` files to `.js` equivalents (`pdf`, `pdf.worker`, `pdf.sandbox`, `viewer`, `debugger`) so classic browser loading paths continue to work.
-   Keep the `.mjs` files alongside the `.js` files.

After running, compare viewer templates before release:

```bash
diff -u pdfjs/web/viewer.html pdfjs/web/viewer.php
```

## Releasing

1. Update version in plugin header & stable tag in `readme.md`.
2. Add changelog entry.
3. Run `npm run build` and verify no raw imports or errors.
4. Commit & tag: `git tag vX.Y.Z && git push --tags`.
5. Submit to WordPress.org.

## Troubleshooting

| Issue                    | Fix                                                                      |
| ------------------------ | ------------------------------------------------------------------------ |
| PDF not loading          | Check file URL / attachment permissions                                  |
| Block invalid in editor  | Use "Attempt Block Recovery"                                             |
| Buttons missing          | Verify settings page options + transient values                          |
| Build fails on syntax    | Ensure Node 18+ and clean install (`rm -rf node_modules && npm install`) |
| Sass deprecation warning | Updated tooling already; ensure latest `sass-loader`                     |

## Contributing

-   Fork, branch, and submit PRs with clear descriptions.
-   Follow WordPress coding standards (PHP & JS).
-   Keep PRs focused—prefer multiple small PRs to one large one.

## License

GPLv2 or later – same as the plugin.
