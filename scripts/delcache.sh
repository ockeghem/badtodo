echo "Nginxのキャッシュをすべて削除します。"
read -p "準備ができたらリターンキーを押して下さい。キャッシュの削除をやめる場合は CTRL-C を押してください"
docker exec -it badtodo-nginx /bin/sh -c "rm /var/cache/nginx/*"
