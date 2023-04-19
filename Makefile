#!make
include .env

# Start prod environment.
prod:
	mkcert -install
	mkdir -p data/certs
	mkcert -cert-file data/certs/local-cert.pem -key-file data/certs/local-key.pem "${DOMAIN}" "*.${DOMAIN}"
	docker compose --profile traefik --profile adminer up -d
	docker exec -t drupal composer install
	docker exec -t drupal drush deploy
	docker exec -t drupal yarn
	docker exec -t drupal yarn build
	docker exec -t app rm -rf node_modules

# Start local dev environment.
dev:
	mkcert -install
	mkdir -p data/certs
	mkcert -cert-file data/certs/local-cert.pem -key-file data/certs/local-key.pem "${DOMAIN}" "*.${DOMAIN}"
	docker compose -f docker-compose.yml -f docker-compose.dev.yml --profile traefik --profile adminer up -d
	docker compose exec -it drupal composer install
	docker compose exec -it drupal yarn
	docker compose exec -it drupal yarn build

# Stop local dev environment.
dev-down:
	docker compose -f docker-compose.yml -f docker-compose.dev.yml --profile traefik --profile adminer --profile pma down

ssh:
	docker compose exec -it drupal bash

logs:
	docker compose logs drupal

check:
	docker compose exec -it drupal composer check
	docker compose exec -it drupal yarn lint

check-fix:
	docker compose exec -it drupal composer check --fix
	docker compose exec -it drupal yarn format

export-translations:
	docker compose exec -it drupal composer export-translations

import-translations:
	docker compose exec -it drupal composer import
