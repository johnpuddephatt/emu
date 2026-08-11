# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project overview

Sage 11 (Roots) WordPress theme for East Marsh United ("emu"), living inside a Bedrock install (Bedrock root is four levels up at `../../../../`, site served at `https://emu.test` via Herd). It's a homepage rebuild of the eastmarshunited 11ty site; a useful reference project with similar patterns is `~/Sites/carrfenton/web/app/themes/carrfenton`.

## Commands

Run from this theme directory unless noted:

- `npm run dev` — Vite dev server with HMR
- `npm run build` — production build (also regenerates `public/build/assets/theme.json`)
- `composer install` / `composer require …` — PHP deps live in the **theme's** vendor dir, not Bedrock root (`functions.php` requires the theme's `vendor/autoload.php`)
- `wp acorn <command>` — Acorn/Laravel commands (e.g. `wp acorn optimize:clear`, `wp acorn acf:cache`)
- `wp eval '…'` — probing WP/Acorn state; `wp eval` boots Acorn properly, plain `php -r` + wp-load does not
- Linting (from Bedrock root): `composer lint` (`pint --test`) / `composer lint:fix` (`pint`), preset `per`. Tests: `composer test` (Pest, Bedrock-level only — the theme has no tests)
- `npm run translate` / `translate:compile` — i18n pot/po/mo workflow via wp-cli

### Acorn cache gotcha

Acorn's storage path is `web/app/cache/acorn/` at the Bedrock level (not the theme's `storage/`). Its package-discovery cache is **not** invalidated by `composer require` in the theme, so newly installed Acorn packages silently never register. After installing/removing an Acorn package, run `wp acorn optimize:clear` or delete `web/app/cache/acorn/framework/cache/{packages,services}.php`.

## Architecture

Bootstrap: `functions.php` boots the Acorn application with `App\Providers\ThemeServiceProvider`, then loads `app/setup.php` (theme supports, menus, editor asset injection) and `app/filters.php`. PSR-4: `App\` → `app/`.

- **Blocks** (`app/Blocks/*.php`): ACF blocks via `log1x/acf-composer`. Each class defines block metadata + an ACF field group (`fields()` using `Builder`) + view data (`with()`), and renders `resources/views/blocks/<slug>.blade.php`.
- **Fields** (`app/Fields/*.php`): standalone ACF field groups (e.g. `EventDetails` attaches start/end/location to the `event` post type).
- **Post types & taxonomies**: registered declaratively in `config/poet.php` via `log1x/poet` — post types `event`, `workstream` and `person`, taxonomy `role_type` (on `person`). `person` is deliberately non-public: bios are surfaced by the People block's modal, and there's no `single-person` template.
- **Views** (`resources/views/`): Blade templates — `layouts/`, `partials/`, `sections/` (header/footer), `blocks/`. View composers in `app/View/Composers/`.
- **Icons**: `blade-ui-kit/blade-icons`, set in `resources/icons/`, used as `<x-icon-name />` (prefix `icon`, see `config/blade-icons.php`).
- **Patterns**: block patterns in `patterns/` (PHP files).
- **JS** (`resources/js/app.js`): Alpine.js (+collapse) and Swiper, both exposed on `window`. `resources/js/editor.js` registers/unregisters core block styles for the editor.
- **theme.json**: the root `theme.json` is a source file; `@roots/vite-plugin`'s `wordpressThemeJson` merges in Tailwind colors/fonts/sizes and writes the real one to `public/build/assets/theme.json` (a `theme_file_path` filter in `setup.php` points WP at it). Editing `theme.json` or the Tailwind palette requires a Vite build/dev run to take effect.

### CSS (Tailwind v4, CSS-based config)

Config lives in `resources/css/_tailwind-config.css`, not a JS file. The default Tailwind palette is reset (`--color-*: initial`) and replaced with plain-named brand colors (`black` #292929, `cream` #f7f3e8, `gray`, `green`, `blue`, `pink`, `red`, `yellow` — no `brand-` prefix). Global/block styles are in `resources/css/common/`, with per-core-block overrides under `common/blocks/`. Custom utilities `containerish`/`containerishy` implement full-bleed sections aligned to the global wide width; `max-w-wide`/`max-w-content` map to theme.json layout sizes.

## Conventions and gotchas

- **acf-composer slugs**: `$name` with acronyms auto-kebabs badly ("CTA panel" → `c-t-a-panel`), breaking the view lookup. Set an explicit `$slug`. Changing a slug orphans already-inserted block instances (saved as `acf/<old-slug>`).
- **Every block must declare both `public $blockVersion = 3;` and `public $apiVersion = 3;`** — acf-composer defaults both to 2, and `$blockVersion` alone leaves the WP apiVersion at 2 (deprecation warning).
- **Dark sections** use the `has-black-background-color` class (not raw `bg-black`) so buttons/links auto-adapt via `common/blocks/_backgrounds.css` and `button.css`.
- **`.prose` overrides must not go in `@layer components`** — the typography plugin emits into `utilities`, which beats `components` in v4's cascade regardless of specificity. Write them unlayered (or `@layer utilities`); the plugin wraps element selectors in `:where()` so plain `.prose h1 { … }` wins.
- **Editor previews**: Alpine/Swiper don't run in the editor iframe, so interactive blocks render static fallbacks when `$block->preview` is true.
- **`<dialog>` needs `m-auto`** to sit centred — the UA stylesheet centres modal dialogs with `margin: auto`, which Tailwind's preflight resets to `0`. Alpine directives on a dialog (or anything else) also need an ancestor with `x-data`, even an empty one, or they never bind (see `blocks/people.blade.php`).
- **No arrow functions (`=>`) in Alpine attributes** on block output. Block markup passes through `the_content`, and `wptexturize` treats the `>` as the end of the tag, then curly-quotes the rest of the attribute — the `x-data` fails to parse with a bare `SyntaxError` and the whole component silently dies. Use `function () { … }` (see `blocks/podcast.blade.php`, `blocks/impact-stats.blade.php`).
