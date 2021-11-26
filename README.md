<div align="center">
    <img src="/public/logo.png" width="135" height="44">
</div>

## Installation
  
1. `git clone -b master https://github.com/akhileshdarjee/packt.git`

2. `composer install`

3. `cp .env.example .env`

4. `php artisan key:generate`

5. Create database and set database credentials in '.env' file

6. Set Packt API token in '.env' -> ```PACKT_API_TOKEN="LjHaJEoAcYLbrjmswRuGWwcs2dKiizbqeFZ51jnw"```

7. `composer dumpautoload -o`

8. `php artisan migrate`
  
  
## Permissions
  
```
cd /path/to/your/laravel/directory
sudo chown -R $USER:www-data bootstrap/cache
sudo chmod -R ug+rw bootstrap/cache
sudo chown -R $USER:www-data storage/
sudo chmod -R ug+rw storage/
```
  
  
Now, you've completed the configuration step :v:

9. Serve it on your local server, `php artisan serve --port=8081`
  
10. Hit the URL: http://localhost:8081  
  
Enjoy...!!! :thumbsup:


## Resources:

1. Website Theme (Aviato)
https://themefisher.com/products/aviato-e-commerce-template/

2. Favicons
https://realfavicongenerator.net/

3. Hero Slider Image (Pixabay)
https://pixabay.com/
