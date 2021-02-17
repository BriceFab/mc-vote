REM Installation des vendor
composer install --no-dev --optimize-autoloader

REM Build des assets
npm install
npm run build

(+ upload dans le dossier public/build)

REM Mise à jour de la base de données
php bin/console doctrine:migrations:migrate

REM Clear le cache
APP_ENV=prod APP_DEBUG=0 php bin/console cache:clear

REM ===== Création nouvelle prod =====
git pull
git checkout branche
composer install --no-dev --optimize-autoloader
npm install
npm run build
php bin/console doctrine:schema:create
php bin/console doctrine:migrations:sync-metadata-storage
php bin/console doctrine:migrations:version --add --all
php bin/console doctrine:fixtures:load --group=base --append
APP_ENV=prod APP_DEBUG=0 php bin/console cache:clear
