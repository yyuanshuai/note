## 安装doker

```shell
sudo yum remove docker \
docker-client \
docker-client-latest \
docker-common \
docker-latest \
docker-latest-logrotate \
docker-logrotate \
docker-engine
#安装必须的依赖
sudo yum install -y yum-utils device-mapper-persistent-data lvm2
#设置 docker repo 的 yum 位置
sudo yum-config-manager --add-repo https://download.docker.com/linux/centos/docker-ce.repo
#安装 docker，以及 docker-cli
sudo yum install docker-ce docker-ce-cli containerd.io
systemctl start docker
sudo systemctl enable docker#设置 docker 开机自启
#https://hub.docker.com
#测试 docker 常用命令，注意切换到 root 用户下
#https://docs.docker.com/engine/reference/commandline/docker/

#配置 docker 镜像加速
sudo mkdir -p /etc/docker
sudo tee /etc/docker/daemon.json <<-'EOF'
{
  "registry-mirrors": ["https://yprtfvq7.mirror.aliyuncs.com"]
}
EOF
sudo systemctl daemon-reload
sudo systemctl restart docker



sudo groupadd docker  # 创建docker用户组
sudo gpasswd -a $USER docker  # 把当前用户加入到docker用户组>>sudo usermod -aG docker your-user
newgrp docker   # 更新当前用户组变动（就不用退出并重新登录了）
```





```shell
docker info
docker version 
docker search -s version `image_name`//docker search centos 
docker pull `image_name`:'version'//docker pull centos 
docker images| -a -q | 
docker run --rm --restart=always -it -p 80:80 --name nginx01 -v ~/docker/nginx/nginx.conf:/etc/nginx/nginx.conf nginx:latest /bin/bash
##--rm 容器退出时自动移除容器
##--restart=always 不管退出状态码是什么始终重启容器。当指定always时，docker daemon将无限次数地重启容器。容器也会在daemon启动时尝试重启，不管容器当时的状态如何。//docker container update --restart=always 容器名字
##-d 容器启动后在后台运行
##-it 
```



```shell
#常用容器操作
docker ps |-a | -q
docker start `container_id`
docker stop `container_id`
docker restart `container_id`
docker rm `container_id`
docker rmi `image_id
docker attach `container_id`#进入后台运行的容器
docker rmi -f $(docker images)#强制删除所有镜像：
docker rm -f $(docker ps -a -q)#强制删除所有容器
*docker update redis --restart=always#将Reids容器更新为总是启动

#打包镜像 -t 表示指定镜像仓库名称/镜像名称:镜像标签 .表示使用当前目录下的Dockerfile文件
docker build -t mall/mall-admin:1.0-SNAPSHOT .
#执行容器内部命令
docker exec -it $container_id /bin/bash 
# 使用root账号进入容器内部
docker exec -it --user root $ContainerName /bin/bash
#强制停止容器
docker kill $ContainerName
#修改容器的启动方式
docker container update --restart=always $ContainerName
#指定容器网络
docker run -p 80:80 --name nginx \
--network my-bridge-network \
-d nginx:1.17.0
#指定容器时区
docker run -p 80:80 --name nginx \
-e TZ="Asia/Shanghai" \
-d nginx:1.17.0
#同步宿主机时间到容器
docker cp /etc/localtime $ContainerName:/etc/
```



```shell
#查看信息
#查看容器产生的全部日志
docker logs $ContainerName
#动态查看容器产生的日志
docker logs -f $ContainerName
#查看容器内部进程
docker top `container_id`
#获取容器/镜像的元数据。
docker inspect [OPTIONS] NAME|ID [NAME|ID...] 
#查看容器内部IP地址
docker inspect --format '{{ .NetworkSettings.IPAddress }}' <container-ID> 
#查询出容器的pid
docker inspect --format "{{.State.Pid}}" $ContainerName
#查看指定容器资源占用状况，比如cpu、内存、网络、io状态：
docker stats $ContainerName
#查看所有容器资源占用情况：
docker stats -a
#查看所有网络
docker network ls
#查看Docker镜像的存放位置：
docker info | grep "Docker Root Dir"

