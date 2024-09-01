## Environment

Running this application can occur in one of a few ways:
 - [Laravel Herd](https://herd.laravel.com)
   - This is my preferred method for running Laravel sites locally as everything is provided out of the box
 - An existing Docker environment providing MySQL/MariaDB, PHP and Nginx or Apache.
 - PHPs built in webserver using `php artisan serve`
   - This requires PHP and Composer to be installed in the local environment. This should not be used for production.

## Set Up

To run this application locally, the repository should be cloned to an appropriate folder for environment, and the following commands should be run:  
`composer install`  
`npm install`  
`cp .env.example .env`  
`php artisan key:generate`  

At this point, you should configure your database, and other appropriate environment variables in the .env files as necessary  
`php artisan migrate`  
`php artisan app:get-pinboard-data`  

`npm run dev`  
This will trigger the build or the Vue frontend with Vite and will provide you a network address to access the application
