#!/bin/bash
set -e

check_setfacl() {
    local path="${1:-.}"
    local tmp="$path/.setfacl-test-$$"
    touch "$tmp" 2>/dev/null || return 2
    setfacl -m u:"$(whoami)":rw "$tmp" >/dev/null 2>&1 && rm "$tmp" && return 0
    rm "$tmp"
    return 1
}

[[ $APP_ENV == dev ]] && until nc -z -w 5 "${DATABASE_HOST:-database}" 3306; do
    printf "Waiting for MySQL...\n"
    sleep 1
done

if [[ $APP_ENV == prod ]]; then
  echo "Recreating cache directory"
  rm -rf var/
  mkdir -p var/cache var/log
  chmod -R 777 var/
fi

if check_setfacl var; then
    HTTPDUSER=$(ps axo user,comm | grep -E '[a]pache|[h]ttpd|[_]www|[w]ww-data|[n]ginx' | grep -v root | awk 'NR==1{print $1}')
    setfacl -dR -m u:"$HTTPDUSER":rwX -m u:"$(whoami)":rwX var
    setfacl -R -m u:"$HTTPDUSER":rwX -m u:"$(whoami)":rwX var
else
    printf "Skipping setfacl: unsupported on this volume.\n\n"
fi

php bin/console doctrine:database:create --if-not-exists --no-interaction
php bin/console doctrine:migrations:migrate --no-interaction --allow-no-migration

exec docker-php-entrypoint "$@"
