FROM php:8.4-cli

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

RUN apt-get update \
    && apt-get install -y --no-install-recommends git unzip \
    && rm -rf /var/lib/apt/lists/*

WORKDIR /app/playground

EXPOSE 8000

ENTRYPOINT ["sh", "/app/playground/docker/boot.sh"]
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
