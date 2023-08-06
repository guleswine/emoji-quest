
<p align="center">
    <a href="https://emoji-quest.com/" title="Emoji Quest Logo"><img src="https://emoji-quest.com/logo.png"></a>
</p>

## About


Multiplayer online game in real time with elements of turn-based strategy.

**[Log in and play](https://emoji-quest.com/)**

**[Play as guest](https://emoji-quest.com/guest)**

## Install
```
docker-compose up -d
docker-compose exec app cp .env.example .env
docker-compose exec app composer install
docker-compose exec app npm install
docker-compose exec app npm run build
docker-compose exec app php artisan key:generate
docker-compose exec app php artisan migrate

docker-compose exec app php artisan game:init
```
## Used

- [Laravel](https://laravel.com/)
- [Vue](https://vuejs.org)
- [Centrifugo](https://centrifugal.dev)
- [Open emoji](https://www.openmoji.org)
