FROM php:7.1-cli

RUN apt-get update && \
    apt-get install -y --no-install-recommends git zip

RUN curl --silent --show-error https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN mkdir -p /app

COPY ./ /app

RUN  composer --working-dir=/app install

RUN cd /app && SKIP_TEST=1 ./vendor/bin/phpunit -d memory_limit=1G


CMD ["/bin/sh"]
