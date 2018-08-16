[demo-server](http://docmaker-server.randqun.com/)

[demo-client](http://docmaker.randqun.com/)

###安装服务端

>$ composer install
>
>config .env
>
>php artisan key:generate
>
>$ php artisan migrate
>
>$ php artisan passport:install
>
>$ chmod -R 777 bootstrap/cache/ storage/


>去数据库找到oauth_client 找到password_client 为1的那条(第二条)，复制secret 和id 
粘贴到vue中docMakerClient/config/index.js 中。（详情查看laravel官方文档passport）
>

####发起一次请求
>进入工作界面,创建项目模块，接口

>选择method,host,uri ,填写请求参数（支持formdata和json），填写header 选择send
>不出意外会返回你想要的结果，目前支持json格式化


>确认接口正常返回之后，可以选择保存，会保存所选择的内容（可选）
>点击查看文档


>选择生成，此时会生成文档的半成品，如果有些字段你曾经使用过，可能会自动填充上说明
>选择保存文档，此时会记录你填写的字段

>在弹出框底部可选菜单选择markdown，进入markdown文档编辑界面，编辑完成后点击保存


>登录后台即可以看见文档(服务端)

####设置

>进入设置界面可以为每个项目设置hosts，在此后的使用中可以在host的下拉菜单中选择


####其他
>进入docMakerClient中的backend文件夹配置config中数据库链接参数，服务端的域名，修改Authorization(在查看network中的headers)，这一步最麻烦但是非常重要
>执行 php -f import.php 
>可以将mysql数据库中对字段的注释导入，可以更快的进入使用的状态