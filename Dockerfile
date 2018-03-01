FROM php:fpm
VOLUME /boom/SERVER/cfg/sourcemod

# RUN apt-get update
# RUN apt-get install -y gnupg wget
# RUN echo "deb http://packages.dotdeb.org stable all" >> /etc/apt/sources.list
# RUN echo "deb-src http://packages.dotdeb.org stable all" >> /etc/apt/sources.list
# RUN wget http://www.dotdeb.org/dotdeb.gpg
# RUN cat dotdeb.gpg | apt-key add -

# RUN rm /etc/apt/preferences.d/no-debian-php
RUN apt-get update
RUN apt-get install -y nginx g++ libicu-dev mysql-client

RUN docker-php-ext-configure pdo_mysql
RUN docker-php-ext-configure intl
RUN docker-php-ext-configure bcmath

RUN docker-php-ext-install pdo_mysql
RUN docker-php-ext-install intl
RUN docker-php-ext-install bcmath
# RUN docker-php-ext-enable pdo_mysql
# RUN docker-php-ext-enable pdo_intl

COPY . /boom
COPY ./DOCKER/nginx_boom_panel.conf /etc/nginx/sites-available/default
COPY ./DOCKER/wait_for_mysql.sh ./wait_for_mysql.sh
COPY ./DOCKER/php.ini /usr/local/etc/php
COPY ./DOCKER/config.docker.php ./WEB/config.php

RUN chmod 777 -R /boom
RUN chmod 777 -R /usr/local/etc/php/
RUN chmod 777 -R /etc/nginx/sites-available/default
RUN chmod 777 -R /usr/local/etc/php
RUN chmod 777 ./wait_for_mysql.sh
RUN echo "cgi.fix_pathinfo: 0;" >> /usr/local/etc/php-fpm.conf

ENV TIMEZONE="0" DEBUG="0" LANG="en" \
    DBHOST="localhost" DBNAME="boompanel" DBUSER="root" DBPASS=""

EXPOSE 80

CMD /bin/bash -c "./wait_for_mysql.sh ${DBHOST}:3306 && mysql --host ${DBHOST} -u ${DBUSER} -p${DBPASS} < /boom/database.sql && mysql --host ${DBHOST} -u ${DBUSER} -p${DBPASS} < /boom/database-update.sql && /etc/init.d/php7.0-fpm start && nginx -g 'daemon off;'"
