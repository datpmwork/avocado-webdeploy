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

Create a supervisor config file with content as below
```
[program:webdeploy]
process_name=%(program_name)s_%(process_num)02d
command=php [Your Actual Website Directory]/artisan queue:work --sleep=3 --tries=3 --daemon
autostart=true
autorestart=true
user=root
numprocs=2
redirect_stderr=true
stdout_logfile=[Your Actual Website Directory]/storage/logs/worker.log

[program:webdeploy_broadcaster]
process_name=%(program_name)s_%(process_num)02d
command=node [Your Actual Website Directory]/socket.js
autostart=true
autorestart=true
user=root
numprocs=1
redirect_stderr=true
stdout_logfile=[Your Actual Website Directory]/storage/logs/socket.log

```