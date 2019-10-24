|命令|参数|说明|示例|示例说明|
|---|---|---|---|---|---|
|docker version 
|docker search `image_name`| | 搜索镜像 | docker search centos 
|docker pull `image_name`| | 拉取镜像 | docker pull centos 
|docker images| -a -q | 本地镜像列表
|docker run |-it -p(设置端口) -v(目录映射)| 运行镜像(创建容器) |   docker run -it -p 81:80 centos:latest /bin/bash
|docker ps |-a -q| 容器列表
|docker start `container_id`| 
|docker stop `container_id`| 
|docker restart `container_id`| 
|docker attach `container_id`| 进入后台运行的容器 
|docker rm `container_id`|
|docker rmi `image_id`|
|docker exec `container_id` `command`| -it | 发送命令到容器
|docker 
|docker build [选项] <上下文路径/URL/->| -t -f(指定dockerfile文件位置)| 



****后台运行容器****
> ctrl + p + q



### 停止容器
docker stop $(docker ps -a -q)

### 删除容器
docker rm $(docker ps -a -q)

### 删除镜像
docker image rm $(docker image ls -a -q)

### 删除数据卷：
docker volume rm $(docker volume ls -q)

### 删除 network：
docker network rm $(docker network ls -q)

-----------------------------------------------------------------------------
### 最直接并全面清理的的就是以下命令

$docker stop $(docker ps -a -q) && docker system prune --all --force

-----------------------------------------------------------------------------
版权声明：本文为CSDN博主「心飞路漫」的原创文章，遵循CC 4.0 by-sa版权协议，转载请附上原文出处链接及本声明。
原文链接：https://blog.csdn.net/qq_34924407/article/details/82777691