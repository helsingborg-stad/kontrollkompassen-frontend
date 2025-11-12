# Dev container Dockerfile for Kontrollkompassen frontend
# Based on Microsoft devcontainer PHP image with Node support
FROM mcr.microsoft.com/devcontainers/php:1-8.2

# Install system packages required for composer/npm and common PHP extensions
RUN apt-get update \
    && apt-get install -y --no-install-recommends \
    ca-certificates \
    curl \
    git \
    unzip \
    libzip-dev \
    zlib1g-dev \
    libicu-dev \
    && rm -rf /var/lib/apt/lists/*

# Install Node.js (LTS) from NodeSource so we have npm available
RUN curl -fsSL https://deb.nodesource.com/setup_24.x | bash - \
    && apt-get install -y nodejs \
    && npm --version && node --version

# Install PHP extensions commonly needed by frameworks / composer packages
# The base image provides the `install-php-extensions` helper script
RUN install-php-extensions zip intl mbstring pcntl || true

# Ensure composer is available (some base images include it, ensure idempotent)
RUN if ! command -v composer > /dev/null; then \
    php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" && \
    php composer-setup.php --install-dir=/usr/local/bin --filename=composer && \
    php -r "unlink('composer-setup.php');" ; fi

WORKDIR /workspace

USER vscode
