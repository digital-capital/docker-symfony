#FROM php:7.4-fpm-buster
FROM php:8.2-fpm-buster
ARG TIMEZONE

COPY docker/php/php.ini /usr/local/etc/php/conf.d/docker-php-config.ini

RUN apt-get update && apt-get install -y \
    gnupg \
    g++ \
    procps \
    openssl \
    git \
    unzip \
    zlib1g-dev \
    libzip-dev \
    libfreetype6-dev \
    libpng-dev \
    libjpeg-dev \
    libicu-dev  \
    libonig-dev \
    libxslt1-dev \
    amqp-tools \
    librabbitmq-dev \
    librabbitmq4 \
    acl \
    npm \
    && echo 'alias sf="php bin/console"' >> ~/.bashrc

#install yarn
RUN npm install -g yarn

RUN docker-php-ext-configure gd --with-jpeg --with-freetype 

RUN docker-php-ext-install \
    pdo pdo_mysql zip xsl gd intl opcache exif mbstring 
    
# Extract PHP source
RUN docker-php-source extract

# Create directory for amqp extension
RUN mkdir /usr/src/php/ext/amqp

# Download AMQP master branch files to extension directory
RUN curl -L https://github.com/php-amqp/php-amqp/archive/master.tar.gz | tar -xzC /usr/src/php/ext/amqp --strip-components=1

# Install amqp extension using built-in docker binary
RUN docker-php-ext-install amqp

RUN docker-php-source delete
# Set timezone
RUN ln -snf /usr/share/zoneinfo/${TIMEZONE} /etc/localtime && echo ${TIMEZONE} > /etc/timezone \
    && printf '[PHP]\ndate.timezone = "%s"\n', ${TIMEZONE} > /usr/local/etc/php/conf.d/tzone.ini \
    && "date"

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /var/www/symfony
