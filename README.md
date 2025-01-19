# MSPRBACK_filrouge2

## setup XAMP virtualhost

In XAMPP installation directory (typically, C:\xampp) open the httpd-vhosts.conf file in the apache\conf\extra\  
Replace the contents of this file with the following directives:

    <VirtualHost *:80>
        DocumentRoot "C:/xampp/htdocs/"
        ServerName localhost
    </VirtualHost>
    <VirtualHost *:80>
        DocumentRoot "C:/xampp/wordpress/htdocs/app1"
        ServerName app1
    </VirtualHost>

restart XAMP apache

At this point, your virtual host is configured. However, if you try browsing to the wordpress.localhost domain, your browser will show a failure notice, since this domain does not exist in reality. To resolve this, it is necessary to map the custom domain to the local IP address. To do this, open the file C:\windows\system32\drivers\etc\hosts and add the following line to it:

MAKE A BACKUP COPY then edit as admin  

    127.0.0.1           wordpress.localhost

go to http://app1/ to access index.php

