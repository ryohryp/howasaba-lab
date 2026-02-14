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
- **Usage**: Run `C:\tools\composer.bat` for all composer commands.

## テスト実行 (Running Tests)

このプロジェクトでは PHPUnit を使用しています。環境変数 PATH に PHP が含まれていないため、以下のコマンドを使用してください。

### コマンドライン
```powershell
C:\tools\php\php.exe vendor/bin/phpunit
```

## プロジェクト構造 (Project Structure)

テーマは `wos-survival` に統合されました（旧 `wos-frost-fire` の機能を継承）。

- **Root**: `i:\04_develop\howasaba-lab`
- **Active Theme**: `wos-survival`
- **Theme Path (Local)**: `wp-content/themes/wos-survival`
- **Deploy Target (Server)**: `/howasaba-code.com/public_html/wp-content/themes/wos-survival`
- **Tests**: `tests/`

## API エンドポイント (Custom REST API)

ギフトコード自動登録などのためにカスタムREST APIを実装しています。

- **Namespace**: `wos-radar/v1`
- **Endpoints**:
    - `POST /add-code`: ギフトコードの登録
        - Params: `code_string`, `rewards`, `expiration_date`
        - Note: 疎通確認用にGETリクエストも許可（固定メッセージ返却）している場合があります。

## デプロイ (CI/CD)

GitHub Actions を使用して Xserver へ自動デプロイされます。

### ワークフロー概要 (`.github/workflows/deploy.yml`)
- **トリガー**: `main` ブランチへのプッシュ
- **ビルドプロセス**:
    1. **Setup Node.js**: v18
    2. **Install**: `npm ci`
    3. **Build**: `npm run build` (Generate `dist/` assets)
- **デプロイ (FTP)**:
    - **Source**: `./wp-content/themes/wos-survival/` (Build artifacts included)
    - **Target**: `/howasaba-code.com/public_html/wp-content/themes/wos-survival/`
    - **Note**: `local-dir` を指定してテーマディレクトリのみをアップロードし、構造の意図しないネストを防いでいます。

### 必要な Secrets
- `FTP_SERVER`, `FTP_USERNAME`, `FTP_PASSWORD`
