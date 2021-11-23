<div align="center">
    <img src="https://www.packtpub.com/media/logo/stores/1/logo.png">
</div>

## Installation
  
1. `git clone -b master https://github.com/akhileshdarjee/packt.git`

2. `composer install`

3. `cp .env.example .env`

4. `php artisan key:generate`

5. Set '.env' variable -> ```PACKT_API_TOKEN="LjHaJEoAcYLbrjmswRuGWwcs2dKiizbqeFZ51jnw"```

6. `composer dumpautoload -o`
  
  
## Permissions
  
```
cd /path/to/your/laravel/directory
sudo chown -R $USER:www-data bootstrap/cache
sudo chmod -R ug+rw bootstrap/cache
sudo chown -R $USER:www-data storage/
sudo chmod -R ug+rw storage/
```
  
  
Now, you've completed the configuration step :v:

7. Serve it on your local server, `php artisan serve --port=8081`
  
8. Hit the URL: http://localhost:8081  
  
Enjoy...!!! :thumbsup: