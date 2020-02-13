#!/usr/bin/env bash

source /app/vagrant/provision/common.sh

#== Import script args ==

timezone=$(echo "$1")

#== Provision script ==

info "Provision-script user: $(whoami)"

export DEBIAN_FRONTEND=noninteractive

info "Configure timezone"
timedatectl set-timezone "${timezone}" --no-ask-password

info "Update OS software"
rm -Rf /etc/apt/sources.list.d/elastic-5.x.list
wget -q -O - https://artifacts.elastic.co/GPG-KEY-elasticsearch | apt-key add -
rm -Rf /etc/apt/sources.list.d/elastic-5.x.list
echo "deb https://artifacts.elastic.co/packages/5.x/apt stable main" | tee -a /etc/apt/sources.list.d/elastic-5.x.list
apt-get update
apt-get upgrade -y
apt-get install -y curl apache2-utils apt-transport-https openjdk-8-jre jq

info "Install ElasticSearch"
apt-get install -y elasticsearch
systemctl start elasticsearch
systemctl enable elasticsearch
sleep 20
curl -X GET "localhost:9200" | jq '.'
echo "Done!"

info "Install Kibana"
apt-get install -y kibana
systemctl enable kibana
systemctl start kibana
echo "Done!"
