FROM ubuntu:18.04

ARG DEBIAN_FRONTEND=noninteractive

RUN mkdir -p /var/www
RUN mkdir -p /var/salesforce

RUN apt-get update && \
    apt-get install --no-install-recommends -y software-properties-common && \
    apt-get install -y --no-install-recommends apt-utils && \
    add-apt-repository ppa:ondrej/php -y && \
    apt-get install --no-install-recommends -y --allow-unauthenticated \
        composer \
        sshfs \
        curl \
        git \
        php7.4 \
        php7.4-common \
        php7.4-dev \
        php7.4-pdo \
        php7.4-curl \
        php7.4-gd \
        php7.4-igbinary \
        php7.4-imagick \
        php7.4-intl \
        php7.4-json \
        php7.4-mbstring \
        php7.4-opcache \
        php7.4-soap \
        php7.4-sybase \
        php7.4-xml \
        php7.4-zip \
        php-cli-prompt \
        php-pear \
        tzdata \
        php-xdebug \
        gpg-agent \
        g++ \
        make

RUN curl https://packages.microsoft.com/keys/microsoft.asc | apt-key add - && \
    curl https://packages.microsoft.com/config/ubuntu/18.04/prod.list > /etc/apt/sources.list.d/mssql-release.list && \
    apt-get update && \
    ACCEPT_EULA=Y apt-get install -y msodbcsql17 && \
    apt-get install -y unixodbc-dev

RUN ln -fs /usr/share/zoneinfo/America/Fortaleza /etc/localtime && \
            dpkg-reconfigure --frontend noninteractive tzdata

RUN pecl install sqlsrv && \
      pecl install pdo_sqlsrv && \
      printf "; priority=20\nextension=sqlsrv.so\n" > /etc/php/7.4/mods-available/sqlsrv.ini && \
      printf "; priority=30\nextension=pdo_sqlsrv.so\n" > /etc/php/7.4/mods-available/pdo_sqlsrv.ini

RUN phpenmod \
      curl \
      dev \
      common \
      gd \
      igbinary \
      imagick \
      intl \
      mbstring \
      opcache \
      pear \
      soap \
      sybase \
      xml \
      sqlsrv \
      pdo_sqlsrv \
      zip

WORKDIR /var/www

COPY ./src .

RUN composer config --global github-oauth.github.com && \
    composer install && \
    find /root -name "*.git*" -exec rm -rf {} + && \
    find /var/www -name "*.git*" -exec rm -rf {} +

COPY ./docker/locale.gen /etc/locale.gen

COPY ./docker/xdebug.ini /etc/php/7.4/cli/conf.d/20-xdebug.ini

COPY ./docker/php.ini /etc/php/7.4/cli/php.ini

COPY ./src/.env.example /var/www/.env