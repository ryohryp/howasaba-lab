# wos-furnace-core

WordPress Theme for "howasaba-code.com" (Server Core Design).
Designed with a "Tech/Cyberpunk" aesthetic using Tailwind CSS (CDN).

## デプロイ設定 (GitHub Actions)

このリポジトリは `main` ブランチへのプッシュ時に Xserver へ自動デプロイするように設定されています。

### 1. GitHub Secrets の設定

リポジトリの **Settings > Secrets and variables > Actions** に以下のシークレットを登録してください。

| Secret Name | Description | Example Value |
|---|---|---|
| `FTP_SERVER` | FTPホスト名 (Xserver) | `sv****.xserver.jp` |
| `FTP_USERNAME` | FTPユーザー名 | `your_username` |
| `FTP_PASSWORD` | FTPパスワード | `your_password` |
| `SERVER_DIR` | アップロード先ディレクトリ | `/your-domain.com/public_html/wp-content/themes/wos-furnace-core/` |

> [!IMPORTANT]
> `SERVER_DIR` は必ず末尾にスラッシュ `/` を付けてください。
> パスはXserverのFTPルートからの絶対パス、またはFTPアカウントのルートからの相対パスに合わせてください。

## WordPress 側の準備

### テーマのインストール
自動デプロイが成功すると、Xserver上の指定ディレクトリにテーマファイルが転送されます。
- 基本的に `FTP-Deploy-Action` は存在しないディレクトリを自動生成しようとしますが、権限エラー等を防ぐため、初回のみ **FTPソフト等で `wos-furnace-core` フォルダを手動作成** しておくことを推奨します。
- テーマ一覧に「wos-furnace-core」が表示されたら有効化してください。

### ツールページのセットアップ
`https://wos-navi.vercel.app/` を埋め込むための固定ページを作成します。

1. WordPress管理画面で **固定ページ > 新規追加** をクリック。
2. タイトルに「ツール」など任意の名前を入力。
3. 右側のサイドバー「ページ設定」または「テンプレート」セクションで、**テンプレート: Tools Page** を選択。
    - ※ ブロックエディタの場合は「テンプレート」パネルで切り替えます。
4. 本文は空のままでOKです。
5. 「公開」をクリック。

### トップページの設定
独自の「SERVER AGE」ヒーローセクションを表示するためには:
1. WordPress管理画面で **設定 > 表示設定** をクリック。
2. 「ホームページの表示」で「**固定ページ**」を選択する場合:
   - 専用のトップページ用固定ページを作成し、テンプレート（またはデフォルト）を適用します。
   - `front-page.php` が存在するため、WordPressの表示設定に関わらず、サイトのルートURLにアクセスするとこのテンプレートが優先的に使用される場合があります（設定によります）。
   - 確実に適用するには、表示設定で「最新の投稿」のままでも `front-page.php` が優先されます。
