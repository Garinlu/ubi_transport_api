# UBI TRANSPORT API

Symfony 5.2

## Getting started

### Environment

* PHP 7.2.5 or higher
* Composer 1.8 or higher
* Symfony 5.2

### Installation

To use this API, you must clone this repository

```shell
git clone https://github.com/Garinlu/ubi_transport_api.git
```

Then, go in the project folder, and install all packages :

```shell
composer install
```

Then, Init database with script :

```shell
composer initDb
```

To finish, configure your database authentication informations :

* For main database: create a file .env.local in set `DATABASE_URL` variable
* For testing database: create a file .env.test.local in set `DATABASE_URL` variable

### Serving your application

There is an example for Nginx :

```
server {
	listen 8005;
    root /PATH/TO/FILES/public;
	client_max_body_size 50M;

    location / {
		try_files $uri /index.php$is_args$args;
    }

    location ~ ^/index\.php(/|$) {
		fastcgi_buffers 16 16k;
		fastcgi_buffer_size 32k;
        fastcgi_pass unix:/run/php/php7.4-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        fastcgi_param DOCUMENT_ROOT $realpath_root;
        include fastcgi_params;
	}
}
```

Or you can use the Dev server : `symfony server:start`

## Test

To run Test, run :

```shell
composer test
```

## Routes

* PUT `/student`
  Add a student

* POST `/student/{id}`
  Edit a student

* DELETE `/student/{id}`
  Remove a student

* PUT `/grade/student/{id}`
  Add a grade to a student

* GET `/grade/student/{id}/average`
  Get grades average of a student

* GET `/grade/class/average`
  Get grades average

## Cors

The default configuration of this API allow only request from localhost. For allowing another domain as request source,
please update nelmio configuration.
