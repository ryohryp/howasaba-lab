---
name: Howasaba Lab Dev
description: Development workflow and environment details for Howasaba Lab WordPress project.
---

# Howasaba Lab Development Skill

このスキルは、Howasaba Lab プロジェクト（WordPressテーマ開発）における開発環境とワークフローを定義します。

## Artifact Rules (Language Requirement)

**以下の成果物（Artifacts）は必ず日本語で記述してください。**
- `implementation_plan.md` (Implementation Plan)
- `task.md` (Task List)
- `walkthrough.md` (Walkthrough)


## 環境設定 (Environment Setup)

### PHP
- **Executable Path**: `C:\tools\php\php.exe`
- **Version**: 8.3.29
- **Configuration**: `C:\tools\php\php.ini` (Extensions: curl, mbstring, openssl, zip enabled)

### Composer
- **Wrapper Script**: `C:\tools\composer.bat`
- **Usage**: Run `C:\tools\composer.bat` for all composer commands.

### Python (Automation Scripts)
- **Scripts**: `scripts/`
- **Dependencies**: `requirements.txt`
- **Usage**: `python scripts/fetch_gift_codes.py` (Env vars required)
- **i18n Tool**: `python scripts/compile_mo_pure.py` (Compiles .po to .mo without msgfmt)
- **Article Poster**: `python scripts/post_gen6_article.py` (Draft creation for Gen 6 Heroes)


## Project Structure

- **Root**: `i:\04_develop\howasaba-lab`
- **Active Theme**: `wos-survival`
- **Domain**: `howasaba-code.com`
- **Theme Path (Local)**: `wp-content/themes/wos-survival`
- **Deploy Target (Server)**: `/howasaba-code.com/public_html/wp-content/themes/wos-survival`

## Frontend Architecture (Frost & Fire Design)

### Design Concept: "Sharp & Vivid Survival"
- **Style**: Modern Flat Design (No Glassmorphism).
- **Colors**: Deep Freeze (`slate-900`) background with Fire Crystal (`orange-400` / `red-500`) and Ice (`sky-400`) accents.
- **Typography**: Bold, high-contrast, magazine-style layout.

### Technology Stack
- **Tailwind CSS**: Utility-first CSS framework. Configured in `tailwind.config.js`.
- **Alpine.js**: Lightweight JavaScript framework for interactivity (Filtering, Tab switching).
- **Vite Asset Loader**: `inc/class-vite-asset-loader.php` handles asset enqueueing.

## Data Structure (Custom Post Types)

### 1. Heroes (`wos_hero`)
- **Taxonomies**: `hero_generation` (Gen 1..11), `hero_type` (Infantry/Lancer/Marksman), `hero_rarity` (SSR/SR/R).
- **Meta Fields**: 
    - `_hero_stats_atk/def/hp`
    - `_skill_exploration_active`, `_skill_expedition_1/2`
    - `_japanese_name`
- **Helper**: `wos_seed_heroes()` in `functions.php`

### 2. Events (`wos_event`)
- **Meta Fields**: `_event_start_date`, `_event_duration`, `_server_age_requirement`.
- **Helper**: `wos_seed_events()`

### 3. Gift Codes (`gift_code`)
- **Meta Fields**: `_wos_code_string`, `_wos_rewards`, `_wos_expiration_date`.
- **Meta Fields**: `_wos_code_string`, `_wos_rewards`, `_wos_expiration_date`.
- **Feature**: "Radar" UI effect for new codes (`gift-code-radar.css`).

## REST API Custom Architecture

Xserver（FastCGI）の制限により、標準の `Authorization` ヘッダーが削除されるため、独自のトークン認証を実装しています。

### Authentication
- **Header**: `X-Radar-Token`
- **Value**: `WOS_RADAR_TOKEN` (Environment Variable)
- **Validation**: `inc/api-endpoints.php`

### Endpoints
1. **Gift Code Registration**
   - `POST /wp-json/wos-radar/v1/add-code`
   - **Payload**: `{ "code_string": "...", "rewards": "..." }`

2. **Post Management**
   - `POST /wp-json/wos-radar/v1/create-post` (Draft Creation)
   - `POST /wp-json/wos-radar/v1/update-post` (Update Existing)


## Tier List Generator System

英雄のTierリスト（日本語名・スキル詳細含む）を自動管理するシステムです。

- **Custom Fields (ACF)**: `inc/acf-tier-list.php`
- **Generator**:
    1. **Data Seeding**: `/wp-admin/?seed_heroes=1` (Upsert logic)
    2. **Gen 6 Update**: `/wp-admin/?seed_gen6=1` & `?seed_gen6_skills=1`
    3. **Page Creation**: `/wp-admin/?seed_pages=1`
- **Shortcode**: `[wos_tier_list]` creates the comparison table.

## Deployment (CI/CD)

GitHub Actions を使用して Xserver へ自動デプロイされます。

- **Workflow**: `.github/workflows/deploy.yml`
- **Trigger**: Push to `main`
- **Process**: `npm ci` -> `npm run build` -> FTP Upload (Theme dir only)
- **Secrets**: `FTP_SERVER`, `FTP_USERNAME`, `FTP_PASSWORD`
