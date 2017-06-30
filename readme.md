# My personal website

## Built with [Laravel](https://laravel.com) and [Vue.js](https://vuejs.org)

### Requirements

    PHP >= 5.6.4
    PHP Zip extension
    PHP GD
    Redis Server
    Node.js >= 7.4.0
    npm >= 4.1.2

### Installation

1- Install dependencies

```bash
composer install
```

```bash
npm install
```

2 - Compile assets

```bash
npm run prod
```

3 - generate key

```bash
php artisan key:generate
```

4 - Set up database and .env settings. Then migrate:

```bash
php artisan migrate
```

Now, either seed default credentials and change them later on, at the backend,
or change them directly on the seeder, before running it. Remember
to remove credentials before you publish anything.

Register on the frontend, as well.
Use the same backend email to have admin admin privilleges.

Default Credentials for first admin (backend) are:

    name: admin
    email: admin@example.com
    password: secret

5 - Run seeder

```bash
php artisan db:seed
```


### Notes

This is a (somehow permanent) work in progress! Sudden/heavy changes can occurr without notice.

Just like any other open-source software, this one is provided "as is". Use at your own risk.

### License

Open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT)
