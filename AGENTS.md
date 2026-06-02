# Agent System Instructions & Context for Vox Populi Digital

Welcome, Agent! This file is your guide to understanding the project structure, stack, and development workflow. Always read and adhere to this file to prevent environment issues or syntax regressions.

---

## 🌟 Project Overview
**Vox Populi Digital** is a high-performance web platform built using a modern PHP/WordPress stack. It follows the **Twelve-Factor App** methodology to decouple configuration from code and facilitate containerized deployments.

---

## 🛠️ Technology Stack

### 1. Backend Core & Boilerplate
- **Roots Bedrock**: A modern WordPress boilerplate that organizes code cleanly, manages dependencies via Composer, and secures configuration using environment variables (`.env`).
- **PHP**: Version `8.4` (managed via FrankenPHP).
- **WordPress**: Version `6.9.4` (installed as a dependency via Composer in `web/wp`).
- **Database**: **SQLite** (using the `sqlite-database-integration` plugin). No separate MySQL/MariaDB container is required; data is persisted locally in the filesystem.

### 2. Theme & Presentation Layer
- **Roots Sage 11**: Located in `web/app/themes/voxpopuli/`. It features:
  - **Blade Template Engine**: Clean separation of views (`resources/views/`) and business logic (`app/View/Composers/` or controllers).
  - **Acorn v6**: Integrates Laravel-like components, dependency injection, and configuration management into WordPress.

### 3. Frontend Tooling & Styling
- **Vite 8**: Next-generation frontend tooling for blistering fast HMR (Hot Module Replacement) and asset compilation.
- **Tailwind CSS v4**: Built using the `@tailwindcss/vite` plugin for optimized styles.
- **DaisyUI v5**: Tailwind components library.

---

## 🐳 Dockerized Environment & Services

All tools—including PHP, Composer, Node, and Vite—run containerized via `docker-compose.yml`. **Do not run composer or npm commands directly on the host machine** if you can avoid it, as version mismatches can occur.

The project defines two core services:

### 1. `app` Service (FrankenPHP)
- **Image**: `dunglas/frankenphp:php8.4-alpine`
- **Port**: `8080` (accessible at `http://localhost:8080`)
- **Includes**:
  - PHP 8.4 + essential extensions (`gd`, `zip`, `exif`, `mysqli`, `pdo_mysql`, `opcache`).
  - **WP-CLI**: Installed at `/usr/local/bin/wp`.
  - **Composer 2**: Ready to manage plugins and packages.
- **Use Case**: Running PHP tests, database migrations, WP-CLI queries, or Composer operations.
- **Example Commands**:
  ```bash
  # Run Pint linting
  docker compose exec app composer lint
  
  # Run Pest tests
  docker compose exec app composer test
  
  # Run a WP-CLI command
  docker compose exec app wp option get home
  ```

### 2. `node` Service (Frontend compilation)
- **Image**: `node:20-alpine`
- **Port**: `5174` (Vite dev server)
- **Working Directory**: `/app/web/app/themes/voxpopuli`
- **Behavior**: Automatically boots with `npm install && npm run dev` to watch asset fil0es and serve them via HMR.
- **Use Case**: Installing frontend assets, adding packages, or compiling assets for production.
- **Example Commands**:
  ```bash
  # Install new npm package in the theme
  docker compose exec node npm install <package-name>
  ```

---

## 📂 Key Directory Map

- `/web/` — WordPress root.
- `/web/wp/` — Core WordPress installation (managed by Composer; **do not edit directly**).
- `/web/app/themes/voxpopuli/` — Custom theme directory (Sage 10).
  - `/app/` — PHP application logic, controller classes, and view composers.
  - `/resources/views/` — Blade template views (where HTML is structured).
  - `/resources/styles/` — Global CSS styling rules.
  - `/resources/js/` — JavaScript files.
- `/config/` — Environment and WordPress configurations.
- `Dockerfile` — Custom multi-stage build defining the `dev` and `prod` targets.
- `docker-compose.yml` — Orchestrates the local environment services.

---

## 🧠 Architectural Rules for Agents

1. **Keep HTML Semantic**: Use clean, modern HTML5 structures. Avoid styling hacks.
2. **Separation of Concerns**: Write Blade views under `resources/views/` and bind dynamic data using View Composers under `app/View/Composers/` or standard controllers when applicable.
3. **No Direct Local Executions**: When running tests or asset builds on behalf of the user, run them in their respective Docker containers using the commands documented above.
4. **Lint before PRs**: Run Laravel Pint (`composer lint:fix` inside the `app` container) before committing changes to ensure consistent code styling.

---

## ⚡ Performance & Security Best Practices for Agents (DOs & DONTs)

When writing code or implementing changes in this codebase, you MUST adhere strictly to the following architectural, performance, and security rules:

### 1. Cross-Site Scripting (XSS) Prevention (Security)
- **DO NOT** use Blade raw output syntax `{!! get_search_query() !!}` or `{!! esc_attr_x(...) !!}` to render user inputs or standard attributes. Doing so introduces reflected/stored XSS vulnerabilities.
- **DO** use Blade native escaped tags `{{ get_search_query() }}` or `{{ esc_attr_x(...) }}` for all data attributes, placeholders, and content rendering, ensuring everything is properly escaped via PHP's `htmlspecialchars` (by Acorn's template compiler).
- **EXCEPTION**: Only use `{!! !!}` when rendering rich HTML content that has been explicitly sanitized using safe custom functions or WordPress's `wp_kses` family.

### 2. WP_Query Overhead Optimization (Performance)
- **DO NOT** run standard `WP_Query` loops or `get_posts()` without setting `'no_found_rows' => true` unless you are actively rendering page numbers/pagination links. Without this flag, MySQL/SQLite executes an expensive `SQL_CALC_FOUND_ROWS` query to calculate total entries, creating severe CPU overhead on lists, homepages, and sitemaps.
- **DO** add `'no_found_rows' => true` to all informational queries, sticky queries, suggested post loops, and sitemap generation queries.

### 3. Dynamic Reading Time and Word Count Calculations (Performance)
- **DO NOT** dynamically run `str_word_count(strip_tags($content))` for every single card or post render inside loop constructs. This is a CPU-intensive operation that scales poorly (`O(N)` where N is content size and number of posts).
- **DO** retrieve the pre-computed `vp_reading_time` and `vp_word_count` post metadata directly using `get_post_meta($post_id, 'vp_reading_time', true)`.
- **DO** fall back to dynamic string parsing ONLY if the metadata does not exist, keeping rendering performance at `O(1)` database reads.

### Good vs Bad Code Examples

#### ❌ Bad (Vulnerable & CPU-Intensive)
```html
<!-- search.blade.php -->
<h1>«{!! get_search_query() !!}»</h1>

<!-- PostCard component -->
@php
$content = get_post_field('post_content', $post->ID);
$word_count = str_word_count(strip_tags($content));
$reading_time = max(1, ceil($word_count / 200));
@endphp
```

#### 🍏 Good (Safe & High Performance)
```html
<!-- search.blade.php -->
<h1>«{{ get_search_query(false) }}»</h1>

<!-- PostCard component -->
@php
$reading_time = get_post_meta($post->ID, 'vp_reading_time', true);
if (!$reading_time) {
  $content = get_post_field('post_content', $post->ID);
  $word_count = str_word_count(strip_tags($content));
  $reading_time = max(1, ceil($word_count / 200));
}
@endphp
```

