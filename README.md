# WordCamp Mannheim – Organizers Blocks

A collection of three WordPress Gutenberg block plugins for displaying event organizers, built as a teaching example for WordCamp Mannheim. Each plugin implements the same block (`wcma/organizers-list-*`) in a different architectural style to demonstrate the trade-offs between the available approaches.

## Plugins

### `wcma-organizers-block-js` — JavaScript-only registration

The block is registered entirely through JavaScript using `@wordpress/scripts` as the build toolchain. The editor UI is built with React components from `@wordpress/components`, and the front-end output is rendered server-side via a PHP `render.php` file that is referenced from `block.json`. This is the modern, recommended approach for blocks that need a rich editor experience.

### `wcma-organizers-block-php-only` — PHP-only registration

The block is registered entirely in PHP without a `block.json` file, using the classic `register_block_type_from_metadata()` / `register_block_type()` approach with all attributes defined in PHP. Useful for understanding the underlying primitives or for environments where a build step is not available.

### `wcma-organizers-block-mixed` — Mixed registration

The block metadata lives in a `block.json` file, but the block is registered in PHP using `register_block_type()` rather than through a JS build step. There is no compiled JavaScript; the editor falls back to a server-side render preview. Good for blocks where a full JS editor UI is not needed but you still want the `block.json` metadata format.

## Block features

All three blocks expose the same attributes and front-end output:

- **Avatar** — toggle visibility, choose the size (48 / 96 / 128 / 192 px)
- **Name** — toggle visibility, choose the type (display name / first name / nickname)
- **Bio** — toggle visibility
- **Link** — toggle link to author archive, optional custom link text prefix
- **Display type** — list or grid layout; configurable column count (2–6) in grid mode

Organizers are users who have at least one published post. The block queries WordPress users and filters by the `author` role.

## Requirements

- PHP 8.1+
- WordPress 6.3+
- Node.js 20+ and npm (for `wcma-organizers-block-js` only)

## Development

```bash
# Install PHP tools (PHPCS + PHPStan)
composer install

# Lint PHP
vendor/bin/phpcs
vendor/bin/phpstan analyse

# Build JS block
cd wcma-organizers-block-js
npm install
npm run build

# Lint JS
npm run lint:js
```

## Try it in WordPress Playground

Open a fully configured demo environment — includes all three plugins activated, six sample organizers with bios, and an Organizers page set as the front page:

**[Launch in WordPress Playground](https://playground.wordpress.net/?blueprint-url=https://raw.githubusercontent.com/2ndkauboy/wcma-organizers-blocks/develop/blueprint.json)**
