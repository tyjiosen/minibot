# minibot



基于QQMini机器HTTPAPI插件的一个sdk



#### 环境要求

> - PHP >= 7.3
> - PHP cURL扩展

#### 安装

```code 
composer require tyjiosen/minibot dev-master
```

#### 调用方法

```php
$config = [

	'url' => 'http://localhost:53392', //HTTP服务地址
	'password' => '123456' // 访问密码 不设置可不传
];

$main = new Tyjiosen\Minibot\Main($config);

//请按接口文档来调用接口 https://mnhttp2-qqbot.doc.coding.io/
//下面是调用示范 调用的方法名就是接口路径 结果是插件返回的 
$main->log(['msg'=>'打印日志测试']); //打印日志
$main->get_friend_info(['bot'=>'机器qq','qq'=>'好友qq']); //取好友信息
$main->get_friend_list(['bot'=>'机器qq']); //取好友列表



```





