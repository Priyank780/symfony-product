### How to setup dev env

1. Create dir and add self-signed certificate files `/home/priyank/ssl-cert-snakeoil.pem` and `/home/priyank/ssl-cert-snakeoil.key`
2. Run `docker compose up -d`
3. Run `docker compose exec php-fpm composer install`
4. Run `chmod -R 777 ./var/*` to give permissions to log dir.
5. Open browser and load `https://localhost:8097/api/v1/doc`