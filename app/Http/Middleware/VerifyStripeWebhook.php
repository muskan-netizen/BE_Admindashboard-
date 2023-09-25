<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Stripe\Exception\SignatureVerificationException;
use Stripe\Webhook;

class VerifyStripeWebhook
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        try {
            $payload = $request->getContent();
            $sigHeader = $request->header('Stripe-Signature');
            $event = Webhook::constructEvent($payload, $sigHeader, config('services.stripe.webhook_secret'));
            $request->attributes->add(['stripe_event' => $event]);
        } catch (SignatureVerificationException $e) {
            return response('Invalid webhook signature.', 401);
        }

        return $next($request);
    }
}
