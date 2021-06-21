<?php

namespace App\Repositories\v1;

use Illuminate\Support\Facades\Crypt;

class UserRepository
{

    /**
     * @return array
     */
    public function testApi()
    {
        $dataJWT = [
            'user' => 'hauhuu',
            'attributes' => [
                'profile' => [
                    'username' => 'admin',
                    'password' => 'admin',
                ]
            ]
        ];

        //Set JWT
        $jwt = setDataFromJWT($dataJWT);
        $jwt = Crypt::encryptString($jwt);
        return [
            'jwt' => $jwt,
            'server_host' => $_SERVER['SERVER_ADDR'],
            'server_name' => $_SERVER['SERVER_NAME'],
            'server_port' => $_SERVER['SERVER_PORT'],
            'branch' => 'master',
        ];
    }
}
