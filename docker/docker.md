## 安装doker

```
sudo groupadd docker  # 创建docker用户组
sudo gpasswd -a $USER docker  # 把当前用户加入到docker用户组>>sudo usermod -aG docker your-user
newgrp docker   # 更新当前用户组变动（就不用退出并重新登录了）
```





```
docker info
docker version 
docker search -s version `image_name`//docker search centos 
docker pull `image_name`//docker pull centos 
docker images| -a -q | 
docker run --rm --restart=always -it -p 80:80 --name nginx01 -v ~/docker/nginx/nginx.conf:/etc/nginx/nginx.conf nginx:latest /bin/bash
##--rm 容器退出时自动移除容器
##--restart=always 不管退出状态码是什么始终重启容器。当指定always时，docker daemon将无限次数地重启容器。容器也会在daemon启动时尝试重启，不管容器当时的状态如何。//docker container update --restart=always 容器名字
##-d 容器启动后在后台运行
##-it 
```



```
docker ps |-a | -q
docker start `container_id`
docker stop `container_id`
docker restart `container_id`
docker rm `container_id`
docker rmi `image_id
docker attach `container_id`#进入后台运行的容器

docker exec -it container_id /bin/bash 
docker top `container_id`#查看容器内部进程
docker inspect [OPTIONS] NAME|ID [NAME|ID...] #获取容器/镜像的元数据。
docker inspect --format '{{ .NetworkSettings.IPAddress }}' <container-ID> #查看容器内部IP地址
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



## 配置阿里云镜像加速器
```
sudo mkdir -p /etc/docker
sudo tee /etc/docker/daemon.json <<-'EOF'
{
  "registry-mirrors": ["https://yprtfvq7.mirror.aliyuncs.com"]
}
EOF
sudo systemctl daemon-reload
sudo systemctl restart docker
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
#mkdir -p ~/docker/docker-volume/mysql/data/db ~/docker/docker-volume/mysql/data/conf.d ~/docker/docker-volume/mysql/logs
docker run -d -p 3306:3306 \
--name mysql \
-e MYSQL_ROOT_PASSWORD=root \
-v ~/docker/docker-volume/mysql/data/db:/var/lib/mysql \
-v ~/docker/docker-volume/mysql/data/conf.d:/etc/mysql/conf.d \
-v ~/docker/docker-volume/mysql/logs:/var/log/mysql \
mysql:latest \
mysqld --character-set-server=utf8mb4 --collation-server=utf8mb4_unicode_ci

docker run -d -p 3306:3306 \
--name mysql \
-e MYSQL_ROOT_PASSWORD=root \
-v D:/mydocker/docker-volume/mysql/data/db:/var/lib/mysql \
-v D:/mydocker/docker-volume/mysql/data/conf.d:/etc/mysql/conf.d \
-v D:/mydocker/docker-volume/mysql/logs:/var/log/mysql \
mysql:5.7 \
mysqld --character-set-server=utf8mb4 --collation-server=utf8mb4_unicode_ci
```

### nginx

```
##mkdir -p ~/docker/docker-volume/nginx/conf.d  ~/docker/docker-volume/nginx/html ~/docker/docker-volume/nginx/log
##将nginx.conf复制到~/docker/docker-volume/nginx
##注意 : 容器内的/etc/nginx/fastcgi_params和宿主机的/usr/local/etc/nginx/fastcgi.conf||fastcgi_params
docker run -it -p 80:80 -v ~/docker/docker-volume/nginx/nginx.conf:/etc/nginx/nginx.conf -v ~/docker/docker-volume/nginx/conf.d:/etc/nginx/conf.d -v ~/docker/docker-volume/nginx/html:/usr/share/nginx/html -v ~/docker/docker-volume/nginx/log:/var/log/nginx --name nginx nginx:latest /bin/bash
```

### Redis

```
docker run -itd --name redis -p 6379:6379 redis
```



### RabbitMQ

```
docker run --name rabbitmq -d -p 15672:15672 -p 5672:5672 rabbitmq:3.8-management
#-p:指定容器内部端口号与宿主机之间的映射，rabbitMq默认要使用15672为其web端界面访问时端口，5672为数据通信端口
#-management:表示web有管理界面插件
```

### ElasticSearch&&Kibana

```
docker run -d --rm --name elasticsearch -p 9200:9200 -p9300:9300 -e "discovery.type=single-node" -e "cluster.name=docker-cluster" elasticsearch:6.5.0 

cd /usr/share/elasticsearch/plugins/

elasticsearch-plugin install https://github.com/medcl/elasticsearch-analysis-ik/releases/download/v7.2.0/elasticsearch-analysis-ik-7.2.0.zip

docker restart elasticsearch 

docker run -d --name kibana --link=elasticsearch:test  -p 5601:5601 kibana:7.2.0

/usr/share/elasticsearch/config/elasticsearch.yml添加以下两行,解决跨域
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