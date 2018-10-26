<?php

namespace App\Observers;
use App\Customer;

class CustomerObserver
{
     /**
     * Handle the Customer "created" event.
     *
     * @param  \App\Customer  $customer
     * @return void
     */
    public function creating(Customer $customer)
    {
        $customer->api_token =  uniqid(base64_encode(str_random(60)));
    }
}
