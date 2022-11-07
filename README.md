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
    - database:homestead
    - user:homestead
    - password:secret
    - host:mysql
- these are the default , but you can change these credentials in ./docker/env/mysql.env 

```
$ ./docker/php-artisan key:generate
```

- to run migrations and add fake data 

```
$ ./docker/php-artisan migrate --seed
```
to run the server (base url : http://localhost:8081)
```
$ docker compose up
```

to run test

```
$ ./docker/php-artisan test
```

- default user credentials
    - email:test@test.com
    - password:123456
    
you can test all the apis by import postman collection file included in the repo.
no need to add the token to the authorization header postman will do this automatically (it will add the token to a collection variable and use it as a header for each request in the collection).

![Alt text](UPDATED_ERD.png?raw=true "ERD")


Assumptions:
- all the apis need to be authenticated by client token so there will be login api to get this token and refresh token api to refresh this token when expired
- room facilities may belongs to many rooms like (single bed , air condition, microwave, TV etc...) so there is a relation Many to Many between rooms and facilities
- price per night may change from room to another in the same hotel , so price per night is a room data not the hotel
- no need to add direct relation between the hotel and its country , because the city is belongs to only one country , so the relation is the hotel belongs to the country through the city


real data example
- reservation 
```json
    {
        "room_id": 1,
        "hotel_id": 1,
        "hotel_name": "Regency",
        "city_name": "Alexanria",
        "country_iso_code": "EGY",
        "price_per_night": 53.23
    }
```
- hotel
```json
    {
        "id": 1,
        "name": "Regency",
        "city": {
            "id": 1,
            "name": "Alexanria",
            "country": {
                "id": 1,
                "name": "Egypt",
                "iso_code": "EGY"
            }
        }
    }
```
- room
```json
  {
        "id": 1,
        "number": 1001,
        "price_per_night": 53.23,
        "hotel": {
            "id": 1,
            "name": "Regency",
            "city": {
                "id": 1,
                "name": "Alexanria",
                "country": {
                    "id": 1,
                    "name": "Egypt",
                    "iso_code": "EGY"
                }
            }
        },
        "facilities": [
            {
                "id": 1,
                "name": "Single bed",
                "description": "loream ipsum"
            },
            {
                "id": 2,
                "name": "Air condition",
                "description": "loream ipsum"
            },
            {
                "id": 3,
                "name": "Microwave",
                "description": "loream ipsum"
            },
            {
                "id": 4,
                "name": "TV",
                "description": "loream ipsum"
            }
        ]
    },
    {
        "id": 2,
        "number": 1002,
        "price_per_night": 604.97,
        "hotel": {
            "id": 1,
             "name": "Regency",
            "city": {
                "id": 1,
                "name": "Alexanria",
                "country": {
                    "id": 1,
                    "name": "Egypt",
                    "iso_code": "EGY"
                }
            }
        },
        "facilities": [
             {
                "id": 2,
                "name": "Air condition",
                "description": "loream ipsum"
            },
            {
                "id": 3,
                "name": "Microwave",
                "description": "loream ipsum"
            },
            {
                "id": 4,
                "name": "TV",
                "description": "loream ipsum"
            }
        ]
    }
```
