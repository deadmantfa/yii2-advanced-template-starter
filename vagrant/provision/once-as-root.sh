#!/usr/bin/env bash

source /app/vagrant/provision/common.sh

#== Import script args ==

timezone=$(echo "$1")
domain=$(echo "$2")
domain_file="${domain/./-}"
database=$(echo "$3")
database_test=$(echo "$4")
domain_ip=$(echo "$5")
ws=$(echo "$6")

#== Provision script ==

info "Provision-script user: $(whoami)"

export DEBIAN_FRONTEND=noninteractive

info "Configure timezone"
timedatectl set-timezone "${timezone}" --no-ask-password

info "Prepare root password for MySQL"
debconf-set-selections <<<"mysql-community-server mysql-community-server/root-pass password \"''\""
debconf-set-selections <<<"mysql-community-server mysql-community-server/re-root-pass password \"''\""
echo "Done!"

info "Remove old files"
rm -f /app/environments/dev/common/config/main-local.php 2>/dev/null
rm -f /app/environments/dev/common/config/test-local.php 2>/dev/null
rm -f /app/common/config/main-local.php 2>/dev/null
rm -f /app/common/config/test-local.php 2>/dev/null
rm -f /app/common/config/main-local-example.php 2>/dev/null
rm -f /app/common/config/test-local-example.php 2>/dev/null
rm -f /app/common/config/params-local.php 2>/dev/null
rm -f /app/common/config/codeception-local.php 2>/dev/null

rm -f /app/console/config/main-local.php 2>/dev/null
rm -f /app/console/config/test-local.php 2>/dev/null
rm -f /app/console/config/params-local.php 2>/dev/null

rm -f /app/backend/config/main-local.php 2>/dev/null
rm -f /app/backend/config/test-local.php 2>/dev/null
rm -f /app/backend/config/params-local.php 2>/dev/null
rm -f /app/backend/config/codeception-local.php 2>/dev/null

rm -f /app/frontend/config/main-local.php 2>/dev/null
rm -f /app/frontend/config/test-local.php 2>/dev/null
rm -f /app/frontend/config/params-local.php 2>/dev/null
rm -f /app/frontend/config/codeception-local.php 2>/dev/null

rm -rf /app/vendor 2>/dev/null
rm -f /app/composer.lock 2>/dev/null
info "Done"

info "Update OS"
apt-get update
apt-get upgrade -y

info "Local SSL"
cd ~ || exit
apt-get install libnss3-tools curl apache2-utils apt-transport-https openjdk-8-jre jq -y
wget -q https://github.com/FiloSottile/mkcert/releases/download/v1.4.0/mkcert-v1.4.0-linux-amd64 >/dev/null 2>&1
mv mkcert-v1.4.0-linux-amd64 mkcert
chmod +x mkcert
cp mkcert /usr/local/bin/
export CAROOT=/app/vagrant/nginx/ssl/root/
mkcert -install >/dev/null 2>&1
mkcert "*.${domain}" >/dev/null 2>&1
cp "/root/_wildcard.${domain}.pem" /app/vagrant/nginx/ssl/.
cp "/root/_wildcard.${domain}-key.pem" /app/vagrant/nginx/ssl/.
rm -Rf /app/vagrant/nginx/ssl/root/rootCA.crt
openssl x509 -outform der -in /app/vagrant/nginx/ssl/root/rootCA.pem -out /app/vagrant/nginx/ssl/root/rootCA.crt >/dev/null 2>&1
echo "Done!"

info "Install additional software"
apt-get install -y gnupg gcc g++ make php7.4-curl php7.4-cli php7.4-intl php7.4-mysqlnd php7.4-gd php7.4-fpm php7.4-mbstring php7.4-xml unzip nginx mysql-server php.xdebug php7.4-dev php7.4-bcmath php7.4-zip php7.4-gmp bash-completion

