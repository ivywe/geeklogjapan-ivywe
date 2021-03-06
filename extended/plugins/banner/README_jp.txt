= バナープラグイン(Banner Plugin) =
                                                                 2010/04/26
                           hiroshi sakuramoto    hiroron AT hiroron DOT com
                              Presented by:Ivy komma AT ivywe DOT co DOT jp
                                                                 ver: 1.1.1

このプラグインはGeeklogでバナー(画像広告)を管理するプラグインです。掲載期間を設定することで表示開始時期の設定から表示終了時期を自由に設定することができます。また掲載期間を入力しないことも可能でその場合は無期限に表示されます。またカテゴリを作ることができ、カテゴリ内のバナーをランダムでローテーション表示させることができます。ヘッダやフッタへのバナー表示もテンプレートファイルの簡単な編集でバナーを表示させることができるようになっています。バナーごとのクリック数のカウントも行っており管理画面で確認することができます。
バナー登録もアドバンストエディタを使って簡単に行えます。



== インストール方法 ==

=== お勧めインストール方法(Windows限定) ===

wkyGeeklog自動インストーラー(wkyGeeklogInstaller)を使ってのインストールをお勧めします。

wkyGeeklogInstaller(以下wGI)は簡単にGeeklogをインストールできるソフトです。
プラグインやテーマのインストールにも対応し、レシピと呼ばれる、インストール用の
設定などが書いてある専用ファイルをダウンロードして取得し、wGIを起動してから、
その画面にドラッグ＆ドロップするだけでインストールができます。

wkyGeeklogInstallerダウンロード
http://hiroron.com/filemgmt/viewcat.php?cid=3
※最新バージョンのものを選んでダウンロード下さい

Bannerプラグインインストール用レシピダウンロード
http://hiroron.com/filemgmt/index.php?id=174


=== インストール手順 ===

 * public_html,admin ディレクトリ以外のディレクトリとファイルを非公開ディレクトリの plugins/banner を作成し、その中へコピーします。
 * public_html ディレクトリを公開ディレクトリの banner を作成し、コピーします。
 * admin ディレクトリを公開ディレクトリの admin/plugins/banner を作成し、コピーします。
 * 管理者権限をもつユーザでログインし、プラグインからインストールします。

以上までで問題がでなければインストールは終わりです。



== アンインストール方法 ==

=== 手順の説明 ===

 * 管理者権限をもつユーザでログインし、プラグインからアンインストールします。
 * アンインストールが無事できたら、インストール時に配置したディレクトリやファイルを削除します。

以上でアンインストールは完了です。





== 使い方/使用方法 ==

=== 自動タグ(Autotag)でバナーを設置 ===

この機能はGeeklogの標準機能を使っているため、自動タグプラグイン(Autotag Plugin)が入っていなくても使えます。記事やブロックや静的ページなどで自動タグは使えます。

==== 単独のバナーを表示する方法 ====

自動タグ [banner:] を使います。指定のバナーIDが掲載期間内ならバナーが表示されます。

  [banner:バナーID (バナータイトル)]
  ※バナータイトルはオプションです。指定しない場合はバナー登録時のタイトルが使われます。

==== ランダムにローテーションするバナーを表示する方法 ====

自動タグ [randombanner:] を使います。指定のカテゴリIDに登録されているバナーから掲載期間内のバナーが表示されます。

  [randombanner:カテゴリID (バナータイトル)]
  ※バナータイトルはオプションです。指定しない場合はバナー登録時のタイトルが使われます。

==== カテゴリ内の複数バナーを全て表示する方法 ====

自動タグ [bannercategory:] を使います。指定のカテゴリIDに登録されているバナーから掲載期間内のバナーが全て表示されます。

  [bannercategory:カテゴリID (表示フォーマット)]
  ※表示フォーマットはオプションです。複数バナーを整列させるために利用します。
  ※フォーマットには%sを必ず含めてください。含めないとバナーが表示されません。。
  ※初期値は「%s<br>」です。これは複数バナーが１つずつ改行されて表示されます。
  ※表示フォーマットを「<li>%s</li>」と指定すればリスト表示で複数バナーを表示できます。



=== ヘッダやフッタにバナーを設置 ===

Geeklogのテンプレート header.thml,footer.thtml の中でテーマ変数 {banner}、{randombanner} を使うことでバナーを表示することができます。
 {banner} に表示されるバナーは、ヘッダーテンプレートにはバナーID「header」で登録されているもの、フッターテンプレートはバナーID「footer」で登録されているものが表示されます。
 {randombanner} に表示されるバナーは、ヘッダーテンプレートにはカテゴリID「header」に登録されているもの、フッターテンプレートはカテゴリID「footer」に登録されているものからランダムでローテーションしながら表示されます。
どちらのテーマ変数も掲載期間が登録されていれば掲載期間内のものだけが対象となります。

==== サンプル）ヘッダにバナーを設置 ====

