このファイル群が提供するものは、脆弱性診実習用アプリ（通称「やられサイト」）Bad Todo Listです。特徴は以下の通りです。

- Windows、Mac（Intel、Apple Silicone）、Linux環境で動作可能
- LAMP(Linux+APache+MySQL+PHP)で開発された古典的なマルチページアプリケーション
- 多くの種類の脆弱性を含む
  - ウェブ健康診断仕様の13種類の脆弱性
  - 安全なウェブサイトの作り方、OWASP Top 10、徳丸本などに掲載の主要脆弱性を網羅
  - 脆弱性スキャナでは発見することが難しい診断項目を多く含む
- Burp Suiteによる実習に最適化（他のツールでの実習も可能）

脆弱性診断で出てくるであろう基本的な脆弱性が網羅されているので、これを一通り習得すれば、あなたも立派な「脆弱性診断員」ではないでしょうかw

改訂履歴は[CHANGELOG](./CHANGELOG.md)を参照ください。

## [インストール方法](docs/install.md)

## [バージョンアップ方法](docs/versionup.md)

## [使い方](docs/usage.md)

## [対応している脆弱性](docs/vulnerabilities.md)

## ライセンス

### 本ソフトウェアの利用に関して
- 本ソフトウェアの著作権は[徳丸浩](https://twitter.com/ockeghem/)に帰属します
- 本ソフトウェアBad Todo Listは、非営利目的の個人のみ利用できます。営利目的での利用を希望される場合は[徳丸浩](https://twitter.com/ockeghem/)までご連絡ください。
- 本ソフトウェアを変更して配布することはできません。
- 本ソフトウェアの使用条件はクリエイティブ・コモンズ・ライセンスの下で配布を許可します。
- クリエイティブ・コモンズ・ライセンスの詳細や表示については、[クリエイティブ・コモンズ・ジャパンのウェブサイト](http://creativecommons.jp)をご参照ください。 

### 学校・教育機関での利用について
以下のいずれかを満たす教育機関等では、申請いただければ、費用等の必要なくBad Todoをご利用いただけます。

- 学校教育法で定められた学校およびその学校法人
- 国および地方自治体で設立および管轄している大学校、大学共同利用機関
- 公共職業能力開発施設および職業訓練法人

詳しくは[こちらの利用申込みフォーム](https://docs.google.com/forms/d/e/1FAIpQLSdlQTG6t9V7JFpEfL0DHKvMzR98AsaDV3B997y3BMEutHmE-Q/viewform)記載内容をご確認の上、お申し込みください。

<a rel="license" href="http://creativecommons.org/licenses/by-nc-nd/4.0/"><img alt="クリエイティブ・コモンズ・ライセンス" style="border-width:0" src="https://i.creativecommons.org/l/by-nc-nd/4.0/88x31.png" /></a><br />この 作品 は <a rel="license" href="http://creativecommons.org/licenses/by-nc-nd/4.0/">クリエイティブ・コモンズ 表示 - 非営利 - 改変禁止 4.0 国際 ライセンス</a>の下に提供されています。

## 利用ソフトウェア一覧

- Nginx: リバースプロキシ
- Apache: Webサーバー
- PHP 5.3.3: 言語
- MariaDB: データベース
- MailCatcher: メール確認用ソフトウェア
- Adminer: データベース管理ソフトウェア
- Libxml2: XML解析ライブラリ
- jQuery: JavaScriptライブラリ
- DOMPurify: XSSサニタイズ用JavaScriptライブラリ

これらソフトウェアについては、それぞれのライセンスに従います。

## アイコンについて
Bad Todo Listを利用するためには、会員登録時にアイコン画像を指定する必要があります。実習をスムーズに進められるように、アイコン用画像を /materials フォルダにて用意しました。アイコンの元画像は以下のフリー素材を利用させていただいております。これら素材については、それぞれのライセンスに従います。

むぎちゃさん（イラストAC）
https://www.ac-illust.com/main/profile.php?id=GwsmhQzT&area=1


## 免責

当著作物は、いかなる保証もせず、あるがままに提供されます。
ドキュメントおよびソフトウェア等は改善のため、予告なく変更する場合があります。

