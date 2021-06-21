<?php

namespace App\Repositories\v1;

use Illuminate\Support\Facades\Crypt;
use Faker;

class UserRepository
{
    /**
     * @param $username
     * @return string
     */
    public function register($username)
    {
        $faker = Faker\Factory::create();
        $data = [
            'user' => $username,
            'attributes' => [
                'profile' => [
                    'firstname' => $faker->firstName,
                    'lastname' => $faker->lastName,
                ]
            ]
        ];

        //Set JWT
        $jwt = setDataFromJWT($data);
        return Crypt::encryptString($jwt);
    }
}
