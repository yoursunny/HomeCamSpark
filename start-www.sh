#!/bin/bash
mkdir -p /tmp/motion
ln -s $(readlink -f webhook.php) /tmp/motion/webhook.php
php5 -S 127.0.0.1:20080 -t /tmp/motion/ &
./ngrok tcp 20080 >/dev/null &
sleep 10
php5 register-webhook.php
