#!/usr/bin/env bash

source /app/vagrant/provision/common.sh

#== Provision script ==

info "Provision-script user: $(whoami)"

info "Restart web-stack"
systemctl restart php7.4-fpm
systemctl restart nginx
systemctl restart mysql
systemctl restart elasticsearch
systemctl restart kibana
systemctl restart yii2-chat
