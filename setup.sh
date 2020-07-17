#!/bin/sh
# set -e
# get setup details
declare -A details
while IFS= read -r name || [[ -n "$name" ]]; do
    arrIN=(${name//=/ })
    details[${arrIN[0]}]=${arrIN[1]}
done < .env.setup
# install laravel using setup details
#install laravel using docker instead of composer
composer create-project --prefer-dist laravel/laravel ./www ${details[LARAVEL_VERSION]}

cp ./.env.docker.example ./www/.env
#run docker
docker-compose up  -d --build
echo "Starting shell"
echo "Shell started."

docker-compose exec -T -i laravel-applidation sh