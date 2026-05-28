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
