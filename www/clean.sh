#!/bin/sh
cd /var/www

rm -r html/todo/temp
rm -r html/todo/icons
rm -r html/todo/attachment
rm -r html/todo/logs
rm -r log

mkdir -p html/todo/temp
mkdir -p html/todo/icons
mkdir -p html/todo/attachment
mkdir -p html/todo/logs
mkdir    log

cp materials/*.png html/todo/icons/
cp materials/*.txt html/todo/attachment/
truncate -s 0 html/todo/logs/todolog

chown -R www-data:www-data html/todo/temp html/todo/icons html/todo/attachment html/todo/logs log
chmod 755 *.sh
