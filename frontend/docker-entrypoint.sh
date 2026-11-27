#!/bin/sh
set -eu

exec docker-php-entrypoint "$@"
