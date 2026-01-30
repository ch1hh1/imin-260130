# AIご案内係

**ローカルPCでのみ動作**する、FAQ／ナレッジ検索型の案内システムです。  
要件定義書「AIご案内係_要件定義書v1.0」に基づき、実装指示書v1.0の範囲（RAG・AI・FR-005・非機能要件を除く）で実装しています。

## 技術スタック

- **フレームワーク**: Laravel 11
- **DB**: SQLite（デフォルト）。`.env` で MySQL に切り替え可能
- **起動**: `php artisan serve` で単一PC上で動作

## 前提条件

- PHP 8.2 以上
- Composer
- （任意）MySQL を使用する場合は MySQL サーバー

## ローカルでの起動手順

1. **リポジトリのクローン／展開**
   ```bash
   cd imin-260130
   ```

2. **依存関係のインストール**
   ```bash
   composer install
   ```

3. **環境設定**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
   - デフォルトでは SQLite を使用します。`database/database.sqlite` が存在することを確認してください（空ファイルで可）。
   - MySQL を使う場合は `.env` で `DB_CONNECTION=mysql` と DB 接続情報を設定し、`database/database.sqlite` は不要です。

4. **データベースの準備**
   ```bash
   php artisan migrate
   php artisan db:seed
   ```
   - 初回はロール（管理者・編集者・閲覧者・一般利用者）とサンプル管理者ユーザーが作成されます。
   - 管理者: `admin@example.com` / パスワード: `password`

5. **サーバー起動**
   ```bash
   php artisan serve
   ```
   - ブラウザで http://localhost:8000 を開きます。
   - 一般利用者向け: http://localhost:8000/chat
   - 管理画面: http://localhost:8000/admin （ログイン必要）

## 設定項目（config/ai_annai.php および .env）

| 項目 | 説明 | 例 |
|------|------|-----|
| `AI_ANNAI_QUESTION_MAX_LENGTH` | 質問の最大文字数 | 4000 |
| `AI_ANNAI_SESSION_TIMEOUT_MINUTES` | 会話セッションの無操作タイムアウト（分） | 30 |
| `AI_ANNAI_DISCLAIMER` | 免責文言 | 回答は参考情報であり… |
| `AI_ANNAI_SHOW_SOURCES` | 参照元（ナレッジ）を表示するか | true |
| `AI_ANNAI_LOG_RETENTION_DAYS` | ログ保持期間（日）の目安 | 90 |

## 機能概要

- **フロント（一般利用者）**: 質問入力、キーワード検索による回答表示、参照元表示、会話継続（セッション）、免責表示
- **管理画面**: ナレッジ（FAQ）の登録・編集・承認フロー（下書き／レビュー中／公開／アーカイブ）、検索・一覧、利用状況、会話ログ・操作ログ、CSV エクスポート、権限管理（ロール）

## 注意事項

- RAG・AI・Dify・LM Studio は使用していません。回答は登録ナレッジのキーワード検索で生成されます。
- FR-005（ガードレール）は未実装です。
- 本システムはローカルPCでの利用を想定しています。
