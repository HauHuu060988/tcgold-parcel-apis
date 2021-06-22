## Prerequisite

- PHP > 7.0
- Postgres
> Refer to file .env.development
 
## Installation

- Clone code from github
- Run CLI: cp .env.development .env
- Run CLI: ./composer.phar install
- Run CLI: ./composer.phar dump-autoload
- Run CLI: php artisan migrate --force

## Unit Test

- Run CLI: ./vendor/bin/phpunit

## License
This project is licensed under the MIT License.

## Apis

**Register to get jwt**

     URL: /api/v1/register  
     Method: POST
     Payload: { username: $username}

**Create Parcel**

     URL: /api/v1/parcel  
     Method: POST
     Authorization: Bearer {jwt}
     Payload: { 'name' => $name, 'weight' => $weight, 'volume' => $volume, 'value' => $value, 'model' => $model }
     
**Get Parcel**

     URL: /api/v1/parcel/{id} 
     Method: GET
     Authorization: Bearer {jwt}

**Update Parcel**

     URL: /api/v1/parcel/{id}
     Method: PUT
     Authorization: Bearer {jwt}
     Payload: { 'name' => $name, 'weight' => $weight, 'volume' => $volume, 'value' => $value, 'model' => $model }
  
**Calculate Parcels**

     URL: /api/v1/parcels?parcelIds=<id>,<id>,<id>  
     Method: GET
     Authorization: Bearer {jwt}

**Delete Parcel**

     URL: /api/v1/parcel/{id}
     Method: DELETE
     Authorization: Bearer {jwt}
