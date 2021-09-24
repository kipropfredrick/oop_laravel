<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
       '/USSD-Q7NMAqYVcdCNmVgE','/ussd-Q7NMAqcdCNmVgE', '/c2b/validate-UjQerTLb4EM78rHBSmYgCG',
       '/c2b/confirm-7CavgY5gFFwzktQH6XjcS2','/stkPush','/simulate_payment','/test-accesstoken',
       '/USSD-test-Q7NMAqY34','/admin/check-booking-exists','/TravelCardTransaction','simulatetransaction'
    ];
}
