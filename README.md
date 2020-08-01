# DcrPHP/Config配置类

## 1、安装
　　composer install dcrphp/config

## 2、初始化
```
　　$clsConfig = new Config();  
　　$clsConfig->addDirectory(__DIR__ . '\config');  
　　//$clsConfig->addFile(__DIR__ . '\config\app.php'); 
　　//$clsConfig = new Config(__DIR__ . '\config'); 
　　//$clsConfig = new Config(__DIR__ . '\config\app.php'); 
　　$clsConfig->setDriver('php');//解析php格式的  
　　$clsConfig->set('my', array('email'=>'junqing124@126.com'));
　　$clsConfig->init();
```  

## 3、使用
```
　　$clsConfig->get(); //获取全部   
　　$clsConfig->get('app.debug'); //获取app文件下的debug配置  
　　$clsConfig->get('my.email'); //获取自定义配置
```    

## 4、扩展
　　请在src/Driver/目录下以Php.php为例加类，比如想加实别ini，添加Ini.php,调用时setDriver('ini')即可

## 5、说明
　　配置读取的以文件名为item名，比如app.php里的配置会读取成:  
```
　　$config['app']['default_timezone'] = 'PRC';
　　$config['app']['debug'] = 1;
``` 
