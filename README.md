# vtms_backend
## How to run
There is two way to run, using [Laravel Sail](#using-laravel-sail) or [XAMPP](#using-xampp). Follow the instructions accordingly.
### Using Laravel Sail 
Make sure [git](https://git-scm.com/), [Windows Subsystem for Linux](https://docs.microsoft.com/en-us/windows/wsl/install), and [Docker](https://www.docker.com/products/docker-desktop) is installed before starting.
#### 0. Clone the repository
```
git clone git@github.com:yasirsoleh/vtms_backend.git
```
#### 1. Make sure to be inside the directory
```
cd vtms-backend
```
#### 2. Composer install using docker to prepare vendor folder
```
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v $(pwd):/opt \
    -w /opt \
    laravelsail/php80-composer:latest \
    composer install --ignore-platform-reqs
```
#### 3. Prepare .env
```
cp .env.example .env
```
#### 4. Edit .env and change the variable as follows
```
DB_HOST=mariadb
DB_USERNAME=sail
DB_PASSWORD=password
```
#### 5. Run using sail detached
```
./vendor/bin/sail up -d
```
#### 6. Run node dependencies
```
./vendor/bin/sail npm install
./vendor/bin/sail npm run dev
```
#### 7. Run migration
```
./vendor/bin/sail artisan migrate:fresh
```
#### 8. Run database seeder
```
./vendor/bin/sail artisan db:seed
```
#### 9. Open browser and go to localhost
```
http://localhost
```

### Using XAMPP
Make sure [git](https://git-scm.com/), [XAMPP  (version 8 or above)](https://www.apachefriends.org/download.html), [Composer](https://getcomposer.org/download/), and [Node.js](https://nodejs.org/en/download/) is installed before starting.
#### 0. Clone the repository
```
git clone git@github.com:yasirsoleh/vtms_backend.git
```
#### 1. Make sure to be inside the directory
```
cd vtms-backend
```
#### 2. Composer install dependencies
```
composer install
```
#### 3. Install node dependencies
```
npm install
npm run dev
```
#### 4. Prepare .env
```
cp .env.example .env
```
#### 5. Create new database in phpmyadmin with the name
```
dcrsms
```
#### 6. Run migration
```
php artisan migrate:fresh
```
#### 7. Run database seeder
```
php artisan db:seed
```
#### 8. Serve the app
```
php artisan serve
```
#### 9. Open browser and go to localhost
```
http://localhost:8000
```
