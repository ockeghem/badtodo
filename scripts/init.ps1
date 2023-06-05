Write-Output "データベースとコンテンツを初期化します。初期化をやめる場合は CTRL-C を押してください"
pause
docker exec -it badtodo-apache /var/www/clean.sh
docker exec -it badtodo-db /bin/sh -c "mysql -u root -pwasbook </docker-entrypoint-initdb.d/create-tables.sql"
