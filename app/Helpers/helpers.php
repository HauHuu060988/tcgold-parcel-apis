<?php

use Firebase\JWT\JWT;
use Illuminate\Http\Response as Response;

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

if (!function_exists('getUserIP')) {
    function getUserIP()
    {
        // Get real visitor IP behind CloudFlare network
        if (isset($_SERVER['HTTP_CF_CONNECTING_IP'])) {
            $_SERVER['REMOTE_ADDR'] = $_SERVER['HTTP_CF_CONNECTING_IP'];
            $_SERVER['HTTP_CLIENT_IP'] = $_SERVER['HTTP_CF_CONNECTING_IP'];
        }
        $client = @$_SERVER['HTTP_CLIENT_IP'];
        $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
        $remote = $_SERVER['REMOTE_ADDR'];
        if (filter_var($client, FILTER_VALIDATE_IP)) {
            $ip = $client;
        } elseif (filter_var($forward, FILTER_VALIDATE_IP)) {
            $ip = $forward;
        } else {
            $ip = $remote;
        }
        return $ip;
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

            $jwt = JWT::encode($token, $key, $algo);

            return $jwt;
        } catch (Exception $e) {
            return null;
        }

        return null;
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

if (!function_exists('generateNumericOTP')) {
    function generateNumericOTP($n)
    {
        $iDigits = '135792468'; //1357902468
        // Iterate for n-times and pick a single character

// ---generate a random number
        // ---take modulus of same with length of Digits (say i)
        // ---append the character at place (i) from Digits to otp
        $iOtp = '';

        for ($i = 1; $i <= $n; $i++) {
            $iOtp .= substr($iDigits, (rand() % (strlen($iDigits))), 1);
        }
        return $iOtp;
    }
}

if (!function_exists('utf8convert')) {
    function utf8convert($str)
    {
        $unicode = array(
            'a' => 'á|à|ả|ã|ạ|ă|ắ|ặ|ằ|ẳ|ẵ|â|ấ|ầ|ẩ|ẫ|ậ',
            'd' => 'đ',
            'e' => 'é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ',
            'i' => 'í|ì|ỉ|ĩ|ị',
            'o' => 'ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ',
            'u' => 'ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự',
            'y' => 'ý|ỳ|ỷ|ỹ|ỵ',
            'A' => 'Á|À|Ả|Ã|Ạ|Ă|Ắ|Ặ|Ằ|Ẳ|Ẵ|Â|Ấ|Ầ|Ẩ|Ẫ|Ậ',
            'D' => 'Đ',
            'E' => 'É|È|Ẻ|Ẽ|Ẹ|Ê|Ế|Ề|Ể|Ễ|Ệ',
            'I' => 'Í|Ì|Ỉ|Ĩ|Ị',
            'O' => 'Ó|Ò|Ỏ|Õ|Ọ|Ô|Ố|Ồ|Ổ|Ỗ|Ộ|Ơ|Ớ|Ờ|Ở|Ỡ|Ợ',
            'U' => 'Ú|Ù|Ủ|Ũ|Ụ|Ư|Ứ|Ừ|Ử|Ữ|Ự',
            'Y' => 'Ý|Ỳ|Ỷ|Ỹ|Ỵ',
        );

        foreach ($unicode as $nonUnicode => $uni) {
            $str = preg_replace("/($uni)/i", $nonUnicode, $str);
        }
        return $str;
    }
}
if (!function_exists('changeArrayKey')) {
    function changeArrayKey(array $arrData, string $inputKey)
    {
        $arrResult = [];
        foreach ($arrData as $value) {
            $key = $value[$inputKey];
            $arrResult[$key] = $value;
        }
        return $arrResult;
    }
}

if (!function_exists('convertObjToArray')) {
    function convertObjToArray($obj, &$arr)
    {

        if (!is_object($obj) && !is_array($obj)) {
            $arr = $obj;
            return $arr;
        }

        foreach ($obj as $key => $value) {
            if (!empty($value)) {
                $arr[$key] = array();
                convertObjToArray($value, $arr[$key]);
            } else {
                $arr[$key] = $value;
            }
        }
        return $arr;
    }
}

if (!function_exists('isJSON')) {
    function isJSON($string)
    {
        return is_string($string) && is_array(json_decode(
            $string,
            true
        )) && (json_last_error() == JSON_ERROR_NONE) ? true : false;
    }
}

if (!function_exists('slugify')) {
    function slugify($text, $delimiter = '-')
    {
        // replace non letter or digits by -
        $text = preg_replace('~[^\pL\d]+~u', $delimiter, $text);

        // transliterate
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

        // remove unwanted characters
        $text = preg_replace('~[^-\w]+~', '', $text);

        // trim
        $text = trim($text, $delimiter);

        // remove duplicate -
        $text = preg_replace('~-+~', $delimiter, $text);

        // lowercase
        $text = strtolower($text);

        return $text;
    }
}

if (!function_exists('sortArray')) {
    function sortArray($array, $option)
    {
        $totalItem = count($array);
        usort($array, function ($currentItem, $nextItem) use ($option, $totalItem) {
            $count = 1;
            /* Duyệt từng trường muốn order */
            foreach ($option as $key => $value) {
                /* Nếu không có trường muốn order trong array thì duyệt xuống trường muốn order tiếp theo */
                if (!isset($currentItem[$key]) || !isset($nextItem[$key])) {
                    continue;
                }
                $count++;
                /*
                    Nếu là số thì thì lấy giá trị hiện tại trừ giá trị kế tiếp.
                    Nếu là chữ thì dùng hàm strcmp để lấy kết quả
                 */
                if (is_numeric($currentItem[$key]) && is_numeric($nextItem[$key])) {
                    $resultCompare = $currentItem[$key] - $nextItem[$key];
                } else {
                    $resultCompare = strcmp(strtolower($currentItem[$key]), strtolower($nextItem[$key]));
                }

                /* Giá trị 2 phần tử bằng nhau thì duyệt tiếp xuống trường muốn order tiếp theo*/
                if (($resultCompare == 0) && ($count != $totalItem)) {
                    continue;
                }

                /* Nếu sắp xếp tăng dần thì giữ nguyên giá trị so sánh. Ngược lại trả về phủ định của nó */
                if ($value == SORT_BY_TYPE_ASC_ID) {
                    return $resultCompare;
                } else {
                    return 0 - $resultCompare;
                }
            }
        });
        return $array;
    }
}
