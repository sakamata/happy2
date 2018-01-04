# Happy2 docker

Happy2のシステムをdockerコンテナ起動します。

dockerがインストールされたローカルPC上で、Happyサービスを立ち上げ、アプリを体験することができます。


## Get Started

システムはアプリとデータベースで構成されます。

これらのサービスをdocker-composeでまとめてbuildして、upすることで、Happyシステムが起動します。

```
$ git clone --depth 1 https://github.com/sakamata/happy2.git
$ cd happy2/docker

必要なファイルは、このディレクトリ以下にあります。
$ ls
README.md		app			db			docker-compose.yml

必要なコードはbuildでgit取得されます。
$ docker-compose build
db uses an image, skipping
Building app
Step 1/21 : FROM centos:centos7
 :
 :
Successfully tagged docker_app:latest

システム起動
$ docker-compose up -d
Creating network "docker_default" with the default driver
Pulling db (mysql:latest)...
  :
Creating happy2-db ... 
Creating happy2-db ... done
Creating happy2-app ... 
Creating happy2-app ... done
```

システム起動後、以下のローカルホストURLへアクセスするとHappyサービスのログイン画面が表示されます。

[https://localhost/happy2/web/](https://localhost/happy2/web/)

「[新規ユーザ登録はこちら](https://localhost/happy2/web/account/signup)」から始めてください。
(Facebookからの登録は無効になっています。)

## 管理画面

[管理画面へのログイン](https://localhost/happy2/web/digest/signinugsffx01geo)

管理ツールにログインするには、Apacheのダイジェスト認証と管理画面の管理ユーザ認証の２段階があります。

どちらの認証も同じユーザ・パスワードでログインできるように、パスワードを上書きするコマンドを用意しました。
システム起動後に、以下のコマンドを実行すると、２段階の認証をadmin/passwordでログインできるようになります。

```
$ docker exec -it happy2-app /update-admin-password.sh
```

## docker

dockerのインストーラは以下のページよりダウンロードできます。

- [Windows 10 64bit Pro](https://www.docker.com/docker-windows)
- [Mac OSX Yosemite 10.10.3以上](https://www.docker.com/docker-mac)

### アプリ(app)とデータベース(db)

アプリのWebサーバにはApache、データベースにはMySQLを使用しています。
アプリにはWebサーバの他に、WebSocketがphpで8000番ポートで起動します。

データベースのコンテナはDockerfileからbuildするのではなく、[mysql](https://hub.docker.com/r/_/mysql/)のイメージに設定と初期データのあるディレクトリをデータ・ボリュームとしてマウントさせています。

データベースのデータ領域は db/datadir/ に保存され、システムの再起動でもデータは保持されます。
データを初期化したい場合は、data/datadir ディレクトリを削除してください。

## 制限事項

Happy2システムのdockerコンテナでは、まだ確認できない機能がいくつかあります。

- ログイン後のホーム画面でリロードすると、値が初期化され、前の状態を表示できない。
- 集計機能がない。
- 履歴が更新されなくなることがある。

## 更新履歴

### 2017-11-29
-  appコンテナでWebSocketを起動。通知機能がONに。

