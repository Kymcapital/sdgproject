Introduction
This is a Laravel Based Application for tracking SDGs for KCB. It's been designed to offer an alternative solution to capturing employee responses as opposed to using excel. 
In this article, we will walk you through the process & steps required for a successful deployment of the application.
Prerequisites
In order to install SDG Tracker Application on Linux (Ubuntu 20.04), there are a few system requirements that have to be installed and set up:-
Web Server (Apache or Nginx) 
PHP (latest version recommended) and some modules 
Database (such as MariaDB or MySQL) 
Composer PHP package manager
Cron Job

Basically, you need either a LEMP or LAMP stack to run this application. You can follow the links below to install LEMP/LAMP stack on Ubuntu 20.04.

Install LAMP Stack on Ubuntu 20.04
Install and Setup LEMP Stack on Ubuntu 20.04

This setup, however, will show you how to install SDG Tracker with a LEMP stack.


Installation & Configuration
Step 1 - Install Required PHP Extensions 
Apart from the default PHP extensions that get installed alongside the PHP package, there are other extensions that you need to install. Run the command below to install them;

apt install php-bcmath php-gd php-mbstring php-xml php-zip php-tokenizer -y php-common php-fpm php-json php-cli

Ensure that the system is running at least php 7.4 
Step 2 - Create Database and Database User
Login to the database and create a database and database user for the application.

create database sdgdb; 

grant all on sdgdb.* to sdguser@localhost identified by 'sdguser';

Reload database privilege tables;  

flush privileges; 
quit;

Step 3 - Install Composer PHP Package Manager
Assuming you already have the Nginx server, PHP(+extensions) and Database (MariaDB or MySQL), proceed to install PHP Composer.

Install curl
sudo apt install curl unzip -y

Download and install the Composer 
curl -sS https://getcomposer.org/installer | php

Configuration of Composer
You need to move the “composer.phar” file to the “/usr/local/bin/composer” directory, and you can do so using the command:
sudo mv composer.phar /usr/local/bin/composer
After moving the “composer.phar” file to the “/usr/local/bin/composer” directory, also change the mode of the file to executable using the command typed below:
sudo chmod +x /usr/local/bin/composer
At this point, the Composer is installed and ready to serve for the creating and managing the application dependencies. 

To check whether Composer is installed or not, you can type the command:
Composer

Install curl
sudo apt install curl unzip -y
Install curl
sudo apt install curl unzip -y

Step 4 - Install and Configure Nginx
To install Nginx
sudo apt install nginx -y

To check Nginx status
sudo systemctl status nginx

To start Nginx
sudo systemctl start nginx

Follow this article to setup and configure Nginx


Step 5 - Installation of SDG Tracker Application
If all the prerequisites have been met, proceed to install the application.

Navigate to the /var/www/html directory or whichever location that suits your guidelines using the cd command as shown below:
cd /var/www/html

While still under /var/www/html directory, use this command to download the application from Bitbucket account
git clone https://jchegenye-oxygene@bitbucket.org/o2clients/sdg-tracker-2021.git

Once you have downloaded the files, you need to grant some appropriate permissions of the applications project directory to the non-root user of the system. You can do this by typing the commands:
sudo chmod -R 755 /var/www/html/sdg-tracker-2021
sudo chown -R www-data:www-data /var/www/html/sdg-tracker-2021

After setting the permissions of the directory, navigate to the projects directory
cd /var/www/html/sdg-tracker-2021

Run the composer install command - This will download all the applications required dependencies and libraries.
composer install

Now let’s configure the application to connect to the database and other setups.
cp .env.example .env

sudo nano .env

Your .env file should look like below or add what isn't there:-

Make sure you update areas highlighted in red.
APP_NAME="KCB - SDG Tracker"
APP_ENV=local
APP_KEY=base64:KgWGvxj8amX0jbAKyJW/ryY2sEvxXn+s812b5RkOi3E=
APP_DEBUG=true
APP_URL=http://example.com

LOG_CHANNEL=stack
LOG_LEVEL=debug

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=sdgdb
DB_USERNAME=sdguser
DB_PASSWORD=sdgpassword

BROADCAST_DRIVER=log
CACHE_DRIVER=file
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120

MEMCACHED_HOST=127.0.0.1

REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=25
MAIL_USERNAME=smtpusername
MAIL_PASSWORD=smtppassword
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="smtpfromemail@gmail.com"
MAIL_FROM_NAME="${APP_NAME}"

MAIL_BACKUP_TO=sdgadmin@gmail.com
MAIL_BACKUP_CC_TO="sdgexample1@gmail.com,sdgexample2@gmail.com"
MAIL_BACKUP_SUBJECT="Database Backup | KCG SDG Tracker | "

APP_FROM_EMAIL=infoexample@gmail.com

AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=

PUSHER_APP_ID=
PUSHER_APP_KEY=
PUSHER_APP_SECRET=
PUSHER_APP_CLUSTER=mt1

MIX_PUSHER_APP_KEY="${PUSHER_APP_KEY}"
MIX_PUSHER_APP_CLUSTER="${PUSHER_APP_CLUSTER}"

REVIEW_CYCLE_DATE="2021 Q1"

REDIRECT_HTTPS=false
REVIEW_EMAIL_TO="admin_review1@gmail.com,admin_review2@gmail.com"

Once the above changes have been made, close the file and save.

Run this command to generate a new key.
php artisan key:generate 

Run this command to drop all tables and re-run all migrations
php artisan migrate:fresh 

Run this command to seed the database with dummy or sample records.
php artisan db:seed 
Step 6 - Starting the Cron Job
This application runs cron jobs on the server scheduled monthly to backup database .sql files and sends the backup to an email address.

Let’s set up the Cron Jobs to run automatically without initiating manually. To start the SDG Tracker Scheduler itself, we only need to add one Cron job which executes every minute. Go to the terminal, ssh into the server, cd into the project i.e cd /var/www/html/sdg-tracker-2021 and run this command.
crontab -e 

This will open the server’s Crontab file, paste the code below into the file, save and then exit.
* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1 

Do not forget to replace /path-to-your-project with the full path to your Application directory i.e. /var/www/html/sdg-tracker-2021

Restart the server.