# Drupal

## Start project

1. Create `.env` based on `.env.example`.
2. Start docker containers:
    - base command: `docker compose up -d`
    - with local traefik: `docker compose --profile traefik up -d` (otherwise separately running traefik instance required attached to `traefik` docker network)
    - with pma: `docker compose --profile pma up -d`
    - with adminer: `docker compose --profile adminer up -d`
    - with traefik and pma: `docker compose --profile traefik --profile pma up -d`
    - after changes to Dockerfile `--build` arg is required to rebuild image
3. Install composer dependencies: `docker exec -it <drupal_container> composer install`

## Stop project

`docker compose [--profile <profile>, ...] down`
eg. `docker compose --profile traefik --profile pma down`

## List running containers

`docker compose ps`

## Import db

`docker exec -i <db_container> mysql -u<user> -p<pass> <database> < dump.sql`

## Drush

`docker compose exec -it drupal drush ...`

## Composer

`docker compose exec -it drupal composer ...`

## Local https cert setup

1. Install <https://github.com/FiloSottile/mkcert>.
2. `mkcert -install`
3. `mkdir -p data/certs`
4. `mkcert -cert-file data/certs/local-cert.pem -key-file data/certs/local-key.pem "drupal.loc" "*.drupal.loc"`
5. (Re)start project.
