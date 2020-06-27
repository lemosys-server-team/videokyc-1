## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling.

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Installation

After checkout this reposatory you need to follow below steps:

- Run below command to install all required dependencies
``` bash
composer install
```

- After installed dependencies, you need to run below commands to copy .env.example to .env and generate APP_KEY on root: 
``` bash
php -r "file_exists('.env') || copy('.env.example', '.env');"

php artisan key:generate
```

- Edit the .env file to change the attributes for appplication name, appplication url and database to your database configurations (host,username,password etc)
``` bash
APP_NAME="Application Name"
APP_URL="http://example.com"
.
.
.
DB_DATABASE=database_name
DB_USERNAME=database_username
DB_PASSWORD=database_password
```

- After all settings you need to run below command to create database tables into database, before run this command you have created database with name that you set in last step: 
``` bash
php artisan migrate
```

- Now you need to run below command to insert default data into database: 
``` bash
php artisan db:seed
```
This command will create 1 admin and 3 users with default password is "password". Use database inserted email and password for login to User and Admin section.

- To create the symbolic link, you may use the storage:link Artisan command:
``` bash
php artisan storage:link
```

- All done!! Now open below link to access User and Admin section
``` bash
User: http://example.com/public/
Admin: http://example.com/public/admin/login
```
- If you want to use APIs you need to create Passport client for this you need to run below command:
``` bash
php artisan passport:install
```