ProfessionalCSSテーマのヘッダへバナーを設置する例です。設置のご参考にしてください。

===== headerカテゴリを作成 =====

カテゴリID「header」はプラグインインストール時に作成されています。
このカテゴリがないときはカテゴリの新規作成でカテゴリIDを「header」で作成します。
バナーの作成で、今作ったカテゴリを選らんで保存します。
※プラグインのインストール時にサンプルが１つ登録されています。

===== 設定を変更 =====

「コンフィギュレーション」＞「バナー」＞「バナーの管理」の「テンプレートでバナーを表示する」を「はい」に変更して設定を保存します。
※一度変更すれば再度変更する必要はありません。

===== ヘッダのテンプレートを編集 =====

public_html/layout/ProfessionalCSS/header.thtmlへバナー表示するために1行追加。
以下の例では「<div style="margin:0;padding:10px 25px;float:left;">{randombanner}</div>」を追加しています

      <!-- #header { -->
      <!-- ヘッダコンテナ -->
      <div id="header">
        <h1 class="site_name"><a href="{site_url}"><img src="{layout_url}/images/logo.gif" width="157" height="56" alt="{site_name}"{xhtml}></a></h1>
        <div style="margin:0;padding:10px 25px;float:left;">{randombanner}</div>
        <p class="site_slogan">{site_slogan}</p>
      </div>
      <!-- } #header -->


==== サンプル）フッタにバナーを設置 ====

ProfessionalCSSテーマのフッタへバナーを設置する例です。設置のご参考にしてください。

===== footerカテゴリを作成 =====

カテゴリID「footer」はプラグインインストール時に作成されています。
このカテゴリがないときはカテゴリの新規作成でカテゴリIDを「footer」で作成します。
バナーの作成で、今作ったカテゴリを選らんで保存します。
※プラグインのインストール時にサンプルが１つ登録されています。

===== 設定を変更 =====

「コンフィギュレーション」＞「バナー」＞「バナーの管理」の「テンプレートでバナーを表示する」を「はい」に変更して設定を保存します。
※一度変更すれば再度変更する必要はありません。

===== フッタのテンプレートを編集 =====

public_html/layout/ProfessionalCSS/footer.thtmlへバナー表示するために1行追加。
以下の例では「<li class="footer_banner">{randombanner}</li>」を追加しています

  <!-- #footer { -->
  <!-- フッタコンテナ -->
  <div id="footer">
    <ul>
      <li class="footer_banner">{randombanner}</li>
      <li class="copyright_notice"><address>{copyright_notice}</address></li>
      <li class="powered_by">{powered_by}&nbsp;<a href="http://www.geeklog.net/">Geeklog</a>&amp;<a href="http://www.geeklog.jp/">Geeklog Japanese</a></li>
      <!-- Theme by Fumito Arakawa as Phize (http://phize.net/) -->
      <li class="execution_textandtime">{execution_textandtime}</li>
    </ul>
  </div>
  <!-- } #footer -->




=== 話題ごとのブロックへバナーを設置 ===

PHPブロックを使います。カテゴリ登録時に設定された話題ごとにバナーが表示されます。該当の話題の場合にその話題が設定されているカテゴリ内のバナーが全て表示される phpblock_topic_banner と、その中からランダムで１つだけ表示される phpblock_topic_randombanner が使えます。
これらはブロックの編集でタイプに「PHPブロック」を選択してPHPブロックのオプションの関数に入力してください。

  phpblock_topic_banner

    該当の話題が設定してあるカテゴリすべてが対象になる。
    そのカテゴリ内で掲載期間内のもの全てのバナーが表示されます。

  phpblock_topic_randombanner

    該当の話題が設定してあるカテゴリすべてが対象になる。
    そのカテゴリ内で掲載期間内のものからランダムで１つだけバナー表示されます。

※これを使えばPHPブロックを１つ作成するだけで、話題ごとに違うバナー表示に切り替えることが可能です。PHPブロックを使わずに実現しようとすると、ノーマルブロックへの自動タグによるバナー表示を話題をそれぞれに指定して話題の数だけ作成する必要があります。



=== MGブラウザを使う場合の注意点 ===

メディアギャラリプラグイン(mediagallery)とMGメディアブラウザ(mgmediabrowser)がインストールしてある環境だとツールバーにMGアイコンが出てきてMGメディアブラウザが使えますが、使用方法に若干癖がありますので紹介いたします。

  「リンク」は必ず「なし」にして使ってください。リンクはバナープラグインが作成します。

  「位置」も「なし」がおすすめです。



=== イベント告知（情報）などのページにバナープラグインを利用 ===

///アイデア: kobabさん

URLを未入力にすればリンクのないバナーが作れますので、イベント告知用などの静的ページを１つ用意して、そこへ自動タグ [bannercategory:] などにて、表示期間をコントロールできるバナーを表示できます。バナーの説明にはHTMLがそのまま使えるので、それぞれのイベント告知をするHTMLを入力しておくことが可能です。


