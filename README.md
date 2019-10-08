# Laravel Optimizely
A Laravel wrapper for the Optimizely SDK

## Installation

Add the package to your project:

`composer require leroy-merlin-br/laravel-optimizely`

Publish the configs:
`php artisan vendor:publish

Set the environment variables in your .env:
- `OPTIMIZELY_DISK`: Where the [Datafile](https://docs.developers.optimizely.com/full-stack/docs/get-the-datafile) is going to be stored (we recommend in-memory storage like Redis)

- `OPTIMIZELY_DATAFILE_FILEPATH`: The path where the Datafile is going to be stored, if you're using a key/value storage, this would be the key.

- `OPTIMIZELY_WEBHOOK_SECRET`: The secret token that Optimizely generates for your webhook, this is used to validate incoming request and asure that it is secure, the request is going to fail otherwise



## Contributing

## Versioning

## License
[MIT License](https://github.com/leroy-merlin-br/laravel-optimizely/blob/master/LICENSE)