info "Update OS software"
rm -Rf /etc/apt/sources.list.d/elastic-5.x.list
wget -qO - https://artifacts.elastic.co/GPG-KEY-elasticsearch | apt-key add -
echo "deb https://artifacts.elastic.co/packages/5.x/apt stable main" | tee -a /etc/apt/sources.list.d/elastic-5.x.list
apt-get update
apt-get upgrade -y

info "Install ElasticSearch"
apt-get install -y elasticsearch
systemctl enable elasticsearch
systemctl start elasticsearch
sleep 20
curl -X GET "localhost:9200" | jq '.'
echo "Done!"

info "Install Kibana"
apt-get install -y kibana
systemctl enable kibana
systemctl start kibana
echo "Done!"

info "Install Node 12 LTS"
curl -sL https://deb.nodesource.com/setup_12.x | bash -
apt-get install -y nodejs

info "Install Sass"
mkdir /home/vagrant/.npm-global
npm config set prefix '/home/vagrant/.npm-global'
export PATH=/home/vagrant/.npm-global/bin:$PATH
echo "export PATH=/home/vagrant/.npm-global/bin:$PATH" >>/home/vagrant/.profile
source /home/vagrant/.profile
npm install -g sass

info "Configure MySQL"
sed -i "s/.*bind-address.*/bind-address = 0.0.0.0/" /etc/mysql/mysql.conf.d/mysqld.cnf
mysql -uroot <<<"CREATE USER 'root'@'%' IDENTIFIED BY ''"
mysql -uroot <<<"GRANT ALL PRIVILEGES ON *.* TO 'root'@'%'"
mysql -uroot <<<"DROP USER 'root'@'localhost'"
mysql -uroot <<<"FLUSH PRIVILEGES"
echo "Done!"

info "Configure PHP-FPM"
sed -i 's/user = www-data/user = vagrant/g' /etc/php/7.4/fpm/pool.d/www.conf
sed -i 's/group = www-data/group = vagrant/g' /etc/php/7.4/fpm/pool.d/www.conf
sed -i 's/owner = www-data/owner = vagrant/g' /etc/php/7.4/fpm/pool.d/www.conf
cat <<EOF >/etc/php/7.4/mods-available/xdebug.ini
zend_extension=xdebug.so
xdebug.remote_enable=1
xdebug.remote_connect_back=1
xdebug.remote_port=9000
xdebug.remote_autostart=1
EOF
echo "Done!"

info "Configure NGINX"
sed -i 's/user www-data/user vagrant/g' /etc/nginx/nginx.conf
echo "Done!"

info "Enabling site configuration"
sed "s/example\.com/$domain/g; s/example-com/$domain_file/g; s/example-ip/$domain_ip/g" /app/vagrant/nginx/app_example.conf >/app/vagrant/nginx/app.conf
ln -s /app/vagrant/nginx/app.conf /etc/nginx/sites-enabled/app.conf
echo "Done!"

info "Initailize databases for MySQL"
mysql -uroot <<<"CREATE DATABASE IF NOT EXISTS ${database}"
mysql -uroot <<<"CREATE DATABASE IF NOT EXISTS ${database_test}"
sed "s/yii2advanced/$database/g" /app/environments/dev/common/config/main-local-example.php >/app/environments/dev/common/config/main-local.php
sed "s/yii2advanced_test/$database_test/g" /app/environments/dev/common/config/test-local-example.php >/app/environments/dev/common/config/test-local.php
sed "s/ws.example.com/$ws/g" /app/environments/dev/common/config/params-local-example.php >/app/environments/dev/common/config/params-local.php
echo "Done!"

info "Install composer"
curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

htpasswd -cb /etc/nginx/htpasswd.users vagrant vagrant

info "Yii Bash Completion"
curl -L https://raw.githubusercontent.com/yiisoft/yii2/master/contrib/completion/bash/yii -o /etc/bash_completion.d/yii

info "Enable Chat service"
ln -s /app/vagrant/system-service/yii2-chat.service /etc/systemd/system/yii2-chat.service
systemctl daemon-reload
systemctl enable yii2-chat.service
