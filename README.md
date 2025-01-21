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

## set up mongodb for php

 Install MongoDB (if not already installed)

    Download and install MongoDB from the official website: https://www.mongodb.com/try/download/community.
    Run MongoDB with the following command:

    mongod

    (Ensure that the MongoDB service is running.)

2. Install Composer (if not already installed)
    
    Install Composer for PHP dependency management: https://getcomposer.org/.

3. Install the MongoDB extension for PHP

The MongoDB extension for PHP allows PHP to connect to and interact with MongoDB.
3.1. Install the MongoDB PHP library with Composer:

In your terminal or command prompt, run the following command to add the MongoDB library to your PHP project:

    composer require mongodb/mongodb

### if not working you might need to add manually the extention:
Steps to Manually Add MongoDB Extension with XAMPP
1. Download the MongoDB PHP Extension

    Go to the PECL MongoDB page: PECL MongoDB Package.
    Download the appropriate version of the php_mongodb.dll file for your PHP version. You need to choose the version that matches:
        PHP version: For example, if you're using PHP 7.4, make sure to download the version of the MongoDB extension that corresponds to PHP 7.4.
        Thread Safety: You should also choose between Thread Safe (TS) and Non-Thread Safe (NTS) based on your PHP setup. For XAMPP, it is typically Thread Safe.
        Download the file like php_mongodb-1.11.3-7.4-ts-x86_64.dll (for 64-bit systems).

2. Locate Your PHP Extension Directory

    Open your XAMPP installation folder. Typically, it is installed in:
        Windows: C:\xampp\php\

    Inside the php folder, locate the ext folder. This is where PHP extensions are stored.
        The path would look like this: C:\xampp\php\ext\

    Copy the downloaded php_mongodb.dll to this ext directory.

3. Edit the php.ini File

    In your XAMPP folder, locate and open the php.ini file for editing.
        The path to the php.ini file is usually: C:\xampp\php\php.ini

    Add the MongoDB extension to php.ini:
        Open php.ini in a text editor like Notepad or any code editor.
        Scroll down to the section where extensions are listed, and add the following line (make sure it is not commented out with a semicolon ;):

extension=php_mongodb.dll

Save and close the php.ini file.

## connect to mongodb

    <?php 

    require 'vendor/autoload.php'; // Autoload Composer (si vous utilisez Composer)

    use MongoDB\Client;

    // Test MongoDB connection
    try {
        $client = new Client("mongodb://localhost:27017");
        echo "MongoDB connection established!";
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
    ?>

## Environment variables

create and update .htaccess file for environment variables:



    SetEnv DB_HOST mysqlDbUrl
    SetEnv DB_USER mysqlUserName
    SetEnv DB_PASSWORD mysqlPassword
    SetEnv DB_DBNAME mysqlDbName

    SetEnv MDB_HOST mongodbDbUrl
    SetEnv MDB_USER mongodbUserName
    SetEnv MDB_PASSWORD mongodbPassword
    SetEnv MDB_DBNAME mongodbDbName





