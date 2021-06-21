<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Response as Response;
use Exception;

class VerifyJwtMiddleware
{
    /**
     * @param $request
     * @param Closure $next
     * @return Response|mixed
     */
    public function handle($request, Closure $next)
    {
        try {
            $data = getDataFromJWT($request);

            if (empty($data)) {
                return getResponse(null, Response::$statusTexts[Response::HTTP_UNAUTHORIZED], false, Response::HTTP_UNAUTHORIZED, Response::HTTP_UNAUTHORIZED);
            }
        } catch (Exception $e) {
            return getResponse(null, $e->getMessage(), false, Response::HTTP_UNAUTHORIZED, Response::HTTP_UNAUTHORIZED);
        }
        return $next($request);
    }
}
