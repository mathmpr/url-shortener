# Requirements

- PHP >= 7.1.3
- BCMath PHP Extension
- Ctype PHP Extension
- JSON PHP Extension
- Mbstring PHP Extension
- OpenSSL PHP Extension
- PDO PHP Extension
- Tokenizer PHP Extension
- XML PHP Extension
- Composer

---

if you use Windows you can use link below to help with a download and install the environment.

- You can download PHP from <a href="https://windows.php.net/downloads/releases/php-8.1.1-nts-Win32-vs16-x64.zip">here</a>
- Another required extensions from <a href="https://pecl.php.net">here</a>
- Composer can be downloaded from <a href="https://getcomposer.org/download/">here</a>
- You can optionally download Postman <a href="https://dl.pstmn.io/download/latest/win64">here</a>

## Install on Windows

1. download PHP from the above link and extract in your preferred location.
2. open `preferred location/php.ini` and search `;extension=fileinfo` and `;extension=pdo_sqlite` and change remove `;` and save file.
3. add `preferred location` to `PATH` in environment variables.
4. in root of project find `./.env.backup` and copy to `./.env`
5. install Composer and set `preferred location/php.exe` location during installation.
6. open a command line in project root and run `php composer install`
7. run `php artisan migrate`
8. run `php artisan passport:install` (I install passport for future auth api calls)

## Start server

1. run `php artisan serve`

## How to create a shortened URL

If you use Postman, you can use file in root of project called `url-shortrner.postman_collection` for create a base Postman collection for use API.

Open Postman or similar and make a POST request to `http://localhost:8000/api/url` with raw body in json format like:
```json
{
    "origin" : "https://laravel.com/docs/8.x/installation"
}
```
Same with cURL
```
curl -XPOST -H "Content-type: application/json" -d '{"origin":"https://laravel.com/docs/8.x/installation"}' 'http://localhost:8000/api/url'
```
In this case the expected response is `http://localhost:8000/xpto12df`. The code `xpto12df` is an example because this code is totally randomly.

You can optionally pass another parameter called `target` that force the code on end of URL.
```json
{
    "origin" : "https://laravel.com/docs/8.x/installation",
    "target" : "install-laravel"
}
```
Same with cURL
```
curl -XPOST -H "Content-type: application/json" -d '{"origin":"https://laravel.com/docs/8.x/installation","target":"install-laravel"}' 'http://localhost:8000/api/url'
```
In this case the expected response is `http://localhost:8000/install-laravel`.

- If you created a URL from a source and try to create it again from the same source, the same shortened URL as previously is returned.
- If a URL has already been shortened with a specific target, the expected return is an error saying that the target is unavailable because it is already in use.
- With each request made to the server, a command is given to delete all shortened URLs that are more than seven days old.

# About structure

## Database

I choose `sqlite` because it is a simple database file based and this makes installation and testing much simpler. I change `./bootstrap/app.php` to create the database if file `./database.sqlite` do not exist in root of project.

## MVC

MVC offered by Laravel itself
