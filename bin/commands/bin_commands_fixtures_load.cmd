REM Ecrase et charge toutes les fixtures
php bin/console doctrine:fixtures:load

REM Importe seulement le groupe de fixtures
php bin/console doctrine:fixtures:load --group=<nomgroupe/class> --append