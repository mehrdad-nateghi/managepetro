# Docker compose and laravel
```
1. docker build -t docker-compose-laravel .
2. docker run --rm -p 80:80 -v D:/Projects/docker-compose-laravel/src:/var/www/html/public laravel-nginx
3. http://localhost/
```