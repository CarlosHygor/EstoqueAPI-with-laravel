FROM mcr.microsoft.com/devcontainers/php:1-8.3-bookworm

# Remove repositório do yarn com chave GPG expirada na imagem base antes do apt-get update
RUN rm -f /etc/apt/sources.list.d/yarn.list \
    && apt-get update && export DEBIAN_FRONTEND=noninteractive \
    && apt-get install -y libpq-dev libzip-dev \
    && docker-php-ext-install pdo pdo_pgsql pgsql zip bcmath \
    && apt-get clean && rm -rf /var/lib/apt/lists/*
