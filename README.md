## Hotel Information System API

## Installation
- clone the repo
```
$ git clone https://github.com/mohamedgaber-intake40/hotel-information-system
```
- move to the project directory
```
$ cd hotel-information-system
```
- install required packages
```
$ ./docker/composer install
```

- copy environment variables file
```
$ cp .env.example .env
```

- add database credentials in .env file

```
$ ./docker/php-artisan key:generate
```

- to run migrations and add fake data 

```
$ ./docker/php-artisan migrate --seed
```
to run the server
```
$ docker compose up
```

to run test

```
$ ./docker/php-artisan test
```

