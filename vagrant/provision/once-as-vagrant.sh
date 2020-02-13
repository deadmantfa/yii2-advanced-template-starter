#!/usr/bin/env bash

source /app/vagrant/provision/common.sh

#== Import script args ==

# shellcheck disable=SC2116
github_token=$(echo "$1")
email=$(echo "$2")
username=$(echo "$3")
password=$(echo "$4")
role=$(echo "$4")

#== Provision script ==

info "Provision-script user: `whoami`"

info "Configure composer"
composer config --global github-oauth.github.com ${github_token}
echo "Done!"

info "Install project dependencies"
cd /app || exit
composer --no-progress --prefer-dist -q install

info "Init project"
./init --env=Development --overwrite=y
rm -f /app/common/config/main-local-example.php
rm -f /app/common/config/test-local-example.php

info "Apply migrations"
./yii migrate --interactive=0
./yii_test migrate --interactive=0
./yii user/create "${email}" "${username}" "${password}" "${role}"
./yii user/confirm "${email}"

info "Create bash-alias 'app' for vagrant user"
echo 'alias app="cd /app"' | tee /home/vagrant/.bash_aliases

info "Enabling colorized prompt for guest console"
sed -i "s/#force_color_prompt=yes/force_color_prompt=yes/" /home/vagrant/.bashrc
