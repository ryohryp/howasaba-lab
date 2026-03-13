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
- **Alpine.js**: Lightweight JavaScript framework for interactivity (Filtering, Tab switching, Sorting).
- **Vite Asset Loader**: `inc/class-vite-asset-loader.php` handles asset enqueueing.

## Data Structure (Supabase Hybrid Architecture)

本プロジェクトは **Supabase を主なデータソース（構造化データ用）** とし、**WordPress をコンテンツ管理およびフォールバック用** に利用するハイブリッド構成を採用しています。

### 0. サーバー環境設定 (wp-config.php)
- **Required Constants**: 以下の定数が Xserver 上の `wp-config.php` で定義されている必要があります。
  ```php
  define( 'SUPABASE_URL', 'https://twkzbonjhvbykcaokgdq.supabase.co' );
  define( 'SUPABASE_ANON_KEY', '...' );
  ```

### 1. Supabase テーブル
- **`heroes`**: 英雄データ（slug, image_url, generation, troop_type, rarity, tier_overall, **japanese_name** 等）。
    - **i18n**: サイトのロケール判定 (`?lang=ja`) に基づき、`japanese_name` か `name` (英語) を動的に切り替えて表示。
- **`gift_codes`**: ギフトコードデータ。`is_active = true` のみパブリック参照可。

### 2. WordPress 連携 (inc/class-supabase-client.php)
- Supabase の PostgREST API を呼び出す `Supabase_Client` クラスを使用。
- 呼び出し結果は WordPress の Transient API で 5分間キャッシュ。

### 3. フィルタ・ソート機能 (Alpine.js)
- **Hero Archive**: `/hero/` ページにて Alpine.js によるクライアントサイドフィルタ (`isVisible`) とソート (`sortItems`) を実装。
- **Default Sort**: 利便性のため、**最新世代 (Generation Descending)** をデフォルトの並び順に設定。

## Deployment (CI/CD)

GitHub Actions を使用して Xserver へ自動デプロイされます。

- **Workflow**: `.github/workflows/deploy.yml`
- **Environment**: Node.js **24** (Forced via `FORCE_JAVASCRIPT_ACTIONS_TO_NODE24`)
- **Trigger**: Push to `main`
- **Process**: `npm ci` -> `npm run build` -> FTP Upload (Theme dir only)
- **Secrets**: `FTP_SERVER`, `FTP_USERNAME`, `FTP_PASSWORD`, `SUPABASE_URL`, `SUPABASE_SERVICE_ROLE_KEY`

## Tier List Generator System

英雄のTierリストを自動管理するシステムです。Supabase の `heroes` テーブルをマスタデータとして、ショートコード `[wos_tier_list]` で表示します。

- **Shortcode**: `[wos_tier_list]` 
    - 世代（`gen`）引数でフィルタリング可能。
    - 言語設定に合わせて表示名とサブ名称を自動で入れ替え。
