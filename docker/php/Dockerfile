FROM php:7.3.27-apache as build

COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/

# To install node and npm this official instruction was used:
# https://github.com/nodesource/distributions/blob/master/README.md
RUN curl -sL https://deb.nodesource.com/setup_12.x | bash -; apt-get install -y nodejs

RUN apt-get update && apt-get install -y \
        unzip \
        libaio1 \
        git \
        mailutils \
        msmtp \
    && install-php-extensions \
        bz2 \
        exif \
        gettext \
        imagick \
        oci8 \
        pcntl \
        soap \
        sysvmsg \
        sysvsem \
        sysvshm \
        xdebug \
        xsl \
        zip \
        shmop \
        calendar \
        && a2enmod rewrite headers \
        && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

COPY docker/php/composer-installer.sh /usr/local/bin/composer-installer

RUN composer-installer \
    && mv composer.phar /usr/local/bin/composer \
    && chmod +x /usr/local/bin/composer \
    && composer --version

COPY docker/mail/msmtprc.conf /etc/msmtprc
RUN chmod 600 /etc/msmtprc
RUN chown www-data:www-data /etc/msmtprc
COPY docker/php/php.ini /usr/local/etc/php/php.ini
COPY docker/apache/vhost.conf /etc/apache2/sites-available/000-default.conf

WORKDIR /srv

ENV PATH=/srv/app/vendor/bin/:${PATH}

RUN mkdir logs cache \
    && chmod g+w logs cache

WORKDIR /srv/app
