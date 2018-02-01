##install

>$ composer install
>
>config .env
>
>$ php artisan passport:install
>
>$ php artisan passport:keys
>
>$ php artisan migrate
>


##nginx 配置

```
server {
    listen 80;
    server_name {host of this project};
set $htdocs  {path to dist after build};
root $htdocs;
  location /client-api/ {
        proxy_pass http://{host of docMakerClient }/;
        }

    location /api/ {
        proxy_pass http://{host of docMakerServer}/;
        }
    location / {
        index index.html index.php;
        try_files $uri $uri/ /index.php?$query_string;
    }
}

server {
    server_name {host of docMakerServer};
    root {path to docmakerServer/public }
    php ...
}

server {
        server_name {host of docMakerClient};
        root {path to docmakerClient/backend };
        php ...
}



