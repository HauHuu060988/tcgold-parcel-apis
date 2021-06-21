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
