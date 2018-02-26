FROM php:7.2-apache

# @url https://docs.docker.com/engine/reference/builder/

# Environment variables are supported by the following list of instructions in the Dockerfile
# ADD
# COPY
# ENV
# EXPOSE
# LABEL
# USER
# WORKDIR
# VOLUME
# STOPSIGNAL

# VOLUME /usr/local/etc/php/conf.d

# Install other required extensions

# Possible values for ext-name:
# bcmath bz2 calendar ctype curl dba dom enchant exif fileinfo filter ftp gd gettext gmp hash iconv imap interbase intl
# json ldap mbstring mcrypt mysqli oci8 odbc opcache pcntl pdo pdo_dblib pdo_firebird pdo_mysql pdo_oci pdo_odbc pdo_pgsql
# pdo_sqlite pgsql phar posix pspell readline recode reflection session shmop simplexml snmp soap sockets spl standard
# sysvmsg sysvsem sysvshm tidy tokenizer wddx xml xmlreader xmlrpc xmlwriter xsl zip

# Get newest packages
#RUN apt-get upgrade -y --force-yes

# Install iconv mcrypt gd and more
RUN apt-get update && \
        apt-get install -y \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        apt-utils \
        sendmail \
        memcached \
        git \
    && docker-php-ext-install -j$(nproc) iconv \
    && docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ \
    && docker-php-ext-install -j$(nproc) gd

RUN docker-php-ext-install mysqli
RUN docker-php-ext-install pdo
RUN docker-php-ext-install pdo_mysql
RUN docker-php-ext-install mbstring

# Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# memcached module
RUN apt-get install -y libmemcached-dev unzip
RUN curl -o /root/memcached.zip https://github.com/php-memcached-dev/php-memcached/archive/php7.zip -L
RUN cd /root && unzip memcached.zip && rm memcached.zip && \
 cd php-memcached-php7 && \
 phpize && ./configure --enable-sasl && make && make install && \
 cd /root && rm -rf /root/php-memcached-* && \
 echo "extension=memcached.so" > /usr/local/etc/php/conf.d/memcached.ini  && \
 echo "memcached.use_sasl = 1" >> /usr/local/etc/php/conf.d/memcached.ini

# Install XDebug
RUN pecl install xdebug
RUN docker-php-ext-enable xdebug
RUN echo "error_reporting = E_ALL" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
RUN echo "display_startup_errors = On" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
RUN echo "display_errors = On" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
RUN echo "xdebug.remote_enable=1" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
RUN echo "xdebug.remote_connect_back=1" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
RUN echo "xdebug.idekey=\"PHPSTORM\"" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
RUN echo "xdebug.remote_port=9001" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

# Enable rewrite mode in apache
RUN a2enmod rewrite

# Restart and check if all good
RUN service apache2 restart

EXPOSE 443
EXPOSE 80