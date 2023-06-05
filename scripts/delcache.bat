@echo off
echo Nginxのキャッシュをすべて削除します。キャッシュの削除をやめる場合は CTRL-C を押してください
pause
docker exec -it badtodo-nginx /bin/sh -c "rm /var/cache/nginx/*"
