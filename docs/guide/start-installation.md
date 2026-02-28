Installation
============

## Requirements

The minimum requirement by this project template is that your Web server supports PHP 8.2.0.

## Installing using Composer

If you do not have [Composer](https://getcomposer.org/), follow the instructions in the
[Installing Yii](https://github.com/yiisoft/yii2/blob/master/docs/guide/start-installation.md#installing-via-composer)
section of the definitive guide to install it.

With Composer installed, you can then install the application using the following commands:

    composer create-project --prefer-dist yiisoft/yii2-app-advanced yii-application

The command installs the advanced application in a directory named `yii-application`. You can choose a different
directory name if you want.

It uses [asset-packagist](https://asset-packagist.org/) for managing bower and npm package dependencies through
Composer.

Also, you can use [asset-plugin](https://packagist.org/packages/fxp/composer-asset-plugin), as in earlier versions, but
it works slowly.

## Preparing application

After you install the application, you have to conduct the following steps to initialize
the installed application. You only need to do these once for all.

1. Open a console terminal, execute the `init` command and select `dev` as environment.

   ```
   /path/to/php-bin/php /path/to/yii-application/init
   ```

   If you automate it with a script you can execute `init` in non-interactive mode.

   ```
   /path/to/php-bin/php /path/to/yii-application/init --env=Development --overwrite=All --delete=All
   ```

2. Create a new database and adjust the `components['db']` configuration in
   `/path/to/yii-application/common/config/main-local.php` accordingly.

3. Open a console terminal, apply migrations with command `/path/to/php-bin/php /path/to/yii-application/yii migrate`.

4. Set document roots of your web server:

    - for your main WebApp "frontpage" `/path/to/yii-application/frontpage/web/` and using the URL `http://frontpage.test/`
    - for backoffice `/path/to/yii-application/backoffice/web/` and using the URL `http://backoffice.test/`

   For Apache it could be the following:

   ```apache
       <VirtualHost *:80>
           ServerName frontpage.test
           DocumentRoot "/path/to/yii-application/frontpage/web/"
           
           <Directory "/path/to/yii-application/frontpage/web/">
               # use mod_rewrite for pretty URL support
               RewriteEngine on
               # If a directory or a file exists, use the request directly
               RewriteCond %{REQUEST_FILENAME} !-f
               RewriteCond %{REQUEST_FILENAME} !-d
               # Otherwise forward the request to index.php
               RewriteRule . index.php

               # use index.php as index file
               DirectoryIndex index.php

               # ...other settings...
               # Apache 2.4
               Require all granted
               
               ## Apache 2.2
               # Order allow,deny
               # Allow from all
           </Directory>
       </VirtualHost>
       
       <VirtualHost *:80>
           ServerName backoffice.test
           DocumentRoot "/path/to/yii-application/backoffice/web/"
           
           <Directory "/path/to/yii-application/backoffice/web/">
               # use mod_rewrite for pretty URL support
               RewriteEngine on
               # If a directory or a file exists, use the request directly
               RewriteCond %{REQUEST_FILENAME} !-f
               RewriteCond %{REQUEST_FILENAME} !-d
               # Otherwise forward the request to index.php
               RewriteRule . index.php

               # use index.php as index file
               DirectoryIndex index.php

               # ...other settings...
               # Apache 2.4
               Require all granted
               
               ## Apache 2.2
               # Order allow,deny
               # Allow from all
           </Directory>
       </VirtualHost>
   ```

   For nginx:

   ```nginx
       server {
           charset utf-8;
           client_max_body_size 128M;

           listen 80; ## listen for ipv4
           #listen [::]:80 default_server ipv6only=on; ## listen for ipv6

           server_name frontpage.test;
           root        /path/to/yii-application/frontpage/web/;
           index       index.php;

           access_log  /path/to/yii-application/log/frontpage-access.log;
           error_log   /path/to/yii-application/log/frontpage-error.log;

           location / {
               # Redirect everything that isn't a real file to index.php
               try_files $uri $uri/ /index.php$is_args$args;
           }

           # uncomment to avoid processing of calls to non-existing static files by Yii
           #location ~ \.(js|css|png|jpg|gif|swf|ico|pdf|mov|fla|zip|rar)$ {
           #    try_files $uri =404;
           #}
           #error_page 404 /404.html;

           # deny accessing php files for the /assets directory
           location ~ ^/assets/.*\.php$ {
               deny all;
           }

           location ~ \.php$ {
               include fastcgi_params;
               fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
               fastcgi_pass 127.0.0.1:9000;
               #fastcgi_pass unix:/var/run/php5-fpm.sock;
               try_files $uri =404;
           }
       
           location ~* /\. {
               deny all;
           }
       }
        
       server {
           charset utf-8;
           client_max_body_size 128M;
       
           listen 80; ## listen for ipv4
           #listen [::]:80 default_server ipv6only=on; ## listen for ipv6
       
           server_name backoffice.test;
           root        /path/to/yii-application/backoffice/web/;
           index       index.php;
       
           access_log  /path/to/yii-application/log/backoffice-access.log;
           error_log   /path/to/yii-application/log/backoffice-error.log;
       
           location / {
               # Redirect everything that isn't a real file to index.php
               try_files $uri $uri/ /index.php$is_args$args;
           }
       
           # uncomment to avoid processing of calls to non-existing static files by Yii
           #location ~ \.(js|css|png|jpg|gif|swf|ico|pdf|mov|fla|zip|rar)$ {
           #    try_files $uri =404;
           #}
           #error_page 404 /404.html;

           # deny accessing php files for the /assets directory
           location ~ ^/assets/.*\.php$ {
               deny all;
           }

           location ~ \.php$ {
               include fastcgi_params;
               fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
               fastcgi_pass 127.0.0.1:9000;
               #fastcgi_pass unix:/var/run/php5-fpm.sock;
               try_files $uri =404;
           }
       
           location ~* /\. {
               deny all;
           }
       }
   ```

5. Change the hosts file to point the domain to your server.

    - Windows: `c:\Windows\System32\Drivers\etc\hosts`
    - Linux: `/etc/hosts`

   Add the following lines:

   ```
   127.0.0.1   frontpage.test
   127.0.0.1   backoffice.test
   ```

6. Open your browser and go to http://frontpage.test/

7. Create a user by selecting the Sign Up menu option at the top of the frontpage home page.

8. Provide the requested credentials, and complete the data entry with the Signup button. You will be presented with a
   message:
   ```
   Thank you for registration. Please check your inbox for verification email.
   ``` 
9. Despite stating that a confirmation email has been sent, the default settings for the mailer prevents the sending of
   a real email.
   Instead, an eml format file is created in the directory `@frontpage/runtime/mail`.
   Either open this file with a mail client such as Outlook or Thunderbird, or use a text editor to retrieve the URL
   which is used to confirm the User creation.
   The URL will need to be modified to remove
   the [quoted printable encoding](https://en.wikipedia.org/wiki/Quoted-printable) before pasting it in your browser.

   This can be accomplished manually as follows:
    - delete soft line breaks ‘=’ and newlines to create a single line with the line below
    - change ‘=3D’ to ‘=’
    - On Mac / Linux, convert \r\n to \n - MIME CRLF line breaks are "real" and should be preserved.

   Paste this URL into a browser tab to complete the User creation. You will be presented with the message:
    ```
    Your email has been confirmed!
    ```
10. You are now automatically logged in to the frontpage application.
    The same credentials can then be used to login to the backoffice application.

> Note: if you want to run advanced template on a single domain so `/` is frontpage and `/admin` is backoffice, refer
> to [Using advanced project template at shared hosting](topic-shared-hosting.md).

## Installing using Vagrant

This way is the easiest but long (~20 min).

**This installation way doesn't require pre-installed software (such as web-server, PHP, MySQL etc.)** - just do next
steps!

#### Manual for Linux/Unix users

1. Install [VirtualBox](https://www.virtualbox.org/wiki/Downloads)
2. Install [Vagrant](https://www.vagrantup.com/downloads.html)
3. Create GitHub [personal API token](https://github.com/blog/1509-personal-api-tokens)
3. Prepare project:

   ```bash
   git clone https://github.com/yiisoft/yii2-app-advanced.git
   cd yii2-app-advanced/vagrant/config
   cp vagrant-local.example.yml vagrant-local.yml
   ```

4. Place your GitHub personal API token to `vagrant-local.yml`
5. Change directory to project root:

   ```bash
   cd yii2-app-advanced
   ```

5. Run command:

   ```bash
   vagrant up
   ```

6. SSH into vagrant box via `vagrant ssh` and execute `php init`.

That's all. You just need to wait for completion! After that you can access project locally by URLs:

* frontpage: http://y2aa-frontpage.test
* backoffice: http://y2aa-backoffice.test

#### Manual for Windows users

1. Install [VirtualBox](https://www.virtualbox.org/wiki/Downloads)
2. Install [Vagrant](https://www.vagrantup.com/downloads.html)
3. Reboot
4. Create GitHub [personal API token](https://github.com/blog/1509-personal-api-tokens)
5. Prepare project:
    * download repo [yii2-app-advanced](https://github.com/yiisoft/yii2-app-advanced/archive/master.zip)
    * unzip it
    * go into directory `yii2-app-advanced-master/vagrant/config`
    * copy `vagrant-local.example.yml` to `vagrant-local.yml`

6. Place your GitHub personal API token to `vagrant-local.yml`

7. Open terminal (`cmd.exe`), **change directory to project root** and run command:

   ```bash
   vagrant up
   ```

   (You can read [here](https://www.wikihow.com/Change-Directories-in-Command-Prompt) how to change directories in
   command prompt)

That's all. You just need to wait for completion! After that you can access project locally by URLs:

* frontpage: http://y2aa-frontpage.test
* backoffice: http://y2aa-backoffice.test

### Installing using Docker

Install the application dependencies

    docker-compose run --rm backoffice composer install

Initialize the application by running the `init` command within a container

    docker-compose run --rm backoffice php /app/init

Adjust the components['db'] configuration in `common/config/main-local.php` accordingly.

        'dsn' => 'mysql:host=mysql;dbname=yii2advanced',
        'username' => 'yii2advanced',
        'password' => 'secret',

> Docker networking creates a DNS entry for the host `mysql` available from your `backoffice` and `frontpage` containers.

> If you want to use another database, such a Postgres, uncomment the corresponding section in `docker-compose.yml` and
> update your database connection.

>         'dsn' => 'pgsql:host=pgsql;dbname=yii2advanced',

For more information about Docker setup please visit the [guide](https://www.yiiframework.com/doc-2.0/guide-index.html).

Start the application

    docker-compose up -d

Run the migrations

    docker-compose run --rm backoffice yii migrate          

Access it in your browser by opening

- frontpage: http://127.0.0.1:20080
- backoffice: http://127.0.0.1:21080

