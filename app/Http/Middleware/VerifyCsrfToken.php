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
       '/USSD-Q7NMAqYVcdCNmVgE','/ussd-Q7NMAqcdCNmVgE', '/validation-url-Q7N976AqYVcdCNmVgE',
       '/confirmation-url-Q7NMii654AqcdCNmVgE','/stkPush','/simulate_payment','/test-accesstoken',
       '/USSD-test-Q7NMAqY34'
    ];
}
