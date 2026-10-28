FROM wordpress:latest

RUN apt-get update \
    && apt-get install -y --no-install-recommends libxml2-dev \
    && docker-php-ext-install soap \
    && rm -rf /var/lib/apt/lists/*

# PHP settings (from site health / requested)
RUN cat > /usr/local/etc/php/conf.d/99-custom.ini <<'INI'
max_input_vars = 10000
max_execution_time = 300
memory_limit = 1024M
max_input_time = -1
upload_max_filesize = 256M
post_max_size = 256M
INI
