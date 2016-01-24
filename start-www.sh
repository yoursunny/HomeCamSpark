#!/bin/bash
php5 -S 127.0.0.1:20080 -t /tmp/motion/ &
./ngrok tcp 20080 >/dev/null &
