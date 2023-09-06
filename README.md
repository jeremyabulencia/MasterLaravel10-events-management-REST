<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

# Installation of Event Management App (REST API)

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

## Defining Routes
`routes.php`
```php
    Route::apiResource('events', EventController::class);
    Route::apiResource('events.attendees', AttendeeController::class)
        ->scoped(['attendee' => 'event']);
```
`EventController.php`
##### Listing
```curl
    curl --location 'http://127.0.0.1:8000/api/events'
```
```php
    <?php

    namespace App\Http\Controllers\Api;

    use App\Http\Controllers\Controller;
    use App\Models\Event;
    use Illuminate\Http\Request;

    class EventController extends Controller
    {
        /**
         * Display a listing of the resource.
         */
        public function index()
        {
            return Event::all();
        }
```

##### Show
```curl
    curl --location 'http://127.0.0.1:8000/api/events/202'
```
```php
    <?php

    namespace App\Http\Controllers\Api;

    use App\Http\Controllers\Controller;
    use App\Models\Event;
    use Illuminate\Http\Request;
    class EventController extends Controller
    {
        public function show(Event $event)
        {
            return $event;
        }
```

##### Store
```curl
    curl --location 'http://127.0.0.1:8000/api/events' \
    --header 'Accept: application/json' \
    --header 'Content-Type: application/json' \
    --data '{
        "name": "FirstEvent",
        "start_time": "2023-08-30 08:00:00",
        "end_time": "2023-09-01 07:59:59"
    }'
```
```php
    <?php

    namespace App\Http\Controllers\Api;

    use App\Http\Controllers\Controller;
    use App\Models\Event;
    use Illuminate\Http\Request;
    class EventController extends Controller
    {
        public function store(Request $request)
        {
            $event = Event::create([
                ...$request->validate([
                    'name'          => 'required|string|max:255',
                    'description'   => 'nullable|string',
                    'start_time'    => 'required|date',
                    'end_time'      => 'required|date|after:start_time'
                ]),
                "user_id"   => 1
            ]);

            return $event;
        }
```

##### Update
```curl
    curl --location --request PUT 'http://127.0.0.1:8000/api/events/202' \
    --header 'Content-Type: application/json' \
    --data '{
        "name": "FirstEvent Updated 2",
        "start_time": "2023-08-30 08:00:00",
        "end_time": "2023-09-01 07:59:59"
    }'
```
```php
    <?php

    namespace App\Http\Controllers\Api;

    use App\Http\Controllers\Controller;
    use App\Models\Event;
    use Illuminate\Http\Request;
    class EventController extends Controller
    {
        public function update(Request $request, Event $event)
        {
            $event->update($request->validate([
                'name'          => 'sometimes|string|max:255',
                'description'   => 'nullable|string',
                'start_time'    => 'sometimes|date',
                'end_time'      => 'sometimes|date|after:start_time'
            ]));

            return $event;
        }
```

