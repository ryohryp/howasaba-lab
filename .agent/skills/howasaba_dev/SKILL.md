---
name: Howasaba Lab Dev
description: Development workflow and environment details for Howasaba Lab WordPress project.
---

# Howasaba Lab Development Skill

このスキルは、Howasaba Lab プロジェクト（WordPressテーマ開発）における開発環境とワークフローを定義します。

## 環境設定 (Environment Setup)

### PHP
- **Executable Path**: `C:\tools\php\php.exe`
- **Version**: 8.3.29
- **Configuration**: `C:\tools\php\php.ini` (Extensions: curl, mbstring, openssl, zip enabled)

### Composer
- **Wrapper Script**: `C:\tools\composer.bat`
- **Executable**: `C:\tools\composer.phar`
- **Usage**: Run `C:\tools\composer.bat` for all composer commands to ensure the correct PHP version is used.

## テスト実行 (Running Tests)

このプロジェクトでは PHPUnit を使用しています。環境変数 PATH に PHP が含まれていないため、以下のコマンドを使用してください。

### コマンドライン
```powershell
C:\tools\php\php.exe vendor/bin/phpunit
```

### VS Code タスク
- **Task Name**: `Run Tests`
- **Shortcut**: `Ctrl+Shift+B` (ビルドタスクとして設定されている場合)

## プロジェクト構造 (Project Structure)

- **Root**: `i:\04_develop\howasaba-lab`
- **Active Theme**: `wos-survival`
- **Theme Path (Local)**: `wp-content/themes/wos-survival`
- **Deploy Target (Server)**: `/howasaba-code.com/public_html/wp-content/themes/wos-survival`
- **Tests**: `tests/`
- **Vendor**: `vendor/`

## API エンドポイント (Custom REST API)

ギフトコード自動登録などのためにカスタムREST APIを実装しています。

- **Namespace**: `wos-radar/v1`
- **Base URL**: `/wp-json/wos-radar/v1`
- **Endpoints**:
    - `POST /add-code`: ギフトコードの登録
        - Required Caps: `edit_posts` (Application Passwords recommended)
        - Params: `code_string`, `rewards`, `expiration_date`

## 開発ガイドライン (Development Guidelines)

1. **言語**: すべてのドキュメント、コミットメッセージ、Artifactは **日本語** で作成してください。特に `implementation_plan.md` と `task.md` は必ず日本語で記述してください。
2. **テスト**: 新機能開発時は、必ず `tests/` に対応するテストを追加し、`Run Tests` タスクで検証してください。
3. **依存関係**: 新しいパッケージを追加する場合は、必ず `C:\tools\composer.bat` を使用してください。

## デプロイ (CI/CD)

このプロジェクトは GitHub Actions を使用して Xserver へ自動デプロイされます。

### ワークフロー概要 (`.github/workflows/deploy.yml`)
- **トリガー**: `main` ブランチへのプッシュ
- **デプロイ**:
    - `SamKirkland/FTP-Deploy-Action` を使用して FTP でファイルを同期
    - **Local Source**: `./wp-content/themes/wos-survival/` (テーマ配下のみアップロード)
    - **Remote Target**: `/howasaba-code.com/public_html/wp-content/themes/wos-survival/` (FTP Root相対パス)
    - **除外**: `.git`, `node_modules` など

### 重要な注意点 (Deploy Pitfalls)
- **FTP Root**: XserverのFTPアカウントはホームディレクトリ直下ではなく `/home/[user]/` がルートになることがあるため、絶対パス `/home/...` で指定すると二重ネストの原因になります。必ず `ftp-cmd ls` 等でルートを確認し、ルートからの相対パスで指定してください。
- **Directory Nesting**: `local-dir` を指定しない場合、リポジトリのルートから構造ごとアップロードされるため、サーバー側で深い階層（`.../theme/wp-content/themes/theme/...`）が作られてしまいます。必ず `local-dir` でアップロード元を限定してください。

### 必要な Secrets
GitHub リポジトリの Settings > Secrets and variables > Actions に以下を設定する必要があります：
- `FTP_SERVER`: `sv16627.xserver.jp`
- `FTP_USERNAME`: `ryohryp@howasaba-code.com`
- `FTP_PASSWORD`: (GitHub Secrets に設定してください)
