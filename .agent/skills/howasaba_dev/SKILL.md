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
- **Theme Root (Local)**: `wp-content/themes/wos-frost-fire`
- **Theme Root (Server)**: `wp-content/themes/wos-furnace-core` (Deploy Target)
- **Tests**: `tests/`
- **Vendor**: `vendor/`

## 開発ガイドライン (Development Guidelines)

1. **言語**: すべてのドキュメント、コミットメッセージ、Artifactは **日本語** で作成してください。
2. **テスト**: 新機能開発時は、必ず `tests/` に対応するテストを追加し、`Run Tests` タスクで検証してください。
3. **依存関係**: 新しいパッケージを追加する場合は、必ず `C:\tools\composer.bat` を使用してください。

## デプロイ (CI/CD)

このプロジェクトは GitHub Actions を使用して Xserver へ自動デプロイされます。

### ワークフロー概要 (`.github/workflows/deploy.yml`)
- **トリガー**: `main` ブランチへのプッシュ
- **ビルド**:
    1. Node.js (v18) をセットアップ
    2. `npm ci` でフロントエンド依存関係をインストール
    3. `npm run build` でアセット（CSS/JS）をビルド
- **デプロイ**:
    - `SamKirkland/FTP-Deploy-Action` を使用して FTP でファイルを同期
    - **同期先**: `/howasaba-code.com/public_html/wp-content/themes/wos-furnace-core/`
    - **除外**: `.git`, `node_modules`, `src` などの開発用ファイル

### 必要な Secrets
GitHub リポジトリの Settings > Secrets and variables > Actions に以下を設定する必要があります：
- `FTP_SERVER`: `sv16627.xserver.jp`
- `FTP_USERNAME`: `ryohryp@howasaba-code.com`
- `FTP_PASSWORD`: (GitHub Secrets に設定してください)

