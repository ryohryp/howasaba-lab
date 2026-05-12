# Howasaba Lab (WoS Frost & Fire) システム設計書

## 1. システム概要 (System Overview)

- **プロジェクト名称**: Howasaba Lab (テーマ名: `wos-survival` / 本番サーバー展開時フォルダ名: `wos-furnace-core`)
- **目的・コンセプト**:
  人気サバイバルシミュレーションゲーム「Whiteout Survival (WoS)」に特化した攻略・データベースポータルサイトの構築。
  **"Sharp & Vivid Survival"**（モダンフラットデザイン、ディープフリーズとファイアクリスタルを基調としたテック系美学）をテーマに、高速でインタラクティブなユーザー体験を提供します。
- **主要機能一覧**:
  1. **英雄データベース (Hero Database)**: クライアントサイドでの高速な世代別・兵種別フィルタリングとソート、アクセスロケールに応じた多言語動的表示 (i18n)。
  2. **自動ギフトコードレーダー (Gift Code Radar)**: Reddit RSS、Wiki等からの自動スクレイピング、高度な文脈解析によるコード抽出、重複排除、SupabaseおよびWordPressへの自動同期。
  3. **自動Tierリストジェネレーター (Tier List Generator)**: ショートコードを用いた最新環境メタに基づく英雄ランキング表の動的生成。
  4. **イベントスケジュール・データベース (Event Schedule & DB)**: ゲーム内イベントの開催期間、必要サーバー稼働日数、専用通貨およびショップ終了期間の統合管理。
  5. **データ一括投入・シード機能 (Data Seeders)**: 開発・メンテナンス用の一括データ投入・SQL生成システム。

---

## 2. システム・アーキテクチャ (System Architecture)

本システムは、**Supabase (構造化データ管理・高速API層)** と **WordPress (コンテンツ表示・CMS・フォールバック層)** を組み合わせた**ハイブリッド構成**を採用しています。
また、フロントエンドのアセット管理には **Vite Asset Loader** を導入し、開発環境と本番環境のビルド統合を自動化しています。

```mermaid
graph TD
    subgraph "External Data Sources"
        R["Reddit RSS Feed"]
        W["WoS Wiki HTML"]
        S["Official SNS Stub"]
    end

    subgraph "Automation & Batch Layer (Python)"
        FGC["fetch_gift_codes.py (Gift Code Radar)"]
        SDB["seed_full_database.py (DB Seeder)"]
    end

    subgraph "Data Layer (Supabase)"
        SB_H[("heroes Table")]
        SB_G[("gift_codes Table")]
    end

    subgraph "Backend Layer (WordPress)"
        WP_REST["Custom REST API (/wos-radar/v1)"]
        WP_DB[("MySQL / Transient Cache")]
        CPT_H["CPT: wos_hero"]
        CPT_G["CPT: gift_code"]
        CPT_E["CPT: wos_event"]
    end

    subgraph "Frontend & Build Layer (Vite + Tailwind + Alpine.js)"
        VAL["Vite Asset Loader"]
        DEV["Dev Mode: HMR / @vite/client (Port 5173)"]
        PROD["Prod Mode: manifest.json (Dist Assets)"]
        UI_H["英雄アーカイブページ (即時フィルタ/ソート)"]
        UI_T["Tierリスト表示 (ショートコード)"]
        UI_E["イベント情報表示"]
    end

    R -->|Feedparser| FGC
    W -->|BeautifulSoup| FGC
    S -->|Stub| FGC
    
    FGC -->|PostgREST API| SB_G
    FGC -->|POST /wos-radar/v1/add-code| WP_REST
    
    SDB -->|PostgREST API / SQL| SB_H
    SDB -->|REST API| WP_REST
    
    WP_REST --> CPT_G
    WP_REST --> CPT_H
    WP_REST --> CPT_E
    
    SB_H -->|Transient API (5分間キャッシュ)| WP_DB
    SB_G -->|Transient API (5分間キャッシュ)| WP_DB
    
    WP_DB --> VAL
    VAL -->|is_dev_mode() == true| DEV
    VAL -->|is_dev_mode() == false| PROD
    
    DEV --> UI_H
    DEV --> UI_T
    DEV --> UI_E
    PROD --> UI_H
    PROD --> UI_T
    PROD --> UI_E
```

---

## 3. データモデル設計 (Data Model Design)

### 3.1 英雄データ (Heroes)
Supabase側の `heroes` テーブルをマスタデータとし、WordPressのカスタム投稿タイプ `wos_hero` とメタデータをマッピングして連携動作します。

