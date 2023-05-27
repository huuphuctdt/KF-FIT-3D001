<?php

namespace App\Providers;

use App\Events\OrderSuccessEvent;
use App\Events\TestEvent;
use App\Listeners\SendEmailToCustomerWhenOrderSuccess;
use App\Listeners\SendSmsToCustomerWhenOrderSuccess;
use App\Listeners\SendTestEvent;
use App\Listeners\UpdateOrderPaymentMethodStatusWhenOrderSuccess;
use App\Listeners\UpdateOrderStatusWhenOrderSuccess;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        OrderSuccessEvent::class => [
            SendEmailToCustomerWhenOrderSuccess::class,
            SendSmsToCustomerWhenOrderSuccess::class,
            UpdateOrderStatusWhenOrderSuccess::class,
            UpdateOrderPaymentMethodStatusWhenOrderSuccess::class,
            // SendEmailToAdminWhenOrderSuccess::class,
        ],
        ProductCreateSuccess::class => [],

    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
