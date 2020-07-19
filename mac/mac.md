## 环境变量

```
/etc/profile 
/etc/paths 
~/.bash_profile 
~/.bash_login 
~/.profile 
~/.bashrc 
```




## php-fpm

* 启动
> sudo php-fpm |
> sudo pkill php-fpm

* php-fpm.log
> /usr/local/var/log/php-fpm.log

* php-fpm.conf
> /private/etc/php-fpm.conf
> /etc/php-fpm.d/*.conf
>
> /private/etc/php.ini.default

## NGINX

```
启动NGINX
`brew services start nginx`
停止运行NGINX
`brew services stop nginx`
重新加载NGINX
`nginx -s reload`
停止nginx
`nginx -s stop`
nginx.conf
```



```
/usr/local/etc/nginx/nginx.conf
/usr/local/etc/nginx/servers/*
/usr/local/Cellar/nginx/1.17.0/bin/nginx/*.conf
```

## 磁盘操作
```
df -h #显示目前在Linux系统上的文件系统的磁盘使用情况统计
diskutil list #查看设备列表
sudo dd bs=4m if=2018-11-13-raspbian-stretch-lite.img of=/dev/rdisk3 #将镜像写入磁盘
diskutil unmount /dev/disk3s1 #分区卸载
diskutil unmoutDisk /dev/disk2 #卸载设备
```

