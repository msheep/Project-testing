Author: JING YANG(715861)

This is a tool for testing the new feature of WPS on QGIS to mock server. The environment conditions are as following:
PHP: 7.0.12
Apache: 2.4.23
MySQL: 5.7.16

index.php --- the entry program
common.php --- contains the global settings
cookie.php --- provide the function to save cookies
delete.php --- the function to delete cookies
test.php --- get the latest request from database
project.sql ---- there are two tables in this project, one is request that stored the request information from clients, another one is cookie which contains the cookie settings.

The steps to install this application:
1. install Apache, PHP, MySQL
2. edit the /etc/hosts and add your new host, such as www.wpstest.com
3. edit the /etc/apache2/extra/httpd-vhosts.conf to add new setting, such as
<VirtualHost *:80>
    DocumentRoot "/Users/eunice/Sites/project"
    ServerName www.wpstest.com
    ErrorLog "/private/var/log/apache2/sites-error_log"
    CustomLog "/private/var/log/apache2/sites-access_log" common
    <Directory "/Users/eunice/Sites/project">
        Options Indexes FollowSymLinks MultiViews
        AllowOverride All
        Require all granted
        #Order allow,deny
        #Allow from all
    </Directory>
</VirtualHost>
4. restart Apache 
5. you can access the test tool!