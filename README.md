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

### Authentication with Gates
`AuthServiceProvider.php`
```php
    public function boot(): void
    {
        Gate::define('update-event', function ($user, Event $event) {
            return $user->id === $event->user_id;
        });

        Gate::define('delete-attendee', function ($user, Event $event, Attendee $attendee) {
            return $user->id === $event->user_id || $user->id === $attendee->user_id;
        });
    }
```
`EventController.php`
```php
    public function update(Request $request, Event $event)
    {
        // if (Gate::denies('update-event', $event)) {
        //     abort(403, 'You are not authorized to update this event.');
        // }
        $this->authorize('update-event', $event);

        $event->update($request->validate([
            'name'          => 'sometimes|string|max:255',
            'description'   => 'nullable|string',
            'start_time'    => 'sometimes|date',
            'end_time'      => 'sometimes|date|after:start_time'
        ]));

        return new EventResource($this->loadRelationships($event));
    }
```
`AttendeeController.php`
```php
    public function destroy(Event $event, Attendee $attendee)
    {
        $this->authorize('delete-attendee', [$event, $attendee]);
        $attendee->delete();

        return response(status : 204);
    }
```

### Authentication with Policies
```bash
    php artisan make:policy EventPolicy --model=Event
```
```bash
    php artisan make:policy AttendeePolicy --model=Attendee
```
`AuthServiceProvider.php`
```php
    class AuthServiceProvider extends ServiceProvider
    {
        /**
         * The model to policy mappings for the application.
         *
         * @var array<class-string, class-string>
         */

        // not required only to override the default behavior (best to use standard ways)
        protected $policies = [
            // Event::class => EventPolicy::class,
            // Attendee::class => AttendeePolicy::class
        ];

        /**
         * Register any authentication / authorization services.
         */
        public function boot(): void
        {
            // commented because Policies were added
            // Gate::define('update-event', function ($user, Event $event) {
            //     return $user->id === $event->user_id;
            // });

            // Gate::define('delete-attendee', function ($user, Event $event, Attendee $attendee) {
            //     return $user->id === $event->user_id || $user->id === $attendee->user_id;
            // });
        }
    }
```
`EventController.php`
```php
    public function __construct()
    {
        $this->middleware('auth:sanctum')->except(['index', 'show']);
        $this->authorizeResource(Event::class,  'event');
    }
```
`AttendeeController.php`
```php
    public function __construct()
    {
        $this->middleware('auth:sanctum')->except(['index', 'show', 'update']);
        $this->authorizeResource(Attendee::class, 'attendee');
    }
```
`EventPolicy.php`
```php
    class EventPolicy
    {
        /**
         * Determine whether the user can view any models.
         */
        public function viewAny(?User $user): bool
        {
            return true;
        }

        /**
         * Determine whether the user can view the model.
         */
        public function view(?User $user, Event $event): bool
        {
            return true;
        }

        /**
         * Determine whether the user can create models.
         */
        public function create(User $user): bool
        {
            return true;
        }

        /**
         * Determine whether the user can update the model.
         */
        public function update(User $user, Event $event): bool
        {
            return $user->id === $event->user_id;
        }

        /**
         * Determine whether the user can delete the model.
         */
        public function delete(User $user, Event $event): bool
        {
            return $user->id === $event->user_id;
        }

        /**
         * Determine whether the user can restore the model.
         */
        public function restore(User $user, Event $event): bool
        {
            //
        }

        /**
         * Determine whether the user can permanently delete the model.
         */
        public function forceDelete(User $user, Event $event): bool
        {
            //
        }
    }
```
`AttendeePolicy.php`
```php
    class AttendeePolicy
    {
        /**
         * Determine whether the user can view any models.
         */
        public function viewAny(?User $user): bool
        {
            return true;
        }

        /**
         * Determine whether the user can view the model.
         */
        public function view(?User $user, Attendee $attendee): bool
        {
            return true;
        }

        /**
         * Determine whether the user can create models.
         */
        public function create(User $user): bool
        {
            return true;
        }

        /**
         * Determine whether the user can delete the model.
         */
        public function delete(User $user, Attendee $attendee): bool
        {
            return $user->id === $attendee->event->user_id || 
                $user->id === $attendee->user_id;
        }

        /**
         * Determine whether the user can restore the model.
         */
        public function restore(User $user, Attendee $attendee): bool
        {
            //
        }

        /**
         * Determine whether the user can permanently delete the model.
         */
        public function forceDelete(User $user, Attendee $attendee): bool
        {
            //
        }
    }
```

### Event Reminder
```bash
    # create app/Console/Commands/SendEventReminders.php
    php artisan make:command SendEventReminders
```
`SendEventReminders.php`
```php
    <?php

        namespace App\Console\Commands;

        use App\Models\Event;
        use Illuminate\Console\Command;
        use Illuminate\Support\Str;

        class SendEventReminders extends Command
        {
            /**
             * The name and signature of the console command.
             *
             * @var string
             */
            protected $signature = 'app:send-event-reminders';

            /**
             * The console command description.
             *
             * @var string
             */
            protected $description = 'Sends notifications to all event attendees that event starts soon';

            /**
             * Execute the console command.
             */
            public function handle()
            {
                $events = Event::with('attendees.user')
                    ->whereBetween('start_time', [now(), now()->addDay()])
                    ->get();

                $eventCount = $events->count();
                $eventLabel = Str::plural('event', $eventCount);
                
                $this->info("Found {$eventCount} {$eventLabel}.");

                $events->each(
                    fn($event) => $event->attendees->each(
                        fn($attendee) => 
                            $this->info("Notifying the user {$attendee->user->id}")
                        )
                    );

                $this->info('Reminder notifications sent successfully!');
            }
        }
```
```bash
    # run the event
    $ php artisan app:send-event-reminders
    Found 1 event.
    Notifying the user 138
    Notifying the user 573
    Reminder notifications sent successfully!
```

