##install

>$ composer install
>
>config .env
>
>$ php artisan migrate
>
>$ php artisan passport:install
>
>$ chmod -R 777 bootstrap/cache/ storage/


>去数据库找到oauth_client 找到password_client 为1的那条(第二条)，复制secret 和id 
粘贴到vue中docMakerClient/config/index.js 中。（详情查看laravel官方文档passport）
>
>进入laravel根url，选择register 注册用户
>在vue中登录即可使用



##nginx 配置

```
server {
    listen 80;
    server_name {host of this project};
set $htdocs  {path to dist after build};
root $htdocs;
  location /client-api/ {
        proxy_pass http://{host of docMakerClient/backend }/;
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



