#!/bin/bash
set -e

echo "Initializing the docker entry point"

if [[ "${1#-}" != "$1" ]]; then
  set -- php-fpm "$@"
fi

if [[ "$1" = 'php-fpm' ]] || [[ "$1" =~ "bin/console" ]]; then
    if [[ "$APP_ENV" == 'prod' ]]; then
        bin/console doctrine:database:create --env=prod --if-not-exists
        bin/console doctrine:migrations:migrate --env=prod --quiet --no-interaction --allow-no-migration
    fi

    if [[ "$APP_ENV" == 'test' ]]; then
        bin/console doctrine:database:create --env=test --quiet --no-interaction
        bin/console doctrine:schema:update --env=test --force --no-interaction --quiet
    fi

    if [[ "$APP_ENV" == 'dev' ]]; then
        bin/console doctrine:database:create --env=dev --if-not-exists
        bin/console doctrine:migrations:migrate --env=dev --quiet --no-interaction --allow-no-migration
        bin/console prooph:create:database
        bin/console event-store:event-stream:create
    fi
fi

exec "$@"
