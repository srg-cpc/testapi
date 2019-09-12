FROM php:7.2-fpm

ENV PHP_MEMORY_LIMIT 1024M
ENV MAX_UPLOAD 128M
ENV PHP_MAX_FILE_UPLOAD 128
ENV PHP_MAX_POST 128M

RUN apt-get update && apt-get install -y zlib1g-dev \
    libpng-dev \
    && docker-php-ext-install gd \
    && docker-php-ext-install zip

RUN apt-get update && apt-get install -my wget gnupg

RUN docker-php-ext-install pdo pdo_mysql
RUN docker-php-ext-install opcache
RUN docker-php-ext-install pcntl
RUN docker-php-ext-install mbstring

RUN apt-get update && apt-get install -y \
		libfreetype6-dev \
		libjpeg62-turbo-dev \
	&& docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ \
	&& docker-php-ext-install -j$(nproc) gd

# install mongodb ext
RUN apt-get install -y autoconf g++ make openssl libssl-dev libcurl4-openssl-dev
RUN apt-get install -y libcurl4-openssl-dev pkg-config
RUN apt-get install -y libsasl2-dev

RUN curl -sL https://deb.nodesource.com/setup_9.x | bash - && \
    apt-get install -y nodejs

RUN apt-get install -y git

RUN apt-get install -y libpng-dev

RUN curl -o /tmp/composer-setup.php https://getcomposer.org/installer \
&& curl -o /tmp/composer-setup.sig https://composer.github.io/installer.sig \
&& php -r "if (hash('SHA384', file_get_contents('/tmp/composer-setup.php')) !== trim(file_get_contents('/tmp/composer-setup.sig'))) { unlink('/tmp/composer-setup.php'); echo 'Invalid installer' . PHP_EOL; exit(1); }" \
&& php /tmp/composer-setup.php --no-ansi --install-dir=/usr/local/bin --filename=composer --snapshot \
&& rm -f /tmp/composer-setup.*
