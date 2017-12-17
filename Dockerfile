FROM php:latest
VOLUME cfg/sourcemod

RUN apt-get update
RUN apt-get install -y nginx php7.0-fpm php7.0-mysql php7.0-bcm mysql-server mysql-client
RUN docker-php-ext-configure pdo_mysql
RUN docker-php-ext-install pdo_mysql
RUN docker-php-ext-enable pdo_mysql

COPY . /boom
COPY ./DOCKER/nginx_boom_panel.conf /etc/nginx/sites-available/default
COPY ./DOCKER/wait_for_mysql.sh ./wait_for_mysql.sh
COPY ./DOCKER/php.ini /usr/local/etc/php

RUN chmod 777 -R /boom
RUN chmod 777 -R /usr/local/etc/php/
RUN chmod 777 -R /etc/nginx/sites-available/default
RUN chmod 777 -R /usr/local/etc/php
RUN chmod 777 ./wait_for_mysql.sh
RUN echo "cgi.fix_pathinfo: 0;" >> /etc/php/7.0/fpm/php.ini

EXPOSE 80

CMD /bin/bash -c './wait_for_mysql.sh db:3306 && mysql --host 172.16.239.24 -u boom -pCexQ33kiEbNyKZci < /boom/database.sql && /etc/init.d/php7.0-fpm start && nginx -g "daemon off;"'
