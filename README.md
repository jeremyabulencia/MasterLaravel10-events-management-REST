<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

## Installation of Event Management App (REST API)

##### Create a laravel project
```bash
    composer create-project --prefer-dist laravel/laravel event-management
```

##### Modify .env file for database connection
```php
    DB_DATABASE=laravel-10-event-management
    DB_USERNAME=[USERNAME]
    DB_PASSWORD=[PASSWORD]
```

##### Run migrate command to check if connection can be established and create the database
```bash
    php artisan migrate
```


##### Create Models with a migration file(-m)
```bash
    # create model with migration files
    php artisan make:model Event -m 
```
<p align="right"><a href="https://github.com/jeremyabulencia/MasterLaravel10-events-management-REST/commit/d7d70495c6afd0d4dc4f9f7582df988dd3a3869f">source code</a></p>

```bash
    # create model with migration files
    php artisan make:model Attendee -m
```
<p align="right"><a href="https://github.com/jeremyabulencia/MasterLaravel10-events-management-REST/commit/1b7a1df6442c8f9a09b563d4e05863f4da223b23">source code</a></p>

##### Create API Controllers
```bash
    # create api controller for Attendee
    php artisan make:controller Api\AttendeeController --api
```
<p align="right"><a href="https://github.com/jeremyabulencia/MasterLaravel10-events-management-REST/commit/cf710283309aa7887da0b4343bf9ff4cb4e91eab">source code</a></p>

```bash
    # create api controller for Event
    php artisan make:controller Api\EventController --api
```
<p align="right"><a href="https://github.com/jeremyabulencia/MasterLaravel10-events-management-REST/commit/36be4b30ac12e03ca3117e74aee89c079ade5452">source code</a></p>

##### Creating routes on `routes/api.php`
```php
    # use namespace on top
    use App\Http\Controllers\Api\AttendeeController;
    use App\Http\Controllers\Api\EventController;

    # add routes below
    Route::apiResource('events', EventController::class);
    Route::apiResource('events.attendees', AttendeeController::class)
        ->scoped(['attendee' => 'event']);
```

##### Define fields on migration and Relationships
```link
    https://github.com/jeremyabulencia/MasterLaravel10-events-management-REST/commit/e0c7ce8ce2d8df604f2ba7c0fbb6225933eeed62
```

##### Run migration with seed to apply migrations to the Database
```bash
    php artisan migrate:refresh --seed
```