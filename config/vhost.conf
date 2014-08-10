<VirtualHost *:80>
	ServerName zcgd2014.aoxpro.com 
	ServerAdmin jim.dev@qq.com
	DocumentRoot /usr/local/apps/aoxpro_zcgd/htdocs
	DirectoryIndex index.html index.php

	<Directory />
		Options FollowSymLinks
		AllowOverride None
	</Directory>

	<Directory /usr/local/apps/aoxpro_zcgd/htdocs>
		Options Includes Indexes FollowSymLinks
		AllowOverride None
		Order allow,deny
		allow from all
		XBitHack on
	</Directory>
</VirtualHost>
