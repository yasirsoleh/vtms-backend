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
cd vtms_backend
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
./vendor/bin/sail artisan migrate:fresh --seed
```
#### 8. Open browser and go to localhost
```
http://localhost
```
