
How to run the project:

1.Install Docker

2.Install Docker Compose

3.Clone the repository

4.Open project folder in terminal

5.Run docker-compose up -d        (or sudo docker-compose up -d)

6.Run docker-compose exec app sh 
    This will open the shell prompt inside the 'app' container.
    All php artisan commands must be executed inside the container. To exit press ctrl+p followed by ctrl+q.
    Alternatively you may execute the commands without entering the container
    by preceding your commands with docker-compose exec   app (ex: docker-compose exec app php artisan key:generate)

7. Run composer install (inside the container or preceded by docker-compose exec app)

8. copy the .env.example and/or change its name to .env

9. run php artisan key:generate

10. change DB credentials in the .env file 
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=33060
    DB_DATABASE=testapi
    DB_USERNAME=testapi
    DB_PASSWORD=testapi

11.If you get an error similar to this when opening localhost:8080
    The stream or file "/var/www/storage/logs/laravel-2019-09-12.log" could 
    not be opened: failed to open stream: Permission    denied
    run chmod -R 777 *
    
12. Run php artisan migrate 

13. Run php artisan db:seed

14. Use api_token "qwertyqwerty" to acces protected routes

15. Use Postman or similar software to test. 

(ex: localhost:8080/api/categories)






