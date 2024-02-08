#!/usr/bin/env bash

# On install les dépendances composer
composer install -n

## SYMFONY ##
# On crée la migration pour la BD
bin/console make:migration --no-interaction

# On exécute la migration
bin/console doc:mig:mig --no-interaction

# On met à jour le schéma de la BD
bin/console d:s:u -f --no-interaction

# On charge les fixtures
bin/console doctrine:fixtures:load --no-interaction

# On nettoie le cache
bin/console cache:clear

## SYMFONY ##

chmod 777 /var/www/public/images/

exec "$@"