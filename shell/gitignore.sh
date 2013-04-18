rsync -av --exclude='*cache*' /home/www/demo/media/ /home/www/new/media/
rsync -av --exclude='*cache*' --exclude='*session*' /home/www/demo/var/ /home/www/new/var/
rsync -av /home/www/demo/blog/wp-content/uploads/  /home/www/new/blog/wp-content/uploads/

#check
diff -r /home/www/new/app /home/www/demo/app/
diff -r /home/www/new/skin/ /home/www/demo/skin/
diff -r /home/www/new/shell/ /home/www/demo/shell/

rsync -av --exclude='*cache*' --exclude='*img360*' /home/www/production/media/ /home/www/demo/media/