## 环境变量

1. /etc/profile 
1. /etc/paths 
1. ~/.bash_profile 
1. ~/.bash_login 
1. ~/.profile 
1. ~/.bashrc 


## php-fpm

* 启动
> sudo php-fpm |
> sudo pkill php-fpm

* php-fpm.log
> /usr/local/var/log/php-fpm.log

* php-fpm.conf
> /private/etc/php-fpm.conf
> /etc/php-fpm.d/*.conf

## NGINX
* 启动NGINX
> `brew services start nginx`
* 停止运行NGINX
> `brew services stop nginx`
* 重新加载NGINX
> `nginx -s reload`
* 停止nginx
> `nginx -s stop`

* nginx.conf
```
/usr/local/etc/nginx/nginx.conf
/usr/local/etc/nginx/servers/*
/usr/local/Cellar/nginx/1.17.0/bin/nginx/*.conf
```