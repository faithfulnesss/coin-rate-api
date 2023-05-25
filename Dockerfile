FROM debian:testing-slim as base

ENV DEBIAN_FRONTEND="noninteractive"

RUN apt-get update && apt-get upgrade -y \
    && apt-get install -y \
        php8.2 php8.2-fpm php8.2-mbstring php8.2-xml php8.2-dom php8.2-zip php8.2-intl \
        php8.2-gd php8.2-imap php8.2-curl php8.2-bcmath \
        nginx rsyslog supervisor curl npm nano \
    && npm install -g n gulp yarn webpack-cli @vue/cli && n 14 \
    && php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php composer-setup.php --install-dir=/usr/bin \
    && php -r "unlink('composer-setup.php');" \
    && mv /usr/bin/composer.phar /usr/bin/composer \
    && rm -rf /var/lib/apt/lists/* /usr/share/man/* /usr/share/doc/* \
    && rm -rf /etc/nginx/sites-enabled

#COPY ./conf/nginx/sites-enabled /etc/nginx/sites-enabled
COPY ./conf/supervisor/supervisord.conf /etc/supervisor/supervisord.conf

COPY ./entrypoint.sh /

CMD ["/bin/bash", "/entrypoint.sh"]

EXPOSE 80
