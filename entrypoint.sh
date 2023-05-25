#!/bin/bash

mkdir -p /run/php

/usr/bin/supervisord -c /etc/supervisor/supervisord.conf    