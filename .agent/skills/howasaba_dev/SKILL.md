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

## Content Guidelines (Tone & Style)

- **客観的・情報中心のトーン**:「副司令官です！」「戦いを始めましょう！」のような、ゲーム世界に没入したロールプレイ的な表現（なりきり）は避けてください。
- **簡潔さ**: 読者はスマホユーザーが多いため、結論を先に述べ、過度な挨拶や煽り文句は削除してください。


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
- **Dependencies**: `requirements.txt` (including `supabase` SDK)
- **Usage**: `python scripts/fetch_gift_codes.py` (Requires `SUPABASE_URL` and `SUPABASE_SERVICE_ROLE_KEY`)
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

## Data Structure (Supabase Hybrid Architecture)

本プロジェクトは **Supabase を主なデータソース（構造化データ用）** とし、**WordPress をコンテンツ管理およびフォールバック用** に利用するハイブリッド構成を採用しています。

### 1. Supabase テーブル
- **`heroes`**: 英雄データ（slug、image_url、generation、troop_type、rarity、tier_overall 等）。パブリック参照可。
- **`gift_codes`**: ギフトコードデータ。`is_active = true` のみパブリック参照可。
- **`events`**: イベント日程等。パブリック参照可。

### 2. WordPress 連携 (inc/class-supabase-client.php)
- Supabase の PostgREST API を呼び出す `Supabase_Client` クラスを使用。
- 呼び出し結果は WordPress の Transient API で 5分間キャッシュ。
- **ショートコード** (`[wos_gift_codes]`, `[wos_tier_list]`) や **テンプレート** (`archive-wos_hero.php`, `single-wos_hero.php`) は、まず Supabase からデータを取得し、エラー時は WordPress の CPT や Metaデータにフォールバック。

### 3. WordPress (コンテンツルーティング用 CPT)
- パーマリンク、SEO設定、サムネイル画像、本文などのコンテンツレイヤーは、引き続き WordPress の `wos_hero` カスタム投稿タイプを利用。
- **Taxonomies**: `hero_generation`, `hero_type`, `hero_rarity`
- **Helper**: `inc/seeders.php` (旧関数統合済み)

## REST API Custom Architecture (Content Updates)

Xserver（FastCGI）の制限により、標準の `Authorization` ヘッダーが削除されるため、独自のトークン認証を実装しています。
※データ構造管理（ギフトコード追加等）は Supabase SDK に移行したため、WP REST API は主に記事やページのコンテンツ更新に使用されます。

### Authentication
- **Header**: `X-Radar-Token`
- **Value**: `WOS_RADAR_TOKEN` (Environment Variable)
- **Validation**: `inc/api-endpoints.php`

### Endpoints
1. **Post Management**
   - `POST /wp-json/wos-radar/v1/update-post` (Update Existing Content via `scripts/update_post.py`)
   - `POST /wp-json/wos-radar/v1/create-post` (Draft Creation)


## Tier List Generator System

英雄のTierリスト（日本語名・スキル詳細含む）を自動管理するシステムです。

- **Custom Fields (ACF)**: `inc/acf-tier-list.php`
- **Generator**:
    1. **Data Seeding**: `/wp-admin/?seed_heroes=1` (Upsert logic)
    2. **Gen 6 Update**: `/wp-admin/?seed_gen6=1` & `?seed_gen6_skills=1`
    3. **Year Beast Event**: `/wp-admin/?seed_year_beast=1`
    4. **Page Creation**: `/wp-admin/?seed_pages=1`
- **Shortcode**: `[wos_tier_list]` creates the comparison table.

## Deployment (CI/CD)

GitHub Actions を使用して Xserver へ自動デプロイされます。

- **Workflow**: `.github/workflows/deploy.yml`
- **Trigger**: Push to `main`
- **Process**: `npm ci` -> `npm run build` -> FTP Upload (Theme dir only)
- **Secrets**: `FTP_SERVER`, `FTP_USERNAME`, `FTP_PASSWORD`
