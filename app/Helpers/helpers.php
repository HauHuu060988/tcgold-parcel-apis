<?php

use Firebase\JWT\JWT;
use Illuminate\Http\Request;
use Illuminate\Http\Response as Response;
use Illuminate\Support\Facades\Crypt;

if (!function_exists('getResponse')) {
//    $responseCode: int; example 0, -1, -2, -3
    //    $responseMsg: string; example: "Success."
    //    $httpCode: int; example: 200, 500, 400, 404
    //    cookie [ name ,value, minutes]
    //    $headers = ['Content-Type' => 'application/json']
    function getResponse(
        $data = [],
        $message = null,
        $status = true,
        $code = null,
        $httpCode = Response::HTTP_OK,
        $headers = ['Content-Type' => 'application/json'],
        $cookie = []
    ) {
        try {

            $result = [
                'status' => $status,
                'code' => $code,
                'message' => $message,
                'data' => $data,
            ];

            $content = json_encode($result, true);


            $response = new Response($content, $httpCode);
            if ($headers) {
                $response->withHeaders($headers);
            }
            if ($cookie) {
                $response->cookie($cookie['name'], $cookie['value'], $cookie['minutes']);
            }
            return $response;
        } catch (Exception $e) {
            $result = [
                'status' => false,
                'code' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'message' => Response::$statusTexts[Response::HTTP_INTERNAL_SERVER_ERROR],
                'data' => $data,
            ];
            $response = new Response(json_encode($result), Response::HTTP_INTERNAL_SERVER_ERROR);
            return $response;
        }
    }
}

if (!function_exists('setDataFromJWT')) {
    /**
     * Create JWT
     *
     * @param $data
     * @return null
     */
    function setDataFromJWT($data)
    {
        try {
            $key = config('jwt.secret');
            $algo = config('jwt.algo');
            $ttl = config('jwt.ttl');

            $token = array(
                "iat" => time(),
                "nbf" => time(),
                "exp" => strtotime("+$ttl minutes", time()),
                "data" => $data,
            );

            return JWT::encode($token, $key, $algo);
        } catch (Exception $e) {
            return null;
        }
    }
}

if (!function_exists('getDataFromJWT')) {

    function getDataFromJWT(Request $request)
    {
        try {
            $data = null;
            $bearerToken = $request->bearerToken();

            if (empty($bearerToken)) {
                $bearerToken = $request->server->get('HTTP_AUTHORIZATION') ?
                    $request->server->get('HTTP_AUTHORIZATION') :
                    $request->server->get('REDIRECT_HTTP_AUTHORIZATION');
            }

            $bearerToken = urldecode($bearerToken);
            $jwt = Crypt::decryptString($bearerToken);
            $key = config('jwt.secret');
            $algo = config('jwt.algo');
            $leeway = config('jwt.leeway');

            JWT::$leeway += $leeway;
            $token = JWT::decode($jwt, $key, array($algo));
            if (is_object($token)) {
                //Check expire
                $current = time();

                if (isset($token->exp) && $token->exp > $current) {
                    if (isset($token->data)) {
                        $data = $token->data;
                    }
                }
            }
            return $data;
        } catch (Exception $e) {
            throw  $e;
        }
    }
}
