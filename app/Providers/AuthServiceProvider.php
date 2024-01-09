<?php

namespace App\Providers;

use App\Models\Attendee;
use App\Models\Event;
use App\Policies\AttendeePolicy;
use App\Policies\EventPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

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
