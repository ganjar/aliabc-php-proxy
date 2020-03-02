container="aliabc-php-demo"
port="80"

up: ; docker-compose up -d

open: ; open "http://"`docker-compose port ${container} ${port}`