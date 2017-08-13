#!/bin/sh
echo 'sudo apt-get update'
sudo apt-get update
echo 'sudo apt-get install apache2 apache2-utils -y'
sudo apt-get install apache2 apache2-utils -y
echo 'sudo apt-get install php5 libapache2-mod-php5 -y'
sudo apt-get install php5 libapache2-mod-php5 -y
echo 'sudo apt-get install php5-sqlite -y'
sudo apt-get install php5-sqlite -y
echo 'sudo apt-get install sqlite3'
sudo apt-get install sqlite3
echo 'sudo cp * /var/www/html'
sudo cp * /var/www/html
echo 'sudo chmod a+wr /var/www/html/*'
sudo chmod a+wrx /var/www/html/*
echo 'sudo chmod a+wr /var/www/html/'
sudo chmod a+wrx /var/www/html/
echo 'sudo apt-get install mysql-server php5-mysql python-mysqldb'
sudo apt-get install mysql-server php5-mysql python-mysqldb
echo 'sudo mysql_install_db'
sudo mysql_install_db
echo 'sudo /usr/bin/mysql_secure_installation'
sudo /usr/bin/mysql_secure_installation
echo 'sudo php mysql-setup.php'
sudo php mysql-setup.php
echo 'sudo apache2ctl restart'
sudo apache2ctl restart
