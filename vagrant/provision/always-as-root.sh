#!/usr/bin/env bash

source /app/vagrant/provision/common.sh

#== Provision script ==

info "Provision-script user: $(whoami)"

info "Restart web-stack"
systemctl restart php7.4-fpm
systemctl restart nginx
systemctl restart mysql
FILE=/etc/systemd/system/yii2-chat.service

if [ -L ${FILE} ]; then
  if [ -e ${FILE} ]; then
    echo "Good link"
    systemctl daemon-reload
    systemctl restart yii2-chat
  else
    echo "Broken link"
    rm $FILE
    ln -s /app/vagrant/system-service/yii2-chat.service $FILE
    systemctl daemon-reload
    systemctl enable yii2-chat.service
    systemctl start yii2-chat.service
  fi
elif [ -e ${FILE} ]; then
  echo "Not a link"
  rm $FILE
  ln -s /app/vagrant/system-service/yii2-chat.service $FILE
  systemctl daemon-reload
  systemctl enable yii2-chat.service
  systemctl start yii2-chat.service
else
  echo "Missing"
  ln -s /app/vagrant/system-service/yii2-chat.service $FILE
  systemctl daemon-reload
  systemctl enable yii2-chat.service
  systemctl start yii2-chat.service
fi
