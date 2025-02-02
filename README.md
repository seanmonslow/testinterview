## Task

In the .env.example you'll see where I've stored the variables.

```
Either local or azure
STORAGE_DRIVER=
The basic url of the azure blob storage
BLOB_ENDPOINT=
The connection string for azure
CONNECTION_STRING=
```

Written a few functional tests where I mocked the storage service and the db calls.

Handled the changing environment between azure and local storage with the laravel service provider.

The front end is a basic form styled with tailwind and no js front-end framework.

I store the endpoint of the image uploaded into the DB with the height, width and when it was uploaded. There's a migration for that.

Can point the env variables to a local running db, call `php artisan migrate` and then `php artisan serve` and should run. Hopefully.