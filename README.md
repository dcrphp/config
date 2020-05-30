# DcrPHP/Config配置类

## 1、安装
　　composer install dcrphp/confog

## 2、初始化
```
　　$clsConfig = new Config();  
　　$clsConfig->addDirectory(__DIR__ . '\config');  
　　//$clsConfig->addFile(__DIR__ . '\config\app.php');  
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
