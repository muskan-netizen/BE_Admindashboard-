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
        'payment/payfast/notify',
        'payment/payfast/notify/app',
        'payment/paypal/notify',
        'payment/mobbex/notify',
        'payment/webhook/stripe_fpx',
        'webhook/lalamove',
        'webhook/ship-rocket',
        'passbase/webhook',
        'webhook/dunzo',
        'webhook/ahoy',
        'ccavenue/success',
        'payment/cashfree/notify',

        /** routes for edit order **/
        'edit-order/*',
        'payment/webhook/*'
    ];
}
