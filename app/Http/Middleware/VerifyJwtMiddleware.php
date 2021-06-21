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
            convertObjToArray(getDataFromJWT($request), $o365Data);
            if (empty($o365Data)) {
                return getResponse(
                    Response::HTTP_UNAUTHORIZED,
                    Response::$statusTexts[Response::HTTP_UNAUTHORIZED],
                    [],
                    Response::HTTP_UNAUTHORIZED
                );
            }
            $request->attributes->add(['o365_data' => $o365Data]);
        } catch (Exception $e) {
            return getResponse(
                Response::HTTP_UNAUTHORIZED,
                $e->getMessage(),
                [],
                Response::HTTP_UNAUTHORIZED
            );
        }
        return $next($request);
    }
}