##### Destroy
```curl
    curl --location --request DELETE 'http://127.0.0.1:8000/api/events/202'
```
```php
    <?php

    namespace App\Http\Controllers\Api;

    use App\Http\Controllers\Controller;
    use App\Models\Event;
    use Illuminate\Http\Request;
    class EventController extends Controller
    {
        public function destroy(Event $event)
        {
            $event->delete();

            return response(status: 204);
        }
```
### API Resource Response JSON
```bash
    php artisan make:resource UserResource
    php artisan make:resource EventResource
    php artisan make:resource AttendeeResource
```
`UserResource.php`
```php
    <?php

    namespace App\Http\Resources;

    use Illuminate\Http\Request;
    use Illuminate\Http\Resources\Json\JsonResource;

    class UserResource extends JsonResource
    {
        /**
         * Transform the resource into an array.
         *
         * @return array<string, mixed>
         */
        public function toArray(Request $request): array
        {
            return parent::toArray($request);
        }
    }
```
`EventResource.php`
```php
    <?php

    namespace App\Http\Resources;

    use Illuminate\Http\Request;
    use Illuminate\Http\Resources\Json\JsonResource;

    class EventResource extends JsonResource
    {
        /**
         * Transform the resource into an array.
         *
         * @return array<string, mixed>
         */
        public function toArray(Request $request): array
        {
            return [
                'id'            => $this->id,
                'name'          => $this->description,
                'description'   => $this->description,
                'start_time'    => $this->start_time,
                'end_time'      => $this->end_time,
                'user'          => new UserResource($this->whenLoaded('user')),
                'attendees'     => AttendeeResource::collection(
                    $this->whenLoaded('attendees')
                ),
            ];
        }
    }
```
`AttendeeResource.php`
```php
    <?php

    namespace App\Http\Resources;

    use Illuminate\Http\Request;
    use Illuminate\Http\Resources\Json\JsonResource;

    class AttendeeResource extends JsonResource
    {
        /**
         * Transform the resource into an array.
         *
         * @return array<string, mixed>
         */
        public function toArray(Request $request): array
        {
            return parent::toArray($request);
        }
    }
```
### Optional Relation Loading
```curl
    http://127.0.0.1:8000/api/events?include=user,attendees
```
```php
    class EventController extends Controller
    {

        public function index()
        {
            $query = Event::query();
            $relations = ['user', 'attendees', 'attendees.user'];

            foreach ($relations as $relation) {
                $query->when(
                    $this->shouldIcludeRelation($relation),
                    fn($q)  => $q->with($relation)
                );
            }

            return EventResource::collection(
                $query->latest()->paginate()
            );
        }

        protected function shouldIcludeRelation(string $relation): bool
        {
            $include = request()->query('include');

            if (!$include) {
                return false;
            }

            $relations = array_map('trim', explode(',', $include));

            return in_array($relation, $relations);
        }
```

### Universal(Reusable) Relation Loading Trait
`CanLoadRelationships.php`
```php
    <?php

    namespace App\Http\Traits;

    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Database\Query\Builder as QueryBuilder;
    use Illuminate\Database\Eloquent\Builder as EloquentBuilder;

    trait CanLoadRelationships
    {
        public function loadRelationships(
            Model|EloquentBuilder|QueryBuilder $for,
            ?array $relations = null
        ): Model|EloquentBuilder|QueryBuilder
        {
            $relations = $relations ?? $this->relations ?? [];
            foreach ($relations as $relation) {
                $for->when(
                    $this->shouldIcludeRelation($relation),
                    fn($q)  => $for instanceof Model ? $for->load($relation) : $for->with($relation)
                );
            }

            return $for;
        }

        protected function shouldIcludeRelation(string $relation): bool
        {
            $include = request()->query('include');

            if (!$include) {
                return false;
            }

            $relations = array_map('trim', explode(',', $include));

            return in_array($relation, $relations);
        }
    }
```
`EventController.php`
```php
    use App\Http\Traits\CanLoadRelationships;
    class EventController extends Controller
    {
        use CanLoadRelationships;
        private array $relations = ['user', 'attendees', 'attendees.user'];

        public function index()
        {        
            $query = $this->loadRelationships(Event::query());
            ...
        }

        public function store(Request $request)
        {
            ...
            return new EventResource($this->loadRelationships($event));
        }

        public function show(Event $event)
        {
            return new EventResource($this->loadRelationships($event));
        }

        public function update(Request $request, Event $event)
        {
            ...

            return new EventResource($this->loadRelationships($event));
        }

```
## Setting up Authentication Using Sanctum
<p>
    <a href="https://laravel.com/docs/10.x/sanctum">
        https://laravel.com/docs/10.x/sanctum
    </a>
</p>

### Sanctum Installation
```bash
    composer require laravel/sanctum
```
### Publish Sanctum configuration and migration
```bash
    php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
```
### Run migrate if needed
```bash
    php artisan migrate
```
### Create Authentication Controller
```bash 
    php artisan make:controller Api/AuthController
```
### Revoking Token
<p>
    <a href="https://laravel.com/docs/10.x/sanctum#revoking-tokens">https://laravel.com/docs/10.x/sanctum#revoking-tokens</a>
</p>

```php
    // Revoke all tokens...
    $user->tokens()->delete();
    
    // Revoke the token that was used to authenticate the current request...
    $request->user()->currentAccessToken()->delete();
    
    // Revoke a specific token...
    $user->tokens()->where('id', $tokenId)->delete();
```