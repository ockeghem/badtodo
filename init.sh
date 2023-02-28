echo データベースとコンテンツを初期化します。
read -p "準備ができたらリターンキーを押しいて下さい。初期化をやめる場合は CTRL-C を押してください"
docker exec -it badtodo-apache /var/www/clean.sh
docker exec -it badtodo-db /bin/sh -c "mysql -u root -pwasbook </docker-entrypoint-initdb.d/create-tables.sql"
