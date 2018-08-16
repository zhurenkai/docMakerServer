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


>去数据库表oauth_client 找到password_client为2的那一条id和secret便是  [docMakerClient](https://github.com/zhurenkai/docMakerClient) 中需要的配置


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

>可以导入本地数据库字段的注释，可以直接根据自己看注释匹配返回字段说明