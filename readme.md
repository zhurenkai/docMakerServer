### demo
[server](http://docmaker-server.randqun.com/)

[client](http://docmaker.randqun.com/)

#### 安装

```
composer install
```
编辑配置文件
```
php artisan key:generate

php artisan migrate

php artisan passport:install

chmod -R 777 bootstrap/cache/ storage/
```

> 去数据库表oauth_client 找到password_client为2的那一条id和secret便是  [docMakerClient](https://github.com/zhurenkai/docMakerClient) 中需要的配置