### Task Scheduling
```link
    https://laravel.com/docs/10.x/scheduling
```
`Kernel.php`
```php
    protected function schedule(Schedule $schedule): void
    {
        // $schedule->command('inspire')->hourly();
        $schedule->command('app:send-event-reminders')
            ->everyMinute();
    }
```
```bash
    # to run command continously
    php artisan schedule:work
```
```bash
    $ php artisan schedule:work

    INFO  Running scheduled tasks every minute.


    2024-01-11 08:13:00 Running ["artisan" app:send-event-reminders] ...................................................................... 450ms DONE
    ⇂ "C:\php8\php.exe" "artisan" app:send-event-reminders > "NUL" 2>&1


    2024-01-11 08:14:00 Running ["artisan" app:send-event-reminders] ...................................................................... 416ms DONE
    ⇂ "C:\php8\php.exe" "artisan" app:send-event-reminders > "NUL" 2>&1


    2024-01-11 08:15:00 Running ["artisan" app:send-event-reminders] ...................................................................... 433ms DONE
    ⇂ "C:\php8\php.exe" "artisan" app:send-event-reminders > "NUL" 2>&1

```

### Notifications
```bash
    php artisan make:Notification EventReminderNotification
```

### Setting Up Email (Mailpit)
`EventReminderNotification.php`
```php
    public function __construct(
        public Event $event
    )
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->line('Reminder: You have an upcoming event!')
                    ->action('View Event', route('events.show', $this->event->id))
                    ->line(
                        "This event {$this->event->name} start at {$this->event->start_time}"
                    );
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'event_id'          => $this->event->id,
            'event_name'        => $this->event->name,
            'event_start_time'  => $this->event->start_time,
        ];
    }
```
`SendEventReminders.php`
```php
    public function handle()
    {
        $events = Event::with('attendees.user')
            ->whereBetween('start_time', [now(), now()->addDay()])
            ->get();

        $eventCount = $events->count();
        $eventLabel = Str::plural('event', $eventCount);
        
        $this->info("Found {$eventCount} {$eventLabel}.");

        $events->each(
            fn($event) => $event->attendees->each(
                fn($attendee) => 
                    $attendee->user->notify(
                        new EventReminderNotification(
                            $event
                        )
                    )
                )
            );

        $this->info('Reminder notifications sent successfully!');
    }
```
#### configure env
`.env`
```php
    MAIL_MAILER=smtp
    MAIL_HOST=127.0.0.1
    MAIL_PORT=1025
    MAIL_USERNAME=null
    MAIL_PASSWORD=null
    MAIL_ENCRYPTION=null
    MAIL_FROM_ADDRESS=jabulencia@examp.com
    MAIL_FROM_NAME="${APP_NAME}"
```
#### run the task
```bash
    php artisan app:send-event-reminders
```
Encountered an error on mailpit
```error
    Connection could not be established with host "mailpit:1025": stream_socket_client(): php_network_getaddresses: getaddrinfo for mailpit failed: No such host is known.
```
I ran this and discovered that it cached the unconfigured settings for mailpit
```bash
    $ grep -R mailpit * | egrep -v storage
    bootstrap/cache/config.php:        'host' => 'mailpit',
    vendor/laravel/sail/src/Console/Concerns/InteractsWithDockerComposeServices.php:        'mailpit',
    vendor/laravel/sail/src/Console/Concerns/InteractsWithDockerComposeServices.php:    protected $defaultServices = ['mysql', 'redis', 'selenium', 'mailpit'];
    vendor/laravel/sail/src/Console/Concerns/InteractsWithDockerComposeServices.php:        if (in_array('mailpit', $services)) {
    vendor/laravel/sail/src/Console/Concerns/InteractsWithDockerComposeServices.php:            $environment = preg_replace("/^MAIL_HOST=(.*)/m", "MAIL_HOST=mailpit", $environment);
    vendor/laravel/sail/stubs/mailpit.stub:mailpit:
    vendor/laravel/sail/stubs/mailpit.stub:    image: 'axllent/mailpit:latest'
```
then I run this to remove cache
```bash
    php artisan config:clear
```
Result
```bash
    php artisan app:send-event-reminders

    Found 5 events.
    Reminder notifications sent successfully!
```
#### check mailpit (localhost:8025)
I set up the mailpit via docker
```link
    https://github.com/jeremyabulencia/setting-up/commit/29e4ad7da295bcc19df182d9dc128a335b5c4931
```

## Queuing
### Setting Up Queue Driver
```
    Redis
    Amazon SQS
    Database
```
```bash
    // create a migration file for jobs table
    php artisan queue:table
```
```bash
    // create a table for queueing
    php artisan migrate
```
`.env`
```php
    QUEUE_CONNECTION=database
```
`EventReminderNotification.php`
```php
    class EventReminderNotification extends Notification implements ShouldQueue
```
run scheduler
```bash
    php artisan app:send-event-reminders 
```
data to be run is saved on *jobs* table
```bash
    // to execute the queued jobs (needs to be running all the time)
    php artisan queue:work
```
 