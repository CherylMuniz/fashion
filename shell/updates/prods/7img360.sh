#!/bin/bash
rsync -av /home/www/production/media/catalog/img360/ /home/www/demo/media/catalog/img360/

rsync -av /home/www/production/blog/wp-content/uploads/ /home/www/demo/blog/wp-content/uploads/