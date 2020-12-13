## Install
`composer install`

## Migration'
`php artisan migrate`

## Install passport
`php artisan passport:install`

## Start server
`php artisan serve`

## Register user
- Method `POST`
- URL `/api/register`
- `name, email, password, password_confirmation`

## Login user
- Method `POST`
- URL `/api/login`
- `email, password`

## Create product
- Method `POST`
- URL `/api/products`
- `name, price`

## Unit test
`./vendor/bin/phpunit tests/Feature/Http/Controllers/ProductTest.php`
