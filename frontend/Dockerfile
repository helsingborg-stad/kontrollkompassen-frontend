FROM dunglas/frankenphp:1-php8.4-bookworm

WORKDIR /app

# The apps's front controller and assets live in app/.  FrankenPHP serves those
# files directly, while Caddy routes application paths to app/index.php.
COPY . /app
COPY Caddyfile /etc/frankenphp/Caddyfile
COPY --chmod=755 docker-entrypoint.sh /usr/local/bin/entrypoint

# TLS terminates at ingress-nginx; bind an unprivileged HTTP port in the pod.
EXPOSE 8080

ENTRYPOINT ["/usr/local/bin/entrypoint"]
CMD ["--config", "/etc/frankenphp/Caddyfile", "--adapter", "caddyfile"]
