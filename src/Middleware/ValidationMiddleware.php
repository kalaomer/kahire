<?php

namespace Kahire\Middleware;

use Closure;
use Kahire\Serializers\Fields\Exceptions\ValidationError;
use Symfony\Component\HttpFoundation\JsonResponse;

class ValidationMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        try {
            return $next($request);
        } catch (ValidationError $e) {
            return new JsonResponse($e->getErrors(), JsonResponse::HTTP_BAD_REQUEST);
        }
    }
}
