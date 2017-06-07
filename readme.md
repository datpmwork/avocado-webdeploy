<p align="center">
    Avocado - Website Deployment
    <img src="https://ivivi.vn/storage/uploads/2017/06/04/151007.PNG"/>
</p>

# How to install

#### Install vendors
```composer install```

```npm install```

Note: Please change some .env variables after installing as below

APP_IP=[YOUR ACTUAL IP ADDRESS]

BROADCAST_DRIVER=redis

#### Generate App Key
```php artisan key:generate```

#### Migration
```php artisan migrate```

#### Install Supervisor
Follow [this instruction](https://gist.github.com/danharper/9136507) to install supervisor 

Note: Please run supervisor under root user for all privileges