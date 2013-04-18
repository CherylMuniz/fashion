rsync -av --exclude='*cache*' /home/www/demo/media/ /home/www/new/media/
rsync -av --exclude='*cache*' --exclude='*session*' /home/www/demo/var/ /home/www/new/var/
rsync -av /home/www/demo/blog/wp-content/uploads/  /home/www/new/blog/wp-content/uploads/

rsync -av --exclude='*cache*' --exclude='*img360*' /home/www/production/media/ /home/www/demo/media/