```



### docker build 命令用于使用 Dockerfile 创建镜像。

docker build [OPTIONS] PATH | URL | -
### docker logs 查看容器内部日志

docker logs -t -f --tail `container_id`


____________________

### 删除镜像

docker image rm $(docker image ls -a -q)
### 删除数据卷：

docker volume rm $(docker volume ls -q)
### 删除 network：
docker network rm $(docker network ls -q)
### docker build 构建镜像

docker build [选项] <上下文路径/URL/->| -t -f(指定dockerfile文件位置)| 
### docker port 列出指定的容器的端口映射，或者查找将PRIVATE_PORT NAT到面向公众的端口。

docker port [OPTIONS] CONTAINER [PRIVATE_PORT[/PROTO]]
###  docker kill 杀掉运行中的容器mynginx -s :向容器发送一个信号
docker kill -s KILL mynginx
### 暂停容器中所有的进程。 恢复容器中所有的进程。
docker pause [OPTIONS] CONTAINER [CONTAINER...]
docker unpause [OPTIONS] CONTAINER [CONTAINER...]
### docker create : 创建一个新的容器但不启动它
docker create [OPTIONS] IMAGE [COMMAND] [ARG...]

### 用于容器与主机之间的数据拷贝。

* docker cp [OPTIONS] CONTAINER:SRC_PATH DEST_PATH|-
* docker cp [OPTIONS] SRC_PATH|- CONTAINER:DEST_PATH
* docker cp /dir 96f7f14e99ab:/www/
### docker diff : 检查容器里文件结构的更改。
docker diff [OPTIONS] CONTAINER

## 镜像仓库

```
docker login : 登陆到一个Docker镜像仓库，如果未指定镜像仓库地址，默认为官方仓库 Docker Hub
docker logout : 登出一个Docker镜像仓库，如果未指定镜像仓库地址，默认为官方仓库 Docker Hub
docker login -u 用户名 -p 密码

docker pull : 从镜像仓库中拉取或者更新指定镜像
docker pull java

docker push : 将本地的镜像上传到镜像仓库,要先登陆到镜像仓库
docker push myapache:v1

docker search : 从Docker Hub查找镜像
docker search -s 10 java
```




### **后台运行容器**

```
ctrl + p + q
```

### 最直接并全面清理的的就是以下命令

```
$docker stop $(docker ps -a -q) && docker system prune --all --force
```



## 安装常用软件



### mysql

```
docker pull mysql:5.7

docker run -p 3306:3306 \
--restart=always \
--name mysql \
-e MYSQL_ROOT_PASSWORD=root \
-v ~/docker/docker-volume/mysql/log:/var/log/mysql \
-v ~/docker/docker-volume/mysql/data:/var/lib/mysql \
-v ~/docker/docker-volume/mysql/conf:/etc/mysql \
-d mysql:5.7 mysqld --character-set-server=utf8mb4 --collation-server=utf8mb4_unicode_ci

​```windows
mkdir -p D:/mydocker/docker-volume/mysql/data D:/mydocker/docker-volume/mysql/conf D:/mydocker/docker-volume/mysql/log

docker run -d -p 3306:3306 --name mysql -e MYSQL_ROOT_PASSWORD=root -v D:/mydocker/docker-volume/mysql/data:/var/lib/mysql -v D:/mydocker/docker-volume/mysql/conf:/etc/mysql -v D:/mydocker/docker-volume/mysql/log:/var/log/mysql mysql:5.7 mysqld --character-set-server=utf8mb4 --collation-server=utf8mb4_unicode_ci
​```
```

```
添加MySQL配置
在宿主机映射数据卷位置新建文件my.cnf
vi /mydata/mysql/conf/my.cnf
----拷入my.cnf
[client]
default-character-set=utf8
[mysql]
default-character-set=utf8
[mysqld]
init_connect='SET collation_connection = utf8_unicode_ci' init_connect='SET NAMES utf8' character-set-server=utf8
collation-server=utf8_unicode_ci
skip-character-set-client-handshake
skip-name-resolve
----
注意：解决 MySQL 连接慢的问题
在配置文件中加入如下，并重启 mysql
[mysqld]
skip-name-resolve
解释：
skip-name-resolve：跳过域名解析
```

### Redis

```
docker pull redis
mkdir -p /mydata/redis/conf
touch /mydata/redis/conf/redis.conf

docker run -p 6379:6379 --name redis \
--restart=always \
-v /mydata/redis/data:/data \
-v /mydata/redis/conf/redis.conf:/etc/redis/redis.conf \
-d redis redis-server /etc/redis/redis.conf

vi /mydata/redis/conf/redis.conf
---添加aof持久化配置,其余配置参考下面
appendonly yes
---

#redis4.0 自描述文件：
#https://raw.githubusercontent.com/antirez/redis/4.0/redis.conf

