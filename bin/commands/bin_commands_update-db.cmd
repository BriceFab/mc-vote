REM Avant une mise en prod
php bin/console doctrine:migrations:migrate
php bin/console make:migration
php bin/console doctrine:migrations:migrate

REM En local (dev)
php bin/console doctrine:schema:update --force