| フィールド名 (Supabase) | フィールド名 (WP Meta/Taxonomy) | データ型 | 説明・仕様 |
| :--- | :--- | :--- | :--- |
| `id` | `ID` (Post ID) | UUID / Int | 一意の識別子 |
| `name` | `post_title` | String | 英雄の英語名称（マスタ連携キー） |
| `japanese_name` | `japanese_name` (Meta) | String | 日本語表示名。サイトのロケール設定に基づき切り替え表示 |
| `slug` | `post_name` | String | URLスラッグ（小文字、ハイフン区切り） |
| `generation` | `hero_generation` (Taxonomy)<br>`generation` (Meta) | Int / Term | 英雄の登場世代（例: 1〜13） |
| `troop_type` | `hero_type` (Taxonomy)<br>`troop_type` (Meta) | String / Term | 兵種（Infantry, Lancer, Marksman） |
| `tier_overall` | `overall_tier` (Meta) | String | 総合Tier評価（S+, S, A, Bなど） |
| `skill_exploration_active` | `post_content` | String | スキル詳細および運用上の評価コメント・解説 |

### 3.2 ギフトコードデータ (Gift Codes)
| フィールド名 (Supabase) | フィールド名 (WP Meta/Taxonomy) | データ型 | 説明・仕様 |
| :--- | :--- | :--- | :--- |
| `id` | `ID` (Post ID) | UUID / Int | 識別子 |
| `code` | `code_string`<br>`_wos_code_string` | String | 特典コード文字列（内部処理および照合用にすべて大文字へ正規化） |
| `rewards` | `rewards`<br>`_wos_rewards` | String | 報酬内容、または発見元ソースページのコンテキスト情報 |
| `is_active` | `post_status` (publish) | Boolean | 有効状態フラグ |
| `created_at` | `post_date` | Timestamp | レーダーによる発見・登録日時 |
| - | `expiration_date`<br>`_wos_expiration_date` | Date | 有効期限（未明示の場合は発見から30日後に自動設定） |

### 3.3 イベントデータ (Events)
ゲーム内イベントの詳細スケジュールや参加条件を管理するため、専用のカスタム投稿タイプ `wos_event` を設計しています。

| フィールド名 (WP Meta/Taxonomy) | データ型 | 説明・仕様 |
| :--- | :--- | :--- |
| `ID` (Post ID) | Int | 識別子 |
| `post_title` | String | イベント名称 |
| `post_content` | String | イベントの詳細な攻略情報・ガイド |
| `event_type` (Taxonomy) | Term | イベントの種類・カテゴリ（階層型タクソノミー） |
| `_event_start_date` (Meta) | Date | イベント開始日 (`YYYY-MM-DD` 形式) |
| `_event_duration` (Meta) | String | イベント開催期間（例: `3 Days`） |
| `_server_age_requirement` (Meta) | Int | 参加に必要なサーバーの最小稼働日数 |
| `_event_currency_name` (Meta) | String | イベント固有の報酬・交換用通貨名（例: `寒玉コイン`） |
| `_event_shop_closing_date` (Meta) | Date | イベントショップ・交換所の最終閉鎖日 (`YYYY-MM-DD` 形式) |

---

## 4. バックエンド・API連携仕様 (Backend & API Specs)

### 4.1 Supabase API連携クライアント (`Supabase_Client`)
- **クラス定義**: `inc/class-supabase-client.php`
- **キャッシュ戦略**: 外部API呼び出しに伴う遅延とサーバー負荷を抑制するため、取得データはWordPress標準の **Transient API** を介して **300秒（5分間）** キャッシュされます。
- **キャッシュキー生成方式**: `sb_` + `md5( $table . serialize( $params ) )`
- **手動フラッシュ**: 静的メソッド `Supabase_Client::flush_cache()` を呼び出すことで、Supabase関連の全Transientをクリア可能。

### 4.2 カスタムREST API層 (`/wos-radar/v1/`)
- **エンドポイント定義**: `inc/api-endpoints.php`
- **セキュリティ・認証方式**: リクエストヘッダー `X-Radar-Token` による事前共有キー認証（`WosRadarSecret2026_Operation!`）を実施。認証成功時はシステム内部で管理者（User ID: 1）としてコンテキストを設定しセキュアにデータ書き込みを許可。
- **主要エンドポイント仕様**:
  - `POST /wp-json/wos-radar/v1/add-code`
    - **用途**: レーダースクリプトからの新規ギフトコード登録。
    - **重複制御**: `code_string` メタ、`_wos_code_string` メタ、および投稿タイトルを横断検索し、同一コードが既に存在する場合は新規作成を行わず、ステータス `200 OK` と識別コード `gift_code_exists` を返却（自動化パイプラインの不要な失敗・停止を防ぐためのフェイルセーフ設計）。
  - `POST /wp-json/wos-radar/v1/create-post`
    - **用途**: 外部からの汎用記事・コンテンツ自動生成。
  - `POST /wp-json/wos-radar/v1/update-post`
    - **用途**: スラッグをキーとした既存コンテンツおよびメタデータの差分更新。

---

## 5. フロントエンド・UI仕様 (Frontend & UI Specs)

### 5.1 英雄アーカイブ・リアルタイムフィルタシステム
- **構成技術**: Alpine.js + Tailwind CSS
- **動作仕様**: サーバーサイドで初期データをフルレンダリングし、Alpine.jsのディレクティブを用いてDOMの状態管理をクライアントサイドへ委譲。これにより、兵種・世代・レアリティのタブ切り替えやテキスト検索が**画面遷移・リロードなしのゼロレイテンシ**で動作します。
- **ソート制御**: ユーザーが最新の環境・新英雄情報に即座にアクセスできるよう、デフォルトの並び順を **「最新世代順 (Generation Descending)」** に設定。

