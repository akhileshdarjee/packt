<div align="center">
    <img src="/public/logo.png" width="135" height="44">
</div>

## Technologies

1. Laravel 8.x

2. PHP 8.0
  
  
## Installation

1. `sudo apt install php8.0-curl php8.0-mysql libapache2-mod-php8.0 php8.0-mbstring php8.0-xml php8.0-opcache php8.0-gd php8.0-zip`

2. `git clone -b master https://github.com/akhileshdarjee/packt.git`

3. `composer install`

4. `cp .env.example .env`

5. `php artisan key:generate`

6. Create database and set database credentials in '.env' file

7. Set Packt API token in '.env' -> ```PACKT_API_TOKEN="LjHaJEoAcYLbrjmswRuGWwcs2dKiizbqeFZ51jnw"```

8. `composer dumpautoload -o`

9. `php artisan migrate`
  
  
## Permissions
  
```
cd /path/to/your/laravel/directory
sudo chown -R $USER:www-data bootstrap/cache
sudo chmod -R ug+rw bootstrap/cache
sudo chown -R $USER:www-data storage/
sudo chmod -R ug+rw storage/
```
  
  
Now, you've completed the configuration step :v:

10. Serve it on your local server, `php artisan serve --port=8081`
  
11. Hit the URL: http://localhost:8081  
  
Enjoy...!!! :thumbsup:


## Resources

1. Website Theme (Aviato)
https://themefisher.com/products/aviato-e-commerce-template/

2. Favicons
https://realfavicongenerator.net/

3. Hero Slider Image (Pixabay)
https://pixabay.com/
