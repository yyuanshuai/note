FROM nginx:latest

LABEL maintainer "yuanshuai"

#容器启动时执行的命令
#ENTRYPOINT ["executable", "param1","param2"...]


VOLUME [ "/etc/nginx/conf.d" , "/data/www"]


#该服务需要暴露的端口并不推荐在此做端口映射
EXPOSE 80

#用于目录的切换，但是和cd不一样的是：如果切换到的目录不存在，WORKDIR会为此创建目录
#WORKDIR path

#CMD可能被run的时候指定的命令覆盖掉--不会执行
#CMD ["nginx", "-g", "daemon off;"]