​```备用
docker run -p 6379:6379 --name redis -v ~/docker/docker-volume/redis/data:/data \
-v ~/docker/docker-volume/redis/conf/redis.conf:/etc/redis/redis.conf \
-d redis redis-server /etc/redis/redis.conf
​```
#使用 redis 镜像执行 redis-cli 命令连接
docker exec -it redis redis-cli
```



### nginx

```shell
随便启动一个 nginx 实例，只是为了复制出配置
docker run -p 80:80 --name nginx -d nginx:1.10
将容器内的配置文件拷贝到当前目录：docker container cp nginx:/etc/nginx .  别忘了后面的点
修改文件名称：mv nginx conf 把这个 conf 移动到/mydata/nginx 下
终止原容器：docker stop nginx
执行命令删除原容器：docker rm $ContainerId
创建新的 nginx；执行以下命令
docker run -p 80:80 --name nginx \
-v /mydata/nginx/html:/usr/share/nginx/html \
-v /mydata/nginx/logs:/var/log/nginx \
-v /mydata/nginx/conf:/etc/nginx \
-d nginx:1.10
docker run -p 80:80 --name nginx \
-v /home/vagrant/docker/docker-volume/nginx/html:/usr/share/nginx/html \
-v /home/vagrant/docker/docker-volume/nginx/logs:/var/log/nginx \
-v /home/vagrant/docker/docker-volume/nginx/conf:/etc/nginx \
-d nginx:1.10
\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
##mkdir -p ~/docker/docker-volume/nginx/conf.d  ~/docker/docker-volume/nginx/html ~/docker/docker-volume/nginx/log
##将nginx.conf复制到~/docker/docker-volume/nginx
##注意 : 容器内的/etc/nginx/fastcgi_params和宿主机的/usr/local/etc/nginx/fastcgi.conf||fastcgi_params
docker run -it -p 80:80 --name nginx -v ~/docker/docker-volume/nginx/nginx.conf:/etc/nginx/nginx.conf -v ~/docker/docker-volume/nginx/conf.d:/etc/nginx/conf.d -v ~/docker/docker-volume/nginx/html:/usr/share/nginx/html -v ~/docker/docker-volume/nginx/log:/var/log/nginx nginx:latest /bin/bash
```



### RabbitMQ

```
docker run --name rabbitmq -d -p 15672:15672 -p 5672:5672 rabbitmq:3.8-management
#-p:指定容器内部端口号与宿主机之间的映射，rabbitMq默认要使用15672为其web端界面访问时端口，5672为数据通信端口
#-management:表示web有管理界面插件
```

### ElasticSearch&&Kibana

```shell
docker pull elasicsearch:7.4.2
docker pull kibana:7.4.2

mkdir -p /mydata/elasticsearch/config
mkdir -p /mydata/elasticsearch/data
echo "http.host: 0.0.0.0" >> /mydata/elasticsearch/config/elasticsearch.yml#
chmod -R 777 /mydata/elasticsearch/#保证权限
docker run --name elasticsearch -p 9200:9200 -p 9300:9300 \
--restart=always \
-e "discovery.type=single-node" \
-e ES_JAVA_OPTS="-Xms64m -Xmx512m" \
-v /mydata/elasticsearch/config/elasticsearch.yml:/usr/share/elasticsearch/config/elasticsearch.yml \
-v /mydata/elasticsearch/data:/usr/share/elasticsearch/data \
-v /mydata/elasticsearch/plugins:/usr/share/elasticsearch/plugins \
-d elasticsearch:7.4.2
#以后再外面装好插件重启即可；
#特别注意：
#-e ES_JAVA_OPTS="-Xms64m -Xmx256m" \ 测试环境下，设置 ES 的初始内存和最大内存，否则导致过大启动不了 ES

docker run --name kibana -e ELASTICSEARCH_HOSTS=http://192.168.56.10:9200 -p 5601:5601 \
-d kibana:7.4.2
#http://192.168.56.10:9200 一定改为自己虚拟机的地址

#安装ik分词
#下载对应版本https://github.com/medcl/elasticsearch-analysis-ik/releases
#解压到新建ik文件夹， 将ik文件夹通过ftp传入elasticsearch容器plugins目录中
#确认是否安装好了分词器： 进入elasticsearch容器bin，执行elasticsearch-plugin list

/////////////////////////////////////////////////////////////////////////////
docker run -d --rm --name elasticsearch -p 9200:9200 -p9300:9300 -e "discovery.type=single-node" -e "cluster.name=docker-cluster" elasticsearch:6.5.0 

cd /usr/share/elasticsearch/plugins/

elasticsearch-plugin install https://github.com/medcl/elasticsearch-analysis-ik/releases/download/v7.2.0/elasticsearch-analysis-ik-7.2.0.zip

docker restart elasticsearch 

docker run -d --name kibana --link=elasticsearch:test  -p 5601:5601 kibana:7.2.0

/usr/share/elasticsearch/config/elasticsearch.yml#添加以下两行,解决跨域
http.cors.enabled: true 
http.cors.allow-origin: "*"
```

### Mongo

```
docker run -d --rm --name mongo -p 27017:27017 mongo:3.6 --auth
* --auth:需要密码才能访问容器服务
```


### jenkins

```
  docker pull jenkins/jenkins:lts
  
  docker run -p 8080:8080 -p 50000:5000 --name jenkins \
  -u root \
  -v /mydata/jenkins_home:/var/jenkins_home \
  -d jenkins/jenkins:lts
```