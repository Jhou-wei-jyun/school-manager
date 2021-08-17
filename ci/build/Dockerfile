FROM ubuntu:xenial
LABEL maintainer="uberbrady, hinchk"

RUN apt-get update && apt-get install -y software-properties-common
RUN LC_ALL=C.UTF-8 add-apt-repository -y ppa:ondrej/php
RUN apt-get update && apt-get install -y \
  apache2 \
  apache2-bin \
  libapache2-mod-php7.2 \
  php7.2-curl \
  php7.2-ldap \
  php7.2-mysql \
  #php7.2-mcrypt \
  php7.2-gd \
  php7.2-xml \
  php7.2-mbstring \
  php7.2-zip \
  php7.2-bcmath \
  patch \
  curl \
  vim \
  git \
  mysql-client \
  supervisor \
  logrotate \
  && apt-get clean \
  && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

#RUN phpenmod mcrypt
RUN phpenmod gd
RUN phpenmod bcmath

RUN sed -i 's/variables_order = .*/variables_order = "EGPCS"/' /etc/php/7.2/apache2/php.ini
RUN sed -i 's/variables_order = .*/variables_order = "EGPCS"/' /etc/php/7.2/cli/php.ini

RUN useradd -m --uid 1000 --gid 50 docker

RUN echo export APACHE_RUN_USER=docker >> /etc/apache2/envvars
RUN echo export APACHE_RUN_GROUP=staff >> /etc/apache2/envvars

COPY src/docker/000-default.conf /etc/apache2/sites-enabled/000-default.conf

#SSL
RUN mkdir -p /var/lib/storage/ssl
COPY src/docker/001-default-ssl.conf /etc/apache2/sites-enabled/001-default-ssl.conf
#COPY docker/001-default-ssl.conf /etc/apache2/sites-available/001-default-ssl.conf

RUN a2enmod ssl
RUN a2enmod rewrite
#RUN a2ensite 001-default-ssl.conf


############## COMPOSER CACHE ###################

# global install of composer
#RUN cd /tmp;curl -sS https://getcomposer.org/installer | php;mv /tmp/composer.phar /usr/local/bin/composer
RUN curl -sL https://getcomposer.org/download/2.0.9/composer.phar -o /usr/local/bin/composer \
  && chmod 755 /usr/local/bin/composer

COPY src/composer.json /var/www/html/composer.json
COPY src/composer.lock /var/www/html/composer.lock
RUN chown -R docker /var/www/html

#USER docker
# hirak/prestissimo is no more need in composer 2.x
# refer: https://php.watch/articles/composer-2
#RUN composer global require hirak/prestissimo
#USER root


############ INITIAL APPLICATION SETUP #####################

COPY src /var/www/html
WORKDIR /var/www/html

#Append to bootstrap file (less brittle than 'patch')
# RUN sed -i 's/return $app;/$env="production";\nreturn $app;/' bootstrap/start.php

#copy all configuration files
# COPY docker/*.php /var/www/html/app/config/production/
COPY src/docker/docker.env /var/www/html/.env

RUN chown -R docker /var/www/html \
  && rm -rf "/var/www/html/storage/private_uploads" && ln -fs "/var/lib/storage/data/private_uploads" "/var/www/html/storage/private_uploads" \
  && rm -rf "/var/www/html/public/uploads" && ln -fs "/var/lib/storage/data/uploads" "/var/www/html/public/uploads" \
  && rm -rf "/var/www/html/storage/app/backups" && ln -fs "/var/lib/storage/dumps" "/var/www/html/storage/app/backups" \
  && mkdir "/var/lib/storage/keys" && ln -fs "/var/lib/storage/keys/oauth-private.key" "/var/www/html/storage/oauth-private.key" \
  && ln -fs "/var/lib/storage/keys/oauth-public.key" "/var/www/html/storage/oauth-public.key" \
  && chown docker "/var/lib/storage/keys/" \
  && chmod +x /var/www/html/artisan \
  && echo "Finished setting up application in /var/www/html"


############## DEPENDENCIES via COMPOSER ###################

# Get dependencies
USER docker
RUN cd /var/www/html;composer install && rm -rf /home/docker/.composer/cache
USER root

############### APPLICATION INSTALL/INIT #################

#RUN php artisan app:install
# too interactive! Try something else

#COPY src/docker/app_install.exp /tmp/app_install.exp
#RUN chmod +x /tmp/app_install.exp
#RUN /tmp/app_install.exp

############### DATA VOLUME #################

VOLUME ["/var/lib/storage"]

##### START SERVER

COPY src/docker/startup.sh src/docker/supervisord.conf /
COPY src/docker/supervisor-exit-event-listener /usr/bin/supervisor-exit-event-listener
RUN chmod +x /startup.sh /usr/bin/supervisor-exit-event-listener

CMD ["/startup.sh"]

EXPOSE 80
EXPOSE 443
