FROM httpd:bullseye
ARG TARGETARCH
RUN apt update \
    && apt install -y msmtp msmtp-mta imgp \
    && mkdir -p /var/www/html/ \
    && mkdir -p /usr/local/libxml2.7.8/ \
    && mkdir -p /etc/php/5.3/cgi/

# 以下はデバッグ用ツールのインストール（本番では必要なし）
# RUN apt install -y vim-tiny less iputils-ping net-tools procps
# デバッグ用終わり

COPY httpd.conf /usr/local/apache2/conf/

# msmtp 系の設定
COPY msmtprc /etc/

# php.ini
COPY php.ini /etc/php/5.3/cgi/

# アーキテクチャに応じて PHPバイナリをコピー
ADD  php-5.3.3p.bin-${TARGETARCH}.tar.gz /usr/local/apache2/cgi-bin
# アーキテクチャに応じて libxml2 をコピー
WORKDIR /usr/local/libxml2.7.8/lib
COPY libxml2.so.2.7.8-${TARGETARCH} libxml2.so.2.7.8
# 諸々の設定
RUN  ln -s libxml2.so.2.7.8 libxml2.so.2 \
  && ln -s libxml2.so.2.7.8 libxml2.so

# コンテンツの設置
ADD entrypoint.taz /root/
WORKDIR /var/www/
ENTRYPOINT /root/entrypoint.sh
