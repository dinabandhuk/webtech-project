# webtech-project
Ride sharing app with laravel backend and Vue frontend. \
Based on [this tutorial](https://www.youtube.com/watch?v=iFOEU6YNBzw).

## Requirements
- `composer` installed locally

## steps to recreate

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
