<?php

namespace OFFLINE\Cashier\Classes;

use Closure;
use Config;

class IsSubscribedMiddleware
{
    /**
     * Checks if a user is currently subscribed
     * to the main subscription
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($request->user() && ! $request->user()->subscribed($this->subscription())) {
            // This user is not a paying customer...
            return $this->redirect();
        }

        return $next($request);
    }

    public function subscription()
    {
        return 'main';
    }

    public function redirect()
    {
        return redirect('billing');
    }
}