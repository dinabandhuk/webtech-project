# webtech-project
Ride sharing app with laravel backend and Vue frontend. \
Based on [this tutorial](https://www.youtube.com/watch?v=iFOEU6YNBzw).

- > Check the tags for releases and major checkpoints in the project.
---
### Lesson learned
- > Check the api and functionality after every change to avoid hours of misdirected debugging.
---
# Laravel Backend

## Requirements
- `composer` installed locally

## Steps to recreate
These are general instructions. Look at commit history for change in code. This is for my personal reference so it's not expected to be understood by third party.

- we will add a seperate model for drivers and apply migrations
```bash
backend $ php artisan make:model Driver --migration
```
- Similarly a model for trip 
```bash
backend $ php artisan make:model Trip --migration
```
- apply the migrations
```bash
backend $ php artisan migrate
```
- define the relationships in model files

- make controllers
```bash
php artisan make:controller LoginController
```
- make notification class
```bash
php artisan make:notification LoginNeedsVerification
```

- use twilio for notifications
```bash
composer require laravel-notification-channels/twilio
```
- refresh the migration to make name field nullable
```bash
php artisan migrate:refresh
```

- add api route to bootstrap/app.php and run
```bash
php artisan install:api
```

- query with
```bash
http POST localhost:8000/api/login/ phone=1234567890
```

- then look at the otp in users table using sqlitebrowser because trillium doesn't send message to nepal for some reason

- verify with login code and obtain the token, it sets to null even after one failure so obtain otp again. example:
```bash
 http POST localhost:8000/api/login/verify phone=1234567890 login_code=60823
 ```

- you will obtain a token like so 
```bash
1|vUnadHCCJmxlvkoE2Bh99BIwKXlzfWqhTjsyFy2k0bcd87d2
```

- now you can provide this token to get the user info:
```bash
 http GET localhost:8000/api/user 'Authorization: Bearer 1|vUnadHCCJmxlvkoE2Bh99BIwKXlzfWqhTjsyFy2k0bcd87d2'
 ```

This returns a response :
```bash
HTTP/1.1 200 OK
Access-Control-Allow-Origin: *
Cache-Control: no-cache, private
Connection: close
Content-Type: application/json
Date: Tue, 25 Feb 2025 03:08:36 GMT
Host: localhost:8000
X-Powered-By: PHP/8.4.4

{
    "created_at": "2025-02-24T09:51:08.000000Z",
    "id": 5,
    "name": null,
    "phone": "1234567890",
    "updated_at": "2025-02-24T10:26:20.000000Z"
}

```
- Similar for `/api/driver` `GET` request

- to update the driver then follow
```bash
[mounam@moksha backend (main)]$ http POST localhost:8000/api/driver 'Authorization: Bearer 1|vUnadHCCJmxlvkoE2Bh99BIwKXlzfWqhTjsyFy2k0bcd87d2' Accept:application/json color=white license_plate=ba50pa2020 make=toyota model=yaris name=dinabandhu year=2004

HTTP/1.1 200 OK
Access-Control-Allow-Origin: *
Cache-Control: no-cache, private
Connection: close
Content-Type: application/json
Date: Tue, 25 Feb 2025 03:37:29 GMT
Host: localhost:8000
X-Powered-By: PHP/8.4.4

{
    "created_at": "2025-02-24T09:51:08.000000Z",
    "driver": {
        "color": "white",
        "created_at": "2025-02-25T03:37:29.000000Z",
        "id": 1,
        "license_plate": "ba50pa2020",
        "make": "toyota",
        "model": "yaris",
        "updated_at": "2025-02-25T03:37:29.000000Z",
        "user_id": 5,
        "year": 2004
    },
    "id": 5,
    "name": "dinabandhu",
    "phone": "1234567890",
    "updated_at": "2025-02-25T03:37:29.000000Z"
}

```

- run migration for only a specific file without clearing the database because we made the driver nullable
```bash
[mounam@moksha backend (main)]$ php artisan migrate:refresh --path=./database/migrations/2025_02_24_064721_create_trips_table.php 
```

- so the trip was created like so 
```bash
[mounam@moksha backend (main)]$ http POST localhost:8000/api/trip 'Authorization: Bearer 1|vUnadHCCJmxlvkoE2Bh99BIwKXlzfWqhTjsyFy2k0bcd87d2' destination_name=Lalitpur destination:='{"lat":12.345, "lng":67.890}' origin:='{"lat": 45.56, "lng": 23.56}
'
HTTP/1.1 201 Created
Access-Control-Allow-Origin: *
Cache-Control: no-cache, private
Connection: close
Content-Type: application/json
Date: Tue, 25 Feb 2025 04:38:26 GMT
Host: localhost:8000
X-Powered-By: PHP/8.4.4

{
    "created_at": "2025-02-25T04:38:26.000000Z",
    "destination": {
        "lat": 12.345,
        "lng": 67.89
    },
    "destination_name": "Lalitpur",
    "id": 1,
    "origin": {
        "lat": 45.56,
        "lng": 23.56
    },
    "updated_at": "2025-02-25T04:38:26.000000Z",
    "user_id": 5
}
```

- get the trip info for trip id (also don't forget to write $request->user()->id on app/Http/Controllers/TripController.php and not $request->user->id and spend an hour debugging)
```bash
[mounam@moksha backend (main)]$ http GET localhost:8000/api/trip/1 'Authorization: Bearer 1|vUnadHCCJmxlvkoE2Bh99BIwKXlzfWqhTjsyFy2k0bcd87d2'
HTTP/1.1 200 OK
Access-Control-Allow-Origin: *
Cache-Control: no-cache, private
Connection: close
Content-Type: application/json
Date: Tue, 25 Feb 2025 05:28:26 GMT
Host: localhost:8000
X-Powered-By: PHP/8.4.4

{
    "created_at": "2025-02-25T05:13:58.000000Z",
    "destination": {
        "lat": 12.345,
        "lng": 67.89
    },
    "destination_name": "Lalitpur",
    "driver_id": null,
    "driver_location": null,
    "id": 1,
    "is_complete": false,
    "is_started": false,
    "origin": {
        "lat": 45.56,
        "lng": 23.56
    },
    "updated_at": "2025-02-25T05:13:58.000000Z",
    "user": {
        "created_at": "2025-02-24T09:51:08.000000Z",
        "id": 5,
        "name": "dinabandhu",
        "phone": "1234567890",
        "updated_at": "2025-02-25T03:37:29.000000Z"
    },
    "user_id": 5
}
```

- websocket events 
```bash
php artisan make:event TripStarted
php artisan make:event TripEnded
php artisan make:event TripAccepted
php artisan make:event TripLocationUpdated
```
- install laravel websockets
```bash
php artisan install:broadcasting
```
- the server may be started via 
```bash
php artisan reverb:start
```
---
# Vue Frontend 

- init vue project 
```bash 
[mounam@moksha frontend (main)]$ npm init vue@latest .
```
- install tailwindcss
```bash
npm install tailwindcss @tailwindcss/vite```
- initialize 