### 5.2 動的Tierリストジェネレーター
- **構成技術**: `inc/shortcode-tier-list.php`
- **利用インターフェース**: ショートコード `[wos_tier_list gen="x"]`
- **レンダリング仕様**: 指定世代（省略時は全データ）の英雄を抽出し、メタデータ `overall_tier` の階層ごとにセクション分割して表示。兵種アイコンや多言語対応ラベルを自動付与したレスポンシブなカードレイアウトを生成。

### 5.3 Vite Asset Loaderによるビルド統合
- **クラス定義**: `inc/class-vite-asset-loader.php`
- **統合目的**: 最新のモジュール型JavaScriptやTailwind CSSのリアルタイムコンパイル結果をWordPressのテーマ機構へシームレスに統合します。
- **動作モード自動判定 (`is_dev_mode`)**:
  - `VITE_DEV_MODE` 定数が定義されている場合、または現在の環境タイプ (`wp_get_environment_type()`) が `local` / `development` に設定されているかを判別。
  - さらにローカル環境においては、Viteのデフォルト開発ポート **5173** へソケット通信を試行し、サーバーが実際に稼働している場合のみ開発モードを有効化する堅牢なフォールバック判定を実装しています。
- **エンキュー制御**:
  - **開発モード時**: Viteの開発サーバー (`http://localhost:5173`) から `@vite/client` を読み込み、`type="module"` 属性を付与してスクリプトをロードすることで、完全なHot Module Replacement (HMR) を実現します。
  - **本番モード時**: ビルド時に生成される `.vite/manifest.json` をパースし、キャッシュバスター用のハッシュが付与された実ファイル (`/dist/assets/...`) を自動ロードするとともに、依存するCSSファイル群も正しく関連付けて展開します。

---

## 6. 自動化・バッチ処理仕様 (Automation Scripts)

### 6.1 自動ギフトコードレーダー (`fetch_gift_codes.py`)
- **実行スケジューラ**: GitHub Actions クーロンジョブ（3時間おき実行: `0 */3 * * *`）。
- **データ収集元**:
  1. Reddit 新着RSSフィード (`r/whiteoutsurvival/new/.rss`)
  2. WoS Wiki ギフトコード一覧ページ HTML
- **コード抽出・フィルタリングアルゴリズム**:
  - **ノイズ除去**: タグストリップ後のテキストから5〜15文字の英数字境界を検出。事前定義された広範な除外リスト（`REDDIT`, `POST`, `SVS`, `UTC`, `THANKS` 等）に合致するトークンを破棄。
  - **キーワード重み付け**: 文字列内に `jpholiday` (優先度2.0), `wos` (優先度1.5), `whiteout` (優先度1.2) 等が含まれるかをスコアリング。
  - **判定ルール**:
    - **Rule A**: 文字列内に数字を含む、または特定キーワードから始まる場合は高確率で特典コードと判定。
    - **Rule B**: 英字のみで構成される場合、すべて大文字表記であること、かつ出現位置の手前50文字の文脈に `code`, `gift`, `cdk`, `redeem` などの特典関連キーワードが存在するかを厳密に検証。
- **データ永続化**: 抽出されたコード群は、Supabaseの `gift_codes` テーブルおよび WordPress の `/wos-radar/v1/add-code` エンドポイントへ並行して送信・登録。

### 6.2 データベース一括初期化・シード (`seed_full_database.py` / `seeders.php`)
- **外部スクリプト (`seed_full_database.py`)**: 第1世代から第13世代までの英雄の基本ステータス・日本語対応表・評価コメントを一元定義。WordPress APIを介して初期データを投入・同期すると同時に、Supabase用のバルクINSERT/UPDATE用SQLスクリプト (`update_heroes.sql`) を自動出力。
- **内部シード機能 (`seeders.php`)**: WordPressの管理者セッションにおいて `/wp-admin/?seed_heroes=1` のクエリパラメータ付きアクセスを行うことで、直接内部関数からデータベース構造をセットアップ・初期化できる開発者向けユーティリティ。

---

## 7. CI/CD・デプロイメント仕様 (CI/CD Pipelines)

- **ワークフロー定義ファイル**: `.github/workflows/deploy.yml`, `.github/workflows/giftcode-radar.yml`
- **同期先サーバー**: Xserver (`sv16627.xserver.jp`) の指定テーマディレクトリ。
- **自動化プロセス**:
  1. リポジトリの `main` ブランチへのプッシュ検知。
  2. 依存関係のクリーンインストール (`npm ci`)。
  3. プロダクション用アセットのビルド・バンドル (`npm run build`)。
  4. 生成されたテーマ配下のファイルをFTP経由でサーバーへ同期転送。
- **実行ランタイム要件**: 安定したビルド環境確保のため、Node.js **24** を強制適用 (`FORCE_JAVASCRIPT_ACTIONS_TO_NODE24` 指定)。
