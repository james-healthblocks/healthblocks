#!/usr/bin/env bash

apt-get -y update
apt-get -y upgrade
apt-get -y install php5.6-curl
apt-get install python-software-properties
curl -sL https://deb.nodesource.com/setup_6.x | sudo -E bash -
apt-get install nodejs

npm install -g bower

cd /var/www

composer install
bower install --allow-root