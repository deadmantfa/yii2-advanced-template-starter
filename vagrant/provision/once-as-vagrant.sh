#!/usr/bin/env bash

source /app/vagrant/provision/common.sh

#== Import script args ==

# shellcheck disable=SC2116
github_token=$(echo "$1")
email=$(echo "$2")
username=$(echo "$3")
password=$(echo "$4")
role=$(echo "$5")

#== Provision script ==

info "Provision-script user: $(whoami)"

info "Configure composer"
composer config --global github-oauth.github.com ${github_token}
echo "Done!"

info "Install project dependencies"
cd /app || exit
composer config -g repo.packagist.org composer https://packagist.org
composer config -g github-protocols https ssh
composer --no-progress --prefer-source -q update

info "Init project"
./init --env=Development --overwrite=y
rm -f /app/common/config/main-local-example.php
rm -f /app/common/config/test-local-example.php

info "Apply migrations"
./yii migrate --interactive=0
./yii_test migrate --interactive=0
yes | ./yii rbac/init
./yii user/create "${email}" "${username}" "${password}" "${role}"
./yii user/confirm "${email}"

info "Generate Keys for OAUTH2"
openssl genrsa -out api/oauth2/private.key 2048
openssl rsa -in api/oauth2/private.key -pubout -out api/oauth2/public.key
chmod 600 api/oauth2/private.key
chmod 600 api/oauth2/public.key

info "Create bash-alias 'app' for vagrant user"
echo 'alias app="cd /app"' | tee /home/vagrant/.bash_aliases

info "Enabling colorized prompt for guest console"
sed -i "s/#force_color_prompt=yes/force_color_prompt=yes/" /home/vagrant/.